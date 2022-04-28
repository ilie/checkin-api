<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //$this->authorize('update', $this->route('user'));
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
            'name' => ['required', 'string', 'max:255'],
            'nif' => ['required', 'string', 'size:9'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->route('user')],
            'social_sec_num' => ['required', 'string', 'max:25'],
            'hours_on_contract' => ['required', 'numeric', 'between:1,40'],
            'is_admin' => ['required', 'boolean']
        ];
    }
}
