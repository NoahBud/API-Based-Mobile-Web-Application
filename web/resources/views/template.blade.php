<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    @stack('head')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

<header>
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-book me-2" viewBox="0 0 16 16">
                <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783c1.664-.203 3.363-.388 4.15-.663 1.592-.276 2.856-.764 2.856-1.12 0-.356-1.264-.844-2.856-1.12-.787-.275-2.486-.46-4.15-.663v1.466z"/>
            </svg>
            <a href="{{ route('accueil') }}" class="text-decoration-none text-black">Book'in</a>
        </h2>
        @auth
            <div class="d-flex justify-content-end align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> Mon compte
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-person"></i> Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="post" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-door-closed-fill"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-end align-items-center my-3">
                <a href="{{ route('login') }}" class="btn btn-outline-light">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </a>
            </div>
        @endauth
    </div>
</header>

<div class="container">
    @yield('content')
</div>

<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-heart-fill text-danger me-2" viewBox="0 0 16 16">
            <path d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
        </svg>
        <p class="mb-0">&copy; 2025, Book'in - Partage de la lecture</p>
        <p class="mb-0">
            <a href="{{ route('ml') }}" class="text-light fw-bold text-decoration-underline">
                Mentions légales
            </a>
        </p>
    </div>
</footer>



@stack('scripts')

</body>
</html>