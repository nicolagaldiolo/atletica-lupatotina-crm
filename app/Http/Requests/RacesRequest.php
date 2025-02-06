<?php

namespace App\Http\Requests;

use App\Enums\RaceType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class RacesRequest extends FormRequest
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
            'type' => ['required', new EnumValue(RaceType::class)],
            'distance' => 'nullable|max:191',
            'date' => 'required|date'
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('nome'),
            'type' => __('tipo'),
            'distance' => __('distanza'),
            'date' => __('data'),
        ];
    }
}
