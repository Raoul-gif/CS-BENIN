@extends('layouts.admin')

@section('title', $centre ? 'Modifier un centre' : 'Ajouter un centre')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-lg shadow-lg p-6 text-white mb-6">
        <h2 class="text-2xl font-bold">{{ $centre ? 'Modifier' : 'Ajouter' }} un centre de santé</h2>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <form action="{{ $centre ? route('admin.centres.update', $centre->id) : route('admin.centres.store') }}" 
              method="POST">
            @csrf
            @if($centre) @method('PUT') @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nom du centre *</label>
                    <input type="text" name="nom" value="{{ $centre->nom ?? old('nom') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('nom') border-red-500 @enderror" required>
                    @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ville *</label>
                    <input type="text" name="ville" value="{{ $centre->ville ?? old('ville') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('ville') border-red-500 @enderror" required>
                    @error('ville') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Téléphone *</label>
                    <input type="text" name="telephone" value="{{ $centre->telephone ?? old('telephone') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('telephone') border-red-500 @enderror" required>
                    @error('telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" value="{{ $centre->email ?? old('email') }}" 
                           class="w-full border rounded-lg px-4 py-2 @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Responsable</label>
                    <input type="text" name="responsable" value="{{ $centre->responsable ?? old('responsable') }}" 
                           class="w-full border rounded-lg px-4 py-2">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Statut</label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="actif" value="1" {{ ($centre && $centre->actif) || !$centre ? 'checked' : '' }}>
                        <span>Centre actif</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Adresse</label>
                <textarea name="adresse" rows="2" class="w-full border rounded-lg px-4 py-2">{{ $centre->adresse ?? old('adresse') }}</textarea>
            </div>
            
            <div class="mt-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Horaires d'ouverture</label>
                <textarea name="horaires" rows="2" class="w-full border rounded-lg px-4 py-2" 
                          placeholder="Ex: Lundi-Vendredi: 8h-18h, Samedi: 9h-13h">{{ $centre->horaires ?? old('horaires') }}</textarea>
            </div>
            
            <div class="mt-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Description / Informations complémentaires</label>
                <textarea name="description" rows="3" class="w-full border rounded-lg px-4 py-2">{{ $centre->description ?? old('description') }}</textarea>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.centres') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Annuler
                </a>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    {{ $centre ? 'Mettre à jour' : 'Enregistrer' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection