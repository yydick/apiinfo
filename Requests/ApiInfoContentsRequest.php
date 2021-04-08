<?php
/**
 * 请求入口格式化
 * 
 * Class ApiInfoContentsRequest 请求格式化
 * 
 * PHP version 7.2
 * 
 * @category Spool
 * @package  ApiInfo
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2021-03-27
 */
namespace Spool\ApiInfo\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * 请求入口格式化
 * 
 * Class ApiInfoContentsRequest 请求格式化
 * 
 * PHP version 7.2
 * 
 * @category Spool
 * @package  ApiInfo
 * @author   yydick Chen <yydick@sohu.com>
 * @license  https://spdx.org/licenses/Apache-2.0.html Apache-2.0
 * @link     http://url.com
 * @DateTime 2021-03-27
 */
class ApiInfoContentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group' => 'nullable|numeric',
            'name' => 'nullable|string',
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
            'group' => '要调用的组',
            'name' => '要调用的名称',
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
            'group' => 'group',
            'name' => 'name',
        ];
    }
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * 
     * @return void
     *
     * @throws Illuminate\Http\Exceptions\HttpResponseException
     */
    public function failedValidation($validator)
    {
        $error = $validator->errors()->first();
        // $allErrors = $validator->errors()->all(); 所有错误
 
        $response = response()->json([
            'code' => 0,
            'msg'  => $error,
            'data' => null,
        ]);
 
        throw new HttpResponseException($response);
    }
}