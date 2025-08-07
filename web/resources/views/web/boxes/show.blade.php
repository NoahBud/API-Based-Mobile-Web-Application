@extends('template')

@section('title') Contenu de {{ $box->name }} @endsection

@section('content')

<a href="{{ route('gestion_boites') }}" class="btn btn-outline-light mb-3">
    <i class="bi bi-arrow-left"></i> Retour à la liste des boîtes
</a>


<div class="card">
    <div class="card-body">
        <h4 class="card-title fw-bold">
            <i class="bi bi-box-seam"></i> {{ $box->name }}
        <span class="badge 
            @if($box->etat === 'Neuf') badge-gradient-success 
            @elseif($box->etat === 'Bon') badge-gradient-warning2
            @elseif($box->etat === 'Usé') badge-gradient-warning
            @elseif($box->etat === 'Très usé') badge-gradient-danger 
            @elseif($box->etat === 'A remplacer') bg-dark 
            @else badge-gradient-secondary 
            @endif">
            {{ $box->etat }}
        </span>
        </h4>
        @if ($box->last_inventory != NULL)
        <h5> 
            Dernier inventaire : {{ $box->last_inventory }}
        </h5>
        @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


        <a href="{{ route('boxes.inventory', $box->id) }}" class="btn btn-gradient-pink-gold mt-3">
            <i class="bi bi-clipboard-check"></i> Faire l'inventaire
        </a>

        <hr class="my-3 border-light">

        <div class="mb-3">
            <p class="card-text">
                <i class="bi bi-geo-alt-fill text-danger"></i> 
                <strong>Coordonnées GPS :</strong> 
                {{ $box->latitude }}, {{ $box->longitude }}
            </p>
            <p class="card-text">
                <i class="bi bi-house-door-fill text-primary"></i> 
                <strong>Adresse :</strong> {{ $box->address }}
            </p>
        </div>

        <h5 class="mt-4"><i class="bi bi-book"></i> Contenu de la boîte :</h5>

@if($box->copies->isEmpty())
    <div class="alert alert-warning mt-3">🚫 Aucun exemplaire dans cette boîte.</div>
@else
    <div class="table-responsive mt-3">
        <table class="table table-dark table-striped table-bordered">
            <thead>
                <tr>
                    <th><i class="bi bi-book"></i> Titre</th>
                    <th><i class="bi bi-person-vcard"></i> Auteur(s)</th>
                    <th><i class="bi bi-card-text"></i> Editeur </th>
                    <th><i class="bi bi-upc-scan"></i> ISBN</th>
                    <th><i class="bi bi-clipboard-check"></i> État</th>
                    <th><i class="bi bi-eye"></i> Inventaire</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($box->copies as $copy)
                    <tr class="{{ $copy->disponibilite ? '' : 'badge-gradient-danger text-white' }}">
                        <td>{{ $copy->book->name }}</td>
                        <td>
                            <ul>
                                @foreach($copy->book->authors as $author)
                                    <li>{{ $author->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $copy->book->editor->name }}</td>
                        <td>{{ $copy->book->ISBN }}</td>
                        <td>
                            <span class="badge 
                                @if($copy->etat === 'neuf') badge-gradient-success 
                                @elseif($copy->etat === 'bon') badge-gradient-warning 
                                @elseif($copy->etat === 'usé') badge-gradient-warning2
                                @elseif($copy->etat === 'très usé') badge-gradient-danger 
                                @else badge-gradient-secondary 
                                @endif">
                                {{ $copy->etat }}
                            </span>
                        </td>
                        <td>
                            @if($copy->disponibilite)
                                ✅ Présent
                            @else
                                ❌ Manquant
                            @endif
                            <br>
                            <small>{{ \Carbon\Carbon::parse($box->last_inventory)->format('d/m/Y') }}</small>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

    </div>
</div>
@endsection
