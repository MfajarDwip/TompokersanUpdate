@extends('layouts.mainlayout')
@section('title', 'Master KK')
@include('sweetalert::alert')

@section('content')

@if (session::has('success'))
<script>
    toastr.success('Data Berhasil ditambahkan')
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
            <h5 class="font-weight-bold text-dark">Master KK</h5>
            <button data-toggle="modal" name='tambah' data-target="#modal-tambahkk">Tambah data</button>
            <form action="{{ route('importkk') }}" method="POST" enctype="multipart/form-data" style="display: inline-block;">
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
                    <th>Nama Lengkap</th>
                    <th>Alamat</th>
                    <th>RW</th>
                    <th>RT</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <form action="masterkk" method="post">
                    @foreach ($data as $no => $value)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $value->masyarakat['no_kk'] }}</td>
                        <td>{{ $value->nama_lengkap }}</td>
                        <td>{{ $value->masyarakat['alamat'] }}</td>
                        <td>{{ $value->masyarakat['rw'] }}</td>
                        <td>{{ $value->masyarakat['rt'] }}</td>
                        <td>
                            <div class="row">
                                <div class="btn-group">
                                    <a class="btn btn-warning btn-sm btn-sm fa fa-pencil" style="color:white;" data-id="" href="" data-toggle="modal" data-target="#modal-edit{{ $value->masyarakat->no_kk }}"></a>
                                    <a class="btn btn-danger btn-sm icon-trash" data-toggle="modal" data-target="#modal-hapus{{ $value->masyarakat->no_kk }}" value="{{ $value->no_kk }}" href="{{ url('masterkk') }}"></a>
                                    <a class="btn btn-success btn-sm icon-head" style=" color: white;" name="kk" value="{{ $value->masyarakat->no_kk }}" href="{{ url('masterkkmas/' . $value->id_kk) }}"></a>
                                </div>
                            </div>
                    </tr>
                    @endforeach
                </form>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-tambahkk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kartu Keluarga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('simpankk') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomor-kartu"></label>
                        <input type="number" id="nomor-kartu" name="no_kk" class="form-control
                            @error('no_kk') is-invalid
                            @enderror" value="{{ old('no_kk') }}" placeholder="Nomor KK" autocomplete="off">
                        @error('no_kk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nomor-kartu"></label>
                        <input type="number" id="nomor-kartu" name="nik" class="form-control
                            @error('nik') is-invalid

                            @enderror" value="{{ old('nik') }}" placeholder="NIK Kepala Keluarga" autocomplete="off">
                        @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="text" name="kepala_keluarga" class="form-control
                            @error('kepala_keluarga') is-invalid

                            @enderror" value="{{ old('kepala_keluarga') }}" placeholder="Nama Kepala Keluarga" autocomplete="off">
                        @error('kepala_keluarga')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="text" name="alamat" class="form-control
                            @error('alamat') is-invalid

                            @enderror" value="{{ old('alamat') }}" placeholder="Alamat" autocomplete="off">
                        @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <input type="text" name="rt" class="form-control
                                    @error('rt') is-invalid

                                    @enderror" value="{{ old('rt') }}" placeholder="RT" autocomplete="off">
                                @error('rt')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col">
                                <input type="text" name="rw" class="form-control
                                    @error('rw') is-invalid

                                    @enderror" value="{{ old('rw') }}" placeholder="RW" autocomplete="off">
                                @error('rw')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="">Kelurahan</label>
                                <input type="text" name="kelurahan" class="form-control" placeholder="Kabupaten" value="Tompokersan" maxlength="50" required="" autocomplete="off" readonly>
                            </div>
                            <div class="col">
                                <label for="">Kode Pos</label>
                                <input type="text" name="kode_pos" class="form-control" placeholder="Kecamatan" value="67311" maxlength="50" required="" autocomplete="off" readonly>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="">Kabupaten</label>
                                <input type="text" name="kabupaten" class="form-control" placeholder="Kabupaten" value="Lumajang" maxlength="50" required="" autocomplete="off" readonly>
                            </div>
                            <div class="col">
                                <label for="">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control" placeholder="Kecamatan" value="Lumajang" maxlength="50" required="" autocomplete="off" readonly>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="">Provinsi</label>
                                <input type="text" name="provinsi" class="form-control" placeholder="Provinsi" value="Jawa Timur" maxlength="50" required="" autocomplete="off" readonly>
                            </div>
                            <div class="col">
                                <label for="">KK Tanggal</label>
                                <input type="date" class="form-control  @error('kk_tgl') is-invalid
                                    @enderror" value="{{ old('kk_tgl') }}" name="kk_tgl" id="myDate" placeholder="yyyy-mm-dd" min="1000-01-01" max="9999-12-31" autocomplete="off">
                                @error('kk_tgl')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-Success" style="background-color: rgb(0, 189, 0); color: white;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($data as $no => $value)
<div class="modal fade" id="modal-hapus{{ $value->masyarakat->no_kk }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Kartu Keluarga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="">Yakin untuk Menghapus Data {{ $value->nama_lengkap }} ?</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <form action="{{ route('hapuskk', $value->masyarakat->no_kk) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- Batas Modal Hapus kk --}}

{{-- Modal Edit kk --}}
@foreach ($data as $no => $value)
<div class="modal fade" id="modal-edit{{ $value->masyarakat->no_kk }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Master KK</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('update-masterkk/' . $value->masyarakat->no_kk) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">No Kartu Keluarga</label>
                            <input type="text" name="nokkedit" class="form-control
                                    @error('nokkedit')is-invalid
                                    @enderror" value="{{ old('nokkedit', $value->masyarakat['no_kk']) }}" placeholder="Nomor KK" autocomplete="off">
                            @error('nokkedit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">NIK</label>
                            <input type="number" id="nomor-kartu" name="nikedit" class="form-control
                                    @error('nikedit') is-invalid

                                    @enderror" value="{{ old('nikedit', $value->nik) }}" required="" placeholder="NIK Kepala Keluuarga" autocomplete="off" readonly>
                            @error('nikedit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Kepala Keluarga</label>
                            <input type="text" name="kepala_keluargaedit" class="form-control
                                    @error('kepala_keluargaedit')is-invalid

                                    @enderror" value="{{ old('kepala_keluargaedit', $value->nama_lengkap) }}" placeholder="Nama Kepala Keluarga" autocomplete="off" readonly>
                            @error('kepala_keluargaedit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <input type="text" name="alamatkkedit" class="form-control
                                    @error('alamatkkedit')is-invalid

                                    @enderror" value="{{ old('alamatkkedit', $value->masyarakat['alamat']) }}" placeholder="Alamat" autocomplete="off">
                            @error('alamatkkedit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="">RT</label>
                                    <input type="text" name="rtedit" value="{{ old('rtedit', $value->masyarakat->rt) }} " class="form-control
                                                @error('rtedit')is-invalid

                                                @enderror" placeholder="RT" autocomplete="off">
                                    @error('rtedit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="">RW</label>
                                    <input type="text" name="rwedit" value="{{ old('rwedit', $value->masyarakat->rw) }}" class="form-control" placeholder="RW">
                                    @error('rwedit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="">Kode Pos</label>
                                    <input type="text" name="kdposedit" value="{{ $value->masyarakat->kode_pos }}" class="form-control" placeholder="Kode Pos" autocomplete="off" readonly>
                                    @error('kdposedit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="">Kelurahan</label>
                                    <input type="text" name="keledit" class="form-control" placeholder="Kelurahan" value="{{ $value->masyarakat->kelurahan }}" autocomplete="off" readonly>
                                </div>
                                <div class="col">
                                    <label for="">Kecamatan</label>
                                    <input type="text" name="kecedit" class="form-control" placeholder="Kecamatan" value="{{ $value->masyarakat->kecamatan }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col">
                                    <label for="">Kabupaten</label>
                                    <input type="text" name="kabedit" class="form-control" placeholder="Kabupaten" value="{{ $value->masyarakat->kabupaten }}" autocomplete="off" readonly>
                                </div>
                                <div class="col">
                                    <label for="">Provinsi</label>
                                    <input type="text" name="provedit" class="form-control" placeholder="Provinsi" value="{{ $value->masyarakat->provinsi }}" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">KK Tanggal</label>
                            <input type="date" class="form-control" value="{{ $value->masyarakat->kk_tgl }}" name="tglkkedit" id="myDate" name="myDate" placeholder="yyyy-mm-dd" min="1000-01-01" max="9999-12-31" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-Success" style="background-color: rgb(0, 189, 0); color: white;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

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