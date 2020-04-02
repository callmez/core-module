<?php

namespace App\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetMobileRequest extends FormRequest
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
            'mobile' => ['regex:/^1[3456789]\d{9}$/'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}
