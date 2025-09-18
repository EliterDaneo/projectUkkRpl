@extends('layouts.app', ['title' => 'Tambah Produk'])

@section('content')
    <div class="card mt-3 shadow-sm">
        <div class="card-header">
            <i class="bi bi-bag-plus"></i>
            Tambah Produk
        </div>
        <div class="card-body">
            <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Pilih Kategori</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Pilih Kategori</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Pilih Suplier</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Pilih Suplier</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="inputName">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Foto Produk</label>
                            <input type="file" class="form-control" id="inputName">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Price</label>
                            <input type="number" class="form-control" id="inputName">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="inputName">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Diskripsi</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-floppy"></i> Simpan Produk</button>
            </form>
        </div>
    </div>
@endsection
