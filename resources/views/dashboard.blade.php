@extends('layouts.mainlayout')
@section('title', 'Dashboard')
<!-- partial -->
@php
    $nama = session()->get('nama');
    $akses = session()->get('hak_akses');
    $rt = session()->get('rt');
    $rw = session()->get('rw');
@endphp

@section('content')
    <h4 class="font-weight-bold text-dark">Dashboard</h4>
    <h6 class="mt-3">Selamat Datang {{ $akses }} {{ $nama }}</h6>
    @csrf
    <!-- Content Row -->
    <div class="row">

        <!-- kartu surat masuk -->
        <div class="col-xl-3 col-md-6 mb-2 mt-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: grey;">
                                Surat Masuk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $suratmasuk }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <img src="image/suratmasuk.png" height="40">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('hak_akses') == 'RT')
            <div class="col-xl-3 col-md-6 mb-2 mt-3">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Surat Ditolak</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $suratditolak }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <img src="image/suratditolak.png" height="40">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- @if (session('hak_akses') == 'RW')
            <div class="col-xl-3 col-md-6 mb-2 mt-3">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Surat Ditolak</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $suratditolak }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <img src="image/suratditolak.png" height="40">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}
        <!-- kartu surat ditolak -->


        <!-- kartu surat selesai -->
        <div class="col-xl-3 col-md-6 mb-2 mt-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Surat Selesai</div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $suratselesai }}
                            </div>

                        </div>
                        <div class="col-auto">
                            <img src="image/suratselesai.png" height="40">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Content Row -->
    <div class="row">

    </div>
    <div class="col-lg-6 mb-4">

    </div>

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-7">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Jenis Pengajuan Surat</h4>
                        <p class="card-description">
                            Menampilkan data jenis pengajuan
                        </p>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Pengajuan Surat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Surat Keterangan Belum Menikah</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Surat Keterangan Domisili</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Surat Keterangan Ijin Keramaian</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Surat Keterangan Memiliki Usaha</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Surat Keterangan Beda Penulisan</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Surat Keterangan Domisili Organisasi</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Surat Keterangan Kematian</td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>Surat Keterangan Lahir</td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>Surat Keterangan Yang Bersangkutan</td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>Surat Pengantar Kehilangan</td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>Surat Penutupan Jalan</td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>SKCK OT</td>
                                    </tr>
                                    <tr>
                                        <td>13</td>
                                        <td>SKCK</td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>SKTM (KIS, SAKIT & PERSALINAN)</td>
                                    </tr>
                                    <tr>
                                        <td>15</td>
                                        <td>SKTM (SEKOLAH & BIDIK MISI)</td>
                                    </tr>
                                    <tr>
                                        <td>16</td>
                                        <td>SKTM RSUD</td>
                                    </tr>
                                    <tr>
                                        <td>17</td>
                                        <td>Surat Keterangan Bepergian</td>
                                    </tr>
                                    <tr>
                                        <td>18</td>
                                        <td>Surat Transaksi Harga Tanah</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
