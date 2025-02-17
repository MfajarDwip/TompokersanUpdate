<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KartukkRequest extends FormRequest
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
            'no_kk' => 'required|max:16|min:16',
            'nik' => 'required|max:16|min:16',
            'kepala_keluarga' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'kabupaten' => 'required',
            'provinsi' => 'required',
            'kode_pos' => 'required',
            'kk_tgl' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'no_kk.required' => 'Nomor kartu keluarga tidak boleh kosong',
            'no_kk.min' => 'Nomor kartu keluarga miniman 16 angka',
            'no_kk.max' => 'Nomor kartu keluarga maximal 16 angka',
            'nik.required' => 'Nik tidak boleh kosong',
            'nik.min' => 'Nik minimal 16 angka',
            'nik.max' => 'Nik maximal 16 angka',
            'kepala_keluarga.required' => 'Nama Kepala Keluarga tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
            'kode_pos.required' => 'Kode Pos Tidak boleh kosong',
            'rt.required' => 'Rt tidak boleh kosong',
            'rw.required' => 'Rw tidak boleh kosong',
            'kk_tgl.required' => 'Tanggal KK tidak boleh kosong',
        ];
    }
}
