<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

     public function rules()
     {
         $functionName = $this->route()->getActionMethod();

         switch ($functionName) {
             case 'addUser':
                 return [
                     'first_name' => ['required', 'string', 'min:3', 'max:50'],
                     'last_name' => ['required', 'string', 'min:3', 'max:50'],
                     'phone' => ['required', 'string', 'min:7', 'max:15', 'starts_with:+', 'unique:users,phone'],
                     'email' => ['nullable', 'email:rfc,dns', 'unique:users,email'],
                     'password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                 ];
             case 'editUserData':
                $id = $this->id;
                 return [
                     'first_name' => ['required', 'string', 'min:3', 'max:50'],
                     'last_name' => ['required', 'string', 'min:3', 'max:50'],
                     'phone' => [
                         'required',
                         'string',
                         'min:7',
                         'max:15',
                         'starts_with:+',
                         Rule::unique('users', 'phone')->ignore($id)
                     ],
                     'email' => [
                         'nullable',
                         'email:rfc,dns',
                         Rule::unique('users', 'email')->ignore($id)
                     ],
                 ];
                 case 'AdminChangePassword':
                    return [
                        'new_password' => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
                    ];
         }
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
