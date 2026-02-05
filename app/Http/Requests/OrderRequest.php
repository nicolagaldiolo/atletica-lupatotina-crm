<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
            'articles' => 'required|array',
            'articles.*.selected' => 'required|boolean',
            'articles.*.variants' => 'nullable|array',
            'articles.*.variants.*' => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $hasOneSelected = collect($this->articles)
                ->contains(fn ($article) =>
                    isset($article['selected']) && (int) $article['selected'] === 1
                );

            if (! $hasOneSelected) {
                $validator->errors()->add(
                    'articles',
                    'Devi selezionare almeno un articolo.'
                );
            }

            foreach ($this->articles as $index => $article) {

                if ((bool) ($article['selected'] ?? false)) {

                    $hasVariant = collect($article['variants'] ?? [])->filter(function($variant){
                        return (bool) $variant;
                    })->isNotEmpty();

                    if (! $hasVariant) {
                        $validator->errors()->add(
                            "articles.$index.variants",
                            'Devi inserire almeno una quantità.'
                        );
                    }
                }
            }

        });
    }
}
