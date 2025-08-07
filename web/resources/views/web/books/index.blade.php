@extends('template')

@section('title') Liste des Livres @endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title fw-bold">
                <i class="bi bi-bookshelf"></i> Liste des Livres
            </h4>

            <div class="d-flex justify-content-between mb-3 mt-2">
                <a href="{{ route('web.boxes.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-box-seam"></i> Retour aux boîtes
                </a>

                <a href="{{ route('web.books.create') }}" class="btn btn-gradient-add">
                    <i class="bi bi-plus-circle"></i> Ajouter un Livre
                </a>

                {{-- <a href="{{ route('web.books.test') }}" class="btn btn-gradient-add">
                    <i class="bi bi-plus-circle"></i> Ajouter un Livre (via ISBN)
                </a> --}}


            </div>

            <form method="GET" action="{{ route('web.books.index') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher un livre..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-light">
                        <i class="bi bi-search"></i> Rechercher
                    </button>
                </div>
            </form>

            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Auteur(s)</th>
                        <th>Éditeur</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $book->name }}</td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($book->authors as $author)
                                        <li class="text-muted small">{{ $author->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $book->editor->name }}</td>
                            <td>
                                <a href="{{ route('web.books.show', $book->id) }}" class="btn btn-gradient-view btn-sm">Voir</a>
                                <a href="{{ route('web.books.edit', $book->id) }}" class="btn btn-gradient-edit btn-sm">Éditer</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Aucun livre trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4 d-flex justify-content-center">
                {{ $books->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
