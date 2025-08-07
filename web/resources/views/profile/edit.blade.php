@extends('template')

@section('title') Mon Profil @endsection

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold text-center mb-4">
            <i class="bi bi-person-circle"></i> Mon Profil
        </h2>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg rounded-4 mb-4 p-4">
                    <div class="card-body">
                        <h4 class="fw-bold text-center mb-3">
                            <i class="bi bi-pencil-square"></i> Modifier mes informations
                        </h4>
                        <div class="max-w-xl mx-auto">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <div class="card shadow-lg rounded-4 mb-4 p-4">
                    <div class="card-body">
                        <h4 class="fw-bold text-center mb-3">
                            <i class="bi bi-key"></i> Modifier mon mot de passe
                        </h4>
                        <div class="max-w-xl mx-auto">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <div class="card shadow-lg rounded-4 p-4 border-danger">
                    <div class="card-body">
                        <h4 class="fw-bold text-center mb-3 text-danger">
                            <i class="bi bi-trash"></i> Supprimer mon compte
                        </h4>
                        <div class="max-w-xl mx-auto">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
