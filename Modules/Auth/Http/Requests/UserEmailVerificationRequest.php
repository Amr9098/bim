<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEmailVerificationRequest extends FormRequest
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
            case 'ResendEmailOtp':
                return [
                    'email' => ['required', 'email:rfc,dns'],
                ];
            case 'CheckEmailOtpVerification':
                return [
                    'code' => ['required', 'numeric', 'digits:4'],
                    'email' => ['required', 'email:rfc,dns'],
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
