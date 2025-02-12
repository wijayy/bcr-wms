<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoxRequest extends FormRequest {
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
            'no_box' => 'required|integer',
            'prefix' => 'nullable|string',
            'count' => 'required|integer|min:1',
            'height' => 'required|integer|min:1',
            'length' => 'required|integer|min:1',
            'width' => 'required|integer|min:1',
            'weight' => 'required|numeric',
        ];
    }
}
