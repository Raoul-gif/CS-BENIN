@extends('layouts.admin')

@section('title', 'Détails de l\'enfant')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">{{ $enfant->prenom }} {{ $enfant->nom }}</h2>
                <p class="text-blue-100 mt-2">
                    Parent : <a href="{{ route('admin.users.edit', $enfant->parent->id) }}" class="underline">
                        {{ $enfant->parent->name }}
                    </a>
                </p>
            </div>
            <a href="{{ route('admin.enfants') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100">
                ← Retour à la liste
            </a>
        </div>
    </div>

    <!-- Informations générales -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
            Informations générales
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Date de naissance</p>
                <p class="font-semibold">{{ $enfant->date_naissance->format('d/m/Y') }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Âge</p>
                <p class="font-semibold">{{ $enfant->age_formate }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Sexe</p>
                <p class="font-semibold">{{ $enfant->sexe == 'M' ? 'Garçon' : 'Fille' }}</p>
            </div>
        </div>
    </div>

    <!-- Rappels à venir -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-clock text-yellow-600 mr-2"></i>
            Rappels à venir ({{ $rappelsAVenir->count() }})
        </h3>
        
        @if($rappelsAVenir->isEmpty())
            <p class="text-gray-500 text-center py-4">Aucun rappel à venir</p>
        @else
            <div class="space-y-3">
                @foreach($rappelsAVenir as $rappel)
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div>
                        <p class="font-medium">{{ $rappel->vaccin->nom }}</p>
                        <p class="text-sm text-gray-600">Prévu le {{ $rappel->date_prevue->format('d/m/Y') }}</p>
                    </div>
                    <span class="text-sm bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full">
                        Dans {{ $rappel->date_prevue->diffInDays(now()) }} jours
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Vaccins effectués -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-check-circle text-green-600 mr-2"></i>
            Vaccins effectués ({{ $rappelsEffectues->count() }})
        </h3>
        
        @if($rappelsEffectues->isEmpty())
            <p class="text-gray-500 text-center py-4">Aucun vaccin effectué</p>
        @else
            <div class="space-y-3">
                @foreach($rappelsEffectues as $rappel)
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div>
                        <p class="font-medium">{{ $rappel->vaccin->nom }}</p>
                        <p class="text-sm text-gray-600">Administré le {{ $rappel->date_administration->format('d/m/Y') }}</p>
                        @if($rappel->centre_sante)
                            <p class="text-xs text-gray-500">Centre: {{ $rappel->centre_sante }}</p>
                        @endif
                    </div>
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection