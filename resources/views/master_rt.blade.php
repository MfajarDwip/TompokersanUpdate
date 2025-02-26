@extends('layouts.mainlayouts')
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
    @if (session::has('succesDelete'))
    <script>
        toastr.success('Data Berhasil Dihapus', '')
    </script>
    @endif
    @if (session::has('errorDelete'))
    <script>
        toastr.success('Terjadi kesalahan saat menghapus data')
    </script>
    @endif
    @if (session::has('errorrt'))
    <script>
        toastr.error('Data RT Sudah Ada', 'Data Gagal Ditambahkan')
    </script>
    @endif
    @if (session::has('errorissetrt'))
    <script>
        toastr.error('Data Akun RT Sudah Ada', 'Data Gagal Ditambahkan')
    </script>
    @endif
    @if (session::has('errorissetrw'))
    <script>
        toastr.error('Akun Sudah Terdaftar Sebagai RW', 'Data Gagal Ditambahkan')
    </script>
    @endif
    @if ($errors->any())
    <script>
        toastr.error('Data Yang Anda Masukkan Salah', 'Data Gagal Ditambahkan')
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
            <h5 class="font-weight-bold text-dark">Master RT</h5>
            <button data-toggle="modal" name='tambah' data-target="#modal-tambah">Tambah data</button>
        </div>
        <table id="myTable" class="table table-bordered">
            <thead style="background-color: grey; color: white;">
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama RW</th>
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
                        <td>{{ $value->nik }}</td>
                        <td>{{ $value->nama_lengkap }}</td>
                        <td>{{ $value->rt }}</td>
                        <td>Ketua RT</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn icon-cog dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #00AAAA; color: white;">
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" data-id="" href="" data-toggle="modal" data-target="#modal-edit{{ $value->nik }}">Edit</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-hapus{{ $value->nik }}">Hapus</a>
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

<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Akun RT</h5>
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
<div class="modal fade" id="modal-edit{{ $value->nik }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Akun RW</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('update-masterrt/' . $value->id_masyarakat) }}" method="post">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label for="">NIK</label>
            <input type="text" name="nik" class="form-control" value="{{ $value->nik }}" maxlength="50" required="" placeholder="NIK" autocomplete="off" readonly>
        </div>
        <div class="form-group">
            <label for="">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ $value->nama_lengkap }}" maxlength="50" required="" placeholder="Nama Lengkap" autocomplete="off" readonly>
        </div>
        <div class="form-group">
            <label for="">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ $value->alamat }}" maxlength="50" required="" placeholder="Alamat" autocomplete="off" readonly>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    <label for="">RT</label>
                    <input type="text" name="rt" class="form-control" value="{{ $value->rt }}" placeholder="RT" autocomplete="off" readonly>
                </div>
                <div class="col">
                    <label for="">RW</label>
                    <input type="text" name="rw" class="form-control" value="{{ $value->rw }}" placeholder="RW" autocomplete="off" readonly>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="">No Telepon</label>
            <input type="text" name="no_hp" class="form-control" value="{{ $value->no_hp }}" maxlength="50" required="" placeholder="No HP" autocomplete="off">
        </div>
        <div class="form-group">
            <label>Kata Sandi</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="" maxlength="50" autocomplete="off">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-success">Simpan</button>
    </div>
</form>
        </div>
    </div>
</div>
@endforeach
{{-- batas modal edit --}}

{{-- Modal Hapus --}}
@foreach ($datartrw as $no => $value)
<div class="modal fade" id="modal-hapus{{ $value->nik }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Akun RT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="">Yakin untuk Menghapus Data {{ $value->nama_lengkap }} ?</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a type="button" href="{{ url('hapus-masterrt/' . $value->nik) }}" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>
@endforeach
{{-- Batas Modal Hapus --}}
@endsection

<style media="screen">
    .button {
        width: 100%;
        height: 50px;
    }

    .left {
        float: left;
        display: block;
    }

    .right {
        float: right;
        display: block;
    }

    .button ul a {
        margin: 10px;
        padding: 10px;
        background: rgb(116, 181, 12);
        color: white;
    }
</style>

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
                    url: "{{ url('/ajaxrt') }}",
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