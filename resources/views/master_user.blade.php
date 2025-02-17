@extends('layouts.mainlayout')
@section('title', 'Master User')
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
     @if (session::has('successhapus'))
    <script>
        toastr.success('Data Berhasil Dihapus', '')
    </script>
    @endif
    @if ($errors->any())
    <script>
        toastr.error('Cek Kembali Data yang Anda Input', 'Data Gagal Ditambahkan')
    </script>
    @endif
    @if (session::has('relation'))
    <script>
        toastr.error('Data Gagal Dihapus', 'Data Memiliki Relasi')
    </script>
    @endif
</div>

<div class="card" style="border-radius: 2px;">
    <div class="card-body">
        <div class="header-atas">
            <h5 class="font-weight-bold text-dark">Master Akun User</h5>
        </div>
        <table id="myTable" class="table table-bordered">
            <thead style="background-color: grey; color: white;">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>RW</th>
                    <th>RT</th>
                    <th>Tempat, Tanggal Lahir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <form action="" method="post">
                    @foreach ($data as $value)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $value->user->nik }}</td>
                        <td>{{ $value->user->nama_lengkap }}</td>
                        <td>{{ $value->user->rw }}</td>
                        <td>{{ $value->user->rt }}</td>
                        <td>{{ $value->user->tempat_lahir }}, {{ $value->user->tgl_lahir }}</td>
                        <td>
                            <a class="btn btn-warning fa fa-pencil" style="color:white;" href="" data-toggle="modal" data-target="#modal-edit{{ $value->id_masyarakat }}">
</a>
                            <a class="btn btn-danger icon-trash" name='Hapus' href="#" data-toggle="modal" data-target="#modal-hapus{{ $value->id_masyarakat  }}" style="margin-left: 10px;">
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </form>
            </tbody>
        </table>
    </div>
</div>

@foreach ($data as $no => $value)
<div class="modal fade" id="modal-edit{{ $value->id_masyarakat }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User Akun</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('masteruser/' . $value->id_masyarakat) }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">NIK</label>
                        <input type="text" name="nik" class="form-control" value="{{ $value->nik }}" placeholder="" autocomplete="off" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Nama Lengkap</label>
                        <input type="text" name="editnama" class="form-control" value="{{ $value->nama_lengkap }}" placeholder="" autocomplete="off" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('nokkedit') }}" placeholder="" autocomplete="off" id="password-field-{{ $value->nik }}">
                            <span toggle="#password-field-{{ $value->nik }}" class="fa fa-fw fa-eye field-icon toggle-password" style="cursor: pointer;"></span>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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


{{-- Modal Hapus --}}
@foreach ($data as $no => $value)
<div class="modal fade" id="modal-hapus{{ $value->id_masyarakat }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Hapus Data Master User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label for="">Yakin untuk Menghapus Data {{ $value->nama_lengkap }} ?</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a type="button" onclick="showNotification()" href="{{ url('masteruser/' . $value->id_masyarakat . '/hapus') }}" class="btn btn-danger">Hapus</a>
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
    .field-icon {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 2;
    }
    .input-group {
        position: relative;
    }
    .form-control {
        padding-right: 30px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#myModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('no_kk');
            var modal = $(this);

            $.ajax({
                url: '/data/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    modal.find('.modal-body').html(response.data);
                }
            });
        });
    });
</script>

{{-- toast cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
