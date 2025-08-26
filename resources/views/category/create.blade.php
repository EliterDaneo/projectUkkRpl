@extends('layouts.app', ['title' => 'Tambah Kategori'])

@section('content')
    <h2>Tambah Kategori</h2>
    <a href="{{ route('category.index') }}" class="btn btn-primary"> Kembali</a>

    <form action="{{ route('category.store') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Nama Kategori</label>
            <input type="text" name="name" class="form-control" id="exampleFormControlInput1"
                placeholder="masukan kategori">
        </div>
        <button type="submit" class="btn btn-outline-primary">Simpan</button>
    </form>
@endsection
