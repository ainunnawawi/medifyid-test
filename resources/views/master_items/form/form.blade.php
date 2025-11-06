<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
        <div class="form-group mb-3">
            <label>Kode Barang</label>
            <input type="text" class="form-control" name="kode_barang" readonly value="{{ $item->kode ?? '' }}">
        </div>
    @endif

    <div class="form-group mb-3">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required value="{{ $item->nama ?? '' }}">
    </div>

    <div class="form-group mb-3">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required value="{{ $item->harga_beli ?? '' }}">
    </div>

    <div class="form-group mb-3">
        <label>Laba (%)</label>
        <input type="number" class="form-control" name="laba" required value="{{ $item->laba ?? '' }}">
    </div>

    {{-- Kategori --}}
    @php
        $selectedKategori = isset($item) ? $item->kategoriItems->pluck('id')->toArray() : [];
    @endphp
    <div class="form-group mb-3">
        <label>Kategori</label>
        <select name="kategori[]" class="form-control" multiple required>
            @foreach ($kategori as $kat)
                <option value="{{ $kat->id }}" {{ in_array($kat->id, $selectedKategori) ? 'selected' : '' }}>
                    {{ $kat->kode }} - {{ $kat->nama }}
                </option>
            @endforeach
        </select>
        <small class="text-muted">Gunakan Ctrl / Cmd untuk memilih lebih dari satu</small>
    </div>

    {{-- Supplier --}}
    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group mb-3">
        <label>Supplier</label>
        <select class="form-control" name="supplier" required>
            <option value="">-- Pilih --</option>
            @foreach (['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'] as $sup)
                <option {{ $selected == $sup ? 'selected' : '' }}>{{ $sup }}</option>
            @endforeach
        </select>
    </div>

    {{-- Jenis --}}
    @php $selectedJenis = $item->jenis ?? ''; @endphp
    <div class="form-group mb-3">
        <label>Jenis</label>
        <select class="form-control" name="jenis" required>
            <option value="">-- Pilih --</option>
            @foreach (['Obat','Alkes','Matkes','Umum','ATK'] as $jenis)
                <option {{ $selectedJenis == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
            @endforeach
        </select>
    </div>

    {{-- 🟩 Upload Gambar --}}
    <div class="form-group mb-3">
        <label>Gambar Produk</label>
        <input type="file" class="form-control" name="foto" accept="image/*" onchange="previewImage(event)">
        <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>

        @if(!empty($item->foto))
            <div class="mt-2">
                <img src="{{ asset('storage/master_items/'.$item->foto) }}" class="img-thumbnail" width="150">
            </div>
        @endif

        <div id="preview" class="mt-2"></div>
    </div>

    <button class="btn btn-primary mt-3 w-100">
        <i class="fa fa-save me-1"></i> Simpan
    </button>
</form>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    const file = event.target.files[0];
    if (file) {
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.classList.add('img-thumbnail', 'mt-2');
        img.style.width = '150px';
        preview.appendChild(img);
    }
}
</script>
