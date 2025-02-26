<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('template/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/base/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('template/vendors/flag-icon-css/css/flag-icon.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/jquery-bar-rating/fontawesome-stars-o.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/jquery-bar-rating/fontawesome-stars.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
    {{--
    <link rel="stylesheet" --}} {{--
        href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css') }}">
    <!-- endinject -->

    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

    {{-- css toast --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="{{ asset('template/images/logo.png') }}" />
    <title>SOBAT-Desa | @yield('title')</title>
</head>


@php
$nama = session()->get('nama');
$nik = session()->get('nik');
$akses = session()->get('hak_akses');
$rt = session()->get('rt');
$rw = session()->get('rw');
@endphp</h4>


<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="dashboard"><img src="{{ asset('template/images/Tompokersan.png') }}" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="dashboard"><img src="{{ asset('template/images/logo.png') }}" alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item dropdown d-flex mr-4 ">
                        <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">
                            <i class="icon-cog"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                            <p class="mb-0 font-weight-normal float-left dropdown-header">Settings</p>
                            <a class="dropdown-item preview-item" data-toggle="modal" data-target="#modal-profile">
                                <i class="icon-head"></i> Profile
                            </a>
                            <a class="dropdown-item preview-item" href="../../logout">
                                <i class="icon-inbox"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <div class="container-fluid page-body-wrapper">
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard">
                            <i class="icon-box menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    @if ($akses == 'RT')
                    <li class="nav-item">
                        <a class="nav-link" href="../suratmasuk">
                            <i class="icon-inbox menu-icon"></i>
                            <span class="menu-title">Surat Masuk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../suratselesai">
                            <i class="icon-outbox menu-icon"></i>
                            <span class="menu-title">Surat Selesai</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../suratditolak">
                            <i class="icon-archive menu-icon"></i>
                            <span class="menu-title">Surat Ditolak</span>
                        </a>
                    </li>
                    @endif

                    @if ($akses == 'RW')
                    <li class="nav-item">
                        <a class="nav-link" href="../suratmasuk">
                            <i class="icon-inbox menu-icon"></i>
                            <span class="menu-title">Surat Masuk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../suratselesai">
                            <i class="icon-outbox menu-icon"></i>
                            <span class="menu-title">Surat Selesai</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                            <a class="nav-link" href="../suratditolak">
                                <i class="icon-archive menu-icon"></i>
                                <span class="menu-title">Surat Ditolak</span>
                            </a>
                        </li> --}}
                    @endif

                    @if ($akses == 'admin')
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                            <i class="icon-mail menu-icon"></i>
                            <span class="menu-title">Pengajuan Surat</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" style="font-size: 0.78rem;" href="../suratmasuk">Surat Masuk</a></li>
                                <li class="nav-item"> <a class="nav-link" style="font-size: 0.78rem;" href="../suratselesai">Surat Selesai</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/masteruser">
                            <i class="icon-head menu-icon"></i>
                            <span class="menu-title">Master Akun User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../masterrw">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Master Akun Rt Rw</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../masterkk">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Master KK</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../mastersurat">
                            <i class="icon-paper menu-icon"></i>
                            <span class="menu-title">Master Surat</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../berita">
                            <i class="icon-globe menu-icon"></i>
                            <span class="menu-title">Berita</span>
                        </a>
                    </li>
                    @else
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="../tentang">
                            <i class="icon-book menu-icon"></i>
                            <span class="menu-title">Tentang</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-sm-12 mb-4 mb-xl-0">
                            @yield('content')
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">SOBAT-Desa</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Aplikasi Pengajuan
                            Surat Kelurahan Tompokersan</span>
                    </div>
                </footer>
            </div>
        </div>
    </div>
    {{-- Modal edit profile --}}
    <div class="modal fade" id="modal-profile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" autocomplete="off">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/image-upload" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="profile">
                            <img class="img-profile rounded-circle" src="" style="">
                            <style>
                                .profile {
                                    display: table-cell;
                                    vertical-align: middle;
                                    text-align: center;
                                }

                                .profile img {
                                    margin: auto;
                                    display: block;
                                    width: 150px;
                                    height: 150px;
                                }
                            </style>
                        </div>
                        <div class="form-group">
                            <input type="file" name="image">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="nik" class="form-control" value="{{ $nik }}" maxlength="50" placeholder="Nama Lengkap" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="namalengkap" class="form-control" value="{{ $nama }}" maxlength="50" placeholder="Nama Lengkap" autocomplete="off" readonly>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="status" class="form-control" value="{{ $akses }}" maxlength="50" placeholder="Status" autocomplete="off" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-secondary" style="background: green; color: white;">
                            simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    {{-- batad modal edit profile --}}
</body>

</html>

<script src="{{ asset('template/vendors/base/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('template/js/off-canvas.js') }}"></script>
<script src="{{ asset('template/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('template/js/template.js') }}"></script>
<script src="{{ asset('template/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('template/vendors/jquery-bar-rating/jquery.barrating.min.js') }}"></script>
<script src="{{ asset('template/js/dashboard.js') }}"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js') }}" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js') }}" integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>