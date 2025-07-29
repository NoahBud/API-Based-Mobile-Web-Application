<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UserRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], 422));
    }
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
        $rules = [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'role' => ['required'],
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'string', 'min:4']; // si on crée
        } else {
            $rules['password'] = ['nullable', 'string', 'min:4']; // si on modifie
        }

        return $rules;
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
