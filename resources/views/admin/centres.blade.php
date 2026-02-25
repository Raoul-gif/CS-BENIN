@extends('layouts.admin')

@section('title', 'Centres de santé')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold">Gestion des centres de santé</h2>
        <p class="text-green-100 mt-2">Liste des centres où les parents peuvent vacciner leurs enfants</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total centres</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-hospital text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Centres actifs</p>
                    <p class="text-2xl font-bold">{{ $stats['actifs'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Vaccins effectués</p>
                    <p class="text-2xl font-bold">{{ $stats['vaccins_total'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-syringe text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et bouton ajouter -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <form action="{{ route('admin.centres') }}" method="GET" class="flex-1 max-w-md">
                <div class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Rechercher par nom, ville ou responsable..."
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <a href="{{ route('admin.centres.ajouter') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Ajouter un centre
            </a>
        </div>
    </div>

    <!-- Liste des centres -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ville</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Téléphone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Responsable</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vaccins</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($centres as $centre)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $centre->id }}</td>
                    <td class="px-6 py-4 font-medium">{{ $centre->nom }}</td>
                    <td class="px-6 py-4">{{ $centre->ville }}</td>
                    <td class="px-6 py-4">{{ $centre->telephone }}</td>
                    <td class="px-6 py-4">{{ $centre->responsable ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @if($centre->actif)
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Actif</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Inactif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                            {{ $centre->vaccins_effectues }} vaccins
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.centres.stats', $centre->id) }}" 
                               class="text-green-600 hover:text-green-900" title="Statistiques">
                                <i class="fas fa-chart-bar"></i>
                            </a>
                            <a href="{{ route('admin.centres.modifier', $centre->id) }}" 
                               class="text-blue-600 hover:text-blue-900" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.centres.supprimer', $centre->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Supprimer ce centre de santé ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-hospital text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500 text-lg">Aucun centre de santé trouvé</p>
                            <a href="{{ route('admin.centres.ajouter') }}" class="mt-2 text-green-600 hover:underline">
                                Ajouter votre premier centre
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t">
            {{ $centres->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection