<?php

namespace App\Http\Controllers;

use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterMasyarakatModel;
use App\Models\MobilePengajuanSuratModel;
use FPDF;
use Illuminate\Http\Request;

class ApiPengajuanRwController extends Controller
{
    //rekap surat rw sesuai status
    public function status_surat_rw(Request $request)
    {
        $userId = $request->user()->id_masyarakat;
        $status = $request->input('status');
        $userRole = $request->user()->role;

        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 3) {
                    return response()->json([
                        'message' => 'Anda bukan rw'
                    ]);
                } else {
                    $suratData = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rw', $userKks->rw);
                    })
                    ->with(['masyarakat.akun', 'surat'])
                    ->when($status, function ($query, $status) {
                        return $query->where('status', $status);
                    })
                    ->get();

                    return response()->json($suratData);
                }
            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }

    }
    
  public function downloadPdf($fileName)
{
    // Path relatif dari direktori public/pdfs
    $filePath = 'pdfs/' . $fileName;

    // Cek apakah file ada di public folder
    if (file_exists(public_path($filePath))) {
        // Kembalikan file sebagai response download
        return response()->download(public_path($filePath));
    }

    // Jika file tidak ditemukan, kembalikan response JSON
    return response()->json(['message' => 'File not found'], 404);
}



    //rekap semua surat rw
    public function rekap_rw(Request $request)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;

        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 3) {
                    return response()->json([
                        'message' => 'Anda bukan rw'
                    ]);
                } else {
                    $suratData = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rw', $userKks->rw);
                    })
                    ->with(['masyarakat', 'surat'])
                    ->whereNotIn('status', ['Dibatalkan'])
                    ->orderByDesc('id_pengajuan')
                    ->paginate(10);


                    return response()->json($suratData);
                }
            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }

    }

public function update_status_setuju_rw(Request $request, $id)
{
    try {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;

        // Ambil data masyarakat berdasarkan user ID
        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();
        if (!$userMasyarakat) {
            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }

        // Ambil data KKS berdasarkan ID KK dari masyarakat
        $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();
        if (!$userKks) {
            return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
        }

        // Cek apakah user memiliki role RW
        if ($userRole != 3) {
            return response()->json(['error' => 'Anda bukan RW'], 403);
        }

        // Ambil data surat yang berhubungan dengan masyarakat di RW yang sama
        $surat = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
            $query->where('rw', $userKks->rw);
        })->findOrFail($id);

        // Perbarui status surat
        $surat->status = "Disetujui RW";

        // Generate PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Set title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Surat Pengantar RW', 0, 1, 'C');
        $pdf->Ln(10); // Space after title

        // Set metadata
        $pdf->SetFont('Arial', '', 12);

        // Nomor Pengantar
        $no_pengantar = uniqid(); // Generate unique ID for pengantar
        $pdf->Cell(40, 10, 'No Pengantar:', 0, 0);
        $pdf->Cell(0, 10, $no_pengantar, 0, 1);
        $pdf->Ln(5); // Space after each item
        
        // Detail Pengajuan
        $pdf->Cell(0, 10, 'Detail Pengajuan:', 0, 1);
        $pdf->Ln(5); // Space before details
        
        // Nama
        $pdf->Cell(40, 10, 'Nama:', 0, 0);
        $pdf->Cell(0, 10, $surat->masyarakat->nama_lengkap, 0, 1);
        $pdf->Ln(5); // Space after each item

        // Jenis Kelamin
        $pdf->Cell(40, 10, 'Jenis Kelamin:', 0, 0);
        $pdf->Cell(0, 10, $surat->masyarakat->jenis_kelamin, 0, 1);
        $pdf->Ln(5); // Space after each item

        // Pekerjaan
        $pdf->Cell(40, 10, 'Pekerjaan:', 0, 0);
        $pdf->Cell(0, 10, $surat->masyarakat->pekerjaan, 0, 1);
        $pdf->Ln(5); // Space after each item
        
        // NIK
        $pdf->Cell(40, 10, 'NIK:', 0, 0);
        $pdf->Cell(0, 10, $surat->masyarakat->nik, 0, 1);
        $pdf->Ln(5); // Space after each item
        
        // Alamat
        $pdf->Cell(40, 10, 'Alamat:', 0, 0);
        $pdf->MultiCell(0, 10, $surat->masyarakat->alamat);
        $pdf->Ln(10); // Space after metadata

        // Add the main content
        $pdf->MultiCell(0, 10, 'Dengan ini menerangkan bahwa yang bersangkutan adalah warga RW ' . $userKks->rw . ', dan benar-benar tinggal di alamat tersebut. Surat ini diterbitkan untuk keperluan ' . $surat->keperluan . '.');
        $pdf->Ln(10);

        // Signature placeholder
        $pdf->Cell(0, 10, 'Hormat kami,', 0, 1, 'R');
        $pdf->Ln(20);
        $pdf->Cell(0, 10, '(Nama RW)', 0, 1, 'R');
        
        // Save PDF directly in the public/pdfs directory
        $filePath = public_path('pdfs/' . $id . '_pengantar_rw.pdf');
        $pdf->Output($filePath, 'F');

        // Save relative path to the database
        $surat->pengantar_rw = 'pdfs/'. $id . '_pengantar_rw.pdf';
        $surat->save();

        return response()->json([
            'message' => 'Status surat dan file PDF berhasil diperbarui',
            'surat' => $surat,
            'file_path' => asset('pdfs/' . $id . '_pengantar_rw.pdf')
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}

}
