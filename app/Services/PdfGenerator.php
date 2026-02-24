<?php
// app/Services/PdfGenerator.php

namespace App\Services;

use App\Models\Enfant;
use App\Models\Carnet;
use App\Models\HistoriqueVaccin; // Ajoute cet import
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfGenerator
{
    /**
     * Générer le carnet de vaccination PDF
     */
    public function genererCarnet(Enfant $enfant): Carnet
    {
        // TEMPORAIRE: Récupérer l'historique directement depuis le modèle
        $historique = HistoriqueVaccin::where('enfant_id', $enfant->id)
                        ->with('vaccin')
                        ->orderBy('date_administration')
                        ->get();

        // Préparer les données pour la vue
        $data = [
            'enfant' => $enfant,
            'historique' => $historique,
            'date_generation' => now()->format('d/m/Y'),
            'numero_carnet' => 'CAR-' . str_pad($enfant->id, 6, '0', STR_PAD_LEFT),
            'cachet' => $this->genererCachetNumerique()
        ];

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.carnet-vaccinal', $data);
        
        // Nom du fichier
        $filename = 'carnets/' . Str::slug($enfant->nom . '-' . $enfant->prenom) . '-' . time() . '.pdf';
        
        // Sauvegarder le PDF
        Storage::disk('public')->put($filename, $pdf->output());
        
        // Calculer le hash pour vérification
        $hash = hash_file('sha256', Storage::disk('public')->path($filename));
        
        // Créer l'entrée dans la table carnets
        $carnet = new Carnet([
            'enfant_id' => $enfant->id,
            'fichier_pdf' => $filename,
            'hash' => $hash,
            'date_generation' => now(),
            'date_expiration' => now()->addYear(), // Valable 1 an
            'statut' => 'valide'
        ]);
        
        // Ajouter la signature numérique
        $carnet->signature = $this->signerDocument($hash);
        $carnet->save();
        
        return $carnet;
    }

    /**
     * Générer un cachet numérique
     */
   private function genererCachetNumerique(): string
{
    return "RÉPUBLIQUE DU BÉNIN\n" .
           "MINISTÈRE DE LA SANTÉ\n" .
           "DIRECTION NATIONALE DE LA VACCINATION\n" .
           "Cotonou, le " . now()->locale('fr')->isoFormat('LL') . "\n" .
           "Cachet officiel N° " . strtoupper(Str::random(8));
}

    /**
     * Signer le document
     */
    private function signerDocument(string $hash): string
    {
        return hash_hmac('sha256', $hash, config('app.key'));
    }

    /**
     * Vérifier l'intégrité d'un carnet
     */
    public function verifierCarnet(Carnet $carnet): bool
    {
        return $carnet->verifierIntegrite();
    }
}