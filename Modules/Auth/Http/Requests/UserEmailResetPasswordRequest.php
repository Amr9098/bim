<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserEmailResetPasswordRequest extends FormRequest
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
            case 'SendRestPasswordViaEmailOTP':
                return [
                    'email' => ['required', 'email:rfc,dns'],
                ];
            case 'CheckOtpForRestPasswordViaEmail':
                return [
                    'code' => ['required', 'numeric', 'digits:4'],
                    'email' => ['required', 'email:rfc,dns'],
                ];
                break;
            case 'changePasswordViaEmail':
                return [
                    'email' => ['required', 'email:rfc,dns'],
                    'new_password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
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
}
