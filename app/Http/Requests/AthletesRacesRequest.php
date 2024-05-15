<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AthletesRacesRequest extends FormRequest
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
        $rules = [
            'fee_id' => 'required|exists:App\Models\Fee,id',
            'subscription_at' => 'required|date',
        ];

        if($this->method() == 'POST'){
            $rules['athletes'] = 'required|array';
            $rules['athletes.*'] = [
                'exists:App\Models\Athlete,id',
                Rule::unique('App\Models\AthleteRace', 'athlete_id')->where(fn (Builder $query) => $query->where('race_id', $this->race->id))
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'fee_id' => __('tariffa'),
            'subscription_at' => __('iscritto_il'),
            'athletes' => __('atleti'),
            'athletes.*' => __('atleta'),
        ];
    }
}
