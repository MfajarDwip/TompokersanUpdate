<?php

namespace App\Http\Controllers;

use App\Http\Requests\KartukkeditRequest;
use App\Http\Requests\KartukkRequest;
use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterMasyarakatModel;
use App\Imports\KkImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KartukkController extends Controller
{
    public function index()
    {
        $data = MobileMasterMasyarakatModel::with('masyarakat')
            ->where('status_keluarga', 'kepala keluarga')
            ->orderBy('nik', 'DESC')->get();

        return view('master_kk', compact('data'))->with('success', '');
    }

    public function update(Request $request, KartukkeditRequest $kartukkeditRequest, $id)
    {
        $validated = $kartukkeditRequest->validated();
        $data = MobileMasterKksModel::where('no_kk', $id)->first();
        $data->update([
            'no_kk' => $validated['nokkedit'],
            'alamat' => $validated['alamatkkedit'],
            'rt' => $validated['rtedit'],
            'rw' => $validated['rwedit'],
            'kode_pos' => $validated['kdposedit'],
            'kelurahan' => $validated['keledit'],
            'kecamatan' => $validated['kecedit'],
            'kabupaten' => $validated['kabedit'],
            'provinsi' => $validated['provedit'],
            'kk_tgl' => $validated['tglkkedit'],
        ]);

        return Redirect('masterkk')->with('successedit', '');
    }

    //Untuk Hapus Master KK
    public function destroy($no_kk)
    {
        try {
            $kk = MobileMasterKksModel::where('no_kk', $no_kk)->first();
            if ($kk) {
                $kk->delete();
                return redirect()->route('masterkk')->with('successHapus', 'Data berhasil dihapus');
            } else {
                return redirect()->route('masterkk')->with('error', 'Data KK tidak ditemukan');
            }
        } catch (\Exception $e) {
            Log::error('Delete Error: ' . $e->getMessage());
            return redirect()->route('masterkk')->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }


    public function simpanmasterkk(KartukkRequest $kkrequest)
    {
        $validated = $kkrequest->validated();
        $check = MobileMasterKksModel::all();
        foreach ($check as $value) {
            if ($value->no_kk == $validated['no_kk']) {
                return redirect()->back()->with('exist', '');
            }
        }
        $data = MobileMasterKksModel::create($validated);

        return redirect('simpankepala/' . $kkrequest->no_kk . '/' . $kkrequest->kepala_keluarga . '/' . $kkrequest->nik);
    }

    public function simpankepalakeluarga(Request $request, $id, $other_id, $nik)
    {
        try {
            $datakepala = MobileMasterKksModel::where('no_kk', '=', $id)
                ->first();
            // dd($datakepala);
            $data = new MobileMasterMasyarakatModel();
            // $uuid = Str::uuid()->toString();
            $data->id_kk = $datakepala->id_kk;
            $data->nik = $nik;
            $data->nama_lengkap = $other_id;
            $data->status_keluarga = 'Kepala Keluarga';
            $data->save();

            return Redirect('masterkk')->with('success', 'Berhasil Disimpan');
        } catch (\Throwable $th) {
        }

        try {
            $data = MobileMasterMasyarakatModel::with('masyarakat')
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->select('master_kks.id')
                ->first();
        } catch (\Throwable $th) {
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        
        Excel::import(new KkImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Berhasil Diimport');
    }

}
