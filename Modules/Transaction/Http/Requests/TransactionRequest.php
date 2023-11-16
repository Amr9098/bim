<?php

namespace Modules\Transaction\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            case 'store':
                $rules = [
                    'amount' => ['required', 'numeric', 'between:1,1000000000000'],
                    'user_id' => ['required', 'exists:users,id'],
                    'due_on' => ['required', 'date_format:Y-m-d', 'after_or_equal:tomorrow'],
                    'vat' => ['required', 'numeric', 'between:0,100'],
                    'is_vat_inclusive' => ['required', 'boolean'],
                ];

                if (!$this->input('is_vat_inclusive')) {
                    $rules['vat'][] = 'in:0';
                }

                break;

            case 'transactions_for_user_by_admin':
                $rules = [
                    'user_id' => ['required', 'exists:users,id'],
                ];


                break;
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least :min.',
            'amount.max' => 'The amount must not exceed :max.',
            'user_id.required' => 'The user ID field is required.',
            'user_id.exists' => 'Invalid user ID.',
            'due_on.required' => 'The due date field is required.',
            'due_on.date' => 'The due date must be a valid date.',
            'due_on.after' => 'The due date must be in the future.',
            'vat.required' => 'The VAT field is required.',
            'vat.numeric' => 'The VAT must be a number.',
            'vat.between' => 'The VAT must be between :min and :max.',
            'is_vat_inclusive.required' => 'The VAT inclusive field is required.',
            'is_vat_inclusive.boolean' => 'The VAT inclusive field must be true or false.',
            'due_on.date_format' => 'The due date must be in the format d-m-Y.',
            'vat.in' => 'The VAT must be 0 when VAT inclusive is false.',
        ];
    }




    public function authorize()
    {
        return true;
    }
}
