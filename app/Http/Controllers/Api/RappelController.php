<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rappel;
use App\Models\Enfant;
use App\Services\CalendrierVaccinalService;
use Illuminate\Http\Request;

class RappelController extends Controller
{
    protected $calendrierService;

    public function __construct(CalendrierVaccinalService $calendrierService)
    {
        $this->calendrierService = $calendrierService;
    }

    public function index(Enfant $enfant)
    {
        return response()->json($enfant->rappels()->with('vaccin')->get());
    }

    public function marquerEffectue(Request $request, Rappel $rappel)
    {
        $request->validate([
            'date_administration' => 'required|date',
            'lot_vaccin' => 'nullable|string',
            'centre_sante' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $rappel->update([
            'statut' => 'effectue',
            'date_administration' => $request->date_administration,
            'lot_vaccin' => $request->lot_vaccin,
            'centre_sante' => $request->centre_sante,
            'notes' => $request->notes
        ]);

        // Créer une entrée dans l'historique
        Historique::create([
            'enfant_id' => $rappel->enfant_id,
            'vaccin_id' => $rappel->vaccin_id,
            'date_administration' => $request->date_administration,
            'lieu_administration' => $request->centre_sante,
            'professionnel_sante' => $request->professionnel_sante,
            'lot_vaccin' => $request->lot_vaccin,
            'notes' => $request->notes
        ]);

        return response()->json(['message' => 'Rappel marqué comme effectué', 'rappel' => $rappel]);
    }

    public function reporter(Request $request, Rappel $rappel)
    {
        $request->validate([
            'date_prevue' => 'required|date|after:today',
            'motif' => 'nullable|string'
        ]);

        $rappel->update([
            'date_prevue' => $request->date_prevue,
            'statut' => 'reporte',
            'notes' => $request->motif
        ]);

        return response()->json(['message' => 'Rappel reporté', 'rappel' => $rappel]);
    }
}