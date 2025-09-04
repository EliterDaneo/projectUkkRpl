@extends('layouts.app', ['title' => 'Tambah Kategori'])

@section('content')
    <a href="{{ route('supplier.index') }}" class="btn btn-primary mt-3"><i class="bi bi-arrow-left"></i> Kembali</a>

    <div class="card mt-3">
        <div class="card-header">
            Tambah Supplier
        </div>
        <div class="card-body">
            <form action="{{ route('supplier.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        id="exampleFormControlInput1" placeholder="masukan kategori">
                    @error('name')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nama Kategori</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                        id="exampleFormControlInput1" placeholder="masukan kategori">
                    @error('phone')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nama Kategori</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                        id="exampleFormControlInput1" placeholder="masukan kategori">
                    @error('address')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-floppy"></i> Simpan</button>
            </form>
        </div>
    </div>
@endsection
