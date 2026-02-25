@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')
<div class="space-y-6">
    

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- SECTION 1: Paramètres des rappels -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                <i class="fas fa-bell text-yellow-600 mr-2"></i>
                Paramètres des rappels
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Jours avant rappel
                    </label>
                    <input type="number" name="rappel_jours_avant" value="{{ $settings['rappel_jours_avant'] }}" 
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           min="1" max="30">
                    <p class="text-xs text-gray-500 mt-1">Nombre de jours avant la date prévue pour envoyer le rappel</p>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Heure d'envoi des rappels
                    </label>
                    <input type="time" name="rappel_heures" value="{{ $settings['rappel_heures'] }}" 
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Heure à laquelle les rappels sont envoyés</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        1er rappel (jours avant)
                    </label>
                    <input type="number" name="delai_rappel_j1" value="{{ $settings['delai_rappel_j1'] }}" 
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           min="1" max="30">
                    <p class="text-xs text-gray-500 mt-1">Premier rappel (ex: 7 jours avant)</p>
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        2ème rappel (jours avant)
                    </label>
                    <input type="number" name="delai_rappel_j2" value="{{ $settings['delai_rappel_j2'] }}" 
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           min="0" max="30">
                    <p class="text-xs text-gray-500 mt-1">Deuxième rappel (ex: 1 jour avant, 0 pour désactiver)</p>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Langues -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                <i class="fas fa-language text-green-600 mr-2"></i>
                Langues disponibles
            </h3>
            
            <div class="space-y-3">
                <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <input type="checkbox" name="langue_fr_active" value="1" {{ $settings['langue_fr_active'] == '1' ? 'checked' : '' }} class="h-5 w-5 text-blue-600 rounded">
                    <span class="font-medium">Français</span>
                    <span class="text-sm text-gray-500">(Langue principale)</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <input type="checkbox" name="langue_fon_active" value="1" {{ $settings['langue_fon_active'] == '1' ? 'checked' : '' }} class="h-5 w-5 text-blue-600 rounded">
                    <span class="font-medium">Fon</span>
                    <span class="text-sm text-gray-500">(Langue locale)</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <input type="checkbox" name="langue_yoruba_active" value="1" {{ $settings['langue_yoruba_active'] == '1' ? 'checked' : '' }} class="h-5 w-5 text-blue-600 rounded">
                    <span class="font-medium">Yoruba</span>
                    <span class="text-sm text-gray-500">(Langue locale)</span>
                </label>
            </div>
        </div>

        <!-- SECTION 3: Informations de contact -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                <i class="fas fa-address-card text-purple-600 mr-2"></i>
                Informations de contact
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Email de support
                    </label>
                    <input type="email" name="email_support" value="{{ $settings['email_support'] }}" 
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Téléphone de support
                    </label>
                    <input type="text" name="telephone_support" value="{{ $settings['telephone_support'] }}" 
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <!-- SECTION 4: Notifications -->
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                <i class="fas fa-bell text-red-600 mr-2"></i>
                Canaux de notification
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <input type="checkbox" name="notification_sms_active" value="1" {{ $settings['notification_sms_active'] == '1' ? 'checked' : '' }} class="h-5 w-5 text-blue-600 rounded">
                    <span class="font-medium">SMS</span>
                    <span class="text-sm text-gray-500">(Notifications par SMS)</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <input type="checkbox" name="notification_email_active" value="1" {{ $settings['notification_email_active'] == '1' ? 'checked' : '' }} class="h-5 w-5 text-blue-600 rounded">
                    <span class="font-medium">Email</span>
                    <span class="text-sm text-gray-500">(Notifications par email)</span>
                </label>
            </div>
        </div>

        <!-- Bouton de sauvegarde -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                <i class="fas fa-save mr-2"></i>
                Enregistrer les paramètres
            </button>
        </div>
    </form>
</div>
@endsection