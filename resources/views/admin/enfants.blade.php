@extends('layouts.admin')

@section('title', 'Gestion des enfants')

@section('content')
<div class="space-y-6">
    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total enfants</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-child text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Garçons</p>
                    <p class="text-2xl font-bold">{{ $stats['garcons'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-mars text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Filles</p>
                    <p class="text-2xl font-bold">{{ $stats['filles'] }}</p>
                </div>
                <div class="bg-pink-100 p-3 rounded-full">
                    <i class="fas fa-venus text-pink-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Avec rappels</p>
                    <p class="text-2xl font-bold">{{ $stats['avec_rappels'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-bell text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de filtres -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-filter text-blue-600 mr-2"></i>
            Filtrer les enfants
        </h3>
        
        <form action="{{ route('admin.enfants') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nom ou prénom..."
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Âge</label>
                <select name="age" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les âges</option>
                    <option value="moins_1" {{ request('age') == 'moins_1' ? 'selected' : '' }}>Moins d'1 an</option>
                    <option value="1_5" {{ request('age') == '1_5' ? 'selected' : '' }}>1 à 5 ans</option>
                    <option value="plus_5" {{ request('age') == 'plus_5' ? 'selected' : '' }}>Plus de 5 ans</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Parent</label>
                <select name="parent_id" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous les parents</option>
                    @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ request('parent_id') == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Filtrer
                </button>
                <a href="{{ route('admin.enfants') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Réinitialiser
                </a>
            </div>
        </form>
        
        @if(request()->hasAny(['search', 'age', 'parent_id']) && request()->filled('search') || request()->filled('age') || request()->filled('parent_id'))
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Filtres actifs : 
                @if(request('search')) Recherche "{{ request('search') }}" @endif
                @if(request('age')) | Âge : 
                    @if(request('age') == 'moins_1') Moins d'1 an
                    @elseif(request('age') == '1_5') 1 à 5 ans
                    @elseif(request('age') == 'plus_5') Plus de 5 ans
                    @endif
                @endif
                @if(request('parent_id')) | Parent sélectionné @endif
            </div>
        @endif
    </div>

    <!-- Liste des enfants -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-xl font-semibold">Liste des enfants</h2>
            <span class="text-sm text-gray-500">
                {{ $enfants->total() }} résultat(s)
            </span>
        </div>
        
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prénom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Âge</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sexe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rappels</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($enfants as $enfant)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">{{ $enfant->id }}</td>
                    <td class="px-6 py-4">{{ $enfant->nom }}</td>
                    <td class="px-6 py-4">{{ $enfant->prenom }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.users.edit', $enfant->parent->id) }}" class="text-blue-600 hover:underline">
                            {{ $enfant->parent->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        {{ $enfant->age_formate }}
                        <span class="text-xs text-gray-500 block">
                            ({{ $enfant->date_naissance->format('d/m/Y') }})
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($enfant->sexe == 'M')
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Garçon</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-pink-100 text-pink-800 rounded-full">Fille</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs bg-gray-100 rounded-full">
                            {{ $enfant->rappels_count }} rappels
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.enfants.show', $enfant->id) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors"
                               title="Voir détails">
                                <i class="fas fa-eye text-lg"></i>
                            </a>
                            
                            <form action="{{ route('admin.enfants.delete', $enfant->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Supprimer cet enfant ? Tous ses rappels seront aussi supprimés.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Supprimer">
                                    <i class="fas fa-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-child text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500 text-lg">Aucun enfant trouvé</p>
                            @if(request()->hasAny(['search', 'age', 'parent_id']))
                                <p class="text-gray-400">Aucun résultat pour les filtres sélectionnés</p>
                                <a href="{{ route('admin.enfants') }}" class="mt-2 text-blue-600 hover:underline">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Voir tous les enfants
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t">
            {{ $enfants->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection