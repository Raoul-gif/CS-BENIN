<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Carnet Santé') }} - Mon espace</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
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
            background: #f3f4f6;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-brand {
            font-size: 1.2rem;
            font-weight: bold;
            color: #1e293b;
        }
        
        .nav-brand i {
            color: #3b82f6;
            margin-right: 8px;
        }
        
        .user-menu {
            position: relative;
        }
        
        .user-menu-button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 16px;
            background: #f3f4f6;
            border-radius: 30px;
            cursor: pointer;
            transition: 0.3s;
        }
        
        .user-menu-button:hover {
            background: #e5e7eb;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .user-menu-content {
            display: none;
            position: absolute;
            right: 0;
            top: 120%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            min-width: 200px;
            z-index: 1000;
        }
        
        .user-menu:hover .user-menu-content {
            display: block;
        }
        
        .user-menu-content a, .user-menu-content button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 12px 16px;
            color: #1e293b;
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
            transition: 0.3s;
        }
        
        .user-menu-content a:hover, .user-menu-content button:hover {
            background: #f3f4f6;
        }
        
        .user-menu-content i {
            width: 20px;
            color: #6b7280;
        }
        
        .user-menu-content hr {
            margin: 8px 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
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
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <i class="fas fa-heartbeat"></i>
            Carnet Santé Bénin
        </div>
        
        <div class="user-menu">
            <div class="user-menu-button">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span>{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
            </div>
            
            <div class="user-menu-content">
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-circle"></i>
                    Mon profil
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
    </nav>
    
    <div class="container">
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
</body>
</html>