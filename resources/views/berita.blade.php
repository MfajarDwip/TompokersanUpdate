@extends('layouts.mainlayout')
@section('title', 'Berita')
@include('sweetalert::alert')
<!-- partial -->
@section('content')
    @if (session::has('success'))
        <script>
            toastr.success('Data Berhasil Ditambahkan', '')
        </script>
    @endif
    @if (session::has('successedit'))
        <script>
            toastr.success('Data Berhasil Diedit', '')
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

    <div class="card" style="border-radius: 2px;">
        <div class="card-body">
            <div class="header-atas">
                <h5 class="font-weight-bold text-dark">Halaman Berita</h5>
                <button data-toggle="modal" name='tambah' data-target="#modal-tambah">Tambah
                    data</button>
            </div>
            <table id="myTable" class="table table-bordered">
                <thead style="background-color: grey; color: white;">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="berita" method="post">
                        @foreach ($data as $value)
                            <tr>
                                <td class="text-lg">{{ $loop->iteration }}</td>
                                <td class="text-lg">{{ $value->judul }}</td>
                                <td class="text-lg">{{ $value->sub_title }}</td>
                                <td class="text-lg">{{ $value->deskripsi }}</td>
                                <td><img style="width: 100px; height: 100px;  border-radius: 5%;"
                                        src="{{ asset('images/' . $value->image) }}">
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="btn-group">
                                            <a class="btn btn-warning btn-sm btn-sm fa fa-pencil" style="color:white;"
                                                data-id="{{ $value->id_berita }}" data-toggle="modal"
                                                data-target="#modal-editberita{{ $value->id }}">
                                            </a>
                                            <a class="btn btn-danger btn-sm icon-trash" data-toggle="modal"
                                                data-target="#modal-hapus{{ $value->id }}"></a>
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


    <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Master Berita</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('simpanberita') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="col">
                            <label for="">Judul Berita</label>
                            <div class="form-group">
                                <input type="text" name="judul"
                                    class="form-control  @error('judul') is-invalid
                        @enderror"
                                    value="{{ old('judul') }}" placeholder="Judul Berita" autocomplete="off">
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="">Tanggal Berita</label>
                            <div class="form-group">
                                <input type="date" name="sub_title"
                                    class="form-control  @error('sub_title') is-invalid
                        @enderror"
                                    value="{{ old('sub_title') }}" placeholder="Sub Judul" autocomplete="off">
                                @error('sub_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="">Deskripsi Berita</label>
                            <div class="form-group">
                                <textarea type="text" name="deskripsi"
                                    class="form-control @error('deskripsi') is-invalid
                        @enderror"
                                    value="{{ old('deskripsi') }}" placeholder="Deskripsi" autocomplete="off"></textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="">Gambar Berita</label>
                            <div class="form-group">
                                <input type="file" name="image"
                                    class="form-control  @error('image')is-invalid
                                @enderror">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-Success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Update Berita --}}
    <!-- Modal Update Berita -->
@foreach ($data as $no => $value)
<div class="modal fade" id="modal-editberita{{ $value->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Berita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url('update-berita/' . $value->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <label for="">Gambar Berita</label>
                    <div class="form-group">
                        <img width="100%;" height="100%;" src="{{ asset('images/' . $value->image) }}" alt="">
                    </div>
                    <div class="form-group">
                        <input type="file" name="image" class="form-control @error('image')is-invalid @enderror">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <label for="">Judul Berita</label>
                    <div class="form-group">
                        <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                            value="{{ old('judul', $value->judul) }}" placeholder="Judul Berita" autocomplete="off">
                        @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <label for="">Tanggal Berita</label>
                    <div class="form-group">
                        <input type="date" name="sub_title" class="form-control @error('sub_title') is-invalid @enderror"
                            value="{{ old('sub_title', $value->sub_title) }}" placeholder="Sub Title" autocomplete="off">
                        @error('sub_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <label for="">Deskripsi Berita</label>
                    <div class="form-group">
                        <textarea type="text" name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                            placeholder="Deskripsi" autocomplete="off">{{ old('deskripsi', $value->deskripsi) }}</textarea>
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

    {{-- Batas Modal Berita --}}

    {{-- Modal Hapus --}}
    @foreach ($data as $no => $value)
        <div class="modal fade" id="modal-hapus{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data Master Berita</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="">Yakin untuk Menghapus Data {{ $value->judul }}?</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <a type="button" onclick="showNotification()" href="{{ url('hapus-berita/' . $value->id) }}"
                            class="btn btn-danger">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- Batas Modal Hapus --}}
@endsection


<style>
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

    a {
        style="margin-top: 10px;

    }
</style>

<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

{{-- toast cdn --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- jquery cdn --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
