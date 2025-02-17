@extends('layouts.mainlayouts')
@section('title', 'Master KK')
@include('sweetalert::alert')
{{-- Section Content --}}
@section('content')
@if (session('import_notifications'))
    <script>
        toastr.error('Data Gagal Ditambahkan', 'NIK sudah Ada');
    </script>
@elseif (session::has('success'))
    <script>
        toastr.success('Data Berhasil Ditambahkan', '');
    </script>
@endif

@if (session::has('successhappus'))
<script>
    toastr.success('Data Berhasil Dihapus', '')
</script>
@endif
@if (session::has('successedit'))
<script>
    toastr.success('Data Berhasil Diperbarui', '')
</script>
@endif
@if (session::has('exist'))
<script>
    toastr.error('Data Gagal Ditambahkan', 'Nomor KK sudah Ada')
</script>
@endif
@if (session::has('error'))
<script>
    toastr.error('Data memiliki relasi', '')
</script>
@endif
@if ($errors->any())
<script>
    toastr.error('Cek Kembali Data yang Anda Masukkan', 'Data Gagal Ditambahkan')
</script>
@endif
@if (session::has('relation'))
<script>
    toastr.error('Data Gagal Dihapus', 'Data Memiliki Relasi')
</script>
@endif

<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">


<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<div class="card" style="border-radius: 2px;">
    <div class="card-body">
    <div class="header-atas">
            <h5 class="font-weight-bold text-dark">Anggota Keluarga</h5>
            <button data-toggle="modal" name='tambah' data-target="#modal-tambahmas">Tambah Data</button>
            <form action="{{ route('importmas') }}" method="POST" enctype="multipart/form-data" style="display: inline-block;">
                @csrf 
                <input type="file" name="file" accept=".xlsx, .xls" style="margin-left: 10px;">
                <button type="submit" class="btn btn-primary btn-sm">Import Excel</button>
            </form>
        </div>
        <table id="myTable" class="table table-bordered">
            <thead style="background-color: grey; color: white;">
                <tr>
                    <th>No</th>
                    <th>No KK</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Status Keluarga</th>
                    <th>Tempat Tanggal Lahir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <form action="" method="post">
                    @foreach ($data as $no => $value)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $value->masyarakat->no_kk }}</td>
                        <td>{{ $value->nik }}</td>
                        <td>{{ $value->nama_lengkap }}</td>
                        <td>{{ $value->status_keluarga }}</td>
                        <td>{{ $value->tempat_lahir }}, {{ $value->tgl_lahir }}</td>
                        <td>
                            <div class="row">
                                <div class="btn-group">
                                    <a class="btn btn-warning  btn-sm btn-sm fa fa-pencil" style="color:white;" href="" data-toggle="modal" data-target="#modal-edit{{ $value->nik }}">
                                    </a>
                                    <a class="btn btn-danger  btn-sm btn-sm icon-trash" name='Hapus' href="#" data-toggle="modal" data-target="#modal-hapus{{ $value->nik }}" href="{{ url('masteruser') }}"></a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </form>
            </tbody>
        </table>
    </div>
</div>

{{-- Batas Page 2 --}}

{{-- modal tambah user --}}
<div class="modal fade" id="modal-tambahmas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Anggota Kartu Keluarga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('simpanuser') }}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="input" name="nokk" class="form-control" maxlength="50" required="" value="{{ $nomor_kk }}" autocomplete="off" readonly>
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" maxlength="50" placeholder="NIK" autocomplete="off">
                        @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap') }}" maxlength="50" placeholder="Nama Lengkap" autocomplete="off">
                        @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <div class="col">
                            <select class="form-control @error('kelamin') is-invalid @enderror" name="kelamin" autocomplete="off" id="exampleFormControlSelect1">
                                <option value="pilih" {{ 'pilih' === old('kelamin') ? 'selected' : '' }}>Pilih
                                </option>
                                <option value="Laki-Laki" {{ 'Laki-Laki' === old('kelamin') ? 'selected' : '' }}>
                                    Laki-Laki
                                </option>
                                <option value="Perempuan" {{ 'Perempuan' === old('kelamin') ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                            @error('kelamin')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}" maxlength="50" placeholder="Tempat Lahir" autocomplete="off">
                            @error('tempat_lahir')
                            <div class="invalid-feedback"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror" value="{{ old('tgl_lahir') }}" maxlength="50" placeholder="Tanggal Lahir" autocomplete="off">
                            @error('tgl_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Agama</label>
                            <div class="col">
                                <select class="form-control @error('agama') is-invalid @enderror" value="{{ old('agama') }}" name="agama" autocomplete="off" id="exampleFormControlSelect1">
                                    <option value="pilih" {{ 'pilih' === old('agama') ? 'selected' : '' }}>Pilih
                                    </option>
                                    <option value="Islam" {{ 'Islam' === old('agama') ? 'selected' : '' }}>Islam
                                    </option>
                                    <option value="Kristen Protestan" {{ 'Kristen Protestan' === old('agama') ? 'selected' : '' }}>
                                        Kristen Protestan</option>
                                    <option value="Katolik" {{ 'Katolik' === old('agama') ? 'selected' : '' }}>
                                        Katolik</option>
                                    <option value="Hindu" {{ 'Hindu' === old('agama') ? 'selected' : '' }}>Hindu
                                    </option>
                                    <option value="Buddha" {{ 'Buddha' === old('agama') ? 'selected' : '' }}>
                                        Buddha</option>
                                    <option value="Konghucu" {{ 'Konghucu' === old('agama') ? 'selected' : '' }}>
                                        Konghucu</option>
                                </select>
                                @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <label>Pendidikan</label>
                            <div class="col">
                                <select class="form-control @error('pendidikan') is-invalid @enderror" value="{{ old('pendidikan') }}" name="pendidikan" autocomplete="off" id="exampleFormControlSelect1">
                                    <option value="pilih" {{ 'pilih' === old('pendidikan') ? 'selected' : '' }}>
                                        Pilih</option>
                                    <option value="Tidak/Belum Sekolah" {{ 'Tidak/Belum Sekolah' === old('pendidikan') ? 'selected' : '' }}>
                                        Tidak/Belum
                                        Sekolah</option>
                                    <option value="Belum Tamat SD/Sederajat" {{ 'Belum Tamat SD/Sederajat' === old('pendidikan') ? 'selected' : '' }}>
                                        Belum
                                        Tamat SD/Sederajat</option>
                                    <option value="Tamat SD/Sederajat" {{ 'Tamat SD/Sederajat' === old('pendidikan') ? 'selected' : '' }}>
                                        Tamat
                                        SD/Sederajat</option>
                                    <option value="SLTP/Sederajat" {{ 'SLTP/Sederajat' === old('pendidikan') ? 'selected' : '' }}>
                                        SLTP/Sederajat</option>
                                    <option value="SLTA/Sederajat" {{ 'SLTA/Sederajat' === old('pendidikan') ? 'selected' : '' }}>
                                        SLTA/Sederajat</option>
                                    <option value="Diploma I/II" {{ 'Diploma I/II' === old('pendidikan') ? 'selected' : '' }}>
                                        Diploma I/II</option>
                                    <option value="Diploma III" {{ 'Diploma III' === old('pendidikan') ? 'selected' : '' }}>
                                        Diploma III/S.Muda</option>
                                    <option value="Diploma IV/Strata I" {{ 'Diploma IV/Strata I' === old('pendidikan') ? 'selected' : '' }}>
                                        Diploma IV/Strata I</option>
                                    <option value="Strata II" {{ 'Strata II' === old('pendidikan') ? 'selected' : '' }}>
                                        Strata II</option>
                                    <option value="Strata III" {{ 'Strata III' === old('pendidikan') ? 'selected' : '' }}>
                                        Strata III</option>
                                </select>
                                @error('pendidikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ old('pekerjaan') }}" maxlength="50" placeholder="Pekerjaan" autocomplete="off">
                        @error('pekerjaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Golongan Darah</label>
                            <div class="col">
                                <select class="form-control @error('gol_darah') is-invalid @enderror" value="{{ old('gol_darah') }}" name="gol_darah" autocomplete="off" id="exampleFormControlSelect1">
                                    <option value="pilih" {{ 'pilih' === old('gol_darah') ? 'selected' : '' }}>
                                        Pilih</option>
                                    <option value="-" {{ '-' === old('gol_darah') ? 'selected' : '' }}>-
                                    </option>
                                    <option value="A" {{ 'A' === old('gol_darah') ? 'selected' : '' }}>A
                                    </option>
                                    <option value="B" {{ 'B' === old('gol_darah') ? 'selected' : '' }}>B
                                    </option>
                                    <option value="O" {{ 'O' === old('gol_darah') ? 'selected' : '' }}>O
                                    </option>
                                    <option value="AB" {{ 'AB' === old('gol_darah') ? 'selected' : '' }}>AB
                                    </option>
                                </select>
                                @error('gol_darah')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <label>Status Perkawinan</label>
                            <div class="col">
                                <select class="form-control @error('status_perkawinan') is-invalid @enderror" value="{{ old('status_perkawinan') }}" name="status_perkawinan" autocomplete="off" id="exampleFormControlSelect1">
                                    <option value="pilih" {{ 'pilih' === old('status_perkawinan') ? 'selected' : '' }}>
                                        Pilih</option>
                                    <option value="Belum Kawin" {{ 'Belum Kawin' === old('status_perkawinan') ? 'selected' : '' }}>
                                        Belum
                                        Kawin</option>
                                    <option value="Kawin" {{ 'Kawin' === old('status_perkawinan') ? 'selected' : '' }}>
                                        Kawin</option>
                                    <option value="Cerai" {{ 'Cerai' === old('status_perkawinan') ? 'selected' : '' }}>
                                        Cerai</option>
                                </select>
                                @error('status_perkawinan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Perkawinan</label>
                        <input type="date" name="tgl_perkawinan" class="form-control @error('tgl_perkawinan') is-invalid @enderror" value="{{ old('tgl_perkawinan') }}" maxlength="50" placeholder="Tanggal Perkawinan" autocomplete="off">
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Status Keluarga</label>
                            <div class="col">
                                <select class="form-control @error('status_keluarga') is-invalid @enderror)" value="{{ old('status_keluarga') }}" name="status_keluarga" autocomplete="off" id="exampleFormControlSelect1">
                                    <option value="pilih" {{ 'pilih' === old('status_keluarga') ? 'selected' : '' }}>
                                        Pilih</option>
                                    {{-- <option>Kepala Keluarga</option> --}}
                                    <option value="Istri" {{ 'Istri' === old('status_keluarga') ? 'selected' : '' }}>
                                        Istri</option>
                                    <option value="Anak" {{ 'Anak' === old('status_keluarga') ? 'selected' : '' }}>
                                        Anak</option>
                                    <option value="Wali" {{ 'Wali' === old('status_keluarga') ? 'selected' : '' }}>
                                        Wali</option>
                                </select>
                                @error('status_keluarga')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <label>Kewarganegaraan</label>
                            <div class="col">
                                <select class="form-control @error('kewarganegaraan') is-invalid @enderror" value="{{ old('kewarganegaraan') }}" name="kewarganegaraan" autocomplete="off" id="exampleFormControlSelect1">
                                    <option value="pilih" {{ 'pilih' === old('kewarganegaraan') ? 'selected' : '' }}>
                                        Pilih</option>
                                    <option value="WNI" {{ 'WNI' === old('kewarganegaraan') ? 'selected' : '' }}>
                                        WNI
                                    </option>
                                    <option value="WNA" {{ 'WNA' === old('kewarganegaraan') ? 'selected' : '' }}>
                                        WNA
                                    </option>
                                </select>
                                @error('kewarganegaraan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control @error('nama_ayah') is-invalid @enderror" value="{{ old('nama_ayah') }}" maxlength="50" placeholder="Nama Ayah" autocomplete="off">
                            @error('nama_ayah')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label>Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control @error('nama_ibu') is-invalid @enderror" value="{{ old('nama_ibu') }}" maxlength="50" placeholder="Nama Ibu" autocomplete="off">
                            @error('nama_ibu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-Success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- batas modal tambah user --}}


{{-- MOdal Edit --}}
@foreach ($data as $no => $value)
<div class="modal fade" id="modal-edit{{ $value->nik }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Anggota Kartu Keluarga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('update-masteruser/' . $value->nik) }}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="input" name="nokk" class="form-control" maxlength="50" required="" value="{{ $nomor_kk }}" autocomplete="off" readonly>
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" name="nik" class="form-control @error('nik') is-invalid
                            @enderror" maxlength="50" placeholder="NIK" autocomplete="off" value="{{ old('nik', $value->nik) }}">
                        @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid
                                 @enderror" value="{{ old('nama_lengkap', $value->nama_lengkap) }}" maxlength="50" placeholder="Nama Lengkap" autocomplete="off">
                        @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <div class="col">
                            <select class="form-control @error('kelamin') is-invalid
                                @enderror" name="kelamin" autocomplete="off" id="exampleFormControlSelect1">
                                <option>{{ $value->jenis_kelamin }}</option>
                                <option value="Laki-Laki" {{ 'Laki-Laki' === old('kelamin') ? 'selected' : '' }}>
                                    Laki Laki
                                </option>
                                <option value="Perempuan" {{ 'Perempuan' === old('kelamin') ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>

                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid
                                    @enderror" value="{{ old('tempat_lahir', $value->tempat_lahir) }}" maxlength="50" placeholder="Tempat Lahir" autocomplete="off">
                            @error('tempat_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid
                                    @enderror" value="{{ old('tgl_lahir', $value->tgl_lahir) }}" maxlength="50" placeholder="Tanggal Lahir" autocomplete="off">
                            @error('tgl_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Agama</label>
                            <div class="col">
                                <select class="form-control @error('agama') is-invalid
                                    @enderror" name="agama" autocomplete="off" id="exampleFormControlSelect1">
                                    <option>{{ $value->agama }}</option>
                                    <option value="Islam" {{ 'Islam' === old('agama') ? 'selected' : '' }}>Islam
                                    </option>
                                    <option value="Kristen Protestan" {{ 'Kristen Protestan' === old('agama') ? 'selected' : '' }}>
                                        Kristen
                                        Protestan</option>
                                    <option value="Katolik" {{ 'Katolik' === old('agama') ? 'selected' : '' }}>
                                        Katolik
                                    </option>
                                    <option value="Hindu" {{ 'Hindu' === old('agama') ? 'selected' : '' }}>Hindu
                                    </option>
                                    <option value="Buddha" {{ 'Buddha' === old('agama') ? 'selected' : '' }}>
                                        Buddha
                                    </option>
                                    <option value="Konghucu" {{ 'Konghucu' === old('agama') ? 'selected' : '' }}>
                                        Konghucu</option>
                                </select>
                                @error('agama')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <label>Pendidikan</label>
                            <div class="col">
                                <select class="form-control @error('pendidikan') is-invalid
                                    @enderror" name="pendidikan" autocomplete="off" id="exampleFormControlSelect1">
                                    <option>{{ $value->pendidikan }}</option>
                                    <option value="Tidak/Belum Sekolah" {{ 'Tidak/Belum Sekolah' === old('pendidikan') ? 'selected' : '' }}>
                                        Tidak/Belum Sekolah</option>
                                    <option value="Belum Tamat SD/Sederajat" {{ 'Belum Tamat SD/Sederajat' === old('pendidikan') ? 'selected' : '' }}>
                                        Belum
                                        Tamat SD/Sederajat</option>
                                    <option value="Tamat SD/Sederajat" {{ 'Tamat SD/Sederajat' === old('pendidikan') ? 'selected' : '' }}>
                                        Tamat SD/Sederajat</option>
                                    <option value="SLTP" {{ 'SLTP' === old('pendidikan') ? 'selected' : '' }}>
                                        SLTP/Sederajat</option>
                                    <option value="SLTA" {{ 'SLTA' === old('pendidikan') ? 'selected' : '' }}>
                                        SLTA/Sederajat</option>
                                    <option value="Diploma I/II" {{ 'Diploma I/II' === old('pendidikan') ? 'selected' : '' }}>Diploma
                                        I/II</option>
                                    <option value="Diploma III/S.Muda" {{ 'Diploma III/S.Muda' === old('pendidikan') ? 'selected' : '' }}>
                                        Diploma
                                        III/S.Muda</option>
                                    <option value="Diploma IV/Strata I" {{ 'Diploma IV/Strata I' === old('pendidikan') ? 'selected' : '' }}>
                                        Diploma
                                        IV/Strata I</option>
                                    <option value="Strata II" {{ 'Strata II' === old('pendidikan') ? 'selected' : '' }}>
                                        Strata II
                                    </option>
                                    <option value="Strata III" {{ 'Strata III' === old('pendidikan') ? 'selected' : '' }}>
                                        Strata III
                                    </option>
                                </select>
                                @error('pendidikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control @error('pekerjaan') is-invalid
                                @enderror" value="{{ old('pekerjaan', $value->pekerjaan) }}" maxlength="50" placeholder="Pekerjaan" autocomplete="off">
                        @error('pekerjaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Golongan Darah</label>
                            <div class="col">
                                <select class="form-control @error('gol_darah') is-invalid
                                    @enderror" name="gol_darah" autocomplete="off" id="exampleFormControlSelect1">
                                    <option>{{ $value->golongan_darah }}</option>
                                    <option value="-" {{ '-' === old('gol_darah') ? 'selected' : '' }}>-
                                    </option>
                                    <option value="A" {{ 'A' === old('gol_darah') ? 'selected' : '' }}>A
                                    </option>
                                    <option value="B" {{ 'B' === old('gol_darah') ? 'selected' : '' }}>B
                                    </option>
                                    <option value="O" {{ 'O' === old('gol_darah') ? 'selected' : '' }}>O
                                    </option>
                                    <option value="AB" {{ 'AB' === old('gol_darah') ? 'selected' : '' }}>AB
                                    </option>
                                </select>
                                @error('gol_darah')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <label>Status Perkawinan</label>
                            <div class="col">
                                <select class="form-control @error('status_perkawinan') is-invalid
                                    @enderror" name="status_perkawinan" autocomplete="off" id="exampleFormControlSelect1">
                                    <option>{{ $value->status_perkawinan }}</option>
                                    <option value="Belum Kawin" {{ 'Belum Kawin' === old('status_perkawinan') ? 'selected' : '' }}>
                                        Belum Kawin</option>
                                    <option value="Kawin" {{ 'Kawin' === old('status_perkawinan') ? 'selected' : '' }}>
                                        Kawin</option>
                                    <option value="Cerai" {{ 'Cerai' === old('status_perkawinan') ? 'selected' : '' }}>
                                        Cerai</option>
                                </select>
                                @error('status_perkawinan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Perkawinan</label>
                        <input type="date" name="tgl_perkawinan" class="form-control @error('tgl_perkawinan') is-invalid
                                @enderror" value="{{ $value->tgl_perkawinan }}" maxlength="50" placeholder="Tanggal Perkawinan" autocomplete="off">
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Status Keluarga</label>
                            <div class="col">
                                <select class="form-control @error('status_keluarga')is-invalid

                                    @enderror" name="status_keluarga" autocomplete="off" id="exampleFormControlSelect1">
                                    <option>
                                        {{ $value->status_keluarga }}
                                    </option>
                                    <option value="Kepala Keluarga" {{ 'Kepala Keluarga' === old('status_keluarga') ? 'selected' : '' }}>
                                        Kepala Keluarga</option>
                                    <option value="Istri" {{ 'Istri' === old('status_keluarga') ? 'selected' : '' }}>
                                        Istri</option>
                                    <option value="Anak" {{ 'Anak' === old('status_keluarga') ? 'selected' : '' }}>
                                        Anak</option>
                                    <option value="Wali" {{ 'Wali' === old('status_keluarga') ? 'selected' : '' }}>
                                        Wali</option>
                                </select>
                                @error('status_keluarga')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <label>Kewarganegaraan</label>
                            <div class="col">
                                <select class="form-control @error('kewarganegaraan') is-invalid
                                    @enderror" name="kewarganegaraan" autocomplete="off" id="exampleFormControlSelect1">
                                    <option>
                                        {{ $value->kewarganegaraan }}
                                    </option>
                                    <option value="WNI" {{ 'WNI' === old('kewarganegaraan') ? 'selected' : '' }}>WNI
                                    </option>
                                    <option value="WNA" {{ 'WNA' === old('kewarganegaraan') ? 'selected' : '' }}>WNA
                                    </option>
                                </select>
                                @error('kewarganegaraan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col">
                            <label>Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control @error('nama_ayah') is-invalid
                                    @enderror" value="{{ old('nama_ayah', $value->nama_ayah) }}" maxlength="50" placeholder="Nama Ayah" autocomplete="off">
                            @error('nama_ayah')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label>Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control @error('nama_ibu') is-invalid
                                    @enderror" value="{{ old('nama_ibu', $value->nama_ibu) }}" maxlength="50" placeholder="Nama Ibu" autocomplete="off">
                            @error('nama_ibu')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-Success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
{{-- batad modal edit kk --}}

{{-- Modal Hapus user --}}
@foreach ($data as $no => $value)
<div class="modal fade" id="modal-hapus{{ $value->nik }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url($value->nik . '/hapus-masteruser') }}" method="get">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Anggota Kartu Keluarga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="{{ $value->masyarakat->id }}" name="id">
                    <label for="">Yakin untuk Menghapus Data {{ $value->nama_lengkap }} ?</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
{{-- batad modal hapus user --}}
@endsection
{{-- Batas Section Content --}}

<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
    $(document).ready(function() {
        readData()
        $('#input').keyup(function() {
            var strcari = $("#input").val();
            if (strcari != "") {
                $("#read").html('<p class="text-muted">Menunggu Mencari Data ...</p>')
                $.ajax({
                    type: "get",
                    url: "{{ url('ajax') }}",
                    data: "nik=" + strcari,
                    success: function(data) {
                        $("#read").html(data);
                    }
                });
            } else {
                readData()
            }
        });
    });

    function readData() {
        $.get("{{ url('read') }}", {}, function(data, status) {
            $("#read").html(data);
        });
    }
</script>


<script>
    function isiTextfield(nilai) {
        document.getElementById("input").value = nilai;
    }

    var input = document.querySelector('#input'); // mengambil elemen input

    input.addEventListener('keyup', function(event) {
        if (event.keyCode === 13) { // cek jika tombol yang ditekan adalah tombol "Enter"
            event.preventDefault(); // membatalkan perilaku default tombol "Enter"
            document.querySelector('form').submit(); // melakukan submit form
        }
    });
</script>
<script>
    function isiTextfield2(nilai) {
        document.getElementById("input2").value = nilai;
    }

    var input = document.querySelector('#input'); // mengambil elemen input

    input.addEventListener('keyup', function(event) {
        if (event.keyCode === 13) { // cek jika tombol yang ditekan adalah tombol "Enter"
            event.preventDefault(); // membatalkan perilaku default tombol "Enter"
            document.querySelector('form').submit(); // melakukan submit form
        }
    });
</script>


<style>
    table {
        border-collapse: collapse;
        white-space: nowrap;
        min-width: 100%;
    }

    .header-atas {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header h4 {
        margin: 0;
    }

    .header button {
        margin-left: auto;
    }
</style>

{{-- toast cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- jquery cdn --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>