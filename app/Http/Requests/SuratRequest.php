<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuratRequest extends FormRequest
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
            'id_surat' => 'required|numeric',
            'nama_surat' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'id_surat.required' => 'Nomor Surat harus diisi',
            'id_surat.numeric' => 'Nomor Surat harus berisi angka',
            'nama_surat.required' => 'Nama Surat Harus diisi',
            // 'image.required' => 'Gambar Surat Harus diisi',
        ];
    }
}