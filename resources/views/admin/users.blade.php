@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="space-y-6">
   
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Barre de recherche -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
        <form action="{{ route('admin.users') }}" method="GET" class="flex gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Rechercher par nom ou email..." 
                       class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" 
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                <i class="fas fa-search mr-2"></i>
                Rechercher
            </button>
            @if(request('search'))
                <a href="{{ route('admin.users') }}" 
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Effacer
                </a>
            @endif
        </form>
    </div>

    <!-- Résultats de recherche -->
    @if(request('search'))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-blue-700">
            <i class="fas fa-info-circle mr-2"></i>
            Résultats pour la recherche : "{{ request('search') }}"
        </div>
    @endif

    <!-- Tableau des utilisateurs -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-semibold text-gray-700">
                <i class="fas fa-users mr-2 text-blue-600"></i>
                Liste des utilisateurs ({{ $users->total() }})
            </h3>
            <span class="text-sm text-gray-500">
                Page {{ $users->currentPage() }} sur {{ $users->lastPage() }}
            </span>
        </div>

        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enfants</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">{{ $user->id }}</td>
                    <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->role == 'admin')
                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full font-medium">
                                <i class="fas fa-crown mr-1"></i> Admin
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full font-medium">
                                <i class="fas fa-user mr-1"></i> Utilisateur
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                            {{ $user->enfants_count }} enfant(s)
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors"
                               title="Modifier">
                                <i class="fas fa-edit text-lg"></i>
                            </a>
                            
                            @if($user->id != auth()->id())
                                <form action="{{ route('admin.users.delete', $user->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition-colors"
                                            title="Supprimer">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400 cursor-not-allowed" title="Vous ne pouvez pas vous supprimer vous-même">
                                    <i class="fas fa-trash text-lg"></i>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-user-slash text-gray-400 text-5xl mb-3"></i>
                            <p class="text-gray-500 text-lg">Aucun utilisateur trouvé</p>
                            @if(request('search'))
                                <p class="text-gray-400">Aucun résultat pour "{{ request('search') }}"</p>
                                <a href="{{ route('admin.users') }}" class="mt-2 text-blue-600 hover:underline">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Retour à la liste complète
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="p-4 border-t bg-gray-50">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection