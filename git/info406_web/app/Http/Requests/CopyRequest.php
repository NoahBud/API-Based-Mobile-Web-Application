<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CopyRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     */
    public function authorize(): bool
    {
        return true; // Change si besoin selon la logique d'authentification
    }

    /**
     * Règles de validation pour la requête.
     */
    public function rules(): array
    {
        return [
            'numero_exemplaire' => 'required|string|max:255|unique:copies,numero_exemplaire,' . $this->copy->id,
            'etat' => ['required', 'string'], 
            'disponibilite' => 'required|boolean',
            'book_id' => 'required|exists:books,id',
            'box_id' => 'nullable|exists:boxes,id'
        ];
    }

    /**
     * Messages personnalisés pour les erreurs de validation.
     */
    public function messages(): array
    {
        return [
            'numero_exemplaire.required' => 'Le numéro d’exemplaire est obligatoire.',
            'numero_exemplaire.unique' => 'Ce numéro d’exemplaire existe déjà.',
            'etat.required' => 'L’état est obligatoire.',
            'etat.in' => 'L’état doit être parmi : Neuf, Bon, Usé, Très usé, À remplacer.',
            'disponibilite.required' => 'La disponibilité est obligatoire.',
            'disponibilite.boolean' => 'La disponibilité doit être un booléen (0 ou 1).',
            'book_id.required' => 'L’ID du livre est obligatoire.',
            'book_id.exists' => 'Le livre sélectionné n’existe pas.',
            'box_id.exists' => 'La boîte sélectionnée n’existe pas.',
        ];
    }

    public function attributes()
    {
        return [
            'numero_exemplaire' => 'n° exemplaire du livre',
            'etat' => 'état de l\'exemplaire',
            'disponibilite' => 'disponibilité de l\'exemplaire',
            'book_id' => 'livre lié à cet exemplaire',
            'box_id' => 'boîte dans laquelle se trouve cet exemplaire',
        ];
    }
}
