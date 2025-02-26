<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuratRequest;
use App\Models\MobileMasterSuratModel;
use Illuminate\Http\Request;

class SuratController extends Controller
{
    public function index()
    {
        $data = MobileMasterSuratModel::all();
        return view('master_surat', compact('data'));
    }

    public function store(SuratRequest $suratrequest)
    {
        $valid = $suratrequest->id_surat;
        $check = MobileMasterSuratModel::all();
        foreach ($check as $value) {
            if ($value->id_surat == $valid) {
                return redirect()->back()->with('exist', 'Surat sudah ada')->withInput();
            }
        }

        $imageName = time().'.'.$suratrequest->image->getClientOriginalExtension();
        $suratrequest->image->move(public_path('images/'), $imageName);
        $validated = $suratrequest->validated();
        $validated['image'] = $imageName;

        MobileMasterSuratModel::create([
            'id_surat' => $validated['id_surat'],
            'nama_surat' => $validated['nama_surat'],
            'image' => $imageName,
        ]);

        return redirect('mastersurat')->with('success', 'Surat berhasil ditambahkan');
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = MobileMasterSuratModel::where('id_surat', $id)->first();
            if (!$data) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }
            $data->delete();
            return redirect('mastersurat')->with('successhapus', 'Surat berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('relation', 'Data tidak bisa dihapus, sudah ada pengajuan');
        }
    }

    public function update(SuratRequest $suratrequest, $id)
    {
        $data = MobileMasterSuratModel::where('id_surat', $id)->first();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
    
        $validated = $suratrequest->validated();
    
        $data->update([
            'id_surat' => $validated['id_surat'],
            'nama_surat' => $validated['nama_surat'],
        ]);
    
        if ($suratrequest->hasFile('image')) {
            $imageName = time() . '.' . $suratrequest->image->getClientOriginalExtension();
            $suratrequest->image->move(public_path('images/'), $imageName);
            $data->update([
                'image' => $imageName,
            ]);
        }
    
        return redirect('mastersurat')->with('successedit', 'Data surat berhasil diubah');
    }

    public function updateimage(Request $request, $id)
    {
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'image.required' => 'Gambar Surat Harus diisi',
            'image.mimes' => 'Format Ikon Harus jpeg,png,jpg,gif,svg',
            'image.max' => 'Ukuran Ikon Maksimal 2 Mb',
        ]);
    
        $imageName = time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('images/'), $imageName);
    
        $data = MobileMasterSuratModel::where('id_surat', $id)->first();
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
    
        $data->update([
            'image' => $imageName,
        ]);
    
        return redirect('mastersurat')->with('successedit', 'Ikon surat berhasil diupdate');
    }
}
