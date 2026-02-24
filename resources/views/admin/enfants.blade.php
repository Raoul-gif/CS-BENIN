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

    <!-- Liste des enfants -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold">Liste des enfants</h2>
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
                @foreach($enfants as $enfant)
                <tr>
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
                               class="text-blue-600 hover:text-blue-900"
                               title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <form action="{{ route('admin.enfants.delete', $enfant->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Supprimer cet enfant ? Tous ses rappels seront aussi supprimés.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="p-4 border-t">
            {{ $enfants->links() }}
        </div>
    </div>
</div>
@endsection