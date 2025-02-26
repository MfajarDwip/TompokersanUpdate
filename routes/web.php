<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\KartukkController;
use App\Http\Controllers\KepuharjoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasyarakatController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\RtController;
use App\Http\Controllers\RwController;
use App\Http\Controllers\StatusPdfController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/export', [ExcelController::class, 'export']);

// Route::controller(KepuharjoController::class)->group(function () {
//     Route::get('/', 'index')->name('index');
// });

Route::get('/', [VisitorController::class, 'landingpage']);

// Route Login
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('login/auth', 'store');
});

// Route Logout
Route::controller(KepuharjoController::class)->group(function () {
    Route::get('logout', 'logout')->name('logout');
    // Route::post('login/auth', 'store');
});

Route::middleware('auth.auth')->group(function () {

    Route::controller(VisitorController::class)->group(function () {
        Route::get('/tentang', 'tentang')->name('tentang');
        Route::post('/tentang/update/{id}', 'update');
    });

    Route::controller(KepuharjoController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard')->middleware('auth.auth');
        Route::post('image-upload', 'updateprofil');
        Route::get('/logout', 'logout')->name('logout');
    });

    // Route User Akun
    Route::controller(UserController::class)->group(function () {
        Route::get('/masteruser', 'masteruser')->name('masteruser');
        Route::post('/masteruser/{id}', 'update');
        Route::get('masteruser/{id}/hapus', [UserController::class, 'destroy']);
    });

    // Route Rt
    Route::controller(RtController::class)->group(function () {
        Route::get('/masterrt/{id}', 'master_rt')->name('masterrt');
        Route::get('hapus-masterrt/{nik}', 'hapusMasterRt');
        Route::get('/ajaxrt', 'ajax_rt');
        Route::get('/readrt', 'read');
        Route::post('/simpanrt/{id}', 'simpanmasterrt');
        Route::post('update-masterrt/{id}', [RtController::class, 'updatemasterrt'])->name('update-masterrt');
    });

    // Router RW
    Route::controller(RwController::class)->group(function () {
        Route::get('/masterrw', 'master_rw')->name('masterrw');
        Route::get('/hapus-masterrw/{id}', 'hapusmasterrw');
        Route::get('/ajax', 'ajax');
        Route::get('/read', 'read');
        Route::post('/simpanrw/{id}', 'simpanmasterrw');
        Route::post('update-masterw/{id}', 'updatemasterrw');
        Route::get('/rw', 'Rw');
    });

    // Route Pengajuan Surat
    Route::controller(PengajuanController::class)->group(function () {
        Route::get('/suratmasuk', 'surat_masuk')->name('suratmasuk');
        Route::post('updatestatus/{id}/{akses}', 'update_status');
        Route::get('updatestatustolak/{id}/{akses}', 'update_statustolak');
        Route::get('/suratditolak', 'surat_ditolak')->name('suratditolak');
        Route::get('/suratselesai', 'surat_selesai')->name('suratselesai');
        Route::post('/save-surat', 'store')->name('saveSurat');
        Route::get('/generate-nomor-surat', 'generateNomorSurat');
        Route::get('/edit-surat/{id}', [PengajuanController::class, 'editSurat'])->name('edit.surat');
        Route::post('/update-surat/{id}', [PengajuanController::class, 'updateSurat'])->name('update.surat');

        // web.php
        Route::get('downloadpdf/{id}', [PengajuanController::class, 'downloadPdf'])->name('download.pdf');
        Route::get('/surat/{id_pengajuan}', [PengajuanController::class, 'showDetailSurat']);
    });

    // Route Masyarakat
    Route::controller(MasyarakatController::class)->group(function () {
        Route::get('/masterkkmas/{id}', 'master_kk_mas');
        Route::get('simpanakuns/{id}', 'simpanmasteruserakun');
        Route::get('{id}/hapus-masteruser', 'hapusmasteruser');
        Route::post('/simpanuser', 'simpanmasteruser');
        Route::post('/simpanuserakuns/{id}', 'simpanmasteruserakun');
        Route::post('update-masteruser/{id}', 'updatemasteruser');
        Route::post('/importmas', 'import')->name('importmas');
    });

    // Route Master KK
    Route::controller(KartukkController::class)->group(function () {
        Route::get('/masterkk', 'index')->name('masterkk');
        Route::get('/simpankepala/{id}/{other_id}/{nik}', 'simpankepalakeluarga');
        Route::delete('/masterkk/{no_kk}', 'destroy')->name('hapuskk');
        Route::get('masteruser/{id}/hapus', [UserController::class, 'destroy']);
        Route::get('simpanakunskk/{id}', 'simpanmasteruserakunkk');
        Route::post('/simpankk', 'simpanmasterkk')->name('simpankk');
        Route::post('update-masterkk/{id}', 'update');
        Route::post('/importkk', 'import')->name('importkk');
    });

    // Route Master Berita
    Route::controller(BeritaController::class)->group(function () {
        Route::get('/berita', 'index')->name('berita');
        Route::get('hapus-berita/{id}', 'delete');
        Route::post('update-berita/{id}', 'update');
        Route::post('/simpanberita', 'store')->name('simpanberita');
    });

    // Route Master Surat
    Route::controller(SuratController::class)->group(function () {
        Route::get('/mastersurat', 'index');
        Route::get('hapussurat/{id}', 'delete');
        Route::post('/simpansurat', 'store')->name('simpansurat');
        Route::post('/editsurat/{id}', 'update');
        Route::patch('updateimage/{id}', 'updateimage');
    });
    Route::put('/editpengajuan/{id}', [SuratController::class, 'update'])->name('editpengajuan');
    Route::get('generate-pdf/{id}', [PdfController::class, 'generatePDF']);
    // Route::get('generate-pdf/{id}', [PdfController::class, 'PDFKematian']);
    // Route::get('generate-pdf/{id}', [PdfController::class, 'PDFKenalLahir']);
    Route::get('pdfstatus/{id}', [StatusPdfController::class, 'generatePDF']);
    // routes/web.php
    // Route::get('/pdfs/{filename}', [PdfController::class, 'download'])->name('download.pdf');
    // routes/web.php
    Route::get('/{filename}', [PdfController::class, 'download'])->name('download.pdf');
});
