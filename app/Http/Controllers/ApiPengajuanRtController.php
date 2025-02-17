<?php

namespace App\Http\Controllers;

use App\Models\MobileMasterAkunModel;
use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterMasyarakatModel;
use App\Models\MobilePengajuanSuratModel;
use Illuminate\Http\Request;
use FPDF;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\FirebaseMessaging;

class ApiPengajuanRtController extends Controller
{
    //rekap surat rt sesuai status
    public function status_surat_rt(Request $request)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;
        $status = $request->input('status');

        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 2) {
                    return response()->json([
                        'message' => 'Anda bukan rt'
                    ]);
                } else {
                    $suratData = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rt', $userKks->rt);
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

    //rekap semua surat rt
    public function rekap_rt(Request $request)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;

        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 2) {
                    return response()->json([
                        'message' => 'Anda bukan rt'
                    ]);
                } else {

                    $suratData = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rt', $userKks->rt);
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
    
public function update_status_setuju_rt(Request $request, $id)
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

        // Cek apakah user memiliki role RT
        if ($userRole != 2) {
            return response()->json(['error' => 'Anda bukan RT'], 403);
        }

        // Ambil data surat yang berhubungan dengan masyarakat di RT yang sama
        $surat = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
            $query->where('rt', $userKks->rt);
        })->findOrFail($id);

        // Perbarui status surat dan nomor pengantar
        $no_pengantar = $request->input('no_pengantar');
        $surat->status = "Disetujui RT";
        $surat->no_pengantar = $no_pengantar;

        // Generate PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Add logo or header if needed
        // $pdf->Image('path_to_logo.jpg', 10, 6, 30);
        
        // Set title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Surat Pengantar', 0, 1, 'C');
        $pdf->Ln(10); // Space after title
        
        // Set metadata
        $pdf->SetFont('Arial', '', 12);
        
        // Nomor Pengantar
        $pdf->Cell(0, 10, 'Nomor Pengantar:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $no_pengantar, 0, 1);
        $pdf->Ln(5); // Space after each item
        
        // Nama
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Nama:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $surat->masyarakat->nama_lengkap, 0, 1);
        $pdf->Ln(5); // Space after each item

        // Jenis Kelamin
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Jenis Kelamin:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $surat->masyarakat->jenis_kelamin, 0, 1);
        $pdf->Ln(5); // Space after each item
        
        // Pekerjaan
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Pekerjaan:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $surat->masyarakat->pekerjaan, 0, 1);
        $pdf->Ln(5); // Space after each item
        
        // NIK
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'NIK:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, $surat->masyarakat->nik, 0, 1);
        $pdf->Ln(5); // Space after each item
        
        // Alamat
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Alamat:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, $surat->masyarakat->alamat);
        $pdf->Ln(10); // Space after metadata

        // Add the main content
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, 'Dengan ini menerangkan bahwa yang bersangkutan adalah warga RT ' . $userKks->rt . ', dan benar-benar tinggal di alamat tersebut. Surat ini diterbitkan untuk keperluan ' . $surat->keperluan . '.');
        $pdf->Ln(10);

        // Signature placeholder
        $pdf->Cell(0, 10, 'Hormat kami,', 0, 1, 'R');
        $pdf->Ln(20);
        $pdf->Cell(0, 10, '(Nama RT)', 0, 1, 'R');
        
        // Save PDF directly in the public/pdfs directory
        $filePath = public_path('pdfs/' . $id . '_pengantar_rt.pdf');
        $pdf->Output($filePath, 'F');

        // Save relative path to the database
        $surat->pengantar_rt = 'pdfs/'. $id . '_pengantar_rt.pdf';
        $surat->save();

        return response()->json([
            'message' => 'Status surat dan file PDF berhasil diperbarui',
            'surat' => $surat,
            'file_path' => asset('pdfs/' . $id . '_pengantar_rt.pdf')
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}



   
    public function update_status_tolak_rt(Request $request, $id)
    {
        $userId = $request->user()->id_masyarakat;
        $userRole = $request->user()->role;

        $userMasyarakat = MobileMasterMasyarakatModel::where('id_masyarakat', $userId)->first();

        if ($userMasyarakat) {
            $userKks = MobileMasterKksModel::where('id_kk', $userMasyarakat->id_kk)->first();

            if ($userKks) {
                if ($userRole != 2) {
                    return response()->json([
                        'message' => 'Anda bukan rt'
                    ]);
                } else {

                    $surat = MobilePengajuanSuratModel::whereHas('masyarakat.kks', function ($query) use ($userKks) {
                        $query->where('rt', $userKks->rt);
                    })->findOrFail($id);

                    $keterangan_tolak = $request->input('keterangan_ditolak');

                    $surat->status = "Ditolak RT";
                    $surat->info = "non_active";
                    $surat->keterangan_ditolak = $keterangan_tolak;
                    $surat->save();

                    return response()->json([
                        'message' => 'Status surat updated successfully',
                        'surat' => $surat
                    ]);
                }

            } else {

                return response()->json(['error' => 'Data KKS user tidak ditemukan'], 404);
            }
        } else {

            return response()->json(['error' => 'Data Masyarakat user tidak ditemukan'], 404);
        }
    }

    public function sendNotificationsRw(Request $request)
    {
        $akun = $request->user()->id_masyarakat;

        $userMasyarakat =  MobileMasterMasyarakatModel::whwere('id_masyarakat', $akun)->first();

        if ($userMasyarakat) {
            $userWithSameRw = MobileMasterAkunModel::whereHas('masyarakat', function ($query) use ($userMasyarakat) {
                $query->where('id_kk', $userMasyarakat->id_kk);
            })->where('role', 3)->first();
            if ($userWithSameRw) {
                $token = $userWithSameRw->fcm_token;

                try {
                    $message = CloudMessage::new()
                        ->withNotification(Notification::create("Surat Masuk", "Terdapat surat masuk"))
                        ->withChangedTarget('token', $token);

                    FirebaseMessaging::send($message);
                    return response()->json(['message' => 'Notification sent successfully.']);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Failed to send notification.'], 500);
                }
            } else {
                return response()->json(['message' => 'User with same rt and role 3 not found.'], 404);
            }
        } else {
            return response()->json(['message' => 'User Masyarakat not found.'], 404);
        }
    }

}
