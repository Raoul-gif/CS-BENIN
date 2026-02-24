@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
        use App\Models\User;
        use App\Models\Enfant;
        use App\Models\Vaccin;
        use App\Models\Rappel;
        
        $totalUsers = User::count();
        $totalEnfants = Enfant::count();
        $totalVaccins = Vaccin::count();
        $totalRappels = Rappel::count();
        @endphp
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">👥 Utilisateurs</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">👶 Enfants</h3>
            <p class="text-3xl font-bold text-green-600">{{ $totalEnfants }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">💉 Vaccins</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $totalVaccins }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700">📅 Rappels</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $totalRappels }}</p>
        </div>
    </div>
    
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Bienvenue, {{ auth()->user()->name }} !</h2>
        <p class="text-gray-600">
            Utilisez le menu de gauche pour gérer les utilisateurs, les vaccins et consulter les statistiques.
        </p>
        
        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded">
            <p class="text-blue-800">
                <strong>Statut :</strong> Vous êtes connecté en tant qu'administrateur.
            </p>
            <p class="text-blue-600 mt-2">
                📊 <a href="{{ route('admin.users') }}" class="underline">Gérer les utilisateurs</a> | 
                💉 <a href="{{ route('admin.vaccins') }}" class="underline">Gérer les vaccins</a> | 
                📈 <a href="{{ route('admin.statistiques') }}" class="underline">Voir les statistiques</a>
            </p>
        </div>
    </div>
@endsection