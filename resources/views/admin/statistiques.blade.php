@extends('layouts.admin')

@section('title', 'Statistiques')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        @php
        use App\Models\User;
        use App\Models\Enfant;
        use App\Models\Vaccin;
        use App\Models\Rappel;
        
        $totalUsers = User::count();
        $totalEnfants = Enfant::count();
        $totalVaccins = Vaccin::count();
        $totalRappels = Rappel::count();
        
        $vaccinsEffectues = Rappel::where('statut', 'effectue')->count();
        $vaccinsEnAttente = Rappel::where('statut', 'en_attente')->count();
        $vaccinsReportes = Rappel::where('statut', 'reporte')->count();
        
        $usersWithEnfants = User::has('enfants')->count();
        $usersWithoutEnfants = User::doesntHave('enfants')->count();
        
        $topVaccins = Vaccin::withCount('rappels')
            ->orderBy('rappels_count', 'desc')
            ->take(5)
            ->get();
        @endphp
        
        <!-- Cartes de statistiques -->
        <div class="bg-white rounded-lg shadow p-6 col-span-2">
            <h2 class="text-xl font-bold mb-4">📊 Aperçu général</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</div>
                    <div class="text-gray-600">Utilisateurs</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $totalEnfants }}</div>
                    <div class="text-gray-600">Enfants</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $totalVaccins }}</div>
                    <div class="text-gray-600">Vaccins</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ $totalRappels }}</div>
                    <div class="text-gray-600">Rappels</div>
                </div>
            </div>
        </div>
        
        <!-- Statut des vaccins -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">💉 Statut des vaccins</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Effectués</span>
                        <span class="font-bold">{{ $vaccinsEffectues }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $totalRappels > 0 ? ($vaccinsEffectues / $totalRappels) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span>En attente</span>
                        <span class="font-bold">{{ $vaccinsEnAttente }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ $totalRappels > 0 ? ($vaccinsEnAttente / $totalRappels) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Reportés</span>
                        <span class="font-bold">{{ $vaccinsReportes }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $totalRappels > 0 ? ($vaccinsReportes / $totalRappels) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Utilisateurs avec/sans enfants -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">👥 Utilisateurs</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Avec enfants</span>
                        <span class="font-bold">{{ $usersWithEnfants }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $totalUsers > 0 ? ($usersWithEnfants / $totalUsers) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Sans enfants</span>
                        <span class="font-bold">{{ $usersWithoutEnfants }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-gray-600 h-2.5 rounded-full" style="width: {{ $totalUsers > 0 ? ($usersWithoutEnfants / $totalUsers) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top 5 vaccins les plus administrés -->
        <div class="bg-white rounded-lg shadow p-6 col-span-2">
            <h2 class="text-lg font-semibold mb-4">🏆 Top 5 vaccins les plus administrés</h2>
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Vaccin</th>
                        <th class="text-left">Nombre d'administrations</th>
                        <th class="text-left">Maladie prévenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topVaccins as $vaccin)
                    <tr class="border-b">
                        <td class="py-2">{{ $vaccin->nom }}</td>
                        <td class="py-2">{{ $vaccin->rappels_count }}</td>
                        <td class="py-2">{{ $vaccin->maladie_previent }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">Aucune donnée disponible</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Informations supplémentaires -->
        <div class="bg-white rounded-lg shadow p-6 col-span-2">
            <h2 class="text-lg font-semibold mb-4">ℹ️ Informations</h2>
            <p class="text-gray-600">
                Dernière mise à jour : {{ now()->format('d/m/Y H:i') }}
            </p>
            <p class="text-gray-600 mt-2">
                Total des rappels : {{ $totalRappels }} ({{ $vaccinsEffectues }} effectués, {{ $vaccinsEnAttente }} en attente, {{ $vaccinsReportes }} reportés)
            </p>
        </div>
    </div>
@endsection