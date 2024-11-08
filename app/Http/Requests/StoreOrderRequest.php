<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_id' => 'required|numeric',
            'payment_type' => 'required|string',
            'pay' => 'required|numeric'
            /* "order_date" => "nullable",
            "status" => "nullable",
            "total_products" => "nullable",
            "sub_total" => "nullable",
            "vat" => "nullable",
            "total" => "nullable",
            "invoice_no" => "nullable",
            "due" => "nullable" */
        ];
    }

    /* public function prepareForValidation(): void
    {

    } */
}
