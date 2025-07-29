<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthorRequest extends FormRequest
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
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l\'auteur est obligatoire.',
            'name.string' => 'Le nom de l\'auteur doit être une chaîne de caractères.',
            'name.max' => 'L\'auteur ne peut pas dépasser 255 caractères'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nom de l\auteur',
        ];
    }
}
