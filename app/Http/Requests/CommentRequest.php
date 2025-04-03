<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CommentRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'post_id' => 'required|exists:posts,id',
                'body' => 'required|string',
            ];
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'body' => 'required|string',
            ];
        }

        return [];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        $errorMessage = reset($errors)[0];

        throw new HttpResponseException(response()->json([
            'message' => $errorMessage,
            'is_success' => false,
        ], 422));
    }
}
