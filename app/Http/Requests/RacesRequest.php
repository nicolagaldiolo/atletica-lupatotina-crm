<?php

namespace App\Http\Requests;

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
            'distance' => 'nullable|max:191',
            'date' => 'required|date',
            'is_subscrible' => 'required|boolean',
            'subscrible_expiration' => 'nullable|date',
            'is_visible_on_site' => 'required|boolean',
            'amount' => 'required|numeric'
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('nome'),
            'distance' => __('distanza'),
            'date' => __('data'),
            'is_subscrible' => __('iscrizioni aperte'),
            'subscrible_expiration' => __('iscrizioni aperte fino al'),
            'is_visible_on_site' => __('visualizza sul sito'),
            'amount' => __('importo'),
        ];
    }
}
