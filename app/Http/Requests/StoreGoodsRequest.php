<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoodsRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'note' => "required|file|image",
            'code' => "required|string",
            'desc' => "required|string",
            'unit' => "required|string",
            'stock' => "required|numeric",
            'type' => 'required|in:stock,supplier',
            'material' => "required_if:type,stock|nullable|string",
            'image' => "required_if:type,stock|nullable|file|image",
            'weight' => "required_if:type,stock|nullable|numeric",
            'us_price' => "integer|nullable",
            'id_price' => "integer|nullable",
        ];
    }

    /**
     * Tambahkan aturan validasi secara dinamis berdasarkan nilai type.
     */
    protected function withValidator($validator) {
        $validator->sometimes(['us_price', 'id_price'], 'required_without_all:us_price,id_price', function ($input) {
            return $input->type === 'stock';
        });
    }
}
