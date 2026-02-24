@extends('layouts.admin')

@section('title', 'Gestion des vaccins')

@section('content')
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-xl font-semibold">Liste des vaccins</h2>
            <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-plus mr-2"></i>Ajouter un vaccin
            </a>
        </div>
        
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Âge min (mois)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Âge max</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dose</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Maladie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date début</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date fin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($vaccins as $vaccin)
                <tr>
                    <td class="px-6 py-4">{{ $vaccin->id }}</td>
                    <td class="px-6 py-4">{{ $vaccin->nom }}</td>
                    <td class="px-6 py-4">{{ $vaccin->age_min_mois }}</td>
                    <td class="px-6 py-4">{{ $vaccin->age_max_mois ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $vaccin->dose_numero ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $vaccin->maladie_previent }}</td>
                    
                    <!-- CORRECTION DES DATES ICI - Plus de 00:00:00 -->
                    <td class="px-6 py-4">{{ $vaccin->date_debut ? \Carbon\Carbon::parse($vaccin->date_debut)->format('d/m/Y') : '-' }}</td>
                    <td class="px-6 py-4">{{ $vaccin->date_fin ? \Carbon\Carbon::parse($vaccin->date_fin)->format('d/m/Y') : '-' }}</td>
                    
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.vaccins.edit', $vaccin->id) }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors duration-200 text-sm font-medium">
                                Modifier
                            </a>
                            
                            <form action="{{ route('admin.vaccins.delete', $vaccin->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce vaccin ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors duration-200 text-sm font-medium">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $vaccins->links() }}
    </div>
@endsection