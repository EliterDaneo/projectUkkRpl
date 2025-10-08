@extends('layouts.app', ['title' => 'Edit Produk: ' . $product->name])

@section('content')
    <div class="card mt-4 shadow-lg">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Edit Produk: {{ $product->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('product.update', $product->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Pilih Kategori <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('a') is-invalid @enderror" name="a" id="category_id">
                            <option value="">-- Pilih Kategori --</option>
                            @forelse ($categories as $c)
                                <option value="{{ $c->id }}"
                                    {{ old('a', $product->category_id) == $c->id ? 'selected' : '' }}>
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
                                <option value="{{ $s->id }}"
                                    {{ old('b', $product->supplier_id) == $s->id ? 'selected' : '' }}>
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
                            id="name" value="{{ old('c', $product->name) }}" placeholder="Nama Produk">
                        @error('c')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="image" class="form-label">Ganti Foto Produk</label>
                        @if ($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Foto Lama"
                                    style="max-width: 100px; height: auto; border-radius: 5px; border: 1px solid #ccc;">
                                <small class="text-muted ms-2">Foto Lama</small>
                            </div>
                        @endif
                        <input type="file" name="d" class="form-control @error('d') is-invalid @enderror"
                            id="image">
                        <div class="form-text">Kosongkan jika tidak ingin mengganti foto. Maksimal 2MB.</div>
                        @error('d')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Harga (Price) <span class="text-danger">*</span></label>
                        <input type="number" name="e" class="form-control @error('e') is-invalid @enderror"
                            id="price" value="{{ old('e', $product->price) }}" min="1"
                            placeholder="Harga Produk">
                        @error('e')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="f" class="form-control @error('f') is-invalid @enderror"
                            id="stock" value="{{ old('f', $product->stock) }}" min="0"
                            placeholder="Stok Produk">
                        @error('f')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                    <textarea name="g" class="form-control @error('g') is-invalid @enderror" id="description" rows="4"
                        placeholder="Masukkan deskripsi produk">{{ old('g', $product->description) }}</textarea>
                    @error('g')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <button type="submit" class="btn btn-warning text-dark me-2"><i class="bi bi-save me-1"></i> Update
                    Produk</button>
                <a href="{{ route('product.index') }}" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i>
                    Batal</a>
            </form>
        </div>
    </div>
@endsection
