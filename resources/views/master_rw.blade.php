@extends('layouts.mainlayout')
@section('title', 'Master RT RW')
@include('sweetalert::alert')
<!-- partial -->
@section('content')
<div>
    @if (session::has('success'))
    <script>
        toastr.success('Data Berhasil Ditambahkan', '')
    </script>
    @endif
    @if (session::has('successedit'))
    <script>
        toastr.success('Data Berhasil Diperbarui', '')
    </script>
    @endif
    @if (session::has('errorrt'))
    <script>
        toastr.error('Akun Sudah Terdaftar Sebagai RT', 'Data Gagal Ditambahkan')
    </script>
    @endif
    @if (session::has('errorissetrw'))
    <script>
        toastr.error('Data Akun RW Sudah ada', 'Data Gagal Ditambahkan')
    </script>
    @endif
    @if (session::has('errorrw'))
    <script>
        toastr.error('Akun sudah Terdaftar Sebagai RW', 'Data Gagal Ditambahkan')
    </script>
    @endif
    @if ($errors->any())
    <script>
        toastr.error('Cek Kembali Nomor HP dan Passwors', 'Data Gagal Ditambahkan')
    </script>
    @endif
<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

</div>
<div class="card" style="border-radius: 2px;">
    <div class="card-body">
        <div class="header-atas">
            <h5 class="font-weight-bold text-dark">Master RW</h5>
            <button data-toggle="modal" name='tambah' data-target="#modal-tambah">Tambah
                data</button>
        </div>
        <table id="myTable" class="table table-bordered">
            <thead style="background-color: grey; color: white;">
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama RW</th>
                    <th>RW</th>
                    <th>RT</th>
                    <th>Status</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                <form action="" method="post">
                    @foreach ($datartrw as $no => $value)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $value->user->nik }}</td>
                        <td>{{ $value->user->nama_lengkap }}</td>
                        <td>{{ $value->user->rw }}</td>
                        <td>{{ $value->user->rt }}</td>
                        <td>Ketua RW</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn icon-cog dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #00AAAA; color: white;">
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" data-id="modal-edit" href="" data-toggle="modal" data-target="#modal-edit{{ $value->user->nik }}">Edit</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-hapus{{ $value->user->id_masyarakat }}">Hapus</a>
                                    <form action="" method="get">
                                        <a class="dropdown-item" name="kk" value="" href="{{ url('masterrt/' . $value->user->rw) }}">RT</a>
                                    </form>
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

{{-- coba modal tambah rw --}}
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Akun RW</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group d-inline-flex">
                            <label for="pencarian"></label>
                            <input type="text" id="input" class="form-control" placeholder="Ketikkan NIK...">
                            <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                        <div id="read"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal edit --}}
@foreach ($datartrw as $no => $value)
<div class="modal fade" id="modal-edit{{ $value->user->nik }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Akun RW</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('update-masterw/' . $value->user->nik) }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">NIK</label>
                        <input type="text" name="nik" class="form-control" value="{{ $value->user->nik }}" maxlength="50" required="" placeholder="NIK" autocomplete="off" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ $value->user->nama_lengkap }}" maxlength="50" required="" placeholder="Nama Lengkap" autocomplete="off" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ $value->user->alamat }}" maxlength="50" required="" placeholder="Alamat" autocomplete="off" readonly>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="">RT</label>
                                <input type="text" name="rt" class="form-control" value="{{ $value->user->rt }}" placeholder="RT" autocomplete="off" readonly>
                            </div>
                            <div class="col">
                                <label for="">RW</label>
                                <input type="text" name="rw" class="form-control" value="{{ $value->user->rw }}" placeholder="RW" autocomplete="off" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">No Telepon</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ $value->no_hp }}" maxlength="50" required="" placeholder="No HP" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Kata Sandi</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid
                                @enderror" value="" maxlength="50" autocomplete="off">
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
{{-- batas modal edit --}}

{{-- Modal Hapus --}}
@foreach ($datartrw as $no => $value)
    <div class="modal fade" id="modal-hapus{{ $value->user->id_masyarakat }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Akun RW</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="">Yakin untuk Menghapus Data {{ $value->user->nama_lengkap }} ?</label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <a type="button" href="{{ url('/hapus-masterrw/'.$value->user->id_masyarakat) }}" class="btn btn-danger">Hapus</a> <!-- URL di sini -->
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Batas Modal Hapus --}}
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

{{-- toast cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- jquery cdn --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>