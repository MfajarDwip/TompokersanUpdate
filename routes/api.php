<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiBeritaController;
use App\Http\Controllers\ApiPengajuanController;
use App\Http\Controllers\ApiPengajuanRtController;
use App\Http\Controllers\ApiPengajuanRwController;
use App\Http\Controllers\ApiSuratController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [ApiAuthController::class, 'register']);
Route::post('auth/login', [ApiAuthController::class, 'login']);

Route::get('berita', [ApiBeritaController::class, 'berita']);
Route::get('news', [ApiBeritaController::class, 'news']);
Route::get('surat', [ApiSuratController::class, 'surat']);


Route::post('store', [NotifikasiController::class, 'store']);
Route::post('store-all', [NotifikasiController::class, 'storeall']);
Route::post('pengajuan', [ApiPengajuanController::class, 'pengajuan'])->middleware('guest');


Route::middleware('auth:sanctum')->group(function () {

    //user
    Route::post('auth/fcm-token', [ApiAuthController::class, 'storeFCMToken']);
    Route::get('auth/fcm-token-check', [ApiAuthController::class, 'check']);
    Route::get('auth/me', [ApiAuthController::class, 'me']);
    Route::post('auth/role', [ApiAuthController::class, 'chooseRole']);
    Route::post('auth/logout', [ApiAuthController::class, 'logout']);
    Route::get('keluarga', [ApiAuthController::class, 'keluarga']);
    Route::post('status-surat', [ApiPengajuanController::class, 'status_surat']);
    Route::get('status-proses', [ApiPengajuanController::class, 'status_proses']);
    Route::post('send-notification-user', [ApiPengajuanController::class, 'sendNotificationUser']);
    Route::post('send-notification-rt', [ApiPengajuanRtController::class, 'sendNotification']);
    Route::post('send-notification-rw', [ApiPengajuanRwController::class, 'sendNotification']);
    Route::post('editnohp', [ApiAuthController::class, 'editnohp']);
    Route::post('pembatalan/{id}', [ApiPengajuanController::class, 'pembatalan']);


    //rt
    Route::post('status-surat-rt', [ApiPengajuanRtController::class, 'status_surat_rt']);
    Route::get('rekap-rt', [ApiPengajuanRtController::class, 'rekap_rt']);
    Route::post('update-status-setuju-rt/{id}', [ApiPengajuanRtController::class, 'update_status_setuju_rt']);
    Route::post('update-status-tolak-rt/{id}', [ApiPengajuanRtController::class, 'update_status_tolak_rt']);


    //rw
    Route::post('status-surat-rw', [ApiPengajuanRwController::class, 'status_surat_rw']);
    Route::get('rekap-rw', [ApiPengajuanRwController::class, 'rekap_rw']);
    Route::post('update-status-setuju-rw/{id}', [ApiPengajuanRwController::class, 'update_status_setuju_rw']);
    // routes/web.php
    Route::get('/download-pdf/{fileName}', [ApiPengajuanRwController::class, 'downloadPdf']);



});
