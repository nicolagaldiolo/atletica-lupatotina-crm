<?php

namespace App\Http\Requests;

use App\Enums\GenderType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class AthletesRequest extends FormRequest
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
        $id = $this->athlete->id ?? null;

        return [
            'name' => 'required|max:191',
            'surname' => 'required|max:191',
            'gender' => ['nullable', new EnumValue(GenderType::class, false)],
            'phone' => 'nullable|max:191',
            'email' => 'nullable|max:191|email|unique:athletes,email,' . $id,
            'address' => 'nullable|string',
            'zip' => 'nullable|max:191',
            'city' => 'nullable|max:191',
            'birth_place' => 'nullable|max:191',
            'birth_date' => 'nullable|date',
            'registration_number' => 'nullable|max:191',
            'size' => 'nullable|max:191',
            '10k' => 'nullable|max:191',
            'half_marathon' => 'nullable|max:191',
            'marathon' => 'nullable|max:191'
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('nome'),
            'surname' => __('cognome'),
            'gender' => __('genere'),
            'phone' => __('telefono'),
            'email' => __('email'),
            'address' => __('indirizzo'),
            'zip' => __('cap'),
            'city' => __('comune'),
            'birth_place' => __('luogo di nascita'),
            'birth_date' => __('data di nascita'),
            'registration_number' => __('tessera fidal'),
            'size' => __('taglia'),
            '10k' => __('10mila'),
            'half_marathon' => __('mezza maratona'),
            'marathon' => __('maratona')
        ];
    }
}
