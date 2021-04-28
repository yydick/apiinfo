<?php

namespace Spool\ApiInfo\Requests;

class PageRequest extends BaseRequest
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
        $parent = parent::rules();
        $thisSet = [
            'page' => 'nullable|numeric',
            'pagesize' => 'nullable|numeric',
        ];
        return array_merge($parent, $thisSet);
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $parent = parent::messages();
        $thisSet = [
            'page' => '当前页编号',
            'pagesize' => '每页数量',
        ];
        return array_merge($parent, $thisSet);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        $parent = parent::attributes();
        $thisSet = [
            'page' => '当前页编号',
            'pagesize' => '每页数量',
        ];
        return array_merge($parent, $thisSet);
    }
    /**
     * Get default value.
     *
     * @return array
     */
    public function getDefaultValue(): array
    {
        $parent = parent::getDefaultValue();
        $thisSet = [
            'page' => 1,
            'pagesize' => 10,
        ];
        return array_merge($parent, $thisSet);
    }
}
