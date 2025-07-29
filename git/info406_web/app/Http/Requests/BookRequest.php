<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class BookRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string',
            'genre' => 'required|string',
            'description' => 'required|string',
            'editor_id' => 'required',
            'authors' => 'nullable',
            'authors.*' => 'nullable',
            'new_author' => 'nullable|string|max:255',
            'new_author_age' => 'nullable|integer|min:0',
        ];

        if ($this->isMethod('post')) {
            $book = \App\Models\Book::where('ISBN', $this->ISBN)->first(); // si il existe alors on le garde pour renvoyer ses infos

            if (!$book) {
                $rules['ISBN'] = 'required|string|max:13|unique:books,ISBN';
            }
    }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du livre est obligatoire.',
            'name.string' => 'Le nom du livre doit être une chaîne de caractères.',

            'genre.required' => 'Le genre du livre est obligatoire.',
            'genre.string' => 'Le genre du livre doit être une chaîne de caractères.',

            'description.required' => 'La description du livre est obligatoire.',
            'description.string' => 'La description doit être une chaîne de caractères.',

            'editor_id.required' => 'L’éditeur est obligatoire.',
            'editor_id.exists' => 'L’éditeur sélectionné n’existe pas.',

            'authors.array' => 'Les auteurs doivent être un tableau (possibilité d\'en avoir plusieurs.',
            'authors.*.string' => 'Chaque auteur doit être une chaîne de caractères.',

            'new_author.string' => 'Le nom du nouvel auteur doit être une chaîne de caractères.',
            'new_author.max' => 'Le nom du nouvel auteur ne peut pas dépasser 255 caractères.',

            'new_author_age.integer' => 'L’âge du nouvel auteur doit être un nombre entier.',
            'new_author_age.min' => 'L’âge du nouvel auteur doit être un nombre positif.',

            'ISBN.required' => 'L’ISBN est obligatoire.',
            'ISBN.string' => 'L’ISBN doit être une chaîne de caractères.',
            'ISBN.max' => 'L’ISBN ne peut pas dépasser 13 caractères.',
            'ISBN.unique' => 'Cet ISBN existe déjà dans la base de données.'
        ];
    }

    public function attributes()
    {
        return [
            'ISBN' => 'ISBN du livre',
            'name' => 'Nom du livre',
            'genre' => 'Genre du livre',
            'description' => 'Description du livre',
            'editor_id' => 'Editeur du livre',
        ];
    }
}
