<div class="row">

    <div class="col-md-12 mb-3">
        <label class="form-label">Nomor Inventaris</label>
        <input type="text" name="nomor_inventaris" class="form-control @error('nomor_inventaris') is-invalid @enderror"
            value="{{ old('nomor_inventaris', $inventory->nomor_inventaris ?? '') }}">

        @error('nomor_inventaris')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row">

    <div class="col-md-4 mb-3">

        <label class="form-label">Sarana Inventaris</label>

        <select name="master_sarana_inventaris_id"
            class="form-select @error('master_sarana_inventaris_id') is-invalid @enderror">

            <option value="">-- Pilih Sarana Inventaris --</option>

            @foreach ($masterSaranaInventaris as $item)
                <option value="{{ $item->id }}" @selected(old('master_sarana_inventaris_id', $inventory->master_sarana_inventaris_id ?? '') == $item->id)>

                    {{ $item->nama_sarana_inventaris }}

                </option>
            @endforeach

        </select>

        @error('master_sarana_inventaris_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">Lokasi</label>

        <select name="location_id" class="form-select @error('location_id') is-invalid @enderror">

            <option value="">-- Pilih Lokasi --</option>

            @foreach ($locations as $location)
                <option value="{{ $location->id }}" @selected(old('location_id', $inventory->location_id ?? '') == $location->id)>

                    {{ $location->nama_lokasi }}

                </option>
            @endforeach

        </select>

        @error('location_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="col-md-4 mb-3">

        <label class="form-label">
            Penanggung Jawab
        </label>


        @auth

            {{-- ADMIN --}}
            @if (auth()->user()->role === 'admin')
                <select name="responsible_id" class="form-select @error('responsible_id') is-invalid @enderror">

                    <option value="">
                        -- Pilih Penanggung Jawab --
                    </option>


                    @foreach ($responsibles as $responsible)
                        <option value="{{ $responsible->id }}" @selected(old('responsible_id', $inventory->responsible_id ?? '') == $responsible->id)>

                            {{ $responsible->nama_penanggung_jawab }}

                        </option>
                    @endforeach

                </select>


                {{-- USER PENANGGUNG JAWAB --}}
            @else
                <input type="text" class="form-control"
                    value="{{ auth()->user()->responsible?->nama_penanggung_jawab }}" readonly>


                <input type="hidden" name="responsible_id" value="{{ auth()->user()->responsible_id }}">
            @endif

        @endauth


        @error('responsible_id')
            <div class="invalid-feedback">

                {{ $message }}

            </div>
        @enderror

    </div>

    <div class="mb-3">
        <label class="form-label">User</label>
        <input type="text" name="user" class="form-control" value="{{ old('user', $inventory->user ?? '') }}">
    </div>

</div>

<div class="row">

    <div class="col-md-4 mb-3">

        <label class="form-label">Tanggal Perolehan</label>

        <input type="date" name="tanggal_perolehan"
            class="form-control @error('tanggal_perolehan') is-invalid @enderror"
            value="{{ old(
                'tanggal_perolehan',
                isset($inventory) && $inventory->tanggal_perolehan ? $inventory->tanggal_perolehan->format('Y-m-d') : '',
            ) }}">

        @error('tanggal_perolehan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <input type="text" name="status" class="form-control" value="{{ old('status', $inventory->status ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Foto</label>
    @if (!empty($inventory?->foto))
        <div class="mb-2">
            <img src="{{ asset('storage/' . $inventory->foto) }}" width="150" class="img-thumbnail">
        </div>
    @endif

    <input type="file" name="foto" class="form-control">
</div>

<div class="mb-3">

    <label class="form-label">Spesifikasi</label>

    <textarea name="spesifikasi" rows="4" class="form-control @error('spesifikasi') is-invalid @enderror">{{ old('spesifikasi', $inventory->spesifikasi ?? '') }}</textarea>

    @error('spesifikasi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

</div>

<div class="d-flex justify-content-end">

    <a href="{{ route('inventories.index') }}" class="btn btn-secondary me-2">
        Batal
    </a>

    <button class="btn btn-primary">
        Simpan
    </button>

</div>
