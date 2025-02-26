<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMasyarakatRequest extends FormRequest
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
            'nik' => 'required|max:16|min:16',
            'nama_lengkap' => 'required',
            'kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'agama' => 'required',
            'pendidikan' => 'required',
            'pekerjaan' => 'required',
            'gol_darah' => 'required',
            'status_perkawinan' => 'required',
            'status_keluarga' => 'required',
            'kewarganegaraan' => 'required',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nik.required' => 'NIK Tidak Boleh Kosong',
            'nik.max' => 'NIK Maksimal 16 Angka',
            'nik.min' => 'NIK Minimal 16 Angka',
            'nama_lengkap.required' => 'Nama Lengkap Tidak Boleh Kosong',
            'kelamin.required' => 'Jenis Kelamin Tidak Boleh Kosong',
            'tempat_lahir.required' => 'Tempat Lahir Tidak Boleh Kosong',
            'tgl_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong',
            'agama.required' => 'Agama Tidak Boleh Kosong',
            'pendidikan.required' => 'Pendidikan Tidak Boleh Kosong',
            'pekerjaan.required' => 'Pekerjaan Tidak Boleh Kosong',
            'gol_darah.required' => 'Golongan Darah Tidak Boleh Kosong',
            'status_perkawinan.required' => 'Status Perkawinan Tidak Boleh Kosong',
            'status_keluarga.required' => 'Status Keluarga Tidak Boleh Kosong',
            'kewarganegaraan.required' => 'Kewarganegaraan Tidak Boleh Kosong',
            'nama_ayah.required' => 'Nama Ayah Tidak Boleh Kosong',
            'nama_ibu.required' => 'Nama Ibu Tidak Boleh Kosong',
        ];
    }
}
