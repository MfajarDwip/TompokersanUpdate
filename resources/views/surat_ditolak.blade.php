@extends('layouts.mainlayout')
@section('title', 'Surat Ditolak')
<!-- partial -->
@section('content')
    @php
        $nama = session()->get('nama');
        $akses = session()->get('hak_akses');
        $rt = session()->get('rt');
        $rw = session()->get('rw');
    @endphp
    <div class="card" style="border-radius: 2px;">
        <div class="card-body">
            <div class="header-atas">
                <h5 class="font-weight-bold text-dark">Surat Ditolak</h5>
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
                            <td>{{ $value->created_at->format('d-m-Y') }} Pukul {{ $value->created_at->format('H:i') }}</td>
                            <td>{{ $value->status }}</td>
                            <td>
                                <a class="btn btn-secondary" style="background: #00AAAA; color: white;" data-toggle="modal"
                                    data-target="#exampleModal{{ $value->id }}" style="color: white;"
                                    href="#">Detail Surat</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="exampleModal{{ $value->id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ url('updatestatus/' . $value->id . '/' . $akses) }}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pratinjau Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($akses == 'admin')
                            <div class="form-group">
                                <label for="">Nomor Pengajuan</label>
                                <input type="text" name="nomor_surat"
                                    class="form-control @error('nomor_surat')is-invalid
                            @enderror"
                                    value="{{ old('nomor_surat') }}" autocomplete="off">
                                @error('nomor_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Nomor Pengantar</label>
                                <input type="text" name="" class="form-control"
                                    value="{{ $value->no_pengantar }}" autocomplete="off">
                            </div>
                        @endif
                        {{-- @if ($akses == 'RT')
                            <div class="form-group">
                                <label for="">Nomor Pengantar</label>
                                <input type="text" name="nomor_surat"
                                    class="form-control @error('nomor_surat')is-invalid
                        @enderror"
                                    value="{{ old('nomor_surat') }}" autocomplete="off">
                                @error('nomor_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        @if ($akses == 'RW')
                            <div class="form-group">
                                <label for="">Nomor Pengantar</label>
                                <input type="text" name="nomor_surat" class="form-control"
                                    value="{{ $value->no_pengantar }}" autocomplete="off">
                            </div>
                        @endif --}}
                        <div class="form-group">
                            <label>NIK</label>
                            <input type="text" name="nik" class="form-control" value="{{ $value->nik }}"
                                maxlength="50" required="">
                            <span class="text-danger">
                        </div>
                        <div class="form-group">
                            <label>Nama </label>
                            <input type="text" name="nama" class="form-control" value="{{ $value->nama_lengkap }}"
                                maxlength="50" required="">
                        </div>
                        <div class="form-group">
                            <label>Tempat, Tanggal Lahir </label>
                            <input type="text" name="ttl" class="form-control"
                                value="{{ $value->tempat_lahir }}, {{ $value->tgl_lahir }}" maxlength="50" required="">
                        </div>
                        <div class="form-group">
                            <label>Jenis kelamin</label>
                            <input type="text" name="kelamin" class="form-control" value="{{ $value->jenis_kelamin }}"
                                maxlength="50" required="">
                        </div>
                        <div class="form-group ">
                            <label>Kebangsaan/Agama</label>
                            <input type="text" name="kebangsaan" class="form-control"
                                value="{{ $value->kewarganegaraan }} / {{ $value->agama }}" maxlength="30" required="">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="status" class="form-control"
                                value="{{ $value->status_perkawinan }}" maxlength="50" required="">
                            <span class="text-danger">
                        </div>
                        <div class="form-group">
                            <label>Pekerjaan</label>
                            <input type="text" name="pekerjaan" class="form-control" value="{{ $value->pekerjaan }}"
                                maxlength="50" required="">
                            <span class="text-danger">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" value="{{ $value->alamat }}"
                                maxlength="50" required="">
                            <span class="text-danger">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Pengajuan</label>
                            <input type="text" name="tglpengajuan" class="form-control"
                                value="{{ $value->created_at }}" maxlength="50" required="">
                            <span class="text-danger">
                        </div>
                        <div class="form-group">
                            <label>Keperluan Surat</label>
                            <input type="text" name="keperluan" class="form-control"
                                value="{{ $value->keterangan }}" maxlength="50" required="">
                            <span class="text-danger">
                        </div>
                        <div class="form-group">
                            <label for="">Foto Kartu Keluarga</label>
                            <img src="{{ asset('images/' . $value->image_kk) }}" class="img-thumbnail"
                                alt="Responsive image">
                        </div>
                        <div class="form-group">
                            <label for="">Bukti Pendukung</label>
                            <img src="{{ asset('images/' . $value->image_bukti) }}" class="img-thumbnail"
                                alt="Responsive image">
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan Ditolak</label>
                            <input type="text" name="nomor_surat"
                                class="form-control @error('nomor_surat')is-invalid
                    @enderror"
                                value="{{ $value->keterangan_ditolak }}" autocomplete="off">
                            @error('nomor_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- @if ($akses == 'RT')
                            <div class="form-group">
                                <label>Keterangan Ditolak</label>
                                <input type="text" name="keperluan" class="form-control"
                                    value="{{ $value->keterangan }}" maxlength="50" required="">
                                <span class="text-danger">
                            </div>
                        @endif --}}
                        {{-- </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary"
                            style="background-color: rgb(0, 189, 0); color: white;">Setujui</button>
                        @if ($akses == 'RT')
                            <a type="button" data-toggle="modal" data-target="#modal-ditolak{{ $value->id }}"
                                class="btn btn-danger" data-dismiss="modal" href="">Tolak</a>
                        @endif
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
@endsection
