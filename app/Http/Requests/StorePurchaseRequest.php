<?php

namespace App\Http\Requests;

use App\Enums\PurchaseStatus;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
            'supplier_id' => 'required',
            'date' => 'required|string',
            'total_amount' => 'required|numeric',
            'status' => 'required',
            'purchase_no' => 'required',
            'created_by' => 'required|numeric',
            'updated_by' => 'nullable',
            'products' => 'array',
            'products.*.product_id' => 'required|numeric',
            'products.*.quantity' => 'required|numeric',
            'products.*.unit_cost' => 'required|numeric',
            'products.*.total' => 'required|numeric'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => PurchaseStatus::InProgress->value,
            'created_by' => auth()->user()->id,
            'purchase_no' => IdGenerator::generate([
                'table' => 'purchases',
                'field' => 'purchase_no',
                'length' => 10,
                'prefix' => 'INV/S/',
                'reset_on_prefix_change' => true
            ])
        ]);
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier is required'
        ];
    }
}
