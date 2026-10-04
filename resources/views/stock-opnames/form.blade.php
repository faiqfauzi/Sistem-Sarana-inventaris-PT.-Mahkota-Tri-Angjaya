<div class="mb-3">
    <label class="form-label">Tanggal Stok Opname</label>

    <input type="date" name="tanggal" class="form-control"
        value="{{ old('tanggal', isset($stockOpname) && $stockOpname->tanggal ? $stockOpname->tanggal->format('Y-m-d') : '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Kondisi</label>

    <input type="text" name="kondisi" class="form-control" value="{{ old('kondisi', $stockOpname->kondisi ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Auditor</label>

    <input type="text" name="auditor" class="form-control" value="{{ old('auditor', $stockOpname->auditor ?? '') }}">
</div>

<div class="mb-3">

    <label class="form-label">Foto</label>
    @if (!empty($stockOpname->foto))
        <div class="mb-2">
            <img src="{{ asset('storage/' . $stockOpname->foto) }}" width="150" class="img-thumbnail">
        </div>
    @endif

    <input type="file" name="foto" class="form-control">
</div>

<div class="d-flex justify-content-end">
    <a href="{{ route('inventories.show', $inventory ?? $stockOpname->inventory) }}" class="btn btn-secondary me-2">
        Batal
    </a>

    <button class="btn btn-primary">
        Simpan
    </button>
</div>
