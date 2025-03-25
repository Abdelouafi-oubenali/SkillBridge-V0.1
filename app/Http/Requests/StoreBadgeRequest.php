<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBadgeRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:badges,name',
            'description' => 'nullable|string|max:500',
            'type' => 'required|string|max:255',
        ];
    }

    /**
     * Messages d'erreur personnalisés (optionnel).
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du badge est obligatoire.',
            'name.unique' => 'Ce nom de badge existe déjà.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
            'description.max' => 'La description ne doit pas dépasser 500 caractères.',
        ];
    }
}
