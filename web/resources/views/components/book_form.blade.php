<form action="{{ $action }}" method="POST">
    @csrf
    @isset($book) 
        @method('PUT') 
    @endisset

    <div class="form-group">
        <label for="ISBN">ISBN :</label>
        <input type="text" name="ISBN" id="ISBN" class="form-control @error('ISBN') is-invalid @enderror"
               value="{{ old('ISBN', $book->ISBN ?? '') }}" 
               {{ isset($book) ? 'disabled' : 'required' }}> <!-- Désactivé en modification -->
    </div>

    <div class="form-group mt-3">
        <label for="name">Titre du livre :</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $book->name ?? '') }}" required>
    </div>

    <div class="form-group mt-3">
        <label for="genre">Genre :</label>
        <input type="text" name="genre" id="genre" class="form-control @error('genre') is-invalid @enderror"
               value="{{ old('genre', $book->genre ?? '') }}" required>
    </div>

    <div class="form-group mt-3">
        <label for="description">Description :</label>
        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $book->description ?? '') }}</textarea>
    </div>

    <div class="form-group mt-3">
        <label for="editor_id">Éditeur :</label>
        <select name="editor_id" id="editor_id" class="form-control select2 @error('editor_id') is-invalid @enderror" required">
            <option value="" disabled selected>Choisissez un éditeur</option>
            @foreach($editors as $editor)
                <option value="{{ $editor->id }}" {{ (old('editor_id', $book->editor_id ?? '') == $editor->id) ? 'selected' : '' }}>
                    {{ $editor->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mt-3">
        <label for="authors">Auteurs (Optionnel) :</label>
        <select name="authors[]" id="authors" class="form-control select2 @error('authors') is-invalid @enderror" multiple>
            <option value="none" {{ (isset($book) && $book->authors->isEmpty()) ? 'selected' : '' }}>Aucun</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" 
                    {{ (isset($book) && $book->authors->contains($author->id)) ? 'selected' : '' }}>
                    {{ $author->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group mt-3">
        <label for="new_author">Nom du nouvel auteur (Optionnel):</label>
        <input type="text" name="new_author" id="new_author" class="form-control @error('new_author') is-invalid @enderror"
            placeholder="Nom du nouvel auteur">
    </div>

    <button type="submit" class="btn btn-info mt-3">Enregistrer</button>
    <a href="{{ $cancelRoute }}" class="btn btn-danger mt-3">Annuler</a>
</form>

<!-- Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#authors, #editor_id').select2({
            placeholder: "Sélectionnez une option",
            allowClear: true,
            width: '100%'
        });
    });
</script>
