<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

     public function rules()
     {
         return [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'email:rfc,dns', 'unique:admins,email'],
            'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
         ];
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
