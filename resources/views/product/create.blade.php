@extends('layouts.app', ['title' => 'Tambah Produk'])

@section('content')
    <div class="card mt-3 shadow-sm">
        <div class="card-header">
            <i class="bi bi-bag-plus"></i>
            Tambah Produk
        </div>
        <div class="card-body">
            <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Pilih Kategori</label>
                            <select class="form-select  @error('a') is-invalid @enderror" name="a"
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
                            <select class="form-select @error('b') is-invalid @enderror" name="b"
                                aria-label="Default select example">
                                <option selected>Pilih Suplier</option>
                                @forelse ($suppliers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @empty
                                    <option disabled>Belum Ada Kategori</option>
                                @endforelse
                            </select>
                            @error('b')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Nama Produk</label>
                            <input type="text" name="c" class="form-control @error('c') is-invalid @enderror"
                                id="inputName" value="{{ old('c') }}">
                        </div>
                        @error('c')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Foto Produk</label>
                            <input type="file" name="d" class="form-control @error('d') is-invalid @enderror"
                                id="inputName" value="{{ old('d') }}">
                        </div>
                        @error('d')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Price</label>
                            <input type="number" name="e" class="form-control @error('e') is-invalid @enderror"
                                id="inputName" value="{{ old('e') }}">
                        </div>
                        @error('e')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Stok</label>
                            <input type="number" name="f" class="form-control @error('f') is-invalid @enderror"
                                id="inputName" value="{{ old('f') }}">
                        </div>
                        @error('f')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Diskripsi</label>
                    <textarea name="g" class="form-control @error('g') is-invalid @enderror" id="exampleFormControlTextarea1"
                        rows="3">{{ old('g') }}</textarea>
                    @error('g')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-floppy"></i> Simpan Produk</button>
            </form>
        </div>
    </div>
@endsection
