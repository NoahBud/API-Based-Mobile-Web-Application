<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class BoxRequest extends FormRequest
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
        return [
            'name' => ['required', 'string'], 
            'etat' => ['required', 'string'], 
            'longitude' => ['nullable', 'numeric'],
            'latitude' => ['nullable', 'numeric'], 
            'address' => ['required', 'string'], // 
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Le nom de la boîte est requis.',
            'name.string' => 'Le nom de la boîte doit être une chaîne de caractères.',
            'etat.required' => 'L\'état de la boîte est requis.',
            'etat.string' => 'L\'état de la boîte doit être une chaîne de caractères.',
            'longitude.numeric' => 'La longitude doit être un nombre.',
            'latitude.numeric' => 'La latitude doit être un nombre.',
            'address.required' => 'L\'adresse est requise.',
            'address.string' => 'L\'adresse doit être une chaîne de caractères.',
        ];
    }

    /**
     * Get custom attribute names.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'nom de la boîte',
            'etat' => 'état de la boîte',
            'longitude' => 'longitude de la boîte',
            'latitude' => 'latitude de la boîte',
            'address' => 'adresse de la boîte',
        ];
    }
}
