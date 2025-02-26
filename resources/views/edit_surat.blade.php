@extends('layouts.mainlayout')
@section('title', 'Edit Surat')

@section('content')
<style>
    .surat-container {
        width: 80%;
        margin: auto;
        padding: 20px;
        font-family: 'Times New Roman', Times, serif;
        font-size: 14pt;
        line-height: 1.5;
    }
    .header {
        text-align: center;
        font-weight: bold;
    }
    .content {
        margin-top: 20px;
    }
    .editable {
        display: inline-block;
        min-width: 50px;
        border-bottom: 1px dashed gray;
    }
    .signature {
        text-align: right;
        margin-top: 50px;
    }
</style>

@if($pengajuan) 
<div class="surat-container">
    <div class="header">
        <p>P E M E R I N T A H  K A B U P A T E N  L U M A J A N G</p>
        <p>KECAMATAN LUMAJANG</p>
        <p>KELURAHAN TOMPOKERSAN</p>
        <p>Jl. Basuki Rahmat Telp. (0334) 891940</p>
        <p><strong>SURAT KETERANGAN</strong></p>
        <p>Nomor: {{ $pengajuan->nomor_surat }}</p>
    </div>

    <div class="content">
        <p>Bertandatangan di bawah ini, menerangkan bahwa:</p>
        <p>1. Nama: {{ $pengajuan->nama_lengkap }}</p>
        <p>2. Tempat/Tanggal Lahir: {{ $pengajuan->tempat_lahir }}, {{ $pengajuan->tanggal_lahir }}</p>
        <p>3. Jenis Kelamin: {{ $pengajuan->jenis_kelamin }}</p>
        <p>4. Kebangsaan: {{ $pengajuan->kebangsaan }}</p>
        <p>5. Agama: {{ $pengajuan->agama }}</p>
        <p>6. Status Perkawinan: {{ $pengajuan->status_perkawinan }}</p>
        <p>7. Pekerjaan: {{ $pengajuan->pekerjaan }}</p>
        <p>8. NIK: {{ $pengajuan->nik }}</p>
        <p>9. Alamat: {{ $pengajuan->alamat }}</p>
    </div>

    <p><span class="editable" contenteditable="true" data-keperluan>{{ $pengajuan->keperluan }}</span>.</p>
    <p>Surat Keterangan ini dipergunakan sebagai persyaratan: <span class="editable" contenteditable="true" data-keterangan>{{ $pengajuan->keterangan }}</span></p>
    <p><span class="editable" contenteditable="true" data-info>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</span></p>

    <div class="signature">
        <p>Lumajang, <span class="editable" contenteditable="true" data-tanggal-surat>{{ $pengajuan->tanggal_surat }}</span></p>
        <p><strong>LURAH TOMPOKERSAN</strong></p>
        <img src="{{ asset('image/tompokersan1.jpg') }}" alt="Tanda Tangan Lurah" style="width: 200px; height: auto; margin-bottom: 10px;">
        <p><span class="editable" contenteditable="true">JOKO SETIYO, S.Kom.MM</span></p>
        <p>NIP. <span class="editable" contenteditable="true">19830607 201101 1 010</span></p>
    </div>
    <button id="saveButton" class="btn btn-success">Simpan Perubahan</button>
</div>  
@endif

<script>
document.getElementById("saveButton").addEventListener("click", function() {
    let editedData = {
        keperluan: document.querySelector("span[data-keperluan]").innerText,
        tanggal_surat: document.querySelector("span[data-tanggal-surat]").innerText
    };

    let idPengajuan = "{{ $pengajuan->id_pengajuan }}";
    let akses = "{{ $akses }}";

    fetch(`/updatesurat/${idPengajuan}/${akses}`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(editedData)
    })
    .then(response => {
        if (response.ok) {
            window.location.href = "/suratmasuk";
        } else {
            alert("Terjadi kesalahan");
        }
    })
    .catch(error => console.error("Terjadi kesalahan:", error));
});
</script>
@endsection