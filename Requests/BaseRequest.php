<?php

namespace Spool\ApiInfo\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'apiVersion' => 'nullable|string',
        ];
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'apiVersion' => 'api版本号',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'apiVersion' => 'api版本号',
        ];
    }
    /**
     * Get default value.
     *
     * @return array
     */
    public function getDefaultValue(): array
    {
        return [
            'apiVersion' => '1.0.0',
        ];
    }
    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator 验证器
     *
     * @return void
     *
     * @throws Illuminate\Http\Exceptions\HttpResponseException
     */
    public function failedValidation($validator)
    {
        $error = $validator->errors()->first();
        $response = response()->json(
            [
                'code' => 0,
                'msg'  => $error,
                'data' => null,
            ]
        );
        throw new HttpResponseException($response);
    }
}
