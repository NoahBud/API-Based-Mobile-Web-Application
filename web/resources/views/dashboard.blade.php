@extends('template')

@section('title') Tableau de bord @endsection

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold text-center mb-4">
            <i class="bi bi-speedometer2"></i> Tableau de bord
        </h2>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-lg rounded-4 p-4 text-center">
                    <h4 class="fw-bold"><i class="bi bi-person-circle"></i> Bienvenue, {{ auth()->user()->name }} !</h4>
                    <p class="text-muted">Vous êtes connecté avec l'email : <strong>{{ auth()->user()->email }}</strong></p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-light mt-3">
                        <i class="bi bi-gear"></i> Gérer mon profil
                    </a>
                </div>
            </div>

            @can('manage books')
            <div class="col-md-6">
                <div class="card shadow-lg rounded-4 p-4 text-center">
                    <h5 class="fw-bold">📊 Progression de l'inventaire</h5>
                    <p class="fs-3 fw-bold text-info">{{ $inventoryProgress ?? '0%' }}</p>
                    <p class="text-muted">Boîtes vérifiées : {{ $checkedBoxes ?? 0 }} / {{ $totalBoxes ?? 0 }}</p>
                    <small>(s'actualise tous les mois)</small>
                </div>
            </div>
            @endcan
        </div>

        @can('manage books')
        <div class="mt-5 text-center">
            <a href="{{ route('web.boxes.index') }}" class="btn btn-gradient-blue-green">
                <i class="bi bi-box-seam"></i> Accéder aux boîtes
            </a>
            <a href="{{ route('web.books.index') }}" class="btn btn-gradient-view">
                <i class="bi bi-bookshelf"></i> Voir les livres disponibles
            </a>
        </div>
        @endcan
    </div>
@endsection