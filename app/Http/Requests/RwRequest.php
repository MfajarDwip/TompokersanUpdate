<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RwRequest extends FormRequest
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
            'phone' => 'required|numeric|min:11|max:13',
            'password' => 'required',
            'string',
            'min:10',             // must be at least 10 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[@$!%*#?&]/', // must contain a special character
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Nomor Surat harus diisi',
            'phone.min' => 'Nomor HP minimal 11 angka',
            'phone.mas' => 'Nomor HP minimal 13 angka',
            'phone.numeric' => 'Nomor HP hanya terdiri dari angka',
            'password.numeri.required' => 'Nomor Surat harus diisi',
        ];
    }
}
