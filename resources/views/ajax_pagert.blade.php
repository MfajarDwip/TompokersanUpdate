@foreach ($datart as $item)
    <form action="{{ url('simpanrt/' . $item->nik) }}" method="post">
        @csrf
        <div class="modal-body">
            <input type="hidden" name="id_masyarakat" class="form-control" value="{{ $item->id_masyarakat }}" maxlength="50"
                required="" readonly>
            <div class="form-group">
                <label>NIK</label>
                <input type="text" name="nik" class="form-control" value="{{ $item->nik }}" maxlength="50"
                    required="" placeholder="NIK" autocomplete="off" readonly>
            </div>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ $item->nama_lengkap }}"
                    maxlength="50" required="" autocomplete="off" readonly>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="{{ $item->alamat }}" maxlength="50"
                    required="" autocomplete="off" readonly>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="">RT</label>
                        <input type="text" name="rt" value="{{ $item->rt }} "class="form-control"
                            placeholder="RT" autocomplete="off" readonly>
                    </div>
                    <div class="col">
                        <label for="">RW</label>
                        <input type="text" name="rw" value="{{ $item->rw }}" class="form-control"
                            placeholder="RW" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label>No Handphone</label>
                    <input type="text" name="no_hp"
                        class="form-control @error('no_hp') is-invalid

                @enderror" value=""
                        maxlength="50" autocomplete="off">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Kata Sandi</label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid
                @enderror" value=""
                        maxlength="50" autocomplete="off">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-Success">Simpan</button>
            </div>

    </form>
@endforeach
