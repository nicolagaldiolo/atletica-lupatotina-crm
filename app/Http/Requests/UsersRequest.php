<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
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
        $id = $this->user ? $this->user->id : null;

        return [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,'.$id,
            'password' => 'sometimes|confirmed|min:4',
        ];
    }
}
