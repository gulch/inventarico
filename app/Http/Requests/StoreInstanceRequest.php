<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstanceRequest extends FormRequest
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
            'title' => 'required',
            'id__Thing' => 'required|numeric|min:1',
            'description' => 'string',
            'published_at' => 'required',
            'price' => 'int',
            'is_archived' => 'boolean',
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData(): array
    {
        return $this->withDefaultValues(
            $this->all()
        );
    }

    /**
     * @param array<string, int|string> $input
     * @return array<string, int|string>
     */
    private function withDefaultValues(array $input): array
    {
        $input['is_archived'] = $input['is_archived'] ?? 0;

        return $input;
    }
}
