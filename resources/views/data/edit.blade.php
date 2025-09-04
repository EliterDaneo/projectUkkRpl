@extends('layouts.app', ['title' => 'Edit Kategori'])

@section('content')
    <a href="{{ route('supplier.index') }}" class="btn btn-primary mt-3"><i class="bi bi-arrow-left"></i> Kembali</a>

    <div class="card mt-3">
        <div class="card-header">
            Edit Supplier
        </div>
        <div class="card-body">
            <form action="{{ route('supplier.update', $category->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nama Kategori</label>
                    <input type="text" name="name" value="{{ $category->name }}"
                        class="form-control @error('name') is-invalid @enderror" id="exampleFormControlInput1"
                        placeholder="masukan kategori">
                    @error('name')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nama Kategori</label>
                    <input type="text" name="phone" value="{{ $category->phone }}"
                        class="form-control @error('phone') is-invalid @enderror" id="exampleFormControlInput1"
                        placeholder="masukan kategori">
                    @error('phone')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nama Kategori</label>
                    <input type="text" name="address" value="{{ $category->address }}"
                        class="form-control @error('address') is-invalid @enderror" id="exampleFormControlInput1"
                        placeholder="masukan kategori">
                    @error('address')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-floppy"></i> Perbaharui</button>
            </form>
        </div>
    </div>
@endsection
