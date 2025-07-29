@extends('template')

@section('title') Détails du Livre @endsection

@push('head')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <h4 class="card-title fw-bold">
            <i class="bi bi-book"></i> {{ $book->name }}
        </h4>

        <p><strong>Auteur(s) :</strong></p>
        <ul>
            @foreach($book->authors as $author)
                <li>{{ $author->name }}</li>
            @endforeach
        </ul>

        <p><strong>Éditeur :</strong> {{ $book->editor->name }}</p>

        <div class="d-flex gap-3 mb-4">
            <a href="{{ route('web.books.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
            
            <a href="{{ route('web.boxes.index') }}" class="btn btn-outline-light">
                <i class="bi bi-box"></i> Retour à la gestion des boîtes
            </a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-4">Ajouter un exemplaire</h5>
        
        <form action="{{ route('books.createCopy', $book->id) }}" method="POST">
        @csrf

        <div class="form-group mt-4">
        <label for="box_id" class="form-label fw-bold">Sélectionner une boîte</label>
        <select name="box_id" id="box_id" class="form-controlselect2 @error('box_id') is-invalid @enderror" required>
            <option value="">Choisir une boîte</option>
            @foreach ($boxes as $box)
                <option value="{{ $box->id }}">{{ $box->name }}</option>
            @endforeach
        </select>
    </div>


        <div class="text-end mt-3">
            <button type="submit" class="btn btn-gradient-add">
                <i class="bi bi-plus-circle"></i> Ajouter un Exemplaire
            </button>
        </div>
    </form>

    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title mb-4">Exemplaires disponibles</h5>
        
        @if($book->copies->count() > 0)
            <ul class="list-group">
                @foreach ($book->copies as $copy)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold">#{{ $copy->numero_exemplaire }}</span> - 
                            <span>État: {{ $copy->etat }}</span>
                        </div>
                        <div>
                            <span class="badge {{ $copy->disponibilite ? 'badge-gradient-success' : 'badge-gradient-danger' }}">
                                {{ $copy->disponibilite ? 'Disponible' : 'Indisponible' }}
                            </span>
                            <span class="ms-3 badge badge-gradient-info">
                                Boîte: {{ $copy->box ? $copy->box->name : 'Aucune boîte' }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-info">
                Aucun exemplaire disponible pour ce livre.
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#box_id').select2({
            placeholder: "Sélectionnez une boîte", 
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush

@endsection


