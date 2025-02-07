@extends('template')

@section('title') Gestion des utilisateurs @endsection

@section('content')
    <div class="container">

@can('manage users')
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


        <div class="mb-4">
            <a href="{{ route('users.create') }}" class="btn btn-success btn-lg">
                <i class="bi bi-plus-circle"></i> Ajouter un utilisateur
            </a>
        </div>

        <div class="mb-4">
            <a href="{{ route('accueil') }}" class="btn btn-info btn-lg">
                <i class="bi bi-plus-circle"></i> Retour
            </a>
        </div>
    

        <table class="table table-striped">
            <thead>
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
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-info">{{ $user->getRoleNames()->implode(', ') }}</span>
                        </td>
                        <td class="d-flex justify-content-start">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-dark btn-sm me-2">
                                <i class="bi bi-pencil-square"></i> Modifier
                            </a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endcan
    </div>
@endsection
