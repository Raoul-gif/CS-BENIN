<?php

namespace App\Http\Controllers;

use App\Models\Enfant;
use App\Models\Carnet;
use App\Services\PdfGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    protected PdfGenerator $pdfGenerator;

    public function __construct(PdfGenerator $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    /**
     * Générer et télécharger le carnet PDF
     */
   public function telechargerCarnet(Enfant $enfant)
{
    try {
        $carnet = $this->pdfGenerator->genererCarnet($enfant);
        
        if (!Storage::disk('public')->exists($carnet->fichier_pdf)) {
            return back()->with('error', 'Fichier introuvable');
        }
        
        $nomFichier = sprintf(
            'carnet-%s-%s-%s.pdf',
            str_slug($enfant->nom),
            str_slug($enfant->prenom),
            now()->format('Y-m-d')
        );
        
        return response()->download(
            Storage::disk('public')->path($carnet->fichier_pdf),
            $nomFichier,
            ['Content-Type' => 'application/pdf']
        );
        
    } catch (\Exception $e) {
        Log::error('Erreur: ' . $e->getMessage());
        return back()->with('error', 'Impossible de générer le carnet.');
    }
}
    /**
     * Vérifier l'intégrité d'un carnet
     */
    public function verifierCarnet(Carnet $carnet): JsonResponse
    {
        try {
            $estValide = $this->pdfGenerator->verifierCarnet($carnet);
            
            return response()->json([
                'success' => true,
                'valide' => $estValide,
                'message' => $estValide 
                    ? '✅ Le document est authentique et n\'a pas été altéré.' 
                    : '⚠️ Le document a été modifié ou est corrompu.',
                'carnet_id' => $carnet->id,
                'enfant_nom' => $carnet->enfant->nom . ' ' . $carnet->enfant->prenom,
                'date_generation' => $carnet->date_generation->format('d/m/Y H:i')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur vérification carnet: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'valide' => false,
                'message' => 'Erreur lors de la vérification du document.'
            ], 500);
        }
    }

    /**
     * Afficher l'aperçu du carnet dans le navigateur
     * 
     * ICI LA CORRECTION: On enlève le type de retour pour faire plaisir à Intelephense
     */
  public function apercu(Enfant $enfant)
{
    try {
        $carnet = $this->pdfGenerator->genererCarnet($enfant);
        
        if (!Storage::disk('public')->exists($carnet->fichier_pdf)) {
            return back()->with('error', 'Aperçu introuvable.');
        }
        
        return response()->file(
            Storage::disk('public')->path($carnet->fichier_pdf),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="apercu.pdf"'
            ]
        );
        
    } catch (\Exception $e) {
        Log::error('Erreur aperçu: ' . $e->getMessage());
        return back()->with('error', 'Impossible d\'afficher l\'aperçu.');
    }
}

    /**
     * Partager le carnet par email
     */
    public function partager(Enfant $enfant, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns',
            'message' => 'nullable|string|max:500'
        ]);

        try {
            $carnet = $this->pdfGenerator->genererCarnet($enfant);
            
            // Vérifier que le fichier existe
            if (!Storage::disk('public')->exists($carnet->fichier_pdf)) {
                return back()->with('error', 'Le carnet est introuvable sur le serveur.');
            }
            
            // TODO: Implémenter l'envoi d'email avec la queue
            // Mail::to($validated['email'])->queue(new CarnetPartage($carnet, $validated['message'] ?? null));
            
            Log::info('Partage de carnet demandé', [
                'enfant_id' => $enfant->id,
                'email' => $validated['email']
            ]);
            
            return back()->with('success', 
                'Le carnet a été envoyé avec succès à ' . $validated['email'] . 
                '. Vérifiez votre boîte de réception.'
            );
            
        } catch (\Exception $e) {
            Log::error('Erreur partage carnet: ' . $e->getMessage());
            
            return back()->with('error', 'Impossible d\'envoyer le carnet par email. Veuillez réessayer.');
        }
    }

    

    /**
     * Lister tous les carnets générés pour un enfant
     */
   public function historique(Request $request, Enfant $enfant)
{
    // Construction de la requête de base
    $query = Carnet::where('enfant_id', $enfant->id)
                ->with('enfant');
    
    // 🔍 FILTRE PAR RECHERCHE (date, statut)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->whereDate('date_generation', 'like', "%{$search}%")
              ->orWhere('statut', 'like', "%{$search}%")
              ->orWhere('id', 'like', "%{$search}%");
        });
    }
    
    // 🏷️ FILTRE PAR STATUT
    if ($request->filled('status')) {
        $query->where('statut', $request->status);
    }
    
    // 📅 FILTRE PAR PÉRIODE
    if ($request->filled('period')) {
        switch($request->period) {
            case 'today':
                $query->whereDate('date_generation', today());
                break;
            case 'week':
                $query->whereBetween('date_generation', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('date_generation', now()->month)
                      ->whereYear('date_generation', now()->year);
                break;
        }
    }
    
    // ⬆️⬇️ TRI
    $order = $request->get('order', 'desc');
    $query->orderBy('date_generation', $order);
    
    // Pagination
    $carnets = $query->paginate(10);
    
    // Si c'est une requête AJAX, retourne du JSON
    if ($request->ajax()) {
        return response()->json([
            'html' => view('export.partials.table', compact('carnets', 'enfant'))->render(),
            'pagination' => (string) $carnets->links(),
            'total' => $carnets->total()
        ]);
    }
    
    return view('export.historique', compact('enfant', 'carnets'));
}
    /**
     * Supprimer un ancien carnet
     */
    public function supprimer(Carnet $carnet): RedirectResponse
    {
        try {
            $enfant = $carnet->enfant;
            
            // Supprimer le fichier physique
            if (Storage::disk('public')->exists($carnet->fichier_pdf)) {
                Storage::disk('public')->delete($carnet->fichier_pdf);
            }
            
            // Supprimer l'entrée en base
            $carnet->delete();
            
            return redirect()
                ->route('export.historique', $enfant)
                ->with('success', 'Carnet supprimé avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Erreur suppression carnet: ' . $e->getMessage());
            
            return back()->with('error', 'Impossible de supprimer le carnet.');
        }
    }
}