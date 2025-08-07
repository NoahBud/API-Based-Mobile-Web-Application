@extends('template')

@section('title') Liste des boîtes @endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')

    <form method="GET" action="{{ route('web.boxes.index') }}" class="mb-3 mt-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher une boîte..." value="{{ request()->input('search') }}">
            <button type="submit" class="btn btn-outline-light">
                <i class="bi bi-search"></i> Rechercher
            </button>
        </div>

        <div class="mt-3">
            <label for="etat" class="form-label">Filtrer par état :</label>
            <select name="etat" id="etat" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ request('etat') == 'all' ? 'selected' : '' }}>Tous</option>
                <option value="Neuf" {{ request('etat') == 'Neuf' ? 'selected' : '' }}>Neuf</option>
                <option value="Bon" {{ request('etat') == 'Bon' ? 'selected' : '' }}>Bon</option>
                <option value="Usé" {{ request('etat') == 'Usé' ? 'selected' : '' }}>Usé</option>
                <option value="Très usé" {{ request('etat') == 'Très usé' ? 'selected' : '' }}>Très usé</option>
                <option value="A remplacer" {{ request('etat') == 'A remplacer' ? 'selected' : '' }}>À remplacer</option>
            </select>
        </div>

        <div class="mt-3">
            <label for="sort_inventory" class="form-label">Trier par date d'inventaire :</label>
            <select name="sort_inventory" id="sort_inventory" class="form-select" onchange="this.form.submit()">
                <option value="" {{ request('sort_inventory') == '' ? 'selected' : '' }}>Aucun tri</option>
                <option value="asc" {{ request('sort_inventory') == 'asc' ? 'selected' : '' }}>A faire en priorité</option>
                <option value="desc" {{ request('sort_inventory') == 'desc' ? 'selected' : '' }}>Derniers inventaires</option>
            </select>
        </div>
    </form>


    <a href="{{ route('accueil') }}" class="btn btn-outline-light mb-3">
        <i class="bi bi-arrow-left"></i> Retour à l'accueil
    </a>

    <a href="{{ route('web.boxes.create') }}" class="btn btn-gradient-add mb-3">
        <i class="bi bi-plus-lg"></i> Ajouter une boîte
    </a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @foreach($boxes as $index => $box)
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg border-0 rounded-4 text-light h-100">
                    <div class="card-body">
                        <h4 class="card-title fw-bold">
                            <i class="bi bi-box-seam"></i> {{ $box->name }}
                        </h4>

                        <span class="badge
                            @if($box['etat'] === 'Neuf') badge-gradient-success 
                            @elseif($box['etat'] === 'Bon') badge-gradient-warning2 
                            @elseif($box['etat'] === 'Usé') badge-gradient-warning
                            @elseif($box['etat'] === 'Très usé') badge-gradient-danger 
                            @elseif($box['etat'] === 'A remplacer') bg-dark 
                            @else badge-gradient-secondary 
                            @endif">
                            {{ $box->etat }}
                        </span>

                        <hr class="my-3 border-light">

                        <p class="card-text">
                            <i class="bi bi-geo-alt-fill text-danger"></i> 
                            <strong>Coordonnées GPS :</strong> 
                            {{ $box->latitude }}, {{ $box->longitude }}
                        </p>

                        <p class="card-text">
                            <i class="bi bi-house-door-fill text-primary"></i> 
                            <strong>Adresse :</strong> {{ $box->address }}
                        </p>

                        <div id="map-{{ $index }}" class="map-container"></div>

                        <form action="{{ route('web.boxes.destroy', $box->id) }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-gradient-delete w-100">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>

                        <a href="{{ route('gestion_boites.show', $box->id) }}" class="btn btn-gradient-view w-100 mt-2">
                            <i class="bi bi-eye"></i> Voir son contenu
                        </a>

                        <a href="{{ route('web.boxes.edit', $box->id) }}" class="btn btn-gradient-edit w-100 mt-2">
                            <i class="bi bi-pencil-square"></i> Modifier
                        </a>

                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $boxes->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>


@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var boxes = @json($boxes->items());
        
        boxes.forEach((box, index) => {
            var map = L.map('map-' + index).setView([box.latitude, box.longitude], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            L.marker([box.latitude, box.longitude]).addTo(map)
                .bindPopup(`<b>${box.name}</b><br>${box.address}`)
                .openPopup();
        });
    });
</script>
@endpush

@endsection
