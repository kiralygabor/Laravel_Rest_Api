<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
        if ($this->method() === 'PUT') {
            return [
                'name' => 'nullable|string|max:255|min:3',
                'category_id' => 'nullable|int',
                'price' => 'nullable|numeric',
                'publication_date' => 'nullable|string',
                'edition' => 'nullable|string',
                'author_id' => 'nullable|int',
                'isbn' => 'nullable|string|max:255|min:3',
                'cover' => 'nullable|string|max:255|min:3'

            ];
        }
        
        return [
            'name' => 'required|string|max:255|min:3',
            'category_id' => 'required|int',
            'price' => 'required|numeric',
            'publication_date' => 'required|string',
            'edition' => 'required|string',
            'author_id' => 'required|int',
            'isbn' => 'required|string|max:255|min:3',
            'cover' => 'required|string|max:255|min:3'
        ];
    }
}
