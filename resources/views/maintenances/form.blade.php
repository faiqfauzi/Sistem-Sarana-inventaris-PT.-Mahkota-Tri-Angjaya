<div class="mb-3">
    <label class="form-label">Tanggal Perawatan</label>

    <input
        type="date"
        name="tanggal_perawatan"
        class="form-control"
        value="{{ old(
                'tanggal_perawatan',
                isset($maintenance) && $maintenance->tanggal_perawatan ? $maintenance->tanggal_perawatan->format('Y-m-d') : '',
            ) }}">
</div>

<div class="mb-3">
    <label class="form-label">Perawatan Selanjutnya</label>

    <input
        type="date"
        name="tanggal_perawatan_selanjutnya"
        class="form-control"
        value="{{ old(
                'tanggal_perawatan_selanjutnya',
                isset($maintenance) && $maintenance->tanggal_perawatan_selanjutnya ? $maintenance->tanggal_perawatan_selanjutnya->format('Y-m-d') : '',
            ) }}">
</div>

<div class="mb-3">
    <label class="form-label">Jenis Perawatan</label>

    <input
        type="text"
        name="jenis_perawatan"
        class="form-control"
        value="{{ old('jenis_perawatan', $maintenance->jenis_perawatan ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Pelaksana</label>

    <input
        type="text"
        name="pelaksana"
        class="form-control"
        value="{{ old('pelaksana', $maintenance->pelaksana ?? '') }}">
</div>

<div class="d-flex justify-content-end">
    <a href="{{ route('inventories.show', $inventory ?? $maintenance->inventory) }}"
       class="btn btn-secondary me-2">
        Batal
    </a>

    <button class="btn btn-primary">
        Simpan
    </button>
</div>