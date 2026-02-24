@extends('layouts.admin')

@section('title', 'Modifier un utilisateur')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nom</label>
                <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded px-3 py-2">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded px-3 py-2">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Téléphone</label>
                <input type="text" name="telephone" value="{{ $user->telephone }}" class="w-full border rounded px-3 py-2">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Langue préférée</label>
                <select name="langue_preferee" class="w-full border rounded px-3 py-2">
                    <option value="fr" {{ $user->langue_preferee == 'fr' ? 'selected' : '' }}>Français</option>
                    <option value="fon" {{ $user->langue_preferee == 'fon' ? 'selected' : '' }}>Fon</option>
                    <option value="yoruba" {{ $user->langue_preferee == 'yoruba' ? 'selected' : '' }}>Yoruba</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Rôle</label>
                <select name="role" class="w-full border rounded px-3 py-2">
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Utilisateur</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrateur</option>
                </select>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.users') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2">Annuler</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Mettre à jour</button>
            </div>
        </form>
    </div>
@endsection