<?php

namespace App\Http\Requests;

use App\Enums\OrderRowStatus;
use App\Models\User;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleRequest extends FormRequest
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
            'massive_enable_status' => 'required|boolean',
            'massive_enable_payment' => 'required|boolean',
            'status' => ['required', new EnumValue(OrderRowStatus::class)],
            'payed' => 'required|boolean',
            'bank_transfer' => 'required',
            'cashed_by' => [
                'required',
                Rule::in(User::handlePaymentsOrders()->get()->pluck('id')->toArray())
            ]
        ];
    }
}
