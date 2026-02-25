<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Carnet Santé') }} - Admin</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'figtree', sans-serif;
            background: #f1f5f9;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            background: #1e293b;
            color: white;
            width: 260px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        
        .admin-sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #334155;
        }
        
        /* Logo professionnel - Option 1 */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .logo-icon i {
            font-size: 1.5rem;
            color: white;
        }
        
        .logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        
        .logo-line1 {
            font-size: 0.9rem;
            font-weight: 500;
            color: #e2e8f0;
            letter-spacing: 0.5px;
        }
        
        .logo-line2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .admin-sidebar-nav {
            flex: 1;
            padding: 20px 0;
        }
        
        .admin-sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #cbd5e1;
            text-decoration: none;
            transition: 0.3s;
            border-left: 3px solid transparent;
            font-size: 0.95rem;
        }
        
        .admin-sidebar-nav a i {
            width: 20px;
            font-size: 1.1rem;
            color: #94a3b8;
            transition: 0.3s;
        }
        
        .admin-sidebar-nav a:hover {
            background: #334155;
            color: white;
            border-left-color: #3b82f6;
        }
        
        .admin-sidebar-nav a:hover i {
            color: white;
        }
        
        .admin-sidebar-nav a.active {
            background: #334155;
            color: white;
            border-left-color: #3b82f6;
        }
        
        .admin-sidebar-nav a.active i {
            color: white;
        }
        
        /* Section utilisateur en bas de la sidebar */
        .admin-sidebar-footer {
            border-top: 1px solid #334155;
            padding: 15px;
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-menu-button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            background: #2d3a4f;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            color: white;
            font-size: 0.9rem;
        }
        
        .user-menu-button:hover {
            background: #3b82f6;
        }
        
        .user-avatar {
            width: 28px;
            height: 28px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.8rem;
        }
        
        .user-info {
            flex: 1;
            overflow: hidden;
        }
        
        .user-name {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.85rem;
        }
        
        .user-role {
            font-size: 0.6rem;
            color: #94a3b8;
        }
        
        /* Menu qui s'ouvre au survol */
        .user-menu-content {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            margin-bottom: 8px;
            background: transparent;
            border-radius: 6px;
            overflow: hidden;
            z-index: 1000;
            min-width: 160px;
        }
        
        .user-menu:hover .user-menu-content {
            display: block;
        }
        
        .user-menu-content a, .user-menu-content button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            text-align: left;
            padding: 8px 12px;
            color: #e2e8f0;
            text-decoration: none;
            border: none;
            background: #2d3a4f;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.8rem;
            border-left: 2px solid transparent;
        }
        
        .user-menu-content a i, .user-menu-content button i {
            width: 16px;
            font-size: 0.9rem;
        }
        
        .user-menu-content a:hover, .user-menu-content button:hover {
            background: #3b82f6;
            color: white;
            border-left-color: white;
        }
        
        .user-menu-content hr {
            margin: 2px 0;
            border: none;
            border-top: 1px solid #4a5568;
            opacity: 0.3;
        }
        
        .admin-content {
            flex: 1;
            margin-left: 260px;
            padding: 20px;
            background: #f1f5f9;
            min-height: 100vh;
        }
        
        .admin-header {
            background: white;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .admin-badge {
            background: #3b82f6;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.7rem;
            margin-left: 5px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #3b82f6;
        }
        
        .alert {
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
        }
        
        .alert-error {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
        }
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
        
        .table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .table th {
            background: #f8fafc;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #475569;
        }
        
        .table td {
            padding: 12px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar avec menu utilisateur en bas -->
        <div class="admin-sidebar">
            <div class="admin-sidebar-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-line1">Carnet de Santé</span>
                        <span class="logo-line2">BÉNIN</span>
                    </div>
                </div>
            </div>
            
            <nav class="admin-sidebar-nav">
                <a href="{{ route('admin.home') }}" class="{{ request()->routeIs('admin.home') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    Utilisateurs
                </a>
                <a href="{{ route('admin.enfants') }}" class="{{ request()->routeIs('admin.enfants*') ? 'active' : '' }}">
                    <i class="fas fa-child"></i>
                    Enfants
                </a>
                <a href="{{ route('admin.vaccins') }}" class="{{ request()->routeIs('admin.vaccins*') ? 'active' : '' }}">
                    <i class="fas fa-syringe"></i>
                    Vaccins
                </a>
                <!-- NOUVEAU MENU CENTRES DE SANTÉ -->
                <a href="{{ route('admin.centres') }}" class="{{ request()->routeIs('admin.centres*') ? 'active' : '' }}">
                    <i class="fas fa-hospital"></i>
                    Centres de santé
                </a>
                <a href="{{ route('admin.statistiques') }}" class="{{ request()->routeIs('admin.statistiques') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    Statistiques
                </a>
                <a href="{{ route('admin.management') }}" class="{{ request()->routeIs('admin.management*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i>
                    Administrateurs
                </a>
                <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    Paramètres
                </a>
            </nav>
            
            <!-- Footer avec menu utilisateur -->
            <div class="admin-sidebar-footer">
                <div class="user-menu">
                    <div class="user-menu-button">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="user-role">
                                {{ auth()->user()->email }}
                            </div>
                        </div>
                        <i class="fas fa-chevron-up" style="font-size: 0.7rem;"></i>
                    </div>
                    
                    <div class="user-menu-content">
                        <a href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-circle"></i>
                            Profil
                        </a>
                        <hr>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">
                                <i class="fas fa-sign-out-alt"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="admin-content">
            <div class="admin-header">
                <h1>@yield('title', 'Dashboard')</h1>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
</body>
</html>