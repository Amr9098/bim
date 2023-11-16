<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class PaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_id' => ['required', 'exists:transactions,id'],
            'amount' => ['required', 'between:1,100000000000000'],
            'details' => ['nullable', 'string', 'min:2', 'max:1500']
        ];
        // return [
        //     'transaction_id' => ['required', 'exists:transactions,id'],
        //     'user_id' => ['required', 'exists:users,id', function ($attribute, $value, $fail) {
        //         $transactionId = $this->input('transaction_id');
        //         $isUserMatchTransaction = DB::table('transactions')
        //             ->where('id', $transactionId)
        //             ->where('user_id', $value)
        //             ->exists();

        //         if (!$isUserMatchTransaction) {
        //             $fail('The selected user does not own the specified transaction.');
        //         }
        //     }],
        //     'amount' => ['required', 'between:1,100000000000000'],
        //     'details' => ['nullable', 'string', 'min:2', 'max:1500']
        // ];
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
