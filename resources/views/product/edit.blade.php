@extends('layouts.app', ['title' => 'Edit Produk'])

@section('content')
    <div class="card mt-3 shadow-sm">
        <div class="card-header">
            <i class="bi bi-bag-plus"></i>
            Edit Produk
        </div>
        <div class="card-body">
            <form action="{{ route('product.update', $product->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Pilih Kategori</label>
                            <select class="form-select @error('a') is-invalid @enderror" name="a"
                                aria-label="Default select example">
                                <option selected>Pilih Kategori</option>
                                @forelse ($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @empty
                                    <option disabled>Belum Ada Kategori</option>
                                @endforelse
                            </select>
                            @error('a')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Pilih Suplier</label>
                            <select class="form-select" name="b" aria-label="Default select example">
                                <option selected>Pilih Suplier</option>
                                @forelse ($suppliers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @empty
                                    <option disabled>Belum Ada Kategori</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Nama Produk</label>
                            <input type="text" name="c" class="form-control" id="inputName"
                                value="{{ $product->name }}">
                            {{-- value = memberikan sebuah nilai --}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Foto Produk</label>
                            <input type="file" name="d" class="form-control" id="inputName">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Price</label>
                            <input type="number" name="e" class="form-control" id="inputName"
                                value="{{ $product->price }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Stok</label>
                            <input type="number" name="f" class="form-control" id="inputName"
                                value="{{ $product->stock }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Diskripsi</label>
                    <textarea name="g" class="form-control" id="exampleFormControlTextarea1" rows="3">{{ $product->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-floppy"></i> Simpan Produk</button>
            </form>
        </div>
    </div>
@endsection
