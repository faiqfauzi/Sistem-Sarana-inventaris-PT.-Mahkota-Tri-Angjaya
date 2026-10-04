<div class="mb-3">
    <label class="form-label">Jenis</label>

    <select name="jenis" class="form-select">

        <option value="">Pilih</option>

        <option value="Pemindahan" @selected(old('jenis', $mutation->jenis ?? '') == 'Pemindahan')>

            Pemindahan

        </option>

        <option value="Pemusnahan" @selected(old('jenis', $mutation->jenis ?? '') == 'Pemusnahan')>

            Pemusnahan

        </option>

    </select>
</div>

<div class="mb-3">
    <label class="form-label">Tanggal</label>

    <input type="date" name="tanggal" class="form-control"
        value="{{ old('tanggal', isset($mutation) && $mutation->tanggal ? $mutation->tanggal->format('Y-m-d') : '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Alasan</label>

    <textarea name="alasan" class="form-control" rows="4">{{ old('alasan', $mutation->alasan ?? '') }}</textarea>
</div>

<div class="mb-3">

    <label class="form-label">Dokumen</label>

    @if (isset($mutation) && $mutation->dokumen)
        <div class="mb-2">
            <a href="{{ asset('storage/' . $mutation->dokumen) }}" target="_blank">
                Lihat Dokumen Saat Ini
            </a>
        </div>
    @endif

    <input type="file" name="dokumen" class="form-control">

</div>

<div class="d-flex justify-content-end">

    <a href="{{ route('inventories.show', $inventory ?? $mutation->inventory) }}" class="btn btn-secondary me-2">

        Batal

    </a>

    <button class="btn btn-primary">

        Simpan

    </button>

</div>
