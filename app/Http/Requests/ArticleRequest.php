<?php

namespace App\Http\Requests;

use App\Enums\ArticleType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'type' => ['required', new EnumValue(ArticleType::class, false)],
            'name' => 'required|max:191',
            'quantity' => [Rule::requiredIf($this->type == ArticleType::Simple, 'numeric', 'min:0')],
            'variants' => [Rule::requiredIf($this->type == ArticleType::Variants), 'array'],
            'variants.*' => 'required_with:variants|numeric|min:0',
            'is_unlimited' => 'required|boolean',
            'price' => 'required|numeric',
            'is_active' => 'required|boolean',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('nome'),
            'price' => __('importo'),
            'is_active' => __('attivo'),
        ];
    }
}
