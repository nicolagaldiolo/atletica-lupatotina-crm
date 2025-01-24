<?php

namespace App\Http\Requests;

use App\Enums\GenderType;
use App\Enums\MemberType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'surname' => ['required', 'max:191', Rule::unique('athletes')->where(function ($query) use($id){
                $query->where('name', request()->name);
                $query->where('surname', request()->surname);
                if($id){
                    $query->where('id', '<>', $id);
                }
                return $query;
             })],
            'gender' => ['nullable', new EnumValue(GenderType::class, false)],
            'phone' => 'nullable|max:191',
            'email' => 'nullable|max:191|email|unique:athletes,email,' . $id,
            'address' => 'nullable|string',
            'zip' => 'nullable|max:191',
            'city' => 'nullable|max:191',
            'birth_place' => 'nullable|max:191',
            'birth_date' => 'nullable|date',
            'type' => ['required', new EnumValue(MemberType::class, false)],
            'registration_number' => 'nullable|max:191|unique:athletes,registration_number,' . $id,
            'size' => 'nullable|max:191',
            '10k' => 'nullable|max:191',
            'half_marathon' => 'nullable|max:191',
            'marathon' => 'nullable|max:191',
            'is_active' => 'required|boolean',
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
            'type' => __('tipo'),
            'registration_number' => __('tessera fidal'),
            'size' => __('taglia'),
            '10k' => __('10mila'),
            'half_marathon' => __('mezza maratona'),
            'marathon' => __('maratona'),
            'is_active' => __('attivo'),
        ];
    }

    public function messages(): array
    {
        return [
            'surname.unique' => 'Nome e Cognome già utilizzati',
        ];
    }
}
