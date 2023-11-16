<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthenticationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $functionName = $this->route()->getActionMethod();
        $rules = [];
        switch ($functionName) {
            case 'RegistrationViaEmail':
                return [
                    'first_name' => ['required', 'string', 'min:3', 'max:50'],
                    'last_name' => ['required', 'string', 'min:3', 'max:50'],
                    'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
                    'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                ];
            case 'RegistrationViaPhone':
                return [
                    'first_name' => ['required', 'string', 'min:3', 'max:50'],
                    'last_name' => ['required', 'string', 'min:3', 'max:50'],
                    'phone' => ['required', 'string', 'min:7', 'max:15', 'starts_with:+', 'unique:users,phone'],
                    'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                ];
                break;
                case 'Login':
                    $rules = [
                        'email_or_phone' => [
                            'required',
                            function ($attribute, $value, $fail) {
                                if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/^\+?\d{8,15}$/', $value)) {
                                    $fail('The input must be a valid email address or a valid phone number (8-15 digits).');
                                }
                            },
                        ],
                        'password' => ['required', 'string', 'min:6', 'max:150'],
                    ];
                    break;
        }
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    public function messages()
    {
        return [
            'phone.unique' => 'This phone number already exists ',
            'email.unique' => 'This Email already exists ',
        ];
    }
}
