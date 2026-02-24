@extends('layouts.admin')

@section('title', 'Dashboard Administrateur')

@section('content')
<div class="space-y-6">
    
    @php
    use App\Models\User;
    use App\Models\Enfant;
    use App\Models\Vaccin;
    use App\Models\Rappel;
    use Carbon\Carbon;
    
    // Statistiques globales
    $totalUsers = User::count();
    $totalEnfants = Enfant::count();
    $totalVaccins = Vaccin::count();
    $totalRappels = Rappel::count();
    
    // Statistiques des vaccins
    $vaccinsEffectues = Rappel::where('statut', 'effectue')->count();
    $vaccinsEnAttente = Rappel::where('statut', 'en_attente')->count();
    $vaccinsReportes = Rappel::where('statut', 'reporte')->count();
    
    // Taux de complétion
    $tauxCompletion = $totalRappels > 0 ? round(($vaccinsEffectues / $totalRappels) * 100, 1) : 0;
    
    // Utilisateurs avec/sans enfants
    $usersWithEnfants = User::has('enfants')->count();
    $usersWithoutEnfants = User::doesntHave('enfants')->count();
    
    // Statistiques mensuelles (6 derniers mois)
    $months = [];
    $rappelsData = [];
    $effectuesData = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $month = Carbon::now()->subMonths($i);
        $months[] = $month->locale('fr')->isoFormat('MMM YYYY');
        
        $rappelsMois = Rappel::whereMonth('date_prevue', $month->month)
            ->whereYear('date_prevue', $month->year)
            ->count();
        $rappelsData[] = $rappelsMois;
        
        $effectuesMois = Rappel::where('statut', 'effectue')
            ->whereMonth('date_administration', $month->month)
            ->whereYear('date_administration', $month->year)
            ->count();
        $effectuesData[] = $effectuesMois;
    }
    
    // Top 5 vaccins les plus administrés
    $topVaccins = Vaccin::withCount('rappels')
        ->orderBy('rappels_count', 'desc')
        ->take(5)
        ->get();
    
    // Répartition par âge des enfants
    $enfantsMoins1An = Enfant::where('date_naissance', '>=', Carbon::now()->subYear())->count();
    $enfants1a5Ans = Enfant::whereBetween('date_naissance', [Carbon::now()->subYears(5), Carbon::now()->subYear()])->count();
    $enfantsPlus5Ans = Enfant::where('date_naissance', '<', Carbon::now()->subYears(5))->count();
    
    // Dernières activités
    $recentRappels = Rappel::with(['enfant.parent', 'vaccin'])
        ->latest()
        ->take(5)
        ->get();
    
    // Prochains rappels (7 jours)
    $prochainsRappels = Rappel::with(['enfant', 'vaccin'])
        ->where('statut', 'en_attente')
        ->whereBetween('date_prevue', [Carbon::now(), Carbon::now()->addDays(7)])
        ->orderBy('date_prevue')
        ->take(5)
        ->get();
    @endphp

    <!-- SECTION 1: CARTES DE STATISTIQUES PRINCIPALES -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
            Statistiques globales
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Utilisateurs</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
                    </div>
                    <div class="bg-blue-200 p-3 rounded-full">
                        <i class="fas fa-users text-xl text-blue-700"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-child mr-1 text-green-600"></i>
                    {{ $usersWithEnfants }} avec enfants
                </p>
            </div>

            <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Enfants</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalEnfants }}</p>
                    </div>
                    <div class="bg-green-200 p-3 rounded-full">
                        <i class="fas fa-child text-xl text-green-700"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-calculator mr-1 text-blue-600"></i>
                    {{ round($totalEnfants / max($totalUsers, 1), 1) }}/utilisateur
                </p>
            </div>

            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-600 text-sm font-medium">Vaccins programmés</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalRappels }}</p>
                    </div>
                    <div class="bg-yellow-200 p-3 rounded-full">
                        <i class="fas fa-calendar-check text-xl text-yellow-700"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-clock mr-1 text-orange-600"></i>
                    {{ $vaccinsEnAttente }} en attente
                </p>
            </div>

            <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-medium">Taux de complétion</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $tauxCompletion }}%</p>
                    </div>
                    <div class="bg-purple-200 p-3 rounded-full">
                        <i class="fas fa-chart-pie text-xl text-purple-700"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">
                    <i class="fas fa-check-circle mr-1 text-green-600"></i>
                    {{ $vaccinsEffectues }} effectués
                </p>
            </div>
        </div>
    </div>

    <!-- SECTION 2: GRAPHIQUE D'ÉVOLUTION -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
            Évolution des rappels (6 derniers mois)
        </h3>
        <div style="height: 350px;">
            <canvas id="evolutionChart"></canvas>
        </div>
    </div>

    <!-- SECTION 3: RÉPARTITION DES VACCINS -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-chart-pie text-green-600 mr-2"></i>
            Répartition des vaccins
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div style="height: 250px;">
                <canvas id="statusChart"></canvas>
            </div>
            <div class="flex flex-col justify-center">
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <span class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> Effectués</span>
                        <span class="font-bold">{{ $vaccinsEffectues }} ({{ $totalRappels > 0 ? round(($vaccinsEffectues / $totalRappels) * 100) : 0 }}%)</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <span class="flex items-center"><span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span> En attente</span>
                        <span class="font-bold">{{ $vaccinsEnAttente }} ({{ $totalRappels > 0 ? round(($vaccinsEnAttente / $totalRappels) * 100) : 0 }}%)</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <span class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span> Reportés</span>
                        <span class="font-bold">{{ $vaccinsReportes }} ({{ $totalRappels > 0 ? round(($vaccinsReportes / $totalRappels) * 100) : 0 }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: STATISTIQUES AVANCÉES -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Répartition par âge -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                Répartition par âge des enfants
            </h3>
            <div style="height: 200px;">
                <canvas id="ageChart"></canvas>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                <div class="bg-blue-50 p-2 rounded">
                    <span class="text-xs text-gray-600">Moins d'1 an</span>
                    <p class="font-bold text-blue-600">{{ $enfantsMoins1An }}</p>
                </div>
                <div class="bg-green-50 p-2 rounded">
                    <span class="text-xs text-gray-600">1-5 ans</span>
                    <p class="font-bold text-green-600">{{ $enfants1a5Ans }}</p>
                </div>
                <div class="bg-yellow-50 p-2 rounded">
                    <span class="text-xs text-gray-600">Plus de 5 ans</span>
                    <p class="font-bold text-yellow-600">{{ $enfantsPlus5Ans }}</p>
                </div>
            </div>
        </div>

        <!-- Top vaccins -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                <i class="fas fa-syringe text-red-600 mr-2"></i>
                Top 5 vaccins administrés
            </h3>
            <div class="space-y-4">
                @foreach($topVaccins as $index => $vaccin)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium">{{ $vaccin->nom }}</span>
                        <span class="text-sm text-gray-600">{{ $vaccin->rappels_count }} doses</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php
                            $pourcentage = $totalRappels > 0 ? round(($vaccin->rappels_count / $totalRappels) * 100) : 0;
                            $colors = ['bg-blue-600', 'bg-green-600', 'bg-yellow-600', 'bg-purple-600', 'bg-red-600'];
                        @endphp
                        <div class="{{ $colors[$index % 5] }} h-2.5 rounded-full" style="width: {{ $pourcentage }}%"></div>
                    </div>
                </div>
                @endforeach
                @if($topVaccins->isEmpty())
                    <p class="text-gray-500 text-center py-4">Aucune donnée disponible</p>
                @endif
            </div>
        </div>
    </div>

    <!-- SECTION 5: PROCHAINS RAPPELS -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-bell text-yellow-600 mr-2"></i>
            Prochains rappels (7 jours)
        </h3>
        @if($prochainsRappels->isEmpty())
            <p class="text-gray-500 text-center py-8">Aucun rappel prévu</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">
                @foreach($prochainsRappels as $rappel)
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-3 border border-yellow-200">
                    <div class="text-center">
                        <p class="font-bold text-sm">{{ $rappel->enfant->prenom }}</p>
                        <p class="text-xs text-gray-600">{{ $rappel->vaccin->nom }}</p>
                        <p class="text-xs font-medium text-yellow-800 mt-2 bg-yellow-200 rounded-full py-1">
                            {{ $rappel->date_prevue->format('d/m') }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- SECTION 6: DERNIÈRES ACTIVITÉS -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-history text-gray-600 mr-2"></i>
            Dernières activités
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enfant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vaccin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentRappels as $rappel)
                    <tr>
                        <td class="px-6 py-4 text-sm">{{ $rappel->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">{{ $rappel->enfant->prenom }} {{ $rappel->enfant->nom }}</td>
                        <td class="px-6 py-4">{{ $rappel->enfant->parent->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $rappel->vaccin->nom }}</td>
                        <td class="px-6 py-4">
                            @if($rappel->statut == 'effectue')
                                <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full font-medium">Effectué</span>
                            @elseif($rappel->statut == 'en_attente')
                                <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full font-medium">En attente</span>
                            @else
                                <span class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded-full font-medium">Reporté</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques (les mêmes que précédemment) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [
                {
                    label: 'Rappels programmés',
                    data: {!! json_encode($rappelsData) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                },
                {
                    label: 'Vaccins effectués',
                    data: {!! json_encode($effectuesData) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Graphique des statuts
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Effectués', 'En attente', 'Reportés'],
            datasets: [{
                data: [{{ $vaccinsEffectues }}, {{ $vaccinsEnAttente }}, {{ $vaccinsReportes }}],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            cutout: '65%',
        }
    });

    // Graphique des âges
    const ageCtx = document.getElementById('ageChart').getContext('2d');
    new Chart(ageCtx, {
        type: 'pie',
        data: {
            labels: ['Moins d\'1 an', '1-5 ans', 'Plus de 5 ans'],
            datasets: [{
                data: [{{ $enfantsMoins1An }}, {{ $enfants1a5Ans }}, {{ $enfantsPlus5Ans }}],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endsection