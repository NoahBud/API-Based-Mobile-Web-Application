<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EditorRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'mail' => 'required|email|max:255|unique:editors,mail',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l’éditeur est obligatoire.',
            'name.string' => 'Le nom de l’éditeur doit être une chaîne de caractères.',
            'name.max' => 'Le nom de l’éditeur ne peut pas dépasser 255 caractères.',

            'address.required' => 'L’adresse de l’éditeur est obligatoire.',
            'address.string' => 'L’adresse doit être une chaîne de caractères.',
            'address.max' => 'L’adresse ne peut pas dépasser 255 caractères.',

            'mail.required' => 'L’adresse e-mail est obligatoire.',
            'mail.email' => 'L’adresse e-mail doit être valide.',
            'mail.max' => 'L’adresse e-mail ne peut pas dépasser 255 caractères.',
            'mail.unique' => 'Cette adresse e-mail est déjà utilisée par un autre éditeur.',
        ];
    }

    public function attributes() : array
    {
        return [
            'name' => 'nom de l\'éditeur',
            'address' => 'adresse de l\'éditeur',
            'mail' => 'email de l\'éditeur',
        ];
    }
}
