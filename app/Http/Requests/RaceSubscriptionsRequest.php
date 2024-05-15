<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RaceSubscriptionsRequest extends FormRequest
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
            'fee_id' => 'required|exists:App\Models\Fee,id',
            'athletes' => 'required|array',
            'athletes.*' => [
                'exists:App\Models\Athlete,id'//,
                //Rule::unique('App\Models\AthleteRace', 'athlete_id')->where(fn (Builder $query) => $query->where('race_id', $this->race->id))
            ]
        ];
    }

    public function attributes()
    {
        return [
            'fee_id' => __('tariffa'),
            'athletes' => __('atleti'),
            'athletes.*' => __('atleta'),
        ];
    }
}
