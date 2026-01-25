<?php

namespace App\Http\Requests;

use App\Enums\ArticleType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeasonRequest extends FormRequest
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
            'start_at' => 'required|date',
            'end_at' => 'required|date'
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('nome'),
            'start_at' => __('data inizio'),
            'end_at' => __('data fine'),
        ];
    }
}
