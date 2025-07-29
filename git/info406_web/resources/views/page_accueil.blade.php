@extends('template')

@section('title')
Accueil
@endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<section class="hero">
    <div class="container">
        <h1 class="display-2 fw-bold">📚 Book'in France</h1>
        <h2 class="display-4 fw-italic">Lien vers l'application :</h2>
        <div class="row justify-content-center my-4">
            <div class="col-md-4 col-6">
                <a href="https://www.google.com/search?q=pacman&oq=pacman" target="_blank">
                    <img src="{{ asset('images/app_store.png') }}" alt="Télécharger sur l'App Store" class="img-fluid shadow-lg rounded mb-3 fixed-dimensions">
                </a>
            </div>
            <div class="col-md-4 col-6">
                <a href="https://www.google.com/search?q=pacman&oq=pacman" target="_blank">
                    <img src="{{ asset('images/google-play.png') }}" alt="Télécharger sur Google Play" class="img-fluid shadow-lg rounded mb-3 fixed-dimensions">
                </a>
            </div>
        </div>
        <p class="lead fs-4">Partagez, empruntez et découvrez des livres à travers toute la France.</p>
        <div class="mt-4">
            @can('manage users')
                <a href="{{ route('gestion_users') }}" class="btn btn-light btn-lg me-3 shadow-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle me-2" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                    </svg>
                    Gestion Utilisateurs
                </a>
            @endcan
            <p class='text-white-50 mt-2 small'>Réservé aux employés de Book'in</p>
            @can('manage books')
                <a href="{{ route('gestion_boites') }}" class="btn btn-light btn-lg me-3 shadow-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-boxes me-2" viewBox="0 0 16 16">
                        <path d="M7.752.066a.5.5 0 0 1 .496 0l3.75 2.143a.5.5 0 0 1 .252.434v3.995l3.498 2A.5.5 0 0 1 16 9.07v4.286a.5.5 0 0 1-.252.434l-3.75 2.143a.5.5 0 0 1-.496 0l-3.502-2-3.502 2.001a.5.5 0 0 1-.496 0l-3.75-2.143A.5.5 0 0 1 0 13.357V9.071a.5.5 0 0 1 .252-.434L3.75 6.638V2.643a.5.5 0 0 1 .252-.434L7.752.066ZM4.25 7.504 1.508 9.071l2.742 1.567 2.742-1.567L4.25 7.504ZM7.5 9.933l-2.75 1.571v3.134l2.75-1.571V9.933Zm1 3.134 2.75 1.571v-3.134L8.5 9.933v3.134Zm.508-3.996 2.742 1.567 2.742-1.567-2.742-1.567-2.742 1.567Zm2.242-2.433V3.504L8.5 5.076V8.21l2.75-1.572ZM7.5 8.21V5.076L4.75 3.504v3.134L7.5 8.21ZM5.258 2.643 8 4.21l2.742-1.567L8 1.076 5.258 2.643ZM15 9.933l-2.75 1.571v3.134L15 13.067V9.933ZM3.75 14.638v-3.134L1 9.933v3.134l2.75 1.571Z"/>
                    </svg>
                    Gestion des Boîtes
                </a>
            @endcan
        </div>
    </div>
</section>

<section class="features py-5">
    <div class="container">
        <h2 class="mb-4 fw-bold">📚 Book'in - Partage de la lecture</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0 transition-all h-100">
                    <div class="card-body">
                        <h3 class="card-title">🌍 Accessible partout</h3>
                        <p class="card-text">Des centaines de boîtes à livres réparties dans toute la France.</p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z"/>
                                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                            </svg>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0 transition-all h-100">
                    <div class="card-body">
                        <h3 class="card-title">📖 Partage et échange</h3>
                        <p class="card-text">Donnez une seconde vie à vos livres et découvrez de nouvelles lectures.</p>
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-arrows-repeat" viewBox="0 0 16 16">
                            <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                            <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0 transition-all h-100">
                    <div class="card-body">
                        <h3 class="card-title">💬 Communauté engagée</h3>
                        <p class="card-text">Rejoignez une communauté de passionnés de lecture.</p>
                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="bi bi-chat-square-dots" viewBox="0 0 16 16">
                            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                            <path d="M5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="map py-5">
    <div class="container">
        <h2 class="mb-4 fw-bold">📍 Nos boîtes à livres en France</h2>
        <div id="map" class="shadow-lg rounded-3" style="height: 500px; width: 100%;"></div>
    </div>
</section>

<section class="concept py-5">
    <div class="container">
        <h2 class="mb-4 fw-bold">💡 Le concept de Book'in</h2>
        <p class="fs-4">Book'in repose sur un principe simple : mettre à disposition des boîtes à livres accessibles à tous. 📖<br> Empruntez un livre, déposez-en un et partagez le plaisir de la lecture !</p>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var map = L.map('map').setView([46.603354, 1.888334], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var boxes = @json($boxes); // données passées à la vue

        boxes.forEach(function(box) {
            if (box.latitude && box.longitude) {
                L.marker([box.latitude, box.longitude])
                    .addTo(map)
                    .bindPopup(`<strong>${box.name}</strong><br>${box.address}<br>État: ${box.etat}`);
            }
        });
    });
</script>
@endpush
