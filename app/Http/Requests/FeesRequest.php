<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeesRequest extends FormRequest
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
            'name' => 'required|max:191',
            'expired_at' => 'nullable|date',
            'amount' => 'required|numeric'
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('nome'),
            'expired_at' => __('valida sino al'),
            'amount' => __('importo'),
        ];
    }
}
