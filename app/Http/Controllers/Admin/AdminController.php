<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Enfant;
use App\Models\Vaccin;
use App\Models\Rappel;
use App\Models\Setting;
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
     * MODIFIÉ POUR INCLURE LA RECHERCHE
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
     * Liste tous les enfants
     */
    public function enfants()
    {
        $enfants = Enfant::with('parent')
            ->withCount('rappels')
            ->latest()
            ->paginate(15);
        
        // Statistiques sur les enfants
        $stats = [
            'total' => Enfant::count(),
            'garcons' => Enfant::where('sexe', 'M')->count(),
            'filles' => Enfant::where('sexe', 'F')->count(),
            'avec_rappels' => Enfant::has('rappels')->count(),
        ];
        
        return view('admin.enfants', compact('enfants', 'stats'));
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
}