<div class="mb-3">
    <label class="form-label">Nama Lokasi</label>

    <input
        type="text"
        name="nama_lokasi"
        class="form-control @error('nama_lokasi') is-invalid @enderror"
        value="{{ old('nama_lokasi', $location->nama_lokasi ?? '') }}"
        placeholder="Masukkan nama lokasi">

    @error('nama_lokasi')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Keterangan</label>

    <textarea
        name="keterangan"
        rows="3"
        class="form-control @error('keterangan') is-invalid @enderror"
        placeholder="Masukkan keterangan (opsional)">{{ old('keterangan', $location->keterangan ?? '') }}</textarea>

    @error('keterangan')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="d-flex justify-content-end">
    <a href="{{ route('locations.index') }}" class="btn btn-secondary me-2">
        Batal
    </a>

    <button type="submit" class="btn btn-primary">
        Simpan
    </button>
</div>