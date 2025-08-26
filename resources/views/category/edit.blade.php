@extends('layouts.app', ['title' => 'Edit Kategori'])

@section('content')
    <h2>Edit Kategori</h2>
    <a href="{{ route('category.index') }}" class="btn btn-primary"> Kembali</a>

    <form action="{{ route('category.update', $category->id) }}" method="post">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Nama Kategori</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control"
                id="exampleFormControlInput1" placeholder="masukan kategori">
        </div>
        <button type="submit" class="btn btn-outline-primary">Perbaharui</button>
    </form>
@endsection
