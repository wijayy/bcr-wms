<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoodsRequest extends FormRequest {
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
            'image' => "file|image",
            'code' => "required|string",
            'desc' => "required|string",
            'material' => "required|string",
            'unit' => "required|string",
            'weight' => "required|numeric",
            'us_price' => "required_without:id_price|numeric|nullable",
            'id_price' => "required_without:us_price|numeric|nullable",
        ];
    }
}
