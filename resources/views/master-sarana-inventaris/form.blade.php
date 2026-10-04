<div class="mb-3">
    <label class="form-label">Nama Sarana</label>
    <input type="text"
           name="nama_sarana_inventaris"
           class="form-control @error('nama_sarana_inventaris') is-invalid @enderror"
           value="{{ old('nama_sarana_inventaris', $masterSaranaInventaris->nama_sarana_inventaris ?? '') }}"
           placeholder="Masukkan nama sarana">

    @error('nama_sarana_inventaris')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="d-flex justify-content-end">
    <a href="{{ route('master-sarana-inventaris.index') }}" class="btn btn-secondary me-2">
        Batal
    </a>

    <button type="submit" class="btn btn-primary">
        Simpan
    </button>
</div>