<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use Modules\Core\Rules\Auth\UnusedPassword;
use Illuminate\Foundation\Http\FormRequest;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

/**
 * Class ResetPasswordRequest.
 */
class ResetPasswordRequest extends FormRequest
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
            'mobile' => ['required', 'regex:/^1[3456789]\d{9}$/'],
            'sms' => ['required'],
            'password' => ['string', 'min:8', 'max:30', 'confirmed']
        ];
    }
}
