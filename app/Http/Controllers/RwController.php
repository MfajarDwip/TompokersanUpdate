<?php

namespace App\Http\Controllers;

use App\Models\MobileMasterAkunModel;
use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterMasyarakatModel;
use App\Models\RwaksesModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RwController extends Controller
{
    public function master_rw()
    {
        $datartrw = MobileMasterAkunModel::with('user')
            ->where('master_akuns.role', '=', '3')
            ->get();

        return view('master_rw', compact('datartrw'));
    }

    public function hapusmasterrw(Request $request, $id)
    {
        try {
            $data = MobileMasterAkunModel::where('id_masyarakat', $id)->first();

            if ($data) {
                $data->delete();
                return redirect('masterrw')->with('success', 'Data berhasil dihapus');
            } else {
                return redirect('masterrw')->with('error', 'Data tidak ditemukan');
            }
        } catch (\Exception $e) {
            return redirect('masterrw')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }




    public function ajax(Request $request)
    {

        $nik = $request->nik;
        $results = MobileMasterMasyarakatModel::with('masyarakat')->where('master_masyarakats.nik', 'like', '%' . $nik . '%')->get();
        $c = count($results);
        if ($c == 0) {
            return '<p class="text-muted">Maaf, Data tidak ditemukan</p>';
        } else {
            return view('ajaxpage')->with([
                'data' => $results,
            ]);
        }
    }

    public function read()
    {
        return 'Silahkan Melakukan Pencarian Data';
    }

    public function simpanmasterrw(Request $request, $id)
    {
        $datacheck = DB::table('master_kks')
            ->join('master_masyarakats', 'master_kks.id_kk', '=', 'master_masyarakats.id_kk')
            ->join('master_akuns', 'master_akuns.id_masyarakat', 'master_masyarakats.id_masyarakat')
            ->where('master_kks.rw', $request->rw)
            ->where('master_akuns.role', '3')
            ->first();
        if ($datacheck !== null) {
            return Redirect('masterrw')->with('errorissetrw', '');
        } else {
            // dd('data bisa ditamabhkan');
            $rw = new RwaksesModal();
            $data = $rw->Rw()
                ->where('nik', $id)
                ->first();
            if ($data) {
                if ($data->role == '2') {
                    return Redirect('masterrw')->with('errorrt', '');
                } elseif ($data->role == '3') {
                    return Redirect('masterrw')->with('errorrw', '');
                } elseif ($data->role == '4') {
                    $validator = Validator::make($request->all(), [
                        'no_hp' => 'required|min:10|max:13',
                        'password' => 'required|min:8|max:8',
                    ], [
                        'no_hp.required' => 'Nomor Telepon Tidak Boleh Kosong',
                        'no_hp.min' => 'Nomor Telepon Minimal 10 Angka',
                        'no_hp.max' => 'Nomor Telepon Maksimal 13 Angka',
                        'password.required' => 'Password Tidak Boleh Kosong',
                        'password.min' => 'Password Minimal 8 Karakter, Terdapat Huruf dan Angka',
                        'password.max' => 'Password Maxsimal 8 Karakter, Terdapat Huruf dan Angka',
                    ]);
                    if ($validator->fails()) {
                        // Jika validasi gagal, lakukan tindakan yang sesuai
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                    $data = new MobileMasterAkunModel();
                    $data->uuid = Str::uuid()->toString();
                    // $data->id = $uuid;
                    $data->no_hp =  $validator->validated()['no_hp'];
                    $passwordhash = $validator->validated()['password'];
                    $data->password = Hash::make($passwordhash);
                    $data->role = '3';
                    $data->id_masyarakat = $request->id_masyarakat;
                    $data->save();

                    return Redirect('masterrw')->with('success', '');
                }
            } else {
                $data = DB::table('master_masyarakats')
                    ->join('master_kks', 'master_kks.id_kk', '=', 'master_masyarakats.id_kk')
                    ->where('master_masyarakats.nik', '=', $id)
                    ->first();
                $validator = Validator::make($request->all(), [
                    'no_hp' => 'required|min:10|max:13',
                    'password' => 'required|min:8|max:8',
                ], [
                    'no_hp.required' => 'Nomor Telepon Tidak Boleh Kosong',
                    'no_hp.min' => 'Nomor Telepon Minimal 10 Angka',
                    'no_hp.max' => 'Nomor Telepon Maksimal 13 Angka',
                    'password.required' => 'Password Tidak Boleh Kosong',
                    'password.min' => 'Password Minimal 8 Karakter, Terdapat Huruf dan Angka',
                    'password.max' => 'Password Maxsimal 8 Karakter, Terdapat Huruf dan Angka',
                ]);
                if ($validator->fails()) {
                    // Jika validasi gagal, lakukan tindakan yang sesuai
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $data = new MobileMasterAkunModel();
                $data->uuid = Str::uuid()->toString();
                // $data->id = $uuid;
                $data->no_hp =  $validator->validated()['no_hp'];
                $passwordhash = $validator->validated()['password'];
                $data->password = Hash::make($passwordhash);
                $data->role = '3';
                $data->id_masyarakat = $request->id_masyarakat;
                $data->save();

                return Redirect('masterrw')->with('success', '');
            }
        }
    }

    public function updatemasterrw(Request $request, $id)
    {
        $passwordhash = $request->password;
        $pass = Hash::make($passwordhash);
        $data = DB::table('master_kks')
            ->join('master_masyarakats', 'master_masyarakats.id_kk', '=', 'master_kks.id_kk')
            ->join('master_akuns', 'master_akuns.id_masyarakat', '=', 'master_masyarakats.id_masyarakat')
            ->where('master_masyarakats.nik', $request->nik)
            ->where('master_akuns.role', '3')
            ->update([
                'master_akuns.no_hp' => $request->no_hp,
                'master_akuns.password' => $pass,
            ]);

        return Redirect('masterrw')->with('successedit', '');
    }
}
