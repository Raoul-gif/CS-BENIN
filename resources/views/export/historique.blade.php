{{-- resources/views/export/historique.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des carnets - {{ $enfant->prenom }} {{ $enfant->nom }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .pagination { @apply flex justify-center space-x-1; }
        .page-link { @apply px-3 py-1 rounded border hover:bg-gray-100 transition; }
        .page-item.active .page-link { @apply bg-green-600 text-white border-green-600; }
        .page-item.disabled .page-link { @apply text-gray-400 cursor-not-allowed; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-6">
        {{-- En-tête --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Historique des carnets de vaccination
                </h1>
                <p class="text-gray-600">
                    Enfant: <span class="font-semibold">{{ $enfant->prenom }} {{ $enfant->nom }}</span>
                    | Né(e) le: <span class="font-semibold">{{ $enfant->date_naissance->format('d/m/Y') }}</span>
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('export.carnet', $enfant) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-file-pdf mr-2"></i>Générer nouveau carnet
                </a>
                <a href="{{ route('export.apercu', $enfant) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-eye mr-2"></i>Aperçu
                </a>
            </div>
        </div>
{{-- Après l'en-tête, avant le tableau --}}
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-4 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Recherche --}}
            <div class="flex-1">
                <label for="search" class="sr-only">Rechercher</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           id="search" 
                           placeholder="Rechercher par date, statut..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            
            {{-- Filtres --}}
            <div class="flex gap-2">
                <select id="status-filter" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Tous les statuts</option>
                    <option value="valide">Valide</option>
                    <option value="archive"> Archivé</option>
                    <option value="brouillon">Brouillon</option>
                </select>
                
                <select id="sort-filter" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500">
                    <option value="desc">Plus récent d'abord</option>
                    <option value="asc">Plus ancien d'abord</option>
                </select>
                
                <button id="export-csv" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-download mr-2"></i>CSV
                </button>
            </div>
        </div>
    </div>
    
    {{-- Résultats de recherche --}}
    <div id="search-results" class="hidden p-4 bg-yellow-50 border-b border-yellow-200">
        <div class="flex items-center justify-between">
            <span class="text-yellow-800">
                <i class="fas fa-info-circle mr-2"></i>
                <span id="result-count">0</span> résultat(s) trouvé(s)
            </span>
            <button id="clear-search" class="text-yellow-600 hover:text-yellow-800">
                <i class="fas fa-times"></i> Effacer
            </button>
        </div>
    </div>
</div>
        {{-- Liste des carnets --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if($carnets->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date de génération
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Validité
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Signature
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($carnets as $carnet)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $carnet->date_generation->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $carnet->date_generation->format('H:i:s') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($carnet->statut === 'valide')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                             Valide
                                        </span>
                                    @elseif($carnet->statut === 'archive')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                             Archivé
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                             Brouillon
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($carnet->date_expiration)
                                        @if($carnet->date_expiration->isFuture())
                                            <span class="text-green-600">
                                                Expire le {{ $carnet->date_expiration->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-red-600">
                                                Expiré le {{ $carnet->date_expiration->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Non définie</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($carnet->signature)
                                        <span class="text-xs text-green-600" title="Document signé">
                                            🔏 Signé
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">
                                            Non signé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ Storage::url($carnet->fichier_pdf) }}" 
                                           target="_blank"
                                           class="text-blue-600 hover:text-blue-900"
                                           title="Voir">
                                            👁️
                                        </a>
                                        <a href="{{ route('export.verifier', $carnet) }}" 
                                           class="text-green-600 hover:text-green-900"
                                           title="Vérifier l'intégrité">
                                            ✅
                                        </a>
                                        <form action="{{ route('export.supprimer', $carnet) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Supprimer ce carnet ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Supprimer">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $carnets->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-file-pdf text-6xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Aucun carnet généré
                    </h3>
                    <p class="text-gray-500 mb-6">
                        Commencez par générer le premier carnet de vaccination pour {{ $enfant->prenom }}.
                    </p>
                    <a href="{{ route('export.carnet', $enfant) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Générer mon premier carnet
                    </a>
                </div>
            @endif
        </div>

        {{-- Informations complémentaires --}}
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        À propos des carnets numériques
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Les carnets sont générés au format PDF sécurisé</li>
                            <li>Chaque carnet possède une signature numérique unique</li>
                            <li>La validité d'un carnet est d'un an à partir de sa génération</li>
                            <li>Vous pouvez vérifier l'authenticité d'un carnet à tout moment</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
document.querySelectorAll('.verify-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.preventDefault();
        const url = btn.href;
        const carnetId = btn.dataset.carnetId;
        const messageDiv = document.getElementById(`verify-message-${carnetId}`);
        
        try {
            const response = await fetch(url);
            const data = await response.json();
            
            messageDiv.classList.remove('hidden');
            messageDiv.classList.add(data.valide ? 'bg-green-100' : 'bg-red-100');
            messageDiv.textContent = data.message;
            
            // Faire disparaître après 5 secondes
            setTimeout(() => {
                messageDiv.classList.add('hidden');
            }, 5000);
            
        } catch (error) {
            console.error('Erreur:', error);
        }
    });
});
</script>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const statusFilter = document.getElementById('status-filter');
    const sortFilter = document.getElementById('sort-filter');
    const searchResults = document.getElementById('search-results');
    const resultCount = document.getElementById('result-count');
    const clearSearch = document.getElementById('clear-search');
    const tableRows = document.querySelectorAll('tbody tr');
    
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value;
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            const date = row.querySelector('td:first-child').textContent.toLowerCase();
            const status = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const matchesSearch = date.includes(searchTerm) || status.includes(searchTerm);
            const matchesStatus = !statusValue || status.includes(statusValue);
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Trier les résultats
        if (sortFilter.value) {
            sortTable(sortFilter.value);
        }
        
        // Afficher les résultats
        if (searchTerm || statusValue) {
            searchResults.classList.remove('hidden');
            resultCount.textContent = visibleCount;
        } else {
            searchResults.classList.add('hidden');
        }
    }
    
    function sortTable(order) {
        const tbody = document.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr:not([style*="display: none"])'));
        
        rows.sort((a, b) => {
            const dateA = a.querySelector('td:first-child .text-sm').textContent;
            const dateB = b.querySelector('td:first-child .text-sm').textContent;
            const [dayA, monthA, yearA] = dateA.split('/');
            const [dayB, monthB, yearB] = dateB.split('/');
            
            const timestampA = new Date(yearA, monthA - 1, dayA).getTime();
            const timestampB = new Date(yearB, monthB - 1, dayB).getTime();
            
            return order === 'desc' ? timestampB - timestampA : timestampA - timestampB;
        });
        
        rows.forEach(row => tbody.appendChild(row));
    }
    
    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
    sortFilter.addEventListener('change', () => filterTable());
    
    clearSearch.addEventListener('click', () => {
        searchInput.value = '';
        statusFilter.value = '';
        filterTable();
    });
    
    // Export CSV
    document.getElementById('export-csv').addEventListener('click', function() {
        const rows = [];
        const headers = ['Date', 'Heure', 'Statut', 'Validité', 'Signature'];
        rows.push(headers.join(','));
        
        document.querySelectorAll('tbody tr').forEach(row => {
            if (row.style.display !== 'none') {
                const cols = [
                    row.querySelector('td:first-child .text-sm').textContent,
                    row.querySelector('td:first-child .text-xs').textContent,
                    row.querySelector('td:nth-child(2) span').textContent.trim(),
                    row.querySelector('td:nth-child(3) span')?.textContent.trim() || 'Non définie',
                    row.querySelector('td:nth-child(4) span')?.textContent.trim() || 'Non signé'
                ];
                rows.push(cols.map(col => `"${col}"`).join(','));
            }
        });
        
        const csv = rows.join('\n');
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'carnets-historique.csv';
        a.click();
    });
});
</script>
@endpush
</body>
</html>