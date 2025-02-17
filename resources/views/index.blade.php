<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--=============== BOXICONS ===============-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="shortcut icon" href="{{ asset('template/images/logo.png') }}" />
    <title>Tompokersan</title>
</head>

<body>
    @foreach($data as $value)
    <!--=============== HEADER ===============-->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="#" class="nav__logo">{{ $value->nama_website }}</a>

            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="/login" class="nav__link">Login</a>
                    </li>
                    <li class="nav__item">
                        <a href="#home" class="nav__link active-link">Home</a>
                    </li>
                    <li class="nav__item">
                        <a href="#about" class="nav__link">About us</a>
                    </li>
                    <li class="nav__item">
                        <a href="#services" class="nav__link">Fitur</a>
                    </li>
                    <li class="nav__item">
                        <a href="#contact" class="nav__link">Contact us</a>
                    </li>
                    <i class='bx bx-toggle-left change-theme' id="theme-button"></i>
                </ul>
            </div>
            <div class="nav__toggle" id="nav-toggle">
                <i class='bx bx-grid-alt'></i>
            </div>
            <a href="/login" class="button button__header">Login</a>
        </nav>
    </header>

    <main class="main">
        <!--=============== HOME ===============-->
        <section class="home section" id="home">
            <div class="home__container container grid">
                <img class="svg__img svg__color home__img" src="assets/img/gambarhome.png" alt="">
                <div class="home__data">
                    <h1 class="home__title">{{ $value->judul_home }}</h1>
                    <p class="home__description">{{ $value->deskripsi_home }}</p>
                    <a href="https://wa.me/6281231029398" class="button" target="blank">Hubungi Layanan</a>
                </div>
            </div>
        </section>

        <!--=============== ABOUT ===============-->
        <section class="about section container" id="about">
            <div class="about__container grid">
                <div class="about__video-container">
                    <iframe class="about__video" width="500" height="300" src="{{ $value->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="about__data">
                    <h2 class="section__title-center">{{ $value->judul_tentang }}</h2>
                    <p class="about__description">{{ $value->tentang_aplikasi }}</p>
                </div>
            </div>
        </section>



        <!--=============== SERVICES ===============-->
        <section class="services section container" id="services">
            <h2 class="section__title">Fitur - Fitur Smart App<br> {{ $value->nama_website }}</h2>
            <div class="services__container grid">
                <div class="services__data">
                    <h3 class="services__subtitle">{{ $value->nama_website }} TOMPOKERSAN MOBILE</h3>
                    <img class="svg__color services__img" src="assets/img/gbrgenggam.png" alt="">
                    <p class="services__description">Mengajukan surat sekarang jauh lebih mudah, cukup diajukan dimana
                        saja dalam genggaman tangan
                        {{--
                    <div>
                        <a href="#" class="button button-link">Learn More</a>
                    </div> --}}
                </div>

                <div class="services__data">
                    <h3 class="services__subtitle">{{ $value->nama_website }} TOMPOKERSAN WEBSITE</h3>
                    <img class="svg__color services__img" src="assets/img/gbrmanajemen.png" alt="">
                    <p class="services__description">Selain Aplikasi Android anda dapat mengajukan lewat website dan
                        sekarang pengajuan surat jauh lebih mudah</p>
                    {{-- <div>
                        <a href="#" class="button button-link">Learn More</a>
                    </div> --}}
                </div>

                <div class="services__data">
                    <h3 class="services__subtitle">{{ $value->nama_website }} TOMPOKERSAN TEPAT GUNA</h3>
                    <img class="svg__color services__img" src="assets/img/gbrefisien.png" alt="">
                    <p class="services__description">Manajement surat lebih baik dan lebih mudah dan dimana saja </p>
                    {{-- <div>
                        <a href="#" class="button button-link">Learn More</a>
                    </div> --}}
                </div>
            </div>
        </section>

        <!--=============== APP ===============-->
        <section class="app section container">
            <div class="app__container grid">
                <div class="app__data">
                    <h2 class="section__title-center">Ajukan Surat sekarang <br> di {{ $value->nama_website }}</h2>
                    <p class="app__description">Dengan Aplikasi Mobile yang dapat kamu dapatkan dari link berikut</p>
                    <div class="app__buttons">
                        <a href="{{ $value->link_download }}" class="button button-flex">
                            <i class='bx bxl-play-store button__icon'></i> Google Play
                        </a>
                    </div>
                </div>
                <img class="svg__img svg__color app__img" src="assets/img/Downloading.png" alt="">
            </div>
        </section>

        <!--=============== CONTACT US ===============-->

        <footer class="footer section">
            <div class="contact container" id="contact">
                <div class="contact__container grid">
                    <div class="contact__content">
                        <h2 class="section__title-center">{{ $value->nama_website }}<br> Here</h2>
                        <p class="contact__description">Pengunjung {{ $totalVisits }}</p>
                        <p>Rata Rata Lama Kunjungan {{ $averageDuration }} Menit</p>
                    </div>

                    <ul class="contact__content grid">
                        <li class="contact__address">Telephone: <span class="contact__information">{{ $value->no_telp
                                }}</span></li>
                        <li class="contact__address">Alamat : <span class="contact__information">{{
                                $value->alamat_kelurahan }}</span></li>
                        <li class="contact__address">Email : <span class="contact__information">{{
                                $value->email_kelurahan }}</span></li>
                    </ul>
                    <div class="contact__content">
                        <a href="#" class="button">Contact Us</a>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <!--=============== SCROLL UP ===============-->
    <a href="#" class="scrollup" id="scroll-up">
        <i class='bx bx-up-arrow-alt scrollup__icon'></i>
    </a>

    <!--=============== MAIN JS ===============-->
    <script src="assets/js/main.js"></script>
    @endforeach
</body>

</html>