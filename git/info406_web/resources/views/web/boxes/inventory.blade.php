@extends('template')

@section('title') Inventaire de {{ $box->name }} @endsection

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@section('content')
<a href="{{ route('web.boxes.show', $box->id) }}" class="btn btn-outline-light mb-3 mt-5">
    <i class="bi bi-arrow-left"></i> Retour à la boîte
</a>

<a href="{{ route('web.books.index') }}" class="btn btn-gradient-view btn-sm mb-3 mt-5">
    <i class="bi bi-list-ul"></i> Voir tous les livres
</a>

<div class="card">  
    <div class="card-body">
        <h4 class="card-title fw-bold">
            <i class="bi bi-box-seam"></i> Inventaire de {{ $box->name }}
        </h4>

        <p class="text-muted">Dernier inventaire : 
            <strong>{{ $box->last_inventory ?? 'Jamais' }}</strong>
        </p>

        <form action="{{ route('boxes.saveInventory', $box->id) }}" method="POST">
            @csrf

            <table class="table table-dark table-striped table-bordered">
                <thead>
                    <tr>
                        <th>✔️ Présent</th>
                        <th><i class="bi bi-book"></i> Titre</th>
                        <th><i class="bi bi-person-vcard"></i> Auteur(s)</th>
                        <th><i class="bi bi-card-text"></i> Editeur </th>
                        <th><i class="bi bi-upc-scan"></i> ISBN</th>
                        <th><i class="bi bi-trash"></i> Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($box->copies as $copy)
                        <tr>
                            <td>
                                <input type="checkbox" name="copies[]" value="{{ $copy->id }}" 
                                    {{ $copy->disponibilite ? 'checked' : '' }}>
                                <br>
                                <small>
                                    {{ $box->last_inventory ? \Carbon\Carbon::parse($box->last_inventory)->format('d/m/Y') : 'Jamais' }}
                                </small>
                            </td>

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
                                <button type="button" class="btn btn-danger btn-sm delete-copy" data-route="{{ route('copies.destroy', $copy->id) }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-save"></i> Valider l'inventaire
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.delete-copy').forEach(button => {
    button.addEventListener('click', function () {
        if (confirm('Confirmer la suppression ?')) {
            fetch(this.getAttribute('data-route'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ '_method': 'DELETE' })
            })
            .then(() => location.reload())
            .catch(() => alert('Erreur lors de la suppression.'));
        }
    });
});
</script>
@endpush