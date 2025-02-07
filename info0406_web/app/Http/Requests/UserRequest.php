<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'], 
            'email' =>['required', 'email'],
            'role' =>  ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est requis.',
            'name.string' => 'Le nom doit être composé seulement de lettres, à moins que vous soyez le fils d\'Elon Musk',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'Veuillez entrer un email valide.',
            'role.required' => 'Le rôle est requis.',
        ];
    }

    public function attributes()
    {
        return[
            'name' => 'nom utilisateur',
            'email' => 'email utilisateur',
            'role' => 'role utilisateur',
        ];
    }
}
