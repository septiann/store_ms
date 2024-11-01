<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            'email' => 'nullable|email|max:50|unique:suppliers,email',
            'phone' => 'required|string|max:25|unique:suppliers,phone',
            'address' => 'required|string|max:100',
            'shop_name' => 'nullable|string|max:50',
            'type' => 'required|string|max:25',
            'photo' => 'nullable|image|file|max:1024',
            'bank_name' => 'nullable|max:25',
            'account_holder' => 'nullable|max:50',
            'account_number' => 'nullable|max:25',
        ];
    }
}
