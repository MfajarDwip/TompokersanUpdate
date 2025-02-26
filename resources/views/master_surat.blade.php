@extends('layouts.mainlayout')
@section('title', 'Master Surat')
@include('sweetalert::alert')

@section('content')
<script>
    $(document).ready(function() {
        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @endif

        @if (session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        @if (session('exist'))
            toastr.warning('{{ session('exist') }}');
        @endif

        @if (session('successhapus'))
            toastr.success('{{ session('successhapus') }}');
        @endif

        @if (session('successedit'))
            toastr.success('{{ session('successedit') }}');
        @endif

        @if (session('relation'))
            toastr.warning('{{ session('relation') }}');
        @endif
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- jquery cdn -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<div class="card" style="border-radius: 2px;">
    <div class="card-body">
        <div class="header-atas">
            <h5 class="font-weight-bold text-dark">Halaman Master Surat</h5>
            <button data-toggle="modal" name='tambah' data-target="#modal-tambah">Tambah data</button>
        </div>
        <table id="myTable" class="table table-bordered">
            <thead style="background-color: grey; color: white;">
                <tr>
                    <th>
                        <h6>No</h6>
                    </th>
                    <th>
                        <h6>Jenis Surat</h6>
                    </th>
                    <th>
                        <h6>Ikon Surat</h6>
                    </th>
                    <th>
                        <h6>Aksi</h6>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $no => $value)
                <tr>
                    <td class="text-lg">{{ $no + 1 }}</td>
                    <td class="text-lg">{{ $value->nama_surat }}</td>
                    <td><img style="width: 30px; height: 30px;  border-radius: 5%;" src="{{ asset('images/' . $value->image) }}"></td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-warning btn-sm fa fa-pencil" style="color:white;" data-toggle="modal" data-target="#modal-editsurat{{ $value->id_surat }}"></a>
                            <a class="btn btn-danger btn-sm icon-trash" data-toggle="modal" data-target="#modal-hapus{{ $value->id_surat }}"></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Master Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('simpansurat') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <label for="id_surat">Id Surat</label>
                    <div class="form-group">
                        <input type="number" name="id_surat" class="form-control @error('id_surat') is-invalid @enderror" value="{{ old('id_surat') }}" placeholder="Id Surat" autocomplete="off">
                        @error('id_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <label for="nama_surat">Nama Surat</label>
                    <div class="form-group">
                        <input type="text" name="nama_surat" class="form-control @error('nama_surat') is-invalid @enderror" value="{{ old('nama_surat') }}" placeholder="Jenis Surat" autocomplete="off">
                        @error('nama_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <label for="image">Ikon Surat</label>
                    <div class="form-group">
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success" style="background-color: rgb(0, 189, 0); color: white;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Surat -->
@foreach ($data as $value)
<div class="modal fade" id="modal-editsurat{{ $value->id_surat }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Master Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('editsurat/' . $value->id_surat) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <label for="image">Ikon Surat</label>
                    <div class="form-group">
                        <img width="100%;" height="100%;" src="{{ asset('images/' . $value->image) }}" alt="">
                    </div>
                    <div class="row form-group">
                        <div class="col-md-9">
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <label for="id_surat">Id Surat</label>
                    <div class="form-group">
                        <input type="number" name="id_surat" class="form-control @error('id_surat') is-invalid @enderror" value="{{ old('id_surat', $value->id_surat) }}" placeholder="Id Surat" autocomplete="off" readonly>
                        @error('id_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <label for="nama_surat">Nama Surat</label>
                    <div class="form-group">
                        <input type="text" name="nama_surat" class="form-control @error('nama_surat') is-invalid @enderror" value="{{ old('nama_surat', $value->nama_surat) }}" placeholder="Jenis Surat" autocomplete="off">
                        @error('nama_surat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="background-color: rgb(0, 189, 0); color: white;">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Hapus Surat -->
@foreach ($data as $value)
<div class="modal fade" id="modal-hapus{{ $value->id_surat }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('hapussurat/' . $value->id_surat) }}" method="get">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data Master Surat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="confirm">Yakin untuk Menghapus Jenis {{ $value->nama_surat }} ?</label>
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

@endsection

@section('scripts')
<!-- toast cdn -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- jquery cdn -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
@endsection

<style>
    .header-atas {
        display: flex;
        justify-content: space-between;
        align-items: center;
        }
</style>