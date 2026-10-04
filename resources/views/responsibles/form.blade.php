<div class="mb-3">
    <label class="form-label">Nama Penanggung Jawab</label>

    <input
        type="text"
        name="nama_penanggung_jawab"
        class="form-control @error('nama_penanggung_jawab') is-invalid @enderror"
        value="{{ old('nama_penanggung_jawab', $responsible->nama_penanggung_jawab ?? '') }}"
        placeholder="Masukkan nama penanggung jawab">

    @error('nama_penanggung_jawab')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>

<div class="d-flex justify-content-end">
    <a href="{{ route('responsibles.index') }}" class="btn btn-secondary me-2">
        Batal
    </a>

    <button type="submit" class="btn btn-primary">
        Simpan
    </button>
</div>