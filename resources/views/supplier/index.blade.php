@extends('layouts.app', ['title' => 'Data Supplier'])

@section('content')
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('update'))
        <div class="alert alert-primary" role="alert">
            {{ session('update') }}
        </div>
    @elseif (session('delete'))
        <div class="alert alert-danger" role="alert">
            {{ session('delete') }}
        </div>
    @endif
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Tambah Supplier
                </div>
                <div class="card-body">
                    <form action="{{ route('supplier.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nama Supplier</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="exampleFormControlInput1" placeholder="masukan kategori">
                            @error('name')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">No Supplier</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                id="exampleFormControlInput1" placeholder="masukan kategori">
                            @error('phone')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Alamat Supplier</label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
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
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Data Supplier
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">No Hp</th>
                                <th scope="col">Alamat</th>
                                <th scope="col" width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $d)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $d->name }}</td>
                                    <td>{{ $d->phone }}</td>
                                    <td>{{ $d->address }}</td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#edit-{{ $d->id }}">
                                            <i class="bi bi-trash"></i>
                                            Edit
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="edit-{{ $d->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Data
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('supplier.update', $d->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label for="exampleFormControlInput1"
                                                                    class="form-label">Nama Supplier</label>
                                                                <input type="text" name="name"
                                                                    value="{{ $d->name }}"
                                                                    class="form-control @error('name') is-invalid @enderror"
                                                                    id="exampleFormControlInput1"
                                                                    placeholder="masukan kategori">
                                                                @error('name')
                                                                    <div id="validationServer03Feedback"
                                                                        class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="exampleFormControlInput1" class="form-label">No
                                                                    Hp Supplier</label>
                                                                <input type="text" name="phone"
                                                                    value="{{ $d->phone }}"
                                                                    class="form-control @error('phone') is-invalid @enderror"
                                                                    id="exampleFormControlInput1"
                                                                    placeholder="masukan kategori">
                                                                @error('phone')
                                                                    <div id="validationServer03Feedback"
                                                                        class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="exampleFormControlInput1"
                                                                    class="form-label">Alamat Supplier</label>
                                                                <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ $d->address }}</textarea>
                                                                @error('address')
                                                                    <div id="validationServer03Feedback"
                                                                        class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Perbaharui</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#hapus-{{ $d->id }}">
                                            <i class="bi bi-trash"></i>
                                            Hapus
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="hapus-{{ $d->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah anda yakin ingin menghapus data ini
                                                            <strong>{{ $d->name }}</strong>
                                                        </p>
                                                    </div>
                                                    <form action="{{ route('supplier.destroy', $d->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Data Kosong</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
