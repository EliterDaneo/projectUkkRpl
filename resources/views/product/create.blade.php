@extends('layouts.app', ['title' => 'Tambah Produk'])

@section('content')
    <div class="card mt-4 shadow-lg">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-bag-plus-fill me-2"></i> Tambah Produk Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Pilih Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('a') is-invalid @enderror" name="a" id="category_id">
                            <option value="">-- Pilih Kategori --</option>
                            @forelse ($categories as $c)
                                <option value="{{ $c->id }}" {{ old('a') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @empty
                                <option disabled>Belum Ada Kategori</option>
                            @endforelse
                        </select>
                        @error('a')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="supplier_id" class="form-label">Pilih Suplier <span class="text-danger">*</span></label>
                        <select class="form-select @error('b') is-invalid @enderror" name="b" id="supplier_id">
                            <option value="">-- Pilih Suplier --</option>
                            @forelse ($suppliers as $s)
                                <option value="{{ $s->id }}" {{ old('b') == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @empty
                                <option disabled>Belum Ada Suplier</option>
                            @endforelse
                        </select>
                        @error('b')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" name="c" class="form-control @error('c') is-invalid @enderror"
                            id="name" value="{{ old('c') }}" placeholder="Contoh: Baju Kaos">
                        @error('c')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Foto Produk <span class="text-danger">*</span></label>
                        <input type="file" name="d" class="form-control @error('d') is-invalid @enderror"
                            id="image">
                        <div class="form-text">Maksimal 2MB. Format: JPEG, PNG, JPG, GIF, SVG.</div>
                        @error('d')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Harga (Price) <span class="text-danger">*</span></label>
                        <input type="number" name="e" class="form-control @error('e') is-invalid @enderror"
                            id="price" value="{{ old('e') }}" min="3" placeholder="Contoh: 50000">
                        @error('e')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="f" class="form-control @error('f') is-invalid @enderror"
                            id="stock" value="{{ old('f') }}" min="3" placeholder="Contoh: 100">
                        @error('f')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="g" class="form-control @error('g') is-invalid @enderror" id="description"
                        rows="4" placeholder="Masukkan deskripsi produk minimal 3 karakter">{{ old('g') }}</textarea>
                    @error('g')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-save me-1"></i> Simpan Produk</button>
                <a href="{{ route('product.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i> Batal</a>
            </form>
        </div>
    </div>
@endsection