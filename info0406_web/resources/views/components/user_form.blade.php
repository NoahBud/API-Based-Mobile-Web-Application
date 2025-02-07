<form action="{{ $action }}" method="POST">
    @csrf
    @isset($user) 
        @method('PUT') 
    @endisset

    <div class="mb-3">
        <label for="name" class="form-label">Nom</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
               value="{{ old('name', $user->name ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
               value="{{ old('email', $user->email ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Rôle</label>
        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
            @foreach($roles as $role)
                <option value="{{ $role }}" 
                        {{ (isset($user) && $user->hasRole($role)) ? 'selected' : '' }}> {{ $role }}
                </option>
            @endforeach
        </select>
    </div>

    @if(!isset($user))
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
        </div>
    @endif

    <button type="submit" class="btn btn-info">
        {{ isset($user) ? 'Mettre à jour' : 'Créer' }}
    </button>
    <a href="{{ $cancelRoute }}" class="btn btn-danger">Annuler</a>
</form>
