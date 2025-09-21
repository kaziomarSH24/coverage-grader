<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class PoliceCategoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:policy_categories,name,' . $this->route('id')],
            'description' => ['nullable', 'string'],
            'logo_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:3072'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}

