@extends('template')

@section('title') Réinitialisation du mot de passe @endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg rounded-4">
                    <div class="card-body">
                        <h4 class="card-title fw-bold text-center mb-4">
                            <i class="bi bi-envelope-fill"></i> Réinitialisation du mot de passe
                        </h4>

                        <p class="text-muted text-center">
                            Oubli de votre mot de passe ? Pas de souci ! Renseignez votre adresse e-mail et nous vous enverrons un lien pour le réinitialiser.
                        </p>

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse e-mail</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @error('email')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-gradient-blue-green w-100">
                                <i class="bi bi-send"></i> Envoyer l'e-mail
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection