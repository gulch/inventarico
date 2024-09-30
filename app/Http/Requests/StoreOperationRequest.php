<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreOperationRequest extends FormRequest
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
            'condition' => 'string',
            'currency' => 'string',
            'id__Instance' => 'required|numeric|min:1',
            'id__OperationType' => 'required|numeric|min:1',
            'note' => 'string',
            'operated_at' => 'required',
            'price' => 'numeric',
        ];
    }
}
