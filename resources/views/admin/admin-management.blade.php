@extends('layouts.admin')

@section('title', 'Gestion des administrateurs')

@section('content')
<div class="space-y-6">
   

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- SECTION 1: Recherche d'utilisateurs -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-search text-blue-600 mr-2"></i>
            Rechercher un utilisateur à promouvoir
        </h3>
        
        <form action="{{ route('admin.management') }}" method="GET" class="mb-6">
            <div class="flex gap-2">
                <input type="text" 
                       name="search" 
                       value="{{ $search ?? '' }}"
                       placeholder="Nom ou email de l'utilisateur..." 
                       class="flex-1 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-search mr-2"></i>
                    Rechercher
                </button>
            </div>
        </form>

        @if(isset($search) && $search)
            <h4 class="font-medium text-gray-700 mb-3">Résultats de recherche pour "{{ $search }}" :</h4>
            
            @if($potentialAdmins->isEmpty())
                <div class="bg-gray-50 p-8 text-center rounded-lg">
                    <i class="fas fa-user-slash text-gray-400 text-5xl mb-3"></i>
                    <p class="text-gray-500">Aucun utilisateur trouvé avec ce nom ou email.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($potentialAdmins as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 w-10 h-10 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium">{{ $user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            </div>
                        </div>
                        <form action="{{ route('admin.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <button type="submit" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center"
                                    onclick="return confirm('Promouvoir {{ $user->name }} comme administrateur ?')">
                                <i class="fas fa-user-shield mr-2"></i>
                                Promouvoir Admin
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>

    <!-- SECTION 2: Liste des administrateurs actuels -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
            <i class="fas fa-user-shield text-purple-600 mr-2"></i>
            Administrateurs actuels ({{ $admins->total() }})
        </h3>

        @if($admins->isEmpty())
            <div class="bg-gray-50 p-8 text-center rounded-lg">
                <i class="fas fa-user-shield text-gray-400 text-5xl mb-3"></i>
                <p class="text-gray-500">Aucun administrateur pour le moment.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach($admins as $admin)
                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg border border-purple-100">
                    <div class="flex items-center gap-3">
                        <div class="bg-purple-200 w-12 h-12 rounded-full flex items-center justify-center text-purple-700 font-bold text-lg">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $admin->name }}</p>
                            <p class="text-sm text-gray-600">{{ $admin->email }}</p>
                            <div class="flex gap-2 mt-1">
                                <span class="text-xs bg-purple-200 text-purple-800 px-2 py-1 rounded-full">
                                    <i class="fas fa-calendar-alt mr-1"></i> Depuis {{ $admin->created_at->format('d/m/Y') }}
                                </span>
                                @if($admin->id == auth()->id())
                                <span class="text-xs bg-blue-200 text-blue-800 px-2 py-1 rounded-full">
                                    <i class="fas fa-user mr-1"></i> Vous
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($admin->id != auth()->id())
                    <form action="{{ route('admin.remove', $admin->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center"
                                onclick="return confirm('Retirer {{ $admin->name }} des administrateurs ?')">
                            <i class="fas fa-user-minus mr-2"></i>
                            Retirer
                        </button>
                    </form>
                    @else
                    <span class="bg-gray-200 text-gray-600 px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                        Admin actuel
                    </span>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $admins->links() }}
            </div>
        @endif
    </div>

    <!-- SECTION 3: Informations -->
    <div class="bg-blue-50 rounded-xl border border-blue-200 p-4">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-600 text-xl mt-1"></i>
            <div>
                <h4 class="font-semibold text-blue-800">À propos des administrateurs</h4>
                <p class="text-sm text-blue-700 mt-1">
                    Les administrateurs ont accès à toutes les fonctionnalités de gestion :
                    Dashboard, Utilisateurs, Enfants, Vaccins, Statistiques et Paramètres.
                </p>
                <p class="text-sm text-blue-700 mt-2">
                    <i class="fas fa-shield-alt mr-1"></i>
                    <strong>Note :</strong> Vous ne pouvez pas retirer vos propres droits administrateur.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection