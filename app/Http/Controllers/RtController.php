<?php

namespace App\Http\Controllers;

use App\Models\MobileMasterAkunModel;
use App\Models\RwaksesModal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RtController extends Controller
{
    public function master_rt(Request $request, $id)
    {
        $datartrw = DB::table('master_masyarakats')
            ->join('master_akuns', 'master_akuns.id_masyarakat', '=', 'master_masyarakats.id_masyarakat')
            ->join('master_kks', 'master_kks.id_kk', '=', 'master_masyarakats.id_kk')
            ->where('role', '=', '2')
            ->where('RW', $id)
            ->get();

        return view('master_rt', compact('datartrw'));
    }

    public function simpanmasterrt(Request $request, $id)
    {
        $datacheck = DB::table('master_kks')
            ->join('master_masyarakats', 'master_kks.id_kk', '=', 'master_masyarakats.id_kk')
            ->join('master_akuns', 'master_akuns.id_masyarakat', 'master_masyarakats.id_masyarakat')
            ->where('master_kks.rt', $request->rt)
            ->where('master_kks.rw', $request->rw)
            ->where('master_akuns.role', '2')
            ->first();

        if ($datacheck !== null) {
            return redirect()->back()->with('errorrt', 'Data sudah ada');
        } else {
            $rt = new RwaksesModal();
            $data = $rt->Rw()->where('nik', $id)->first();

            if ($data) {
                if ($data->role == '2') {
                    return redirect('masterrt/' . $request->rt)->with('errorissetrt', 'RT sudah ada');
                } elseif ($data->role == '3') {
                    return redirect('masterrt/' . $request->rt)->with('errorissetrw', 'RW sudah ada');
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
                        return redirect()->back()->withErrors($validator)->withInput();
                    }

                    $data = new MobileMasterAkunModel();
                    $data->uuid = Str::uuid()->toString();
                    $data->no_hp = $validator->validated()['no_hp'];
                    $data->password = Hash::make($validator->validated()['password']);
                    $data->role = '2';
                    $data->id_masyarakat = $request->id_masyarakat;
                    $data->save();

                    return redirect('masterrt/' . $request->rt)->with('success', 'Data berhasil disimpan');
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
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $data = new MobileMasterAkunModel();
                $data->uuid = Str::uuid()->toString();
                $data->no_hp = $validator->validated()['no_hp'];
                $data->password = Hash::make($validator->validated()['password']);
                $data->role = '2';
                $data->id_masyarakat = $request->id_masyarakat;
                $data->save();

                return redirect('masterrt/' . $request->rw)->with('success', 'Data berhasil disimpan');
            }
        }
    }

    public function ajax_rt(Request $request)
    {
        $nik = $request->nik;
        $results = DB::table('master_masyarakats')
            ->join('master_kks', 'master_kks.id_kk', '=', 'master_masyarakats.id_kk')
            ->where('master_masyarakats.nik', 'like', '%' . $nik . '%')
            ->get();
        $c = count($results);

        if ($c == 0) {
            return '<p class="text-muted">Maaf, Data tidak ditemukan</p>';
        } else {
            return view('ajax_pagert')->with([
                'datart' => $results,
            ]);
        }
    }

    public function read()
    {
        return 'Silahkan Melakukan Pencarian Data';
    }

    public function updatemasterrt(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'no_hp' => 'required|min:10|max:13',
        'password' => 'nullable|min:8|max:50',
    ], [
        'no_hp.required' => 'Nomor Telepon Tidak Boleh Kosong',
        'no_hp.min' => 'Nomor Telepon Minimal 10 Angka',
        'no_hp.max' => 'Nomor Telepon Maksimal 13 Angka',
        'password.min' => 'Password Minimal 8 Karakter',
        'password.max' => 'Password Maksimal 50 Karakter',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $updateData = [
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('master_akuns')
            ->where('id_masyarakat', $id)
            ->update($updateData);

        return redirect()->back()->with('successedit', 'Data berhasil diperbarui');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data');
    }
}


    public function hapusMasterRt($nik)
    {
        try {
            DB::table('master_akuns')
                ->join('master_masyarakats', 'master_masyarakats.id_masyarakat', '=', 'master_akuns.id_masyarakat')
                ->where('master_masyarakats.nik', $nik)
                ->delete();
            return redirect()->back()->with('successDelete', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('errorDelete', 'Terjadi kesalahan saat menghapus data');
        }
    }
}
