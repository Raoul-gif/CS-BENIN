<?php
// app/Http/Controllers/HistoriqueController.php

namespace App\Http\Controllers;

use App\Models\Enfant;
use App\Models\HistoriqueVaccin;
use Illuminate\Http\Request;

class HistoriqueController extends Controller
{
    /**
     * Afficher l'historique complet d'un enfant
     */
    public function index(Enfant $enfant)
    {
        $historique = HistoriqueVaccin::with('vaccin')
                        ->where('enfant_id', $enfant->id)
                        ->orderBy('date_administration', 'desc')
                        ->get();

        $prochainsRappels = HistoriqueVaccin::where('enfant_id', $enfant->id)
                                ->whereNotNull('prochain_rappel')
                                ->where('prochain_rappel', '>=', now())
                                ->orderBy('prochain_rappel')
                                ->get();

        return view('historique.index', compact('enfant', 'historique', 'prochainsRappels'));
    }

    /**
     * Ajouter un vaccin administré
     */
    public function store(Request $request, Enfant $enfant)
    {
        $validated = $request->validate([
            'vaccin_id' => 'required|exists:vaccins,id',
            'date_administration' => 'required|date',
            'lieu_administration' => 'required|string|max:255',
            'professionnel_sante' => 'nullable|string|max:255',
            'lot_vaccin' => 'nullable|string|max:50',
            'prochain_rappel' => 'nullable|date|after:date_administration',
            'notes' => 'nullable|string'
        ]);

        $validated['enfant_id'] = $enfant->id;

        HistoriqueVaccin::create($validated);

        return redirect()
            ->route('historique.index', $enfant)
            ->with('success', 'Vaccin enregistré avec succès');
    }

    /**
     * Afficher le détail d'une vaccination
     */
    public function show(HistoriqueVaccin $historique)
    {
        return view('historique.show', compact('historique'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(HistoriqueVaccin $historique)
    {
        return view('historique.edit', compact('historique'));
    }

    /**
     * Mettre à jour une vaccination
     */
    public function update(Request $request, HistoriqueVaccin $historique)
    {
        $validated = $request->validate([
            'date_administration' => 'required|date',
            'lieu_administration' => 'required|string|max:255',
            'professionnel_sante' => 'nullable|string|max:255',
            'lot_vaccin' => 'nullable|string|max:50',
            'prochain_rappel' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $historique->update($validated);

        return redirect()
            ->route('historique.index', $historique->enfant)
            ->with('success', 'Vaccin mis à jour');
    }

    /**
     * Supprimer une entrée
     */
    public function destroy(HistoriqueVaccin $historique)
    {
        $enfant = $historique->enfant;
        $historique->delete();

        return redirect()
            ->route('historique.index', $enfant)
            ->with('success', 'Entrée supprimée');
    }
}