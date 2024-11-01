<?php

namespace App\Http\Requests;

use App\Models\Category;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreProductRequest extends FormRequest
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
            'category_id'   => 'required|numeric',
            'unit_id'       => 'required|numeric',
            'name'          => 'required|string',
            'slug'          => 'required|string',
            'code'          => 'required|string',
            'quantity'      => 'required|numeric',
            'buying_price'  => 'required|numeric',
            'selling_price' => 'required|numeric',
            'stock'         => 'required|numeric',
            'tax'           => 'nullable|numeric',
            'tax_type'      => 'nullable|numeric',
            'notes'         => 'nullable|max:100'
        ];
    }

    protected function prepareForValidation(): void
    {
        $category = Category::findOrFail($this->category_id);

        $this->merge([
            'slug' => Str::slug($this->name, '-'),
            'code' => IdGenerator::generate([
                'table' => 'products',
                'field' => 'code',
                'length' => 5,
                'prefix' => $category->code,
                'reset_on_prefix_change' => true
            ])
        ]);
    }
}
