<form action="{{ $action }}" method="POST">
    @csrf
    @isset($box) 
        @method('PUT') 
    @endisset

    <div class="form-group">
        <label for="name">Nom de la boîte :</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name', $box->name ?? '') }}" required>
    </div>

    <div class="form-group mt-3">
        <label for="etat">État de la boîte :</label>
        <select name="etat" id="etat" class="form-control @error('etat') is-invalid @enderror" required>
            <option value="" disabled selected>Choisissez un état</option>
            @foreach(['Neuf', 'Bon', 'Usé', 'Très usé', 'A remplacer'] as $etat)
                <option value="{{ $etat }}" {{ (old('etat', $box->etat ?? '') == $etat) ? 'selected' : '' }}>
                    {{ $etat }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mt-3">
        <label for="address">Adresse :</label>
        <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
               value="{{ old('address', $box->address ?? '') }}" required>
    </div>

    <button type="submit" class="btn btn-info mt-3">Enregistrer</button>
    <a href="{{ $cancelRoute }}" class="btn btn-danger mt-3">Annuler</a>
</form>
