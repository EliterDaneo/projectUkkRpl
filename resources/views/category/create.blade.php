@extends('layouts.app', ['title' => 'Tambah Kategori'])

@section('content')
    <a href="{{ route('category.index') }}" class="btn btn-primary mt-3"><i class="bi bi-arrow-left"></i> Kembali</a>

    <div class="card mt-3">
        <div class="card-header">
            Tambah Kategori
        </div>
        <div class="card-body">
            <form action="{{ route('category.store') }}" method="post">
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
                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-floppy"></i> Simpan</button>
            </form>
        </div>
    </div>
@endsection
