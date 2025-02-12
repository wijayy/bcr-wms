<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoxDetailRequest extends FormRequest {
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
            'box' => 'required|array',
            'box.*.goods_id' => 'required_with:box',
            'box.*.amount' => 'required_with:box|doesnt_start_with:0',
            'box.*.name' => 'required_if:box.*.amount,-1|present'
        ];
    }
}
