<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enfant;
use App\Models\Vaccin;
use App\Models\Rappel;
use App\Models\Setting;
use App\Models\CentreSante; // NOUVEAU
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard admin avec statistiques globales
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_enfants' => Enfant::count(),
            'total_vaccins' => Vaccin::count(),
            'total_rappels' => Rappel::count(),
            'vaccins_effectues' => Rappel::where('statut', 'effectue')->count(),
            'vaccins_en_attente' => Rappel::where('statut', 'en_attente')->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_enfants = Enfant::with('parent')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_enfants'));
    }

    /**
     * Liste tous les utilisateurs avec recherche
     */
    public function users(Request $request)
    {
        $search = $request->get('search');
        
        $users = User::withCount('enfants')
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15);
        
        return view('admin.users', compact('users'));
    }

    /**
     * Formulaire d'édition d'un utilisateur
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Mise à jour d'un utilisateur
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'telephone', 'langue_preferee', 'role']));
        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour');
    }

    /**
     * Suppression d'un utilisateur
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé');
    }

    /**
     * Statistiques avancées
     */
    public function statistiques()
    {
        $vaccins_par_mois = Rappel::selectRaw('MONTH(date_prevue) as mois, COUNT(*) as total')
            ->whereYear('date_prevue', date('Y'))
            ->groupBy('mois')
            ->get();

        $top_enfants = Enfant::withCount('rappels')
            ->orderBy('rappels_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.statistiques', compact('vaccins_par_mois', 'top_enfants'));
    }

    /**
     * Liste des vaccins (gestion)
     */
    public function vaccins()
    {
        $vaccins = Vaccin::paginate(15);
        return view('admin.vaccins', compact('vaccins'));
    }

    /**
     * Modifier un vaccin
     */
    public function editVaccin($id)
    {
        $vaccin = Vaccin::findOrFail($id);
        return view('admin.edit-vaccin', compact('vaccin'));
    }

    /**
     * Mettre à jour un vaccin
     */
    public function updateVaccin(Request $request, $id)
    {
        $vaccin = Vaccin::findOrFail($id);
        
        // Mise à jour explicite incluant les champs de date
        $vaccin->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'age_min_mois' => $request->age_min_mois,
            'age_max_mois' => $request->age_max_mois,
            'dose_numero' => $request->dose_numero,
            'maladie_previent' => $request->maladie_previent,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);
        
        return redirect()->route('admin.vaccins')->with('success', 'Vaccin mis à jour');
    }

    /**
     * Supprimer un vaccin
     */
    public function deleteVaccin($id)
    {
        $vaccin = Vaccin::findOrFail($id);
        
        // Vérifier si le vaccin a des rappels associés
        $rappelsCount = Rappel::where('vaccin_id', $id)->count();
        
        if ($rappelsCount > 0) {
            return redirect()->route('admin.vaccins')->with('error', 
                "Impossible de supprimer ce vaccin car il est utilisé dans {$rappelsCount} rappels.");
        }
        
        $vaccin->delete();
        
        return redirect()->route('admin.vaccins')->with('success', 'Vaccin supprimé avec succès');
    }

    /**
     * Afficher la page des paramètres
     */
    public function settings()
    {
        $settings = [
            'rappel_jours_avant' => Setting::get('rappel_jours_avant', '7'),
            'rappel_heures' => Setting::get('rappel_heures', '09:00'),
            'langue_fr_active' => Setting::get('langue_fr_active', '1'),
            'langue_fon_active' => Setting::get('langue_fon_active', '1'),
            'langue_yoruba_active' => Setting::get('langue_yoruba_active', '1'),
            'email_support' => Setting::get('email_support', 'support@carnetsante.bj'),
            'telephone_support' => Setting::get('telephone_support', '+229 00 00 00 00'),
            'notification_sms_active' => Setting::get('notification_sms_active', '1'),
            'notification_email_active' => Setting::get('notification_email_active', '1'),
            'delai_rappel_j1' => Setting::get('delai_rappel_j1', '7'),
            'delai_rappel_j2' => Setting::get('delai_rappel_j2', '1'),
        ];

        return view('admin.settings', compact('settings'));
    }

    /**
     * Mettre à jour les paramètres
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'rappel_jours_avant' => 'required|integer|min:1|max:30',
            'rappel_heures' => 'required|date_format:H:i',
            'langue_fr_active' => 'sometimes|boolean',
            'langue_fon_active' => 'sometimes|boolean',
            'langue_yoruba_active' => 'sometimes|boolean',
            'email_support' => 'required|email',
            'telephone_support' => 'required|string',
            'notification_sms_active' => 'sometimes|boolean',
            'notification_email_active' => 'sometimes|boolean',
            'delai_rappel_j1' => 'required|integer|min:1|max:30',
            'delai_rappel_j2' => 'required|integer|min:0|max:30',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? 0);
        }

        return redirect()->route('admin.settings')->with('success', 'Paramètres mis à jour avec succès !');
    }

    /**
     * Liste tous les enfants avec filtres
     */
    public function enfants(Request $request)
    {
        $query = Enfant::with('parent')->withCount('rappels');
        
        // Filtre par recherche (nom/prénom)
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('prenom', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filtre par âge
        if ($request->has('age') && $request->age != '') {
            switch($request->age) {
                case 'moins_1':
                    $query->where('date_naissance', '>=', now()->subYear());
                    break;
                case '1_5':
                    $query->whereBetween('date_naissance', [now()->subYears(5), now()->subYear()]);
                    break;
                case 'plus_5':
                    $query->where('date_naissance', '<', now()->subYears(5));
                    break;
            }
        }
        
        // Filtre par parent
        if ($request->has('parent_id') && $request->parent_id != '') {
            $query->where('user_id', $request->parent_id);
        }
        
        $enfants = $query->latest()->paginate(15);
        
        // Statistiques sur les enfants
        $stats = [
            'total' => Enfant::count(),
            'garcons' => Enfant::where('sexe', 'M')->count(),
            'filles' => Enfant::where('sexe', 'F')->count(),
            'avec_rappels' => Enfant::has('rappels')->count(),
        ];
        
        // Liste des parents pour le filtre
        $parents = User::whereHas('enfants')->orderBy('name')->get();
        
        return view('admin.enfants', compact('enfants', 'stats', 'parents'));
    }

    /**
     * Voir les détails d'un enfant
     */
    public function showEnfant($id)
    {
        $enfant = Enfant::with(['parent', 'rappels.vaccin'])->findOrFail($id);
        
        $rappelsAVenir = $enfant->rappels()
            ->where('statut', 'en_attente')
            ->with('vaccin')
            ->orderBy('date_prevue')
            ->get();
        
        $rappelsEffectues = $enfant->rappels()
            ->where('statut', 'effectue')
            ->with('vaccin')
            ->orderBy('date_administration', 'desc')
            ->get();
        
        return view('admin.show-enfant', compact('enfant', 'rappelsAVenir', 'rappelsEffectues'));
    }

    /**
     * Supprimer un enfant
     */
    public function deleteEnfant($id)
    {
        $enfant = Enfant::findOrFail($id);
        $nom = $enfant->prenom . ' ' . $enfant->nom;
        
        // Supprimer les rappels associés
        $enfant->rappels()->delete();
        
        // Supprimer l'enfant
        $enfant->delete();
        
        return redirect()->route('admin.enfants')->with('success', "L'enfant $nom a été supprimé avec succès.");
    }

    /**
     * Gestion des administrateurs
     */
    public function adminManagement(Request $request)
    {
        $search = $request->get('search');
        
        // Recherche d'utilisateurs pour ajouter comme admin
        $potentialAdmins = collect();
        if ($search) {
            $potentialAdmins = User::where('role', 'user')
                ->where(function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                })
                ->limit(10)
                ->get();
        }
        
        // Liste des admins actuels
        $admins = User::where('role', 'admin')
            ->orderBy('name')
            ->paginate(10);
        
        return view('admin.admin-management', compact('potentialAdmins', 'admins', 'search'));
    }

    /**
     * Ajouter un utilisateur comme administrateur
     */
    public function addAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        $user = User::findOrFail($request->user_id);
        $user->role = 'admin';
        $user->save();
        
        return redirect()->route('admin.management')
            ->with('success', "{$user->name} est maintenant administrateur.");
    }

    /**
     * Retirer un administrateur
     */
    public function removeAdmin($id)
    {
        $user = User::findOrFail($id);
        
        // Empêcher de supprimer son propre compte admin
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.management')
                ->with('error', "Vous ne pouvez pas retirer vos propres droits administrateur.");
        }
        
        $user->role = 'user';
        $user->save();
        
        return redirect()->route('admin.management')
            ->with('success', "{$user->name} n'est plus administrateur.");
    }

    /**
     * Liste des centres de santé
     */
    public function centresListe(Request $request)
    {
        $search = $request->get('search');
        
        $query = CentreSante::withCount('rappels as vaccins_effectues');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%")
                  ->orWhere('responsable', 'like', "%{$search}%");
            });
        }
        
        $centres = $query->paginate(15);
        
        $stats = [
            'total' => CentreSante::count(),
            'actifs' => CentreSante::where('actif', true)->count(),
            'vaccins_total' => Rappel::whereNotNull('centre_sante_id')->count(),
        ];
        
        return view('admin.centres', compact('centres', 'stats'));
    }

    /**
     * Formulaire d'ajout d'un centre
     */
    public function centreAjouter()
    {
        return view('admin.centres-form', ['centre' => null]);
    }

    /**
     * Enregistrer un nouveau centre
     */
    public function centreStore(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'responsable' => 'nullable|string|max:255',
            'horaires' => 'nullable|string',
            'actif' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);
        
        $validated['actif'] = $request->has('actif');
        
        CentreSante::create($validated);
        
        return redirect()->route('admin.centres')
            ->with('success', 'Centre de santé ajouté avec succès.');
    }

    /**
     * Formulaire de modification d'un centre
     */
    public function centreModifier($id)
    {
        $centre = CentreSante::findOrFail($id);
        return view('admin.centres-form', compact('centre'));
    }

    /**
     * Mettre à jour un centre
     */
    public function centreUpdate(Request $request, $id)
    {
        $centre = CentreSante::findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'nullable|string',
            'ville' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'responsable' => 'nullable|string|max:255',
            'horaires' => 'nullable|string',
            'actif' => 'sometimes|boolean',
            'description' => 'nullable|string',
        ]);
        
        $validated['actif'] = $request->has('actif');
        
        $centre->update($validated);
        
        return redirect()->route('admin.centres')
            ->with('success', 'Centre de santé mis à jour avec succès.');
    }

    /**
     * Supprimer un centre
     */
    public function centreSupprimer($id)
    {
        $centre = CentreSante::findOrFail($id);
        $centre->delete();
        
        return redirect()->route('admin.centres')
            ->with('success', 'Centre de santé supprimé avec succès.');
    }

    /**
     * Statistiques détaillées d'un centre
     */
    public function centreStats($id)
    {
        $centre = CentreSante::with(['rappels.enfant', 'rappels.vaccin'])
            ->findOrFail($id);
        
        $vaccinsParMois = Rappel::where('centre_sante_id', $id)
            ->selectRaw('MONTH(date_administration) as mois, COUNT(*) as total')
            ->whereNotNull('date_administration')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();
        
        $topVaccins = Vaccin::select('vaccins.*')
            ->join('rappels', 'vaccins.id', '=', 'rappels.vaccin_id')
            ->where('rappels.centre_sante_id', $id)
            ->selectRaw('vaccins.*, COUNT(*) as total')
            ->groupBy('vaccins.id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        return view('admin.centres-stats', compact('centre', 'vaccinsParMois', 'topVaccins'));
    }
}