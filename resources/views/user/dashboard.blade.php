@extends('layouts.user')

@section('title', 'Mon espace')

@section('content')
    @php
    use App\Models\Enfant;
    use App\Models\Rappel;
    use Carbon\Carbon;
    
    $enfants = Enfant::where('user_id', auth()->id())->get();
    $enfantsCount = $enfants->count();
    
    $rappelsAVenir = Rappel::whereHas('enfant', function($q) {
        $q->where('user_id', auth()->id());
    })->where('statut', 'en_attente')
      ->with(['vaccin', 'enfant'])
      ->orderBy('date_prevue')
      ->get();
    
    $rappelsEffectues = Rappel::whereHas('enfant', function($q) {
        $q->where('user_id', auth()->id());
    })->where('statut', 'effectue')->count();
    
    $prochainRappel = $rappelsAVenir->first();
    @endphp

    <!-- Message de bienvenue personnalisé -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white mb-6">
        <h1 class="text-2xl font-bold">Bonjour, {{ auth()->user()->name }} !</h1>
        <p class="text-blue-100 mt-2">Gérez le carnet de santé de vos enfants facilement.</p>
    </div>

    <!-- Statistiques personnelles -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Mes enfants</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $enfantsCount }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-child text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Vaccins à venir</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $rappelsAVenir->count() }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Vaccins effectués</p>
                    <p class="text-3xl font-bold text-green-600">{{ $rappelsEffectues }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Prochain rappel (mise en avant) -->
    @if($prochainRappel)
    <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-sm text-blue-600 font-semibold">PROCHAIN RAPPEL</span>
                <h3 class="text-xl font-bold mt-1">{{ $prochainRappel->vaccin->nom }}</h3>
                <p class="text-gray-600">Pour {{ $prochainRappel->enfant->prenom }} {{ $prochainRappel->enfant->nom }}</p>
                <p class="text-sm text-gray-500 mt-2">
                    <i class="far fa-calendar mr-1"></i> {{ $prochainRappel->date_prevue->format('d/m/Y') }}
                </p>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">
                    {{ $prochainRappel->date_prevue->diffInDays(now()) }}
                </div>
                <p class="text-sm text-gray-500">jours restants</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Liste des enfants -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">👶 Mes enfants</h2>
            <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                <i class="fas fa-plus mr-1"></i> Ajouter un enfant
            </a>
        </div>
        
        @if($enfants->isEmpty())
            <p class="text-gray-500 text-center py-8">
                Vous n'avez pas encore ajouté d'enfant.
            </p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($enfants as $enfant)
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold">{{ $enfant->prenom }} {{ $enfant->nom }}</h3>
                            <p class="text-sm text-gray-600">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $enfant->date_naissance->format('d/m/Y') }}
                                ({{ $enfant->age_formate }})
                            </p>
                            <p class="text-sm text-gray-600 mt-1">
                                <i class="fas fa-venus-mars mr-1"></i> {{ $enfant->sexe == 'M' ? 'Garçon' : 'Fille' }}
                            </p>
                        </div>
                        <div class="text-right">
                            @php
                                $enfantRappels = $rappelsAVenir->where('enfant_id', $enfant->id);
                            @endphp
                            @if($enfantRappels->count() > 0)
                                <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                                    {{ $enfantRappels->count() }} rappel(s)
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="#" class="text-sm text-blue-600 hover:underline">
                            <i class="fas fa-eye mr-1"></i> Voir
                        </a>
                        <a href="#" class="text-sm text-gray-600 hover:underline">
                            <i class="fas fa-edit mr-1"></i> Modifier
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Rappels à venir -->
    @if($rappelsAVenir->isNotEmpty())
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">📅 Rappels à venir</h2>
        <div class="space-y-3">
            @foreach($rappelsAVenir as $rappel)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <i class="fas fa-syringe text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium">{{ $rappel->enfant->prenom }} - {{ $rappel->vaccin->nom }}</p>
                        <p class="text-sm text-gray-600">Dose {{ $rappel->vaccin->dose_numero ?? '1' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm bg-yellow-100 text-yellow-800 px-3 py-1 rounded">
                        {{ $rappel->date_prevue->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
@endsection