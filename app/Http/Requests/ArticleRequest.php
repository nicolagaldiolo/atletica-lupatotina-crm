<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'is_active' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('nome'),
            'price' => __('importo'),
            'quantity' => __('quantità'),
            'is_active' => __('attivo'),
        ];
    }
}
