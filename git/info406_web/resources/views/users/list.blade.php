@extends('template')

@section('title') Gestion des utilisateurs @endsection

@section('content')
    <div class="container py-5">
        @can('manage users')
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex gap-3 mb-4">
                <a href="{{ route('users.create') }}" class="btn btn-success btn-lg shadow-sm">
                    <i class="bi bi-plus-circle"></i> Ajouter un utilisateur
                </a>
                <a href="{{ route('accueil') }}" class="btn btn-info btn-lg shadow-sm">
                    <i class="bi bi-arrow-bar-left"></i> Retour
                </a>
            </div>

            <div class="card p-4 shadow-lg rounded-4">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="table-light">
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge badge-gradient-info">{{ $user->getRoleNames()->implode(', ') }}</span>
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-dark btn-sm shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Modifier
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm shadow-sm">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endcan
    </div>
@endsection