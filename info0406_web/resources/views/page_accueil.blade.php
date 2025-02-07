@extends('template')

@section('content')
<section class="hero bg-primary text-white text-center py-5 mb-5">
    <div class="container">
        <h1 class="display-2 fw-bold">📚 Book'in France</h1>
        <p class="lead">Partagez, empruntez et découvrez des livres à travers toute la France.</p>
        <div class="mt-4">
            @auth
                <a href="{{ route('gestion') }}" class="btn btn-light btn-lg me-3 shadow-lg">Gestion Utilisateurs</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg me-3 shadow-lg">Se connecter</a>
                <p class='text-white-50 mt-2 small'>Réservé aux employés de Book'in</p>
            @endauth
        </div>
    </div>
</section>

<section class="concept py-5 bg-info text-center text-white mb-5">
    <div class="container">
        <h2 class="mb-4 fw-bold text-dark">💡 Le concept de Book'in</h2>
        <p class="fs-4">Book'in repose sur un principe simple : mettre à disposition des boîtes à livres accessibles à tous. 📖<br> Empruntez un livre, déposez-en un et partagez le plaisir de la lecture !</p>
    </div>
</section>

<section class="map py-5 text-center bg-success text-white mb-5">
    <div class="container">
        <h2 class="mb-4 fw-bold">📍 Nos boîtes à livres en France</h2>
        <div id="map" class="shadow-lg rounded-3" style="height: 500px; width: 100%;"></div>
        <!-- ajouter carte de France avec les boites a livre ? -->
    </div>
</section>

<section class="features py-5 text-center bg-warning text-dark mb-5">
    <div class="container">
        <h2 class="mb-4 fw-bold text-dark">📚 Book'in - Partage de la lecture</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <h3 class="card-title text-primary">🌍 Accessible partout</h3>
                        <p>Des centaines de boîtes à livres réparties dans toute la France.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <h3 class="card-title text-primary">📖 Partage et échange</h3>
                        <p>Donnez une seconde vie à vos livres et découvrez de nouvelles lectures.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body">
                        <h3 class="card-title text-primary">💬 Communauté engagée</h3>
                        <p>Rejoignez une communauté de passionnés de lecture.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
