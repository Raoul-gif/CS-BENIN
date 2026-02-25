@extends('layouts.admin')

@section('title', 'Statistiques - ' . $centre->nom)

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold">{{ $centre->nom }}</h2>
        <p class="text-blue-100 mt-2">{{ $centre->ville }} • {{ $centre->telephone }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Vaccins effectués</p>
            <p class="text-2xl font-bold">{{ $centre->vaccins_effectues }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Statut</p>
            <p class="text-2xl font-bold text-{{ $centre->actif ? 'green' : 'red' }}-600">
                {{ $centre->actif ? 'Actif' : 'Inactif' }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500">Dernière activité</p>
            <p class="text-2xl font-bold">
                {{ $centre->rappels()->latest()->first()?->date_administration?->format('d/m/Y') ?? 'Jamais' }}
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Top 5 vaccins administrés</h3>
        @if($topVaccins->isEmpty())
            <p class="text-gray-500">Aucun vaccin enregistré dans ce centre</p>
        @else
            <div class="space-y-3">
                @foreach($topVaccins as $vaccin)
                <div>
                    <div class="flex justify-between mb-1">
                        <span>{{ $vaccin->nom }}</span>
                        <span class="font-bold">{{ $vaccin->total }} doses</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($vaccin->total / $topVaccins->max('total')) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection