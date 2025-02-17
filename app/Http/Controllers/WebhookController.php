<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        // Mengambil token dari database
        $token = DB::table('landings')->where('id', 1)->value('token');
        if (!$token) {
            return response()->json(['error' => 'No token found'], 404);
        }

        // Memeriksa input JSON
        $data = $request->json()->all();
        if (empty($data)) {
            return response()->json(['error' => 'No JSON input'], 400);
        }

        // Debugging output to see received data
        file_put_contents('debug.txt', print_r($data, true));

        $device = $data['device'] ?? null;
        $sender = $data['sender'] ?? null;
        $message = $data['message'] ?? null;

        // Menyiapkan balasan
        if ($message === "1") {
            $reply = [
                "message" => "📄 *Jenis Surat*\n\n" .
                             "🔹 *1.1.1 CETAK KK BARU*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. KK asli\n" .
                             "   3. FC KTP\n" .
                             "   4. FC Surat Nikah\n" .
                             "   5. FC Akta Cerai\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.2 CETAK KK HILANG / RUSAK*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. Surat Kehilangan\n" .
                             "   3. FC KTP\n" .
                             "   4. FC KK\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.3 CETAK AKTA KELAHIRAN*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. KK asli\n" .
                             "   3. FC Surat Nikah\n" .
                             "   4. Surat Bidan asli\n" .
                             "   5. Surat Lahir dari Kelurahan\n" .
                             "   6. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.4 CETAK AKTA KEMATIAN*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   4. Surat Kematian dari Kelurahan\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.5 SURAT KEMATIAN*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.6 SURAT KETERANGAN USAHA*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.7 SKCK*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   4. FC Akte Kelahiran\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.8 SURAT KETERANGAN BELUM MENIKAH*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.9 SURAT KETERANGAN PENGAJUAN KIS*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.10 SURAT KETERANGAN DOMISILI*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "    3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.11 SURAT KETERANGAN KENAL LAHIR*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   4. FC Surat Nikah\n" .
                             "   5. Surat Bidan Asli\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.12 SURAT KETERANGAN TIDAK MAMPU*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.13 SURAT KETERANGAN DOMISILI PT / CV*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   4. FC Surat Ijin dari yang Berwenang (Notaris/DPM PTSP)\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.14 SURAT KEPEMILIKAN KENDARAAN*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   4. FC STNK dan BPKB\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.15 SURAT KETERANGAN HUBUNGAN KELUARGA*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   4. FC Akte Kelahiran\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.16 SURAT KETERANGAN TIDAK MEMILIKI RUMAH*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.17 SURAT KETERANGAN TIDAK PERNAH DIHUKUM*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.18 SURAT PINDAH*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.19 SURAT KERINGANAN SEKOLAH DAN PENGHASILAN*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.20 SURAT PENGANTAR TUTUP JALAN*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.21 SURAT KETERANGAN LAIN-LAIN*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.22 SURAT TRANSAKSI HARGA TANAH*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   4. FC Sertifikat / Akta Jual Beli\n" .
                             "   5. FC SPPT PBB\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.23 SURAT KETERANGAN BERPERGIAN*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.24 SURAT KETERANGAN STATUS*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   4. FC Surat Kematian\n" .
                             "   5. FC Akta Cerai\n" .
                             "   \n" .
                             "----------------------------------\n\n" .
                             
                             "🔹 *1.1.25 PENGURUSAN SPPT PBB*\n" .
                             "   📝 *Dokumen yang diperlukan:*\n" .
                             "   1. Pengantar RT/RW\n" .
                             "   2. FC KK\n" .
                             "   3. FC KTP\n" .
                             "   4. FC Surat Tanah\n" .
                             "   5. FC SPPT PBB\n" .
                             "   \n" .
                             "*🎉 GRATISSS!!!*"
            ];
        } elseif ($message === "2") {
            $reply = [
                "message" => "📑 *Pengaduan Layanan* 📑\n\nJika Anda mengalami masalah, silakan gunakan link berikut untuk melaporkannya:\n\n👉 [https://linktr.ee/tompokersan]\n\nTerima kasih telah menggunakan layanan kami! 😊"
            ];
        } else {
            $reply = [
                "message" => "👋 *Selamat datang di Layanan Tompokersan!*\n\n" .
                             "Pilih menu yang Anda butuhkan dengan membalas angka berikut:\n" .
                             "1️⃣ *Layanan Surat*\n" .
                             "2️⃣ *Pengaduan*"
            ];
        }

        $this->sendFonnte($sender, $reply, $token);

        return response()->json(['status' => 'success']);
    }

    private function sendFonnte($target, $data, $token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.fonnte.com/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query(array(
                'target' => $target,
                'message' => $data['message'],
                'url' => $data['url'] ?? '',
                'filename' => $data['filename'] ?? '',
                'buttons' => $data['buttons'] ?? ''
            )),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token",
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            error_log('Curl Error: ' . curl_error($curl));
        } else {
            error_log('Response: ' . $response);
        }

        curl_close($curl);

        return $response;
    }
}
