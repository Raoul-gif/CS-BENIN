<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vaccin;
use App\Services\CalendrierVaccinalService;
use Illuminate\Http\Request;

class VaccinController extends Controller
{
    protected $calendrierService;

    public function __construct(CalendrierVaccinalService $calendrierService)
    {
        $this->calendrierService = $calendrierService;
    }

    public function index()
    {
        return response()->json(Vaccin::all());
    }

    public function calendrier()
    {
        $calendrier = [
            'naissance' => Vaccin::where('age_min_mois', 0)->get(),
            '6_semaines' => Vaccin::where('age_min_mois', 1.5)->get(),
            '10_semaines' => Vaccin::where('age_min_mois', 2.5)->get(),
            '14_semaines' => Vaccin::where('age_min_mois', 3.5)->get(),
            '9_mois' => Vaccin::where('age_min_mois', 9)->get(),
            '15_mois' => Vaccin::where('age_min_mois', 15)->get()
        ];

        return response()->json($calendrier);
    }
}