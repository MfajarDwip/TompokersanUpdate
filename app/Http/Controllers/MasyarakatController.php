<?php

namespace App\Http\Controllers;

use App\Http\Requests\MasyarakatRequest;
use App\Models\MobileMasterMasyarakatModel;
use App\models\master_akun;
use App\Models\MobileMasterKksModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\MasImport;
use Maatwebsite\Excel\Facades\Excel;

class MasyarakatController extends Controller
{
    public function master_kk_mas(Request $request, $id)
    {
        $data = MobileMasterMasyarakatModel::with('masyarakat')
            ->where('id_kk', '=', $id)
            ->get();

        return view('master_kk_mas', ['nomor_kk' => $id], compact('data'));
    }

    public function simpanmasteruser(Request $request)
    {

        $this->validate($request, []);
        $request->validate([
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
        ], [
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
        ]);
        $validated = $request->nik;
        $check = MobileMasterMasyarakatModel::all();
        foreach ($check as  $value) {
            if ($value->nik == $validated) {
                return redirect()->back()->with('exist', '')->withInput();
            }
        }
        try {
            $data = new MobileMasterMasyarakatModel();
            // $uuid = Str::uuid()->toString();
            // $data->id_masyarakat = $uuid;
            $data->id_kk = $request->nokk;
            $data->nik = $request->nik;
            $data->nama_lengkap = $request->nama_lengkap;
            $data->jenis_kelamin = $request->kelamin;
            $data->tempat_lahir = $request->tempat_lahir;
            $data->tgl_lahir = $request->tgl_lahir;
            $data->agama = $request->agama;
            $data->pendidikan = $request->pendidikan;
            $data->pekerjaan = $request->pekerjaan;
            $data->golongan_darah = $request->gol_darah;
            $data->status_perkawinan = $request->status_perkawinan;
            $data->tgl_perkawinan = $request->tgl_perkawinan;
            $data->status_keluarga = $request->status_keluarga;
            $data->kewarganegaraan = $request->kewarganegaraan;
            $data->no_paspor = $request->no_paspor;
            $data->no_kitap = $request->no_kitap;
            $data->nama_ayah = $request->nama_ayah;
            $data->nama_ibu = $request->nama_ibu;
            $data->save();

            return Redirect('masterkkmas/' . $data->id_kk)->with('success', '');
        } catch (\Throwable $th) {
            throw $th;
        }

        try {
            $data = MobileMasterMasyarakatModel::with('masyarakat')
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->select('id_kk')
                ->first();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updatemasteruser(MasyarakatRequest $request, $id)
    {
        $validated = $request->validated();
        try {
            $data = DB::table('master_masyarakats')->where('nik', $id)->update([
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'agama' => $request->agama,
                'pendidikan' => $request->pendidikan,
                'pekerjaan' => $request->pekerjaan,
                'golongan_darah' => $request->gol_darah,
                'status_perkawinan' => $request->status_perkawinan,
                'tgl_perkawinan' => $request->tgl_perkawinan,
                'status_keluarga' => $request->status_keluarga,
                'kewarganegaraan' => $request->kewarganegaraan,
                'no_paspor' => $request->no_paspor,
                'no_kitap' => $request->no_kitap,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        try {
            $data = MobileMasterMasyarakatModel::with('masyarakat')
                ->where('nik', '=', $request->nik)
                ->select('id_kk')
                ->first();

            return Redirect('masterkkmas/' . $data->id_kk)->with('successedit', '');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function hapusmasteruser(Request $request, $id)
    {
        try {
            $data = MobileMasterMasyarakatModel::where('nik', $id);
            $data->delete();

            // return Redirect('masterkkmas/'.$request->id_kk);
            return redirect()->back()->with('successhappus', '');
        } catch (\Throwable $th) {
            // return response()->json([
            //     'message' => 'Data Tidak Bisa Dihapus, Sudah Pernah Mengajukan',
            // ], 200);
            return redirect()->back()->with('relation', '');
        }
    }
    public function showAccountData($id)
    {
        $accountData = master_akun::find($id);

        if (!$accountData) {
            return redirect()->back()->with('error', 'Account not found');
        }

        return view('surat_selesai', compact('accountData'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        
        Excel::import(new MasImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Berhasil Diimport');
    }
}
