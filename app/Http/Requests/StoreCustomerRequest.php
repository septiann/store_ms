<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:customers,email',
            'phone' => 'required|string|max:25|unique:customers,phone',
            'address' => 'required|string|max:100',
            'type' => 'required|string',
            'bank_name' => 'max:25',
            'account_holder' => 'max:50',
            'account_number' => 'max:25',
            'photo' => 'image|file|max:1024',
        ];
    }
}
