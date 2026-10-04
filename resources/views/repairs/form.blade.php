{{-- ========================================
    TANGGAL PENGAJUAN
======================================== --}}
<div class="mb-3">

    <label class="form-label">
        Tanggal Pengajuan
    </label>

    <input type="date" name="tanggal_pengajuan" class="form-control @error('tanggal_pengajuan') is-invalid @enderror"
        value="{{ old(
            'tanggal_pengajuan',
            isset($repair) && $repair->tanggal_pengajuan ? $repair->tanggal_pengajuan->format('Y-m-d') : '',
        ) }}"
        required>

    @error('tanggal_pengajuan')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>



{{-- ========================================
    TANGGAL TINDAK LANJUT
======================================== --}}
<div class="mb-3">

    <label class="form-label">
        Tanggal Tindak Lanjut
    </label>

    <input type="date" name="tanggal_tindak_lanjut"
        class="form-control @error('tanggal_tindak_lanjut') is-invalid @enderror"
        value="{{ old(
            'tanggal_tindak_lanjut',
            isset($repair) && $repair->tanggal_tindak_lanjut ? $repair->tanggal_tindak_lanjut->format('Y-m-d') : '',
        ) }}">

    @error('tanggal_tindak_lanjut')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


{{-- ========================================
    JENIS PERBAIKAN
======================================== --}}
<div class="mb-3">

    <label class="form-label">
        Jenis Perbaikan
    </label>

    <input type="text" name="jenis_perbaikan" class="form-control @error('jenis_perbaikan') is-invalid @enderror"
        value="{{ old('jenis_perbaikan', $repair->jenis_perbaikan ?? '') }}" required>

    @error('jenis_perbaikan')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>


{{-- ========================================
    PELAKSANA
======================================== --}}
<div class="mb-3">

    <label class="form-label">
        Pelaksana
    </label>

    <input type="text" name="pelaksana" class="form-control @error('pelaksana') is-invalid @enderror"
        value="{{ old('pelaksana', $repair->pelaksana ?? '') }}" required>

    @error('pelaksana')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

</div>



{{-- ========================================
    DOKUMEN
======================================== --}}
<div class="mb-3">

    <label class="form-label">
        Dokumen
    </label>

    <input type="file" name="dokumen" class="form-control @error('dokumen') is-invalid @enderror"
        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

    @error('dokumen')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror


    {{-- TAMPILKAN DOKUMEN LAMA SAAT EDIT --}}
    @if (isset($repair) && $repair->dokumen)
        <div class="mt-2">

            <small class="text-secondary">
                Dokumen saat ini:
            </small>

            <a href="{{ asset('storage/' . $repair->dokumen) }}" target="_blank"
                class="btn btn-sm btn-outline-secondary ms-2">

                <i class="ti ti-file"></i>

                Lihat Dokumen

            </a>

        </div>
    @endif

</div>


{{-- ========================================
    BUTTON
======================================== --}}
<div class="d-flex justify-content-end gap-2">

    <a href="{{ route('inventories.show', $inventory ?? $repair->inventory) }}" class="btn btn-secondary">

        <i class="ti ti-x me-1"></i>

        Batal

    </a>


    <button type="submit" class="btn btn-primary">

        <i class="ti ti-device-floppy me-1"></i>

        Simpan

    </button>

</div>
