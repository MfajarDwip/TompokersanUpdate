<?php

namespace App\Http\Controllers;

use App\Models\PengajuanModel;
use App\Models\UpdateStatusModel;
use App\Models\MobileMasterAkunModel;
use App\Models\MobileMasterKksModel;
use App\Models\MobileMasterMasyarakatModel;
use App\Http\Requests\PengajuanRequest;
use FPDF;
use Illuminate\Support\Facades\Cache;

class PengajuanController extends Controller
{
    protected $pengajuan;
    protected $updateStatus;

    public function __construct(PengajuanModel $pengajuan, UpdateStatusModel $updateStatus)
    {
        $this->pengajuan = $pengajuan;
        $this->updateStatus = $updateStatus;
    }

    public function surat_masuk()
    {
        $data = $this->pengajuan->pengajuan()
            ->where('pengajuan_surats.status', '=', 'Disetujui RW')
            ->get();

        // Calculate new nomor_surat for the form
        $nomorSuratBaru = $this->getNextNomorSurat();

        return view('surat_masuk', compact('data', 'nomorSuratBaru'));
    }



    public function showSuratMasuk()
    {
        $data = PengajuanModel::all();
        $akses = session()->get('hak_akses');

        // Calculate new nomor_surat for the form
        $nomorSuratBaru = $this->getNextNomorSurat();

        return view('surat_masuk', compact('data', 'akses', 'nomorSuratBaru'));
    }

    private function getNextNomorSurat()
    {
        $lastSurat = PengajuanModel::orderBy('nomor_surat', 'desc')->first();
        if ($lastSurat) {
            $lastNumber = $lastSurat->nomor_surat;
            $parts = explode('.', $lastNumber);

            // Increment last part
            if (is_numeric(end($parts))) {
                $lastPart = (int)end($parts);
                $incrementedPart = $lastPart + 1;
                $parts[count($parts) - 1] = (string)$incrementedPart; // Remove str_pad
            } else {
                $parts[] = '1';
            }

            $nomorSuratBaru = implode('.', $parts);
        } else {
            $nomorSuratBaru = '407.1.1';
        }

        return $nomorSuratBaru;
    }

    private function incrementNomorSurat()
    {
        $lastSurat = PengajuanModel::orderBy('nomor_surat', 'desc')->first();
        if ($lastSurat) {
            $lastNumber = $lastSurat->nomor_surat;
            $parts = explode('.', $lastNumber);

            if (is_numeric(end($parts))) {
                $lastPart = (int)end($parts);
                $incrementedPart = $lastPart + 1;
                $parts[count($parts) - 1] = (string)$incrementedPart;
            } else {
                $parts[] = '1';
            }

            $nomorSuratBaru = implode('.', $parts);
            // Optionally update the database if needed
            return $nomorSuratBaru;
        }
        return '407.1.1';
    }


    public function update_status(PengajuanRequest $request, $id, $akses)
    {
        $status = 'Selesai';
        $validated = $request->validated();
        $pdf = new FPDF();
        $pdf->AddPage();
        $pengajuan = new PengajuanModel;
        $data = $pengajuan->pengajuan()
            ->where('pengajuan_surats.id_pengajuan', $id)->get();
        foreach ($data as $user) {
            $kodeKecamatan = $validated['kode_kecamatan'];
            $nomorSuratTambahan = $validated['nomor_surat_tambahan'];
            $tahunSekarang = date('Y');
            $nomorKelurahan = $request->nomor_surat . '/' . $nomorSuratTambahan . '/' . $kodeKecamatan . '/' . $tahunSekarang;
            $surat = $user->nama_surat;
            if ($surat == 'Surat Keterangan Belum Menikah') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(20, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "                  Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 100);
                $pdf->MultiCell(0, 6, "1. Nama                               : $user->nama_lengkap\n2. Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3. Jenis Kelamin                  : $user->jenis_kelamin\n4. Kebangasaan                   : $user->kewarganegaraan\n5. Agama                             : $user->agama\n6. Status Perkawinan          : $user->status_perkawinan\n7. Pekerjaan                        : $user->pekerjaan\n8. Nik                                  : $user->nik\n9. Alamat                            : $user->alamat", 0, 'L');

                // Paragraf Penutup
                $pdf->SetXY(20, 160);
                $pdf->MultiCell(0, 6, "Orang tersebut di atas benar-benar penduduk Kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang dan berdomisili di alamat tersebut di atas serta menurut keterangan yang bersangkutan hingga saat pembuatan surat keterangan ini, belum pernah menikah.\n", 0, 'L');

                // Set font untuk membuat keterangan menjadi bold

                $pdf->SetX(20);
                $pdf->MultiCell(0, 6, "                 Surat Keterangan ini dipergunakan sebagai persyaratan :", 0, 'L');
                $pdf->SetXY(135, 178);
                $pdf->SetFont('Times', 'B');
                $pdf->MultiCell(0, 6, $user->keterangan, 0, 'L');

                // Set kembali font ke normal setelah keterangan
                $pdf->SetX(20);
                $pdf->SetFont('Times', '');
                $pdf->MultiCell(0, 6, "                 Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');


                $pdf->SetXY(20, 220);  // Adjust position for "Kepala KUA Kecamatan Lumajang"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Mengetahui,\nKepala KUA Kecamatan Lumajang\n\n\n\n\n..............................................", 0, 'L');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                // Existing Tanda Tangan for Lurah Tompokersan
                $pdf->SetXY(130, 210);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Image('image/tompokersan1.jpg', 120, 225, 80, 40, 'JPG');

                // Output PDF
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Keterangan Domisili') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\nJl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(30, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang", 0, 'L');

                $pdf->SetXY(20, 90);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 100);
                $pdf->MultiCell(0, 6, "1. Nama                               : $user->nama_lengkap\n2. Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3. Jenis Kelamin                  : $user->jenis_kelamin\n4. Kebangasaan                   : $user->kewarganegaraan\n5. Agama                             : $user->agama\n6. Status Perkawinan          : $user->status_perkawinan\n7. Pekerjaan                        : $user->pekerjaan\n8. Nik                                  : $user->nik\n9. Alamat                            : $user->alamat", 0, 'L');

                // Paragraf Penutup
                $pdf->SetXY(30, 160);
                $pdf->MultiCell(0, 6, "Berdasarkan surat pengantar dari Ketua RT $user->rt RW $user->rw NO. 23/RT$user->rt/RW$user->rw/$tahunSekarang orang tersebut di atas ", 0, 'L');
                $pdf->SetXY(20, 165);
                $pdf->MultiCell(0, 12, "saat ini berdominasi di $user->alamat RT $user->rt Rw $user->rw  Kelurahan Tompokersan", 0, 'L');
                // Existing Tanda Tangan for Lurah Tompokersan
                $pdf->SetXY(20, 175);  // Position for "Lurah Tompokersan"
                $pdf->MultiCell(0, 6, "           Surat Keterangan ini dipergunakan sebagai persyaratan", 0, 'L');
                $pdf->SetFont('', 'B',);
                $pdf->MultiCell(0, 6, "                     $user->keterangan", 0, 'L');
                $pdf->SetFont('', '',);
                $pdf->SetXY(20, 187);
                $pdf->MultiCell(0, 6, "           Demikian surat keterangan dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);
                $pdf->SetXY(130, 200);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->Image('image/tompokersan1.jpg', 120, 210, 80, 40, 'JPG');
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Keterangan Ijin Keramaian') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT IJIN KERAMAIAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT IJIN KERAMAIAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(20, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "                  Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 95);
                $pdf->MultiCell(0, 6, "1. Nama                               : $user->nama_lengkap\n2. Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3. Jenis Kelamin                  : $user->jenis_kelamin\n4. Kebangasaan                   : $user->kewarganegaraan\n5. Agama                             : $user->agama\n6. Status Perkawinan          : $user->status_perkawinan\n7. Pekerjaan                        : $user->pekerjaan\n8. Nik                                  : $user->nik\n9. Alamat                            : $user->alamat", 0, 'L');

                // Set font untuk membuat keterangan menjadi bold
                $pdf->SetXY(20, 150);
                $pdf->MultiCell(0, 6, "                  Bersama ini memperkenankan mengajukan permohonan ijin keramaian yang akan dilaksanakan pada:", 0, 'L');
                $pdf->SetXY(20, 163);
                $pdf->MultiCell(
                    0,
                    6,
                    "Tanggal                               : $user->tglpelaksanaan
Pukul                                   : $user->jammulai - $user->jamberakhir 
Acara                                   : $user->acara
Tempat                                : $user->tempat",
                    0,
                    'L'
                );

                // Set kembali font ke normal setelah keterangan
                $pdf->SetX(20);
                $pdf->SetFont('Times', '');
                $pdf->MultiCell(0, 6, "                 Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                // Tanda Tangan
                $pdf->SetXY(55, 195);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Mengetahui,", 0, 'L');

                // Tanda tangan Camat Lumajang
                $pdf->SetXY(40, 200);
                $pdf->MultiCell(50, 6, "CAMAT LUMAJANG\n\n\n\n___________________\n", 0, 'C');

                // Tanda tangan Danmil
                $pdf->SetXY(40, 240);
                $pdf->MultiCell(50, 6, "DANRAMIL 0821/01\n\n\n\n___________________\n", 0, 'C');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                // Tanda tangan Lurah
                $pdf->SetXY(100, 195);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');

                // Tanda tangan Kapolsek
                $pdf->SetXY(130, 240);
                $pdf->MultiCell(50, 6, "Kapolsek\n\n\n\n___________________\n", 0, 'C');

                $pdf->Image('image/tompokersan1.jpg', 120, 210, 60, 30, 'JPG');
                // Output File PDF
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Keterangan Memiliki Usaha') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\nJl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(30, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang", 0, 'L');

                $pdf->SetXY(20, 90);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 95);
                $pdf->MultiCell(0, 6, "1.Nama                              : $user->nama_lengkap\n2.Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3.Jenis Kelamin                 : $user->jenis_kelamin\n4.Kebangasaan                   : $user->kewarganegaraan\n5.Agama                             : $user->agama\n6.Status Perkawinan          : $user->status_perkawinan\n7.Pekerjaan                        : $user->pekerjaan\n8.Nik                                  : $user->nik\n9.Alamat                            : $user->alamat", 0, 'L');

                $pdf->SetXY(20, 150);
                $pdf->MultiCell(0, 6, "                 Orang tersebut di atas benar - benar penduduk Kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang dan berdomisili di alamat tersebut di atas dan sesuai keterangan yang bersangkutan memiliki usaha yang masih aktif dikelola sampai saat ini dengan data sebagai berikut :", 0, 'L');
                $pdf->SetXY(20, 163);
                $pdf->MultiCell(0, 6, "
1.	Jenis Usaha		               : $user->jenis_usaha
2.	Bertempat di    	          : $user->tempat_usaha
3. Dikelola sejak tahun   : $user->tahun_kelola
", 0, 'L');
                $pdf->SetXY(20, 187);  // Position for "Lurah Tompokersan"
                $pdf->MultiCell(0, 6, "           Surat Keterangan ini dipergunakan sebagai persyaratan", 0, 'L');
                $pdf->SetFont('', 'B',);
                $pdf->MultiCell(0, 6, "                     $user->keterangan", 0, 'L');
                $pdf->SetFont('', '',);
                $pdf->SetXY(20, 200);
                $pdf->MultiCell(0, 6, "           Demikian surat keterangan dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                $pdf->SetXY(130, 215);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Image('image/ttdtompokersan.jpg', 120, 230, 80, 50, 'JPG');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Keterangan Beda Penulisan') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');
                // $pdf->SetFont('Arial','B',12);
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);

                // Add a multi-line cell with a left indentation of 20mm
                $pdf->MultiCell(
                    0,
                    6,
                    '
                P E M E R I N T A H   K A B U P A T E N  L U M A J A N G
                KECAMATAN LUMAJANG
                KELURAHAN TOMPOKERSAN
                Jl. Basuki Rahmat Telp. (0334) 881940 email:kel.tompokersan123@gmail.com
                LUMAJANG - 67311
        
                    ',
                    0,
                    'C',
                    false,
                    20
                );

                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);

                // Teks "SURAT KETERANGAN"
                $teksSurat = "SURAT KETERANGAN";
                $pdf->MultiCell(
                    0,
                    6,
                    $teksSurat,
                    0,
                    'C',
                    false,
                    20
                );

                // Hitung panjang teks "SURAT KETERANGAN"
                $panjangTeks = $pdf->GetStringWidth($teksSurat);

                // Hitung posisi awal X dan posisi akhir X garis horizontal
                $posisiTengahX = (273 - $panjangTeks) / 2; // 210 adalah lebar halaman standar A4, sesuaikan jika menggunakan ukuran halaman yang berbeda
                $posisiAwalX = $posisiTengahX - ($panjangTeks / 2); // Posisi awal garis
                $posisiAkhirX = $posisiTengahX + ($panjangTeks / 2); // Posisi akhir garis

                // Gambar garis horizontal dimulai dari posisi awal X hingga posisi akhir X
                $garisY = $pdf->GetY() + 2; // Atur posisi Y untuk garis horizontal
                $pdf->Line($posisiAwalX, $garisY, $posisiAkhirX, $garisY); // Gambar garis horizontal

                // MultiCell untuk menampilkan nomor surat di bawah garis horizontal
                $pdf->SetFont('Times', '', 12); // Atur font untuk nomor surat
                $pdf->SetXY(20, $garisY + 1); // Atur posisi X dan Y untuk nomor surat
                $pdf->MultiCell(
                    0,
                    6,
                    "Nomor Surat: $nomorKelurahan",
                    0,
                    'C',
                    false,
                    20
                );

                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, 84);
                $pdf->MultiCell(
                    0,
                    6,
                    '             Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa  : ',
                    0,
                    'L',
                    false,
                    20
                );

                $pdf->SetXY(8, 102);
                $pdf->MultiCell(
                    0,
                    6,
                    "            1.Nama                            : $user->nama_lengkap
            2.Tempat,Tgl Lahir         : $user->tempat_lahir ,$user->tgl_lahir
            3.Jenis Kelamin               : $user->jenis_kelamin
            4.Kebangsaan / Agama    : $user->kewarganegaraan , $user->agama
            5.Status 	                          : $user->status_perkawinan
            6.Pekerjaan 	                    : $user->pekerjaan
            7.NIK	                              : $user->nik
            8.Alamat 	                        : $user->alamat
                    ",
                    0,
                    'L',
                    false,
                    20
                );
                $pdf->setXY(21, 160);
                $pdf->MultiCell(
                    0,
                    6,
                    "Bahwa Tercata sebagau berikut \n - Kartu Keluarga (KK) NO. $user->no_kk                : $user->nama_lengkap \n - Kartu Tanda Penduduk (KTP) NIK.$user->nik : $user->nama_lengkap",
                    0,
                    'L',
                    false,
                    20
                );
                $pdf->Image('image/ttdtompokersan.jpg', 115, 215, 80, 50, 'JPG');
                $pdf->SetXY(21, 165);
                $pdf->MultiCell(
                    0,
                    6,
                    "  
        
Selanjutnya menurut keterangan yang bersangkutan, nama tersebut diatas menunjuk pada 1 (satu) orang yang sama. \n               Surat Keterangan ini digunakan untuk persyaratan : 

        
                                  ",
                    0,
                    'L',
                    false,
                    20
                );
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                // Tanda tangan Lurah
                $pdf->SetXY(125, 215);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->SetXY(20, 196);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('', 'B',);
                $pdf->MultiCell(0, 3, "                $user->keterangan", 0, 'L');
                $pdf->SetFont('', '',);
                $pdf->SetXY(20, 200);
                $pdf->MultiCell(0, 6, "                Demikian surat keterangan dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                $pdf->SetXY(35, 198);
                $pdf->MultiCell(
                    0,
                    6,
                    "  

        
        
        
        
        
                                                                                                        
                                                                                                        
        
                             ",
                    0,
                    'L',
                    false,
                    20
                );
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Keterangan Domisili Organisasi') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\nJl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(30, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang", 0, 'L');

                $pdf->SetXY(20, 90);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 100);
                $pdf->MultiCell(0, 6, " Nama Organisasi             : $user->organisasi \n Nama pimpinan               : $user->pimpinan \n Alamat                             : $user->alamat_organisasi", 0, "L");
                // Paragraf Penutup
                $pdf->SetXY(20, 120);
                $pdf->MultiCell(0, 6, "Menerangkan bahwa, sesuai keterangan Sdr. $user->pimpinan pada saat surat ini dibuat, organisasi tersebut benar-benar berdomisili sebagaimana alamat yang tercantum di atas. ", 0, 'L');
                $pdf->SetXY(20, 135);
                $pdf->MultiCell(0, 6, "Demikian Surat Keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                $pdf->Image('image/tompokersan1.jpg', 120, 225, 80, 40, 'JPG');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);
                // Output PDF
                $pdf->SetXY(130, 200);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Keterangan Kematian') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');
                // $pdf->SetFont('Arial','B',12);
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);

                // Add a multi-line cell with a left indentation of 20mm
                $pdf->MultiCell(
                    0,
                    6,
                    '
                    P E M E R I N T A H   K A B U P A T E N  L U M A J A N G
                    KECAMATAN LUMAJANG
                    KELURAHAN TOMPOKERSAN
                    Jl. Basuki Rahmat Telp. (0334) 881940 email:kel.tompokersan123@gmail.com
                    LUMAJANG - 67311
            
                        ',
                    0,
                    'C',
                    false,
                    20
                );

                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);

                // Teks "SURAT KETERANGAN"
                $teksSurat = "SURAT KETERANGAN";
                $pdf->MultiCell(
                    0,
                    6,
                    $teksSurat,
                    0,
                    'C',
                    false,
                    20
                );

                // Hitung panjang teks "SURAT KETERANGAN"
                $panjangTeks = $pdf->GetStringWidth($teksSurat);

                // Hitung posisi awal X dan posisi akhir X garis horizontal
                $posisiTengahX = (273 - $panjangTeks) / 2; // 210 adalah lebar halaman standar A4, sesuaikan jika menggunakan ukuran halaman yang berbeda
                $posisiAwalX = $posisiTengahX - ($panjangTeks / 2); // Posisi awal garis
                $posisiAkhirX = $posisiTengahX + ($panjangTeks / 2); // Posisi akhir garis

                // Gambar garis horizontal dimulai dari posisi awal X hingga posisi akhir X
                $garisY = $pdf->GetY() + 2; // Atur posisi Y untuk garis horizontal
                $pdf->Line($posisiAwalX, $garisY, $posisiAkhirX, $garisY); // Gambar garis horizontal

                // MultiCell untuk menampilkan nomor surat di bawah garis horizontal
                $pdf->SetFont('Times', '', 12); // Atur font untuk nomor surat
                $pdf->SetXY(20, $garisY + 1); // Atur posisi X dan Y untuk nomor surat
                $pdf->MultiCell(
                    0,
                    6,
                    "Nomor Surat: $nomorKelurahan",
                    0,
                    'C',
                    false,
                    20
                );

                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, 84);
                $pdf->MultiCell(
                    0,
                    6,
                    '             Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa  : ',
                    0,
                    'L',
                    false,
                    20
                );

                $pdf->SetXY(8, 96);
                $pdf->MultiCell(
                    0,
                    6,
                    "            1. Nama                            : $user->nama_lengkap
        2. Tempat,Tgl Lahir         : $user->tempat_lahir ,$user->tgl_lahir
        3. Jenis Kelamin               : $user->jenis_kelamin
        4. Kebangsaan / Agama    : $user->kewarganegaraan , $user->agama
        5. Status 	                          : $user->status_perkawinan
        6. Pekerjaan 	                    : $user->pekerjaan
        7. NIK	                              : $user->nik
        8. Alamat 	                        : $user->alamat
                        ",
                    0,
                    'L',
                    false,
                    20
                );
                $pdf->SetXY(21, 132);
                $pdf->MultiCell(
                    0,
                    6,
                    "  
            
Orang tersebut di atas telah meninggal dunia, dan data kematiannya adalah sebagai berikut :
            
                                      ",
                    0,
                    'L',
                    false,
                    20
                );
                $pdf->SetXY(20, 150);
                $pdf->MultiCell(
                    0,
                    6,
                    "1. Hari Meninggal              : $user->hari
2. Tgl/Tahun Meninggal    : $user->tgltahunmeninggal
3. Tempat Meninggal         : $user->tempat
4. Sebab Meninggal           : $user->sebab
5. Pada usia                        : $user->usia
                    ",
                    0,
                    'L',
                    false,
                    20
                );
                $pdf->SetXy(20, 183);
                $pdf->MultiCell(
                    0,
                    6,
                    "                                               Surat Keterangan ini dibuat berdasarkan laporan dari
1. Nama                                               : $user->nama_lengkap
2. Hubungan dengan yang meninggal : $user->hubunganalm",
                    0,
                    'L',
                    false,
                    20
                );
                $pdf->SetXY(20, 200);
                $pdf->MultiCell(
                    0,
                    6,
                    "Selanjutnya dipergunakan sebagai persyaratan : pengurusan akte kelahiran 
                Demikian surat keterangan ini dibuat unruk dipergunakan sebagai mestinya",
                    0,
                    'L',
                    false,
                    20
                );
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);
                $pdf->Image('image/tompokersan1.jpg', 110, 230, 80, 40, 'JPG');
                $pdf->SetXY(115, 220);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->MultiCell(
                    0,
                    6,
                    "  
            
                                                                                                            
                                                                                                            
        
            
                                 ",
                    0,
                    'L',
                    false,
                    20
                );
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nama_surat . '.pdf'), 'F');
            } else if ($surat == 'Surat Keterangan Lahir') {

                foreach ($data as $id_kk) {
                    $kepalaKeluarga = MobileMasterKksModel::join('master_masyarakats', 'master_masyarakats.id_kk', '=', 'master_kks.id_kk')
                        ->where('status_keluarga', 'Kepala Keluarga')
                        ->where('master_kks.id_kk', $id_kk->id_kk)
                        ->select(
                            'master_masyarakats.nama_lengkap'
                        )
                        ->first();

                    $Istri = MobileMasterKksModel::join('master_masyarakats', 'master_masyarakats.id_kk', '=', 'master_kks.id_kk')
                        ->where('status_keluarga', 'Istri')
                        ->where('master_kks.id_kk', $id_kk->id_kk)
                        ->select(
                            'master_masyarakats.nama_lengkap'
                        )
                        ->first();



                    $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                    $pdf->SetFont('Times', '', 12);
                    $pdf->SetXY(30, 24);
                    $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\nJl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');



                    $pdf->SetFont('Times', 'B', 14);
                    $pdf->SetXY(20, 66);

                    // Teks "SURAT KETERANGAN"
                    $teksSurat = "SURAT KETERANGAN";
                    $pdf->MultiCell(
                        0,
                        6,
                        $teksSurat,
                        0,
                        'C',
                        false,
                        20
                    );

                    // Hitung panjang teks "SURAT KETERANGAN"
                    $panjangTeks = $pdf->GetStringWidth($teksSurat);

                    // Hitung posisi awal X dan posisi akhir X garis horizontal
                    $posisiTengahX = (273 - $panjangTeks) / 2; // 210 adalah lebar halaman standar A4, sesuaikan jika menggunakan ukuran halaman yang berbeda
                    $posisiAwalX = $posisiTengahX - ($panjangTeks / 2); // Posisi awal garis
                    $posisiAkhirX = $posisiTengahX + ($panjangTeks / 2); // Posisi akhir garis

                    // Gambar garis horizontal dimulai dari posisi awal X hingga posisi akhir X
                    $garisY = $pdf->GetY() + 2; // Atur posisi Y untuk garis horizontal
                    $pdf->Line($posisiAwalX, $garisY, $posisiAkhirX, $garisY); // Gambar garis horizontal

                    // MultiCell untuk menampilkan nomor surat di bawah garis horizontal
                    $pdf->SetFont('Times', '', 12); // Atur font untuk nomor surat
                    $pdf->SetXY(20, $garisY + 1); // Atur posisi X dan Y untuk nomor surat
                    $pdf->MultiCell(
                        0,
                        6,
                        "Nomor Surat: $nomorKelurahan",
                        0,
                        'C',
                        false,
                        20
                    );

                    $pdf->SetFont('Times', '', 12);
                    $pdf->SetXY(20, 84);
                    $pdf->MultiCell(
                        0,
                        6,
                        '             Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa  : ',
                        0,
                        'L',
                        false,
                        20
                    );

                    $pdf->SetXY(8, 98);
                    $pdf->MultiCell(
                        0,
                        6,
                        "            1. Nama                            : $user->nama_lengkap
            2. Tempat,Tgl Lahir         : $user->tempat_lahir ,$user->tgl_lahir
            3. Jenis Kelamin               : $user->jenis_kelamin
            4. Kebangsaan / Agama    : $user->kewarganegaraan , $user->agama
            5. Status 	                          : $user->status_perkawinan
            6. Pekerjaan 	                    : $user->pekerjaan
            7. NIK	                              : $user->nik
            8. Alamat 	                        : $user->alamat
                                                    ",
                        0,
                        'L',
                        false,
                        20
                    );
                    $pdf->Image('image/tompokersan1.jpg', 110, 210, 80, 40, 'JPG');
                    $pdf->SetXY(21, 150);
                    $pdf->MultiCell(
                        0,
                        6,
                        " adalah anak dari : 
                                        
                                                  
                                        
                                                                  ",
                        0,
                        'L',
                        false,
                        20
                    );
                    $pdf->SetXY(8, 155);
                    $pdf->MultiCell(
                        0,
                        6,
                        "             Nama Ayah Kandung   : $kepalaKeluarga->nama_lengkap
             Nama Ibu Kandung      : $Istri->nama_lengkap
             Anak ke                        : $user->anak
                    
                                                    ",
                        0,
                        'L',
                        false,
                        20
                    );
                    $pdf->SetXY(20, 175);  // Position for "Lurah Tompokersan"
                    $pdf->MultiCell(0, 6, "           Surat Keterangan ini dipergunakan sebagai persyaratan", 0, 'L');
                    $pdf->SetFont('', 'B',);
                    $pdf->MultiCell(0, 6, "                     $user->keterangan", 0, 'L');
                    $pdf->SetFont('', '',);
                    $pdf->SetXY(20, 187);
                    $pdf->MultiCell(0, 6, "           Demikian surat keterangan dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                    $bulanIndonesia = [
                        'January' => 'Januari',
                        'February' => 'Februari',
                        'March' => 'Maret',
                        'April' => 'April',
                        'May' => 'Mei',
                        'June' => 'Juni',
                        'July' => 'Juli',
                        'August' => 'Agustus',
                        'September' => 'September',
                        'October' => 'Oktober',
                        'November' => 'November',
                        'December' => 'Desember'
                    ];
                    $tanggalInggris = date('d F Y');
                    $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);
                    $pdf->SetXY(110, 200);
                    $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                    $pdf->MultiCell(
                        0,
                        6,
                        "
                                        
             
                                                             ",
                        0,
                        'L',
                        false,
                        20
                    );
                    $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                    $updatestatus = new UpdateStatusModel();
                    $data = $updatestatus->where('id_pengajuan', $id)
                        ->update([
                            'nomor_surat' => $validated['nomor_surat'],
                            'kode_kecamatan' => $validated['kode_kecamatan'],
                            'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                            'status' => $status,
                            'info' => 'non_active',
                            'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                        ]);

                    $this->incrementNomorSurat();

                    return redirect('/suratmasuk')->with('successedit', '');
                }
            } else if ($surat == 'Surat Keterangan Yang Bersangkutan') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(20, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 100);
                $pdf->MultiCell(0, 6, "1. Nama                              : $user->nama_lengkap\n2. Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3. Jenis Kelamin                 : $user->jenis_kelamin\n4. Kebangasaan                   : $user->kewarganegaraan\n5. Agama                             : $user->agama\n6. Status Perkawinan          : $user->status_perkawinan\n7. Pekerjaan                        : $user->pekerjaan\n8. Nik                                  : $user->nik\n9. Alamat                            : $user->alamat", 0, 'L');

                // Paragraf Penutup
                $pdf->SetXY(20, 160);
                $pdf->MultiCell(0, 6, "Berdasarkan surat pengantar dari RT $user->rt RW $user->rw No.  RT$user->rt/RW$user->rw/$tahunSekarang dan menurut orang tersebut di atas saat ini benar-benar masuk dalam Data Terpadu Kesejahteraan Sosial (DTKS).", 0, 'L');

                $pdf->SetXY(20, 175);  // Position for "Lurah Tompokersan"
                $pdf->MultiCell(0, 6, "           Surat Keterangan ini dipergunakan sebagai persyaratan", 0, 'L');
                $pdf->SetFont('', 'B',);
                $pdf->MultiCell(0, 6, "                     $user->keterangan", 0, 'L');
                $pdf->SetFont('', '',);
                $pdf->SetXY(20, 187);
                $pdf->MultiCell(0, 6, "           Demikian surat keterangan dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                $pdf->Image('image/tompokersan1.jpg', 120, 225, 80, 40, 'JPG');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);
                $pdf->SetXY(130, 210);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Pengantar Kehilangan') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(20, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 97);
                $pdf->MultiCell(0, 6, "Nama                              : $user->nama_lengkap\nTempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\nJenis Kelamin                 : $user->jenis_kelamin\nKebangasaan                   : $user->kewarganegaraan\nAgama                             : $user->agama\nStatus Perkawinan          : $user->status_perkawinan\nPekerjaan                        : $user->pekerjaan\nNik                                  : $user->nik\nAlamat                            : $user->alamat", 0, 'L');

                // Paragraf Penutup
                $pdf->SetXY(20, 153);
                $pdf->MultiCell(0, 6, "                     Orang tersebut benar-benar penduduk Kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang dan berdomisili di alamat tersebut di atas, serta berdasarkan keterangan bersangkutan serta Surat Pengantar dari Ketua RT 02 '07 RW  Kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang Tanggal 09 September 2024 Nomor 42/RT02/RW07/2024, bahwa benar telah hilang dokumen sebagai berikut :", 0, 'L');

                $pdf->SetXY(20, 177);
                $pdf->MultiCell(0, 6, "
1. $user->barang_hilang
2. $user->barang_hilang2
3. $user->barang_hilang3", 0, 'L');
                $pdf->SetXY(20, 200);  // Position for "Lurah Tompokersan"
                $pdf->MultiCell(0, 6, "           Surat Keterangan ini dipergunakan sebagai persyaratan", 0, 'L');
                $pdf->SetFont('', 'B',);
                $pdf->MultiCell(0, 6, "                     $user->keterangan", 0, 'L');
                $pdf->SetFont('', '',);
                $pdf->SetXY(20, 212);
                $pdf->MultiCell(0, 6, "           Demikian surat keterangan dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');


                $pdf->Image('image/tompokersan1.jpg', 120, 225, 80, 40, 'JPG');
                // Existing Tanda Tangan for Lurah Tompokersan
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                $pdf->SetXY(130, 220);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Penutupan Jalan') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, 75);
                $pdf->MultiCell(0, 6, "Lumajang, 19 Agustus 2024", 0, 'L');

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, 80);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan
Sifat : Penting
Lampiran : -
Perihal : Pengajuan Penutupan Sebagian Jalan", 0, 'L');

                $pdf->SetXY(110, 75);
                $pdf->MultiCell(0, 6, "Nomor	:	145 / 915 / 427.90.06 / 2024
Kepada
Yth. 1. Kapolsek Kota Lumajang
    2. Kasatlantas Polres Lumajang 
    3. Dinas Perhubungan Kabupaten Lumajang di
L U M A J A N G	



", 0, 'L');

                $pdf->SetXY(20, 110);
                $pdf->MultiCell(0, 6, "                 Bersama ini perkenankanlah kami mengajukan permohonan ijin penutupan badan jalan sebagaimana diatur dalam UU No. 22 Tahun 2009 tentang Lalu Lintas dan Angkutan Jalan atas nama warga kami dengan data sebagai berikut:", 0, 'L');


                //data pribadi
                $pdf->SetXY(20, 130);
                $pdf->MultiCell(0, 6, "Nama                              : $user->nama_lengkap\nTempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\nJenis Kelamin                 : $user->jenis_kelamin\nKebangasaan                   : $user->kewarganegaraan\nAgama                             : $user->agama\nStatus Perkawinan          : $user->status_perkawinan\nPekerjaan                        : $user->pekerjaan\nNik                                  : $user->nik\nAlamat                            : $user->alamat\nLetak ruas jalan              : $user->ruas_jalan \nTanggal pelaksanaan      : $user->tglpelaksanaan\nLama penutupan             : $user->penutupan\nJam pelaksanaan             : $user->tglpelaksanaan\nAcara                              : $user->acara\nKeterangan                     : $user->keterangan\n                  Demikian untuk menjadikan maklum dan periksa.", 0, 'L');


                // Existing Tanda Tangan for Lurah Tompokersan
                $pdf->Image('image/tompokersan1.jpg', 115, 230, 80, 40, 'JPG');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                $pdf->SetXY(130, 220);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');

                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'SKCK OT') {
                $data = MobileMasterMasyarakatModel::where('id_masyarakat', '=', $user->id_masyarakat)
                    ->select('id_kk')
                    ->get();


                foreach ($data as $id_kk) {
                    $keluarga = MobileMasterKksModel::join('master_masyarakats', 'master_masyarakats.id_masyarakat', 'master_kks.id_kk')
                        ->where('status_keluarga', 'Kepala Keluarga')
                        ->where('master_kks.id_kk', $id_kk->id_kk)->get();

                    $roleRt = 2; // Role untuk RT
                    $roleRw = 3;

                    // Query untuk mendapatkan data RT
                    $dataRw = MobileMasterAkunModel::where('role', $roleRw)
                        ->join('master_masyarakats', 'master_akuns.id_masyarakat', '=', 'master_masyarakats.id_masyarakat')
                        ->join('master_kks', 'master_masyarakats.id_kk', '=', 'master_kks.id_kk')
                        ->where('master_kks.rw', $user->rw)
                        ->select(
                            'master_masyarakats.nama_lengkap',
                            'master_kks.rw'
                        )
                        ->first();

                    $dataRt = MobileMasterAkunModel::where('role', $roleRt)
                        ->join('master_masyarakats', 'master_akuns.id_masyarakat', '=', 'master_masyarakats.id_masyarakat')
                        ->join('master_kks', 'master_masyarakats.id_kk', '=', 'master_kks.id_kk')
                        ->where('master_kks.rt', $user->rt)
                        ->select(
                            'master_masyarakats.nama_lengkap',
                            'master_kks.rt'
                        )
                        ->first();

                    if ($user->rw == $dataRw->rw && $user->rt == $dataRt->rt) {

                        $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                        $pdf->SetFont('Times', '', 12);
                        $pdf->SetXY(30, 24);
                        $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                        $pdf->SetFont('Times', 'B', 14);
                        $pdf->SetXY(20, 66);
                        $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                        // Garis bawah judul
                        $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                        $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                        // Nomor Surat
                        $pdf->SetFont('Times', '', 12);
                        $pdf->SetXY(20, $pdf->GetY() + 5);
                        $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                        // Isi Surat
                        $pdf->SetXY(20, 84);
                        $pdf->SetFont('Times', '', 12);
                        $pdf->MultiCell(0, 6, "                  Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                        // Data Pribadi
                        $pdf->SetXY(20, 95);
                        $pdf->MultiCell(0, 6, "1. Nama                               : $user->nama_lengkap\n2. Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3. Jenis Kelamin                  : $user->jenis_kelamin\n4. Kebangasaan                   : $user->kewarganegaraan\n5. Agama                             : $user->agama\n6. Status Perkawinan          : $user->status_perkawinan\n7. Pekerjaan                        : $user->pekerjaan\n8. Nik                                  : $user->nik\n9. Alamat                            : $user->alamat", 0, 'L');

                        // Set font untuk membuat keterangan menjadi bold
                        $pdf->SetXY(20, 150);
                        $pdf->MultiCell(0, 6, "                 Orang tersebut di atas benar-benar penduduk Kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang dan berdomisili di alamat tersebut di atas serta sepengetahuan kami orang tersebut berkelakuan baik dan tidak pernah tersangkut kriminalitas atau organisasi terlarang. Surat keterangan ini digunakan untuk persyaratan menikah dengan anggota TNI.", 0, 'L');

                        // Set kembali font ke normal setelah keterangan
                        $pdf->SetX(20);
                        $pdf->SetFont('Times', '');
                        $pdf->MultiCell(0, 6, "                 Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                        // Tanda Tangan
                        $pdf->SetXY(55, 185);
                        $pdf->SetFont('Times', '', 12);
                        $pdf->MultiCell(0, 6, "Mengetahui,", 0, 'L');

                        // Tanda tangan Camat Lumajang
                        $pdf->SetXY(80, 230);
                        $pdf->MultiCell(50, 6, "CAMAT LUMAJANG \n\n\n\n___________________\n", 0, 'C');

                        $pdf->SetXY(20, 195);
                        $pdf->MultiCell(0, 6, "$dataRw->nama_lengkap                                                       (___________________)\nKetua RW $dataRw->rw Kel. Tompokersan", 0, 'L');


                        $pdf->SetXY(20, 210);
                        $pdf->MultiCell(0, 6, "$dataRt->nama_lengkap                              (___________________)\nKetua RT $dataRt->rt Kel. Tompokersan", 0, 'L');

                        // Tanda tangan Danmil
                        $pdf->SetXY(20, 230);
                        $pdf->MultiCell(50, 6, "DANRAMIL 0821/01\n\n\n\n___________________\n", 0, 'L');

                        // Tanda tangan Lurah
                        $bulanIndonesia = [
                            'January' => 'Januari',
                            'February' => 'Februari',
                            'March' => 'Maret',
                            'April' => 'April',
                            'May' => 'Mei',
                            'June' => 'Juni',
                            'July' => 'Juli',
                            'August' => 'Agustus',
                            'September' => 'September',
                            'October' => 'Oktober',
                            'November' => 'November',
                            'December' => 'Desember'
                        ];
                        $tanggalInggris = date('d F Y');
                        $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                        $pdf->SetXY(130, 230);
                        $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');

                        $pdf->Image('image/tompokersan1.jpg', 130, 240, 60, 30, 'JPG');
                        $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                        $updatestatus = new UpdateStatusModel();
                        $data = $updatestatus->where('id_pengajuan', $id)
                            ->update([
                                'nomor_surat' => $validated['nomor_surat'],
                                'kode_kecamatan' => $validated['kode_kecamatan'],
                                'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                                'status' => $status,
                                'info' => 'non_active',
                                'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                            ]);

                        $this->incrementNomorSurat();

                        return redirect('/suratmasuk')->with('successedit', '');
                    }
                }
            } else if ($surat == 'SKCK') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (210 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(20, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 100);
                $pdf->MultiCell(0, 6, "Nama                              : $user->nama_lengkap\nTempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\nJenis Kelamin                 : $user->jenis_kelamin\nKebangasaan                   : $user->kewarganegaraan\nAgama                             : $user->agama\nStatus Perkawinan          : $user->status_perkawinan\nPekerjaan                        : $user->pekerjaan\nNik                                  : $user->nik\nAlamat                            : $user->alamat", 0, 'L');

                // Paragraf Penutup
                $pdf->SetXY(20, 160);
                $pdf->MultiCell(0, 6, "                 Orang tersebut di atas benar-benar penduduk Kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang dan berdomisili di alamat tersebut di atas serta sepengetahuan kami orang tersebut berkelakuan baik. Surat keterangan ini diberikan untuk memenuhi salah satu persyaratan pembuatan Surat Keterangan Catatan Kepolisian (SKCK).", 0, 'L');

                $pdf->setXY(20, 185);
                $pdf->MultiCell(0, 6, "                 Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                // Existing Tanda Tangan for Lurah Tompokersan
                $pdf->Image('image/tompokersan1.jpg', 115, 230, 80, 40, 'JPG');
                // Tanda tangan Lurah
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                $pdf->SetXY(130, 220);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'SKTM (KIS, SAKIT & PERSALINAN)') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (210 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(20, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 100);
                $pdf->MultiCell(0, 6, "1. Nama                              : $user->nama_lengkap\n2. Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3. Jenis Kelamin                 : $user->jenis_kelamin\n4. Kebangasaan                   : $user->kewarganegaraan\n5. Agama                             : $user->agama\n6. Status Perkawinan          : $user->status_perkawinan\n7. Pekerjaan                        : $user->pekerjaan\n8. Nik                                  : $user->nik\n9. Alamat                            :$user->alamat", 0, 'L');

                // Paragraf Penutup
                $pdf->SetXY(20, 160);
                $pdf->MultiCell(0, 6, "Orang tersebut di atas benar-benar penduduk Kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang dan berdomisili di alamat tersebut di atas.
Berdasar Surat Pengantar RT $user->rt RW $user->rw Kelurahan Tompokersan Tanggal 30 Agustus 2024 Nomor RT$user->rt/RW$user->rw/$tahunSekarang dan menurut keterangan yang bersangkutan, termasuk kurang mampu dalam ekonomi.", 0, 'L');
                $pdf->SetXY(20, 190);
                $pdf->MultiCell(0, 6, "           Surat Keterangan ini dipergunakan sebagai persyaratan", 0, 'L');
                $pdf->SetFont('', 'B',);
                $pdf->MultiCell(0, 6, "                     $user->keterangan", 0, 'L');
                $pdf->SetFont('', '',);
                $pdf->SetXY(20, 203);
                $pdf->MultiCell(0, 6, "           Demikian surat keterangan dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');

                // Existing Tanda Tangan for Lurah Tompokersan
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                $pdf->SetXY(130, 220);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->Image('image/tompokersan1.jpg', 115, 230, 80, 40, 'JPG');
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'SKTM (SEKOLAH & BIDIK MISI)') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (210 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(20, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 95);
                $pdf->MultiCell(0, 6, "1.Nama                              : $user->nama_lengkap\n2.Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3.Jenis Kelamin                 : $user->jenis_kelamin\n4.Kebangasaan                   : $user->kewarganegaraan\n5.Agama                             : $user->agama\n6.Status Perkawinan          : $user->status_perkawinan\n7.Pekerjaan                        : $user->pekerjaan\n8.Nik                                  : $user->nik\n9.Alamat                            :$user->alamat", 0, 'L');

                // Paragraf Penutup
                $pdf->SetXY(20, 150);
                $pdf->MultiCell(0, 6, "                 Orang tersebut di atas benar-benar penduduk RT $user->rt RW $user->rw Kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang. 
Berdasar Surat Pengantar RT $user->rt RW $user->rw Kelurahan Tompokersan Tanggal $tahunSekarang Nomor 470/109/RT $user->rt RW $user->rw/2025 dan menurut keterangan yang bersangkutan, termasuk kurang mampu dalam ekonomi dengan penghasilan per bulan kurang lebih Rp. 1.700.000,00.
Surat keterangan ini diberikan untuk mengajukan persyaratan Program Indonesia Pintar anaknya yaitu:
", 0, 'L');

                $pdf->SetXY(20, 185);
                $pdf->MultiCell(0, 6, "1.Nama                              : $user->nama_lengkap\n2.Tempat/Tanggal Lahir    : $user->tempat $user->tempattgllahir\n3.Agama                             : $user->agama\n4.Alamat                            : $user->alamat\n5.Pendidikan saat ini         : $user->agama\n                  Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');

                // Existing Tanda Tangan for Lurah Tompokersan
                $pdf->Image('image/tompokersan1.jpg', 115, 230, 80, 40, 'JPG');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                $pdf->SetXY(130, 220);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'SKTM RSUD') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\n     Jl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(20, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Saya yang bertandatangan di bawah ini :
Nama	           : JOKO SETIYO,S.Kom.MM
Alamat	         : Kantor Lurah Tompokersan Jl. Basuki Rahmat No.10 Lumajang
Jabatan			       : Lurah Tompokersan
", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 110);
                $pdf->MultiCell(0, 6, "Menyatakan dengan sesungguhnya bahwa :\nNama                              : $user->nama_lengkap\nAlamat                            : $user->alamat", 0, 'L');

                // Paragraf Penutup
                $pdf->SetXY(20, 130);
                $pdf->MultiCell(0, 6, "Adalah benar masyarakat miskin dengan kondisi sebagai berikut :
1.	Pekerjaan sebagai -
2.	Pekerjaan bukan musiman
3.	Membutuhkan pelayanan kesehatan disebabkan sakit 
Demikian surat keterangan ini dibuat dengan sebenarnya untuk mendapatkan pembiayaan pelayanan kesehatan bagi masyarakat miskin.
", 0, 'L');

                $pdf->SetXY(20, 220);  // Adjust position for "Kepala KUA Kecamatan Lumajang"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Mengetahui,\nCAMAT LUMAJANG\n\n\n\n\n..............................................", 0, 'L');

                // Existing Tanda Tangan for Lurah Tompokersan
                $pdf->Image('image/tompokersan1.jpg', 115, 230, 80, 40, 'JPG');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);

                $pdf->SetXY(130, 220);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Keterangan Bepergian') {
                $pdf->Image('image/logohp.png', 18, 20, 20, 0, 'PNG'); // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 20);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\nJl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');

                // Judul Surat
                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 50);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN JALAN / BEPERGIAN", 0, 'C');

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, 58);
                $pdf->MultiCell(0, 6, "Nomor: $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(20, 70);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa:", 0, 'L');

                // Data Pribadi
                $pdf->SetXY(20, 83);
                $pdf->MultiCell(0, 6, "1.Nama                              : $user->nama_lengkap\n2.Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3.Jenis Kelamin                 : $user->jenis_kelamin\n4.Kebangasaan                   : $user->kewarganegaraan\n5.Agama                             : $user->agama\n6.Status Perkawinan          : $user->status_perkawinan\n7.Pekerjaan                        : $user->pekerjaan\n8.Nik                                  : $user->nik\n9.Alamat                            :$user->alamat", 0, 'L');

                // Paragraf Keterangan
                $pdf->SetXY(20, 140);
                $pdf->MultiCell(
                    0,
                    6,
                    "Orang tersebut adalah benar warga kami, dan mengajukan izin bepergian ke Malaysia dengan maksud untuk berwisata dan berkunjung selama 14 (empat belas) hari. Adapun pengikut yang bersangkutan sebagai berikut:",
                    0,
                    'L'
                );

                // Tabel Pengikut
                $pdf->SetXY(10, 160);
                $pdf->SetFont('Times', '', 10);
                $pdf->Cell(10, 7, 'NO', 1, 0, 'C');
                $pdf->Cell(60, 7, 'NAMA', 1, 0, 'C');
                $pdf->Cell(20, 7, 'UMUR', 1, 0, 'C');
                $pdf->Cell(50, 7, 'HUBUNGAN KELUARGA', 1, 0, 'C');
                $pdf->Cell(50, 7, 'KETERANGAN', 1, 1, 'C');

                // Baris Kosong untuk Pengikut
                for ($i = 1; $i <= 5; $i++) {
                    $pdf->Cell(10, 7, $i, 1, 0, 'C');
                    $pdf->Cell(60, 7, '', 1, 0, 'C');
                    $pdf->Cell(20, 7, '', 1, 0, 'C');
                    $pdf->Cell(50, 7, '', 1, 0, 'C');
                    $pdf->Cell(50, 7, '', 1, 1, 'C');
                }

                // Penutup
                $pdf->SetXY(20, 205);
                $pdf->MultiCell(
                    0,
                    6,
                    "Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.",
                    0,
                    'L'
                );
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);
                $pdf->SetXY(120, 210);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                // Tanda Tangan
                $pdf->Image('image/tompokersan1.jpg', 115, 230, 80, 40, 'JPG');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            } else if ($surat == 'Surat Transaksi Harga Tanah') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\nJl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(30, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang,", 0, 'L');

                $pdf->SetXY(20, 90);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "menerangkan dengan sebenarnya bahwa sebidang tanah dengan bukti hak kepemilikan\nberupa : $user->kepemilikan    ", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 103);
                $pdf->MultiCell(0, 6, "Nomor/tanggal                      : $user->nomor, $user->tglpelaksanaan\nAtas nama                             : $user->nama_lengkap\nAlamat                                  : $user->alamat\nPekerjaan                              : $user->pekerjaan\nLetak Persil                           : $user->letakpersil\nLuas                                      : $user->luas m2\nDengan batas-batas sebagai berikut: ", 0, 'L');

                $pdf->SetXY(20, 145);
                $pdf->MultiCell(0, 6, "-	Sebelah Utara	:	$user->utara
-	Sebelah Selatan	:	$user->selatan
SPPT PBB Tahun $tahunSekarang Nomor 35.08.060.018.005-0069.0
Taksasi harga per m2 menurut kondisi pasar saat ini :
1.	Tanah Sebesar		:  RP. $user->besartnh
2.	Bangunan Sebesar	:  RP. $user->besarbgn
Surat Keterangan ini dipergunakan untuk : $user->keterangan
", 0, 'L');
                $pdf->SetXY(110, 145);
                $pdf->MultiCell(0, 6, "-	Sebelah Timur	:	$user->timur
-	Sebelah Barat	:	$user->barat", 0, 'L');
                $pdf->SetXY(20, 188);
                $pdf->MultiCell(0, 6, "Demikian surat keterangan dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                $pdf->SetXY(130, 220);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('Times', '', 12);
                $pdf->Image('image/tompokersan1.jpg', 115, 230, 80, 40, 'JPG');
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
                // Output PDF
            } else if ($surat == 'Surat Keterangan Pindah Nikah') {
                $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');  // Logo
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(30, 24);
                $pdf->MultiCell(0, 6, "P E M E R I N T A H K A B U P A T E N L U M A J A N G\nKECAMATAN LUMAJANG\nKELURAHAN TOMPOKERSAN\nJl. Basuki Rahmat Telp. (0334) 881940 email: kel.tompokersan123@gmail.com\nLUMAJANG - 67311", 0, 'C');


                $pdf->SetFont('Times', 'B', 14);
                $pdf->SetXY(20, 66);
                $pdf->MultiCell(0, 6, "SURAT KETERANGAN", 0, 'C');

                // Garis bawah judul
                $judul_width = $pdf->GetStringWidth("SURAT KETERANGAN");
                $pdf->Line((210 - $judul_width) / 2, $pdf->GetY() + 2, (230 + $judul_width) / 2, $pdf->GetY() + 2);

                // Nomor Surat
                $pdf->SetFont('Times', '', 12);
                $pdf->SetXY(20, $pdf->GetY() + 5);
                $pdf->MultiCell(0, 6, "Nomor : $nomorKelurahan", 0, 'C');

                // Isi Surat
                $pdf->SetXY(30, 84);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang", 0, 'L');

                $pdf->SetXY(20, 90);
                $pdf->SetFont('Times', '', 12);
                $pdf->MultiCell(0, 6, "Kabupaten Lumajang menerangkan dengan sebenarnya bahwa :", 0, 'L');
                // Data Pribadi
                $pdf->SetXY(20, 95);
                $pdf->MultiCell(0, 6, "1.Nama                              : $user->nama_lengkap\n2.Tempat/Tanggal Lahir    : $user->tempat_lahir $user->tgl_lahir\n3.Jenis Kelamin                 : $user->jenis_kelamin\n4.Kebangasaan                   : $user->kewarganegaraan\n5.Agama                             : $user->agama\n6.Status Perkawinan          : $user->status_perkawinan\n7.Pekerjaan                        : $user->pekerjaan\n8.Nik                                  : $user->nik\n9.Alamat                            : $user->alamat", 0, 'L');

                $pdf->SetXY(20, 150);
                $pdf->MultiCell(0, 6, "                 Orang tersebut di atas benar - benar penduduk Kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang dan berdomisili di alamat tersebut di atas,\nSurat Keterangan ini dipergunakan untuk persyaratan Pindah Nikah dengan seorang perempuan, yaitu :", 0, 'L');
                $pdf->SetXY(20, 163);
                $pdf->MultiCell(0, 6, "
1.	Nama		           : $user->nama
2.  Alamat          : $user->alamatrmh
", 0, 'L');
                $pdf->SetXY(20, 180);  // Position for "Lurah Tompokersan"
                $pdf->MultiCell(0, 6, "           Adapun pernikahan akan dilaksakan pada :", 0, 'L');
                $pdf->SetXY(20, 180);
                $pdf->MultiCell(0, 6, "
Hari		                 : $user->hari
Tanggal             : $user->tglpelaksanaan
Tempat              : $user->tempat
", 0, 'L');
                $pdf->SetXY(20, 203);
                $pdf->MultiCell(0, 6, "           Demikian surat keterangan dibuat untuk dipergunakan sebagaimana mestinya.", 0, 'L');
                $pdf->SetXY(130, 210);  // Position for "Lurah Tompokersan"
                $pdf->SetFont('', '', 12);
                $bulanIndonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggalInggris = date('d F Y');
                $tanggalIndonesia = strtr($tanggalInggris, $bulanIndonesia);
                $pdf->MultiCell(0, 6, "Lumajang, " . $tanggalIndonesia . "\nLURAH TOMPOKERSAN", 0, 'C');
                $pdf->Image('image/tompokersan1.jpg', 120, 223, 80, 40, 'JPG');
                // Output PDF
                $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
                $updatestatus = new UpdateStatusModel();
                $data = $updatestatus->where('id_pengajuan', $id)
                    ->update([
                        'nomor_surat' => $validated['nomor_surat'],
                        'kode_kecamatan' => $validated['kode_kecamatan'],
                        'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                        'status' => $status,
                        'info' => 'non_active',
                        'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                    ]);

                $this->incrementNomorSurat();

                return redirect('/suratmasuk')->with('successedit', '');
            }
            $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
            $updatestatus = new UpdateStatusModel();
            $data = $updatestatus->where('id_pengajuan', $id)
                ->update([
                    'nomor_surat' => $validated['nomor_surat'],
                    'kode_kecamatan' => $validated['kode_kecamatan'],
                    'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
                    'status' => $status,
                    'info' => 'non_active',
                    'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
                ]);

            $this->incrementNomorSurat();

            return redirect('/suratmasuk')->with('successedit', '');
        }
    }

    // public function update_status(PengajuanRequest $request, $id, $akses)
    // {
    //     $status = 'Selesai';
    //     $validated = $request->validated();
    //     $pdf = new FPDF();
    //     $pdf->AddPage();
    //     $pengajuan = new PengajuanModel;
    //     $data = $pengajuan->pengajuan()
    //         ->where('pengajuan_surats.id_pengajuan', $id)->get();
    //     foreach ($data as $user) {
    //         $kodeKecamatan = $validated['kode_kecamatan'];
    //         $nomorSuratTambahan = $validated['nomor_surat_tambahan'];
    //         $tahunSekarang = date('Y');
    //         $nomorKelurahan = $request->nomor_surat . '/' . $nomorSuratTambahan . '/' . $kodeKecamatan . '/' . $tahunSekarang;
    //         $pdf->Image('image/logohp.png', 18, 27, 43, 0, 'PNG');
    //         $pdf->SetFont('Times', '', 12);
    //         $pdf->SetXY(30, 24);

    //         // Add a multi-line cell with a left indentation of 20mm
    //         $pdf->MultiCell(
    //             0,
    //             6,
    //             '
    //     P E M E R I N T A H   K A B U P A T E N  L U M A J A N G
    //     KECAMATAN LUMAJANG
    //     KELURAHAN TOMPOKERSAN
    //     Jl. Basuki Rahmat Telp. (0334) 881940 email:kel.tompokersan123@gmail.com
    //     LUMAJANG - 67311

    //         ',
    //             0,
    //             'C',
    //             false,
    //             20
    //         );

    //         $pdf->SetFont('Times', 'B', 14);
    //         $pdf->SetXY(20, 66);

    //         // Teks "SURAT KETERANGAN"
    //         $teksSurat = "SURAT KETERANGAN";
    //         $pdf->MultiCell(
    //             0,
    //             6,
    //             $teksSurat,
    //             0,
    //             'C',
    //             false,
    //             20
    //         );

    //         // Hitung panjang teks "SURAT KETERANGAN"
    //         $panjangTeks = $pdf->GetStringWidth($teksSurat);

    //         // Hitung posisi awal X dan posisi akhir X garis horizontal
    //         $posisiTengahX = (273 - $panjangTeks) / 2; // 210 adalah lebar halaman standar A4, sesuaikan jika menggunakan ukuran halaman yang berbeda
    //         $posisiAwalX = $posisiTengahX - ($panjangTeks / 2); // Posisi awal garis
    //         $posisiAkhirX = $posisiTengahX + ($panjangTeks / 2); // Posisi akhir garis

    //         // Gambar garis horizontal dimulai dari posisi awal X hingga posisi akhir X
    //         $garisY = $pdf->GetY() + 2; // Atur posisi Y untuk garis horizontal
    //         $pdf->Line($posisiAwalX, $garisY, $posisiAkhirX, $garisY); // Gambar garis horizontal

    //         // MultiCell untuk menampilkan nomor surat di bawah garis horizontal
    //         $pdf->SetFont('Times', '', 12); // Atur font untuk nomor surat
    //         $pdf->SetXY(20, $garisY + 1); // Atur posisi X dan Y untuk nomor surat
    //         $pdf->MultiCell(
    //             0,
    //             6,
    //             "Nomor Surat: $nomorKelurahan",
    //             0,
    //             'C',
    //             false,
    //             20
    //         );

    //         $pdf->SetFont('Times', '', 12);
    //         $pdf->SetXY(20, 84);
    //         $pdf->MultiCell(
    //             0,
    //             6,
    //             '             Bertandatangan di bawah ini untuk dan atas nama Lurah Tompokersan Kecamatan Lumajang Kabupaten Lumajang menerangkan dengan sebenarnya bahwa : ',
    //             0,
    //             'L',
    //             false,
    //             20
    //         );

    //         $pdf->SetXY(8, 102);
    //         $pdf->MultiCell(
    //             0,
    //             6,
    //             "            Nama                            : $user->nama_lengkap
    //         Tempat,Tgl Lahir         : $user->tempat_lahir ,$user->tgl_lahir
    //         Jenis Kelamin               : $user->jenis_kelamin
    //         Kebangsaan / Agama    : $user->kewarganegaraan , $user->agama
    //         Status 	                          : $user->status_perkawinan
    //         Pekerjaan 	                    : $user->pekerjaan
    //         NIK	                              : $user->nik
    //         Alamat 	                        : $user->alamat
    //         ",
    //             0,
    //             'L',
    //             false,
    //             20
    //         );
    //         $pdf->Image('image/tompokersan.png', 145, 222, 30, 30, 'PNG');
    //         $pdf->SetXY(21, 150);
    //         $pdf->MultiCell(
    //             0,
    //             6,
    //             "  

    //         Orang tersebut di atas benar-benar penduduk kelurahan Tompokersan Kecamatan Lumajang Kabupaten Lumajang dan domisili di alamat tersebut di atas serta sepengetahuan kami orang tersebut berkelakuan baik. Surat keterangan ini diberikan untuk memenuhi salah satu persyaratan pembuatan $user->nama_surat

    //                     Demikian surat keterangan ini kami buat untuk dapat dipergunakan sebagaimana mestinnya.

    //                       ",
    //             0,
    //             'L',
    //             false,
    //             20
    //         );
    //         $pdf->SetXY(35, 198);
    //         $pdf->MultiCell(
    //             0,
    //             6,
    //             "  

    //                                                                                             Lumajang, " . date('d-m-Y', strtotime($user->created_at)) . "
    //                                                                                             LURAH TOMPOKERSAN





    //                                                                                             JOKO SETIYO,S.Kom.MM.
    //                                                                                             NIP. 19830607 201101 1 010

    //                  ",
    //             0,
    //             'L',
    //             false,
    //             20
    //         );
    //         $pdf->Output(public_path('pdf/' . $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'), 'F');
    //         $updatestatus = new UpdateStatusModel();
    //         $data = $updatestatus->where('id_pengajuan', $id)
    //             ->update([
    //                 'nomor_surat' => $validated['nomor_surat'],
    //                 'kode_kecamatan' => $validated['kode_kecamatan'],
    //                 'nomor_surat_tambahan' => $validated['nomor_surat_tambahan'],
    //                 'status' => $status,
    //                 'info' => 'non_active',
    //                 'file_pdf' => $user->nama_lengkap . '_' . $user->nik . '_' . $user->nama_surat . '_' . $id . '.pdf'
    //             ]);

    //         $this->incrementNomorSurat();

    //         return redirect('/suratmasuk')->with('successedit', '');
    //     }
    // }

    public function status_setuju(Request $request, $id_pengajuan)
    {
        $noPengantar = $request->input('no_pengantar');

        // Ambil data pengajuan
        $pengajuan = PengajuanModel::find($id_pengajuan);

        if (!$pengajuan) {
            return response()->json(['success' => false, 'message' => 'Pengajuan tidak ditemukan']);
        }

        // Generate PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Pengantar Surat');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'No Pengantar: ' . $noPengantar);
        $pdf->Ln();
        $pdf->Cell(0, 10, 'Detail Pengajuan:');
        $pdf->Ln();
        $pdf->Cell(0, 10, 'Nama: ' . $pengajuan->nama);
        // Tambahkan detail lainnya sesuai kebutuhan

        $filePath = 'pdfs/' . $id_pengajuan . '_rt.pdf';
        $pdf->Output(storage_path('app/public/' . $filePath), 'F');

        // Update kolom di database dengan path file PDF
        $pengajuan->pengantar_rt = $filePath;
        $pengajuan->save();

        return response()->json(['success' => true]);
    }

    public function showDetailSurat($id_pengajuan)
    {
        // Mengambil data pengajuan yang sesuai dengan id_pengajuan
        $item = PengajuanModel::where('id_pengajuan', $id_pengajuan)->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Mengirimkan data ke view
        return view('detail_surat', compact('item'));
    }

    public function surat_selesai()
    {
        if (session('hak_akses') == 'admin') {
            $pengajuan = new PengajuanModel();
            $data = $pengajuan->pengajuan()
                ->where('pengajuan_surats.status', '=', 'Selesai')
                ->get();
        }
        return view('surat_selesai', compact('data'));
    }

    // YourController.php

}
