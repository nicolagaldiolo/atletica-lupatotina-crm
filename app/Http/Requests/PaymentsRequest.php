<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentsRequest extends FormRequest
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
            'payments' => 'required|array',
            'payments.*' => 'required|array',
            'payments.*.*.payed' => 'required|boolean',
            'payments.*.*.bank_transfer' => 'required|boolean',
            'payments.*.*.cashed_by' => [
                'required',
                Rule::exists('users', 'id')
            ]
        ];
    }

    public function attributes()
    {
        return [
            'payments' => __('pagamenti')
        ];
    }
}
