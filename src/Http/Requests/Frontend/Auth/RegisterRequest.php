<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RegisterRequest.
 */
class RegisterRequest extends FormRequest
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
            'username' => [
                Rule::requiredIf(function() {
                    return empty($this->email) && empty($this->mobile);
                }),
                'nullable',
                'string'
            ],
            'password' => ['string', 'min:8'],
            'email' => ['nullable', 'email'],
            'mobile' => [
                'nullable',
                Rule::phone()->country(config('core::register.mobile.countries', ['CN']))
            ],
            'code' => ['required_with:mobile']
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
