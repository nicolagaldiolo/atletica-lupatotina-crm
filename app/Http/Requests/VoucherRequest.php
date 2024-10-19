<?php

namespace App\Http\Requests;

use App\Enums\VoucherType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class VoucherRequest extends FormRequest
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
            'name' => 'nullable|string|max:191',
            'type' => ['required', new EnumValue(VoucherType::class, false)],
            'amount' => 'required|numeric|min:1'
        ];
    }

    public function attributes()
    {
        return [
            'type' => __('tipo'),
            'amount' => __('importo'),
        ];
    }
}
