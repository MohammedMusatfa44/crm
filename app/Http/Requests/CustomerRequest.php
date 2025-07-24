<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'ac_number' => 'required|unique:customers,ac_number,' . ($this->customer?->id ?? 'NULL'),
            'full_name' => 'required|string',
            'mobile_number' => 'required',
            'email' => 'nullable|email',
            'status' => 'required',
        ];
    }
}
