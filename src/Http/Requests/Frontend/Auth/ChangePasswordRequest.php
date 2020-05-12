<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Modules\Core\Rules\Auth\UnusedPassword;

class ChangePasswordRequest extends FormRequest
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
            'password' => 'required|max:64',
            'sms' => 'required',
            'password' => ['string', 'min:8', 'max:30', 'confirmed']
        ];
    }
}