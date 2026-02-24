@extends('layouts.admin')

@section('title', 'Modifier un vaccin')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('admin.vaccins.update', $vaccin->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nom du vaccin</label>
                <input type="text" name="nom" value="{{ $vaccin->nom }}" class="w-full border rounded px-3 py-2 @error('nom') border-red-500 @enderror" required>
                @error('nom')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ $vaccin->description }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Âge minimum (mois)</label>
                    <input type="number" step="0.1" name="age_min_mois" value="{{ $vaccin->age_min_mois }}" class="w-full border rounded px-3 py-2 @error('age_min_mois') border-red-500 @enderror" required>
                    @error('age_min_mois')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Âge maximum (mois)</label>
                    <input type="number" step="0.1" name="age_max_mois" value="{{ $vaccin->age_max_mois }}" class="w-full border rounded px-3 py-2 @error('age_max_mois') border-red-500 @enderror">
                    @error('age_max_mois')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Numéro de dose</label>
                    <input type="number" name="dose_numero" value="{{ $vaccin->dose_numero }}" class="w-full border rounded px-3 py-2 @error('dose_numero') border-red-500 @enderror">
                    @error('dose_numero')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Maladie prévenue</label>
                    <input type="text" name="maladie_previent" value="{{ $vaccin->maladie_previent }}" class="w-full border rounded px-3 py-2 @error('maladie_previent') border-red-500 @enderror" required>
                    @error('maladie_previent')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- NOUVEAU : Champs de date ajoutés -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Date de début</label>
                    <input type="date" name="date_debut" value="{{ $vaccin->date_debut }}" class="w-full border rounded px-3 py-2 @error('date_debut') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Date à partir de laquelle ce vaccin est disponible</p>
                    @error('date_debut')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Date de fin</label>
                    <input type="date" name="date_fin" value="{{ $vaccin->date_fin }}" class="w-full border rounded px-3 py-2 @error('date_fin') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Date après laquelle ce vaccin n'est plus recommandé</p>
                    @error('date_fin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end space-x-2 mt-6">
                <a href="{{ route('admin.vaccins') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
@endsection