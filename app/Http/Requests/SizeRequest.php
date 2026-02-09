<?php

namespace App\Http\Requests;

use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SizeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:191', Rule::unique('sizes', 'name')->ignore($this->size)],
            'is_active' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('nome'),
            'is_active' => __('attivo'),
        ];
    }
}
