<?php

namespace Modules\Core\Http\Requests\Frontend\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;

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
                'string'
            ],
            'password' => ['required', 'string'],
            'email' => ['email'],
            'mobile' => ['required', 'string']
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
