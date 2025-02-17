@extends('layouts.mainlayout')
@section('title', 'Surat Masuk')
@include('sweetalert::alert')
@php
$nama = session()->get('nama');
$akses = session()->get('hak_akses');
$rt = session()->get('rt');
$rw = session()->get('rw');
@endphp
@section('content')
@if (session::has('success'))
<script>
    toastr.success('Data Berhasil Ditambahkan', '')
</script>
@endif
@if (session::has('successHapus'))
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
            <h5 class="font-weight-bold text-dark">Surat Masuk</h5>
        </div>
        <table id="myTable" class="table table-bordered">
            <thead style="background-color: grey; color: white;">
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Jenis Surat</th>
                    <th>Waktu Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $no => $value)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $value->nik }}</td>
                    <td>{{ $value->nama_lengkap }}</td>
                    <td>{{ $value->nama_surat }}</td>
                    <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y H:i') }}</td>
                    <td>{{ $value->status }}</td>
                    <td>
                        <div>
                        <a class="btn btn-secondary" style="background: #00AAAA; color: white;" data-toggle="modal" data-target="#exampleModal{{ $value->id_pengajuan }}" href="#">Proses Surat</a>
                        </div>
                        <div>
                        <a class="btn btn-warning" data-toggle="modal" data-target="#editModal{{ $value->id_pengajuan }}" href="#">Edit</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal RT dan RW-->
@foreach ($data as $value)
<div class="modal fade" id="exampleModal{{ $value->id_pengajuan }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('updatestatus/' . $value->id_pengajuan . '/' . $akses) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pratinjau Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($akses == 'admin')
                    <div class="form-group">
                        <label for="">Nomor Surat</label>
                        <div class="d-flex">
                            <input type="text" id="nomor_surat" name="nomor_surat" class="form-control @error('nomor_surat')is-invalid @enderror" value="{{ $nomorSuratBaru }}" autocomplete="off" style="flex: 1; margin-right: 5px;" readonly>
                            <span style="align-self: center; margin-right: 5px;">/</span>
                            <input type="text" id="nomor_surat_tambahan" name="nomor_surat_tambahan" class="form-control @error('nomor_surat_tambahan')is-invalid @enderror" value="{{ old('nomor_surat_tambahan','135') }}" autocomplete="off" style="flex: 1;">
                        </div>
                        @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Kode Kecamatan</label>
                        <input type="text" name="kode_kecamatan" class="form-control @error('kode_kecamatan')is-invalid @enderror" value="{{ old('kode_kecamatan','427.90.60') }}" autocomplete="off" placeholder="427.90.60">
                        @error('kode_kecamatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Nomor Pengantar</label>
                        <input type="text" name="" class="form-control" value="{{ $value->no_pengantar }}" autocomplete="off">
                    </div>
                    @endif
                    @if ($akses == 'RT')
                    <div class="form-group">
                        <label for="">Nomor Pengantar</label>
                        <input type="text" name="nomor_surat" class="form-control @error('nomor_surat')is-invalid @enderror" value="{{ old('nomor_surat') }}" autocomplete="off">
                        @error('nomor_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    @if ($akses == 'RW')
                    <div class="form-group">
                        <label for="">Nomor Pengantar</label>
                        <input type="text" name="nomor_surat" class="form-control" value="{{ $value->no_pengantar }}" autocomplete="off" readonly>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" name="nik" class="form-control" value="{{ $value->nik }}" maxlength="50" required="">
                    </div>
                    <div class="form-group">
                        <label>Nama </label>
                        <input type="text" name="nama" class="form-control" value="{{ $value->nama_lengkap }}" maxlength="50" required="">
                    </div>
                    <div class="form-group">
                        <label>Tempat, Tanggal Lahir </label>
                        <input type="text" name="ttl" class="form-control" value="{{ $value->tempat_lahir }}, {{ $value->tgl_lahir }}" maxlength="50" required="">
                    </div>
                    <div class="form-group">
                        <label>Jenis kelamin</label>
                        <input type="text" name="kelamin" class="form-control" value="{{ $value->jenis_kelamin }}" maxlength="50" required="">
                    </div>
                    <div class="form-group ">
                        <label>Kebangsaan/Agama</label>
                        <input type="text" name="kebangsaan" class="form-control" value="{{ $value->kewarganegaraan }} / {{ $value->agama }}" maxlength="30" required="">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" name="status" class="form-control" value="{{ $value->status_perkawinan }}" maxlength="50" required="">
                    </div>
                    <div class="form-group">
                        <label>Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="{{ $value->pekerjaan }}" maxlength="50" required="">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ $value->alamat }}" maxlength="50" required="">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Pengajuan</label>
                        <input type="text" name="tglpengajuan" class="form-control" value="{{ $value->created_at }}" maxlength="50" required="">
                    </div>
                    <div class="form-group">
                        <label>Keperluan Surat</label>
                        <input type="text" name="keperluan" class="form-control" value="{{ $value->keterangan }}" maxlength="50" required="">
                    </div>

                    <!-- Links to PDFs -->
                    <div class="form-group">
                        <label for="">Bukti Pendukung</label>
                        @if (!empty($value->pengantar_rt))
                        <a href="{{ route('download.pdf', ['filename' => $value->pengantar_rt]) }}" class="btn btn-primary">
                            Lihat bukti RT
                        </a>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="">Bukti Pendukung</label>
                        @if (!empty($value->pengantar_rw))
                        <a href="{{ route('download.pdf', ['filename' => $value->pengantar_rw]) }}" class="btn btn-primary">
                            Lihat Bukti RW
                        </a>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="">Foto Kartu Keluarga</label>
                        <img src="{{ asset('images/' . $value->image_kk) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    <div class="form-group">
                        <label for="">Bukti KTP</label>
                        <img src="{{ asset('images/' . $value->image_ktp) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @if ($value->nama_surat == 'Cetak Kartu Keluarga Baru' || $value->nama_surat == 'Cetak Akta Kelahiran' || $value->nama_surat == 'Surat Keterangan Kenal Lahir')
                    <div class="form-group">
                        <label for="">Foto Surat Nikah</label>
                        <img src="{{ asset('images/' . $value->image_suratnikah) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                    @if ($value->nama_surat == 'Cetak Kartu Keluarga Baru' || $value->nama_surat == 'Surat Keterangan Status')
                    <div class="form-group">
                        <label for="">Foto Akta Cerai</label>
                        <img src="{{ asset('images/' . $value->image_aktacerai) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                    @if ($value->nama_surat == 'Cetak Kartu Keluarga Hilang')
                    <div class="form-group">
                        <label for="">Foto Bukti Kehilangan</label>
                        <img src="{{ asset('images/' . $value->image_suratkehilangan) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                    @if ($value->nama_surat == 'Cetak Akta Kelahiran' || $value->nama_surat == 'Surat Keterangan Kenal Lahir')
                    <div class="form-group">
                        <label for="">Foto Bukti Kelahiran</label>
                        <img src="{{ asset('images/' . $value->image_buktikelahiran) }}" class="img-thumbnail" alt="Responsive image">
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    @if ($akses == 'admin')
                    <button type="submit" class="btn btn-success">Simpan</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


@foreach ($data as $no => $value)
<div class="modal fade" id="modal-ditolak{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('updatestatustolak/' . $value->id . '/' . $akses) }}" method="get">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Keterangan Surat Ditolak</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Keterangan Ditolak</label>
                        <textarea type="text" name="keterangan_ditolak" class="form-control" maxlength="50" required=""></textarea>
                        <span class="text-danger">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection


<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

{{-- toast cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- jquery cdn --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>