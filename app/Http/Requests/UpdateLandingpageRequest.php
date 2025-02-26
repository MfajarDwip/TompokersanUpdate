<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandingpageRequest extends FormRequest
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
            'nama_website' => 'required',
            'judul_home' => 'required',
            'deskripsi_home' => 'required',
            'judul_tentang' => 'required',
            'link_download' => 'required',
            'tentang_aplikasi' => 'required',
            'email_kelurahan' => 'required',
            'no_telp' => 'required',
            'alamat_kelurahan' => 'required',
            'video_url' => 'required|url',
            'token' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nama_website' => 'Field Tidak Boleh Kosong',
            'judul_home' => 'Field Tidak Boleh Kosong',
            'deskripsi_home' => 'Field Tidak Boleh Kosong',
            'judul_tentang' => 'Field Tidak Boleh Kosong',
            'link_download' => 'Field Tidak Boleh Kosong',
            'tentang_aplikasi' => 'Field Tidak Boleh Kosong',
            'email_kelurahan' => 'Field Tidak Boleh Kosong',
            'no_telp' => 'Field Tidak Boleh Kosong',
            'alamat_kelurahan' => 'Field Tidak Boleh Kosong',
            'video_url' => 'Field tidak boleh kosong',
            'token' => 'field tidak boleh kosong'
        ];
    }
}
