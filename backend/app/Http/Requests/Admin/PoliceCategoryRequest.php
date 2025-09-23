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
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:policy_categories,name'],
            'description' => ['nullable', 'string'],
            'logo_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            'status' => ['required', 'in:active,inactive'],
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $id = $this->route('id');
            $rules['name'][3] = 'unique:policy_categories,name,' . $id;
        }

        return $rules;
    }
}
