@extends('layouts.app', ['title' => 'Data Pengguna'])

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
                    Tambah Pengguna
                </div>
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Nama Pengguna</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="exampleFormControlInput1" placeholder="masukan kategori">
                            @error('name')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="exampleFormControlInput1" placeholder="masukan kategori">
                            @error('email')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Role</label>
                            <select class="form-select" aria-label="Default select example" name="role">
                                <option selected>Pilih Role Yang Diinginkan</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            @error('role')
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" id="exampleFormControlInput1"
                                placeholder="masukan kategori">
                            @error('password')
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
                    Data Pengguna
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col" width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $d)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $d->name }}</td>
                                    <td>{{ $d->email }}</td>
                                    <td>
                                        @if ($d->role == 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @else
                                            <span class="badge bg-success">User / Kasir</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#edit-{{ $d->id }}">
                                            <i class="bi bi-pencil"></i>
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
                                                        <form action="{{ route('user.update', $d->id) }}" method="post">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="mb-3">
                                                                <label for="nameInput" class="form-label">Nama
                                                                    Pengguna</label>
                                                                <input type="text" name="name"
                                                                    class="form-control @error('name') is-invalid @enderror"
                                                                    id="nameInput" placeholder="masukan nama pengguna"
                                                                    value="{{ old('name', $d->name) }}">
                                                                @error('name')
                                                                    <div id="nameFeedback" class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="emailInput" class="form-label">Email</label>
                                                                <input type="email" name="email"
                                                                    class="form-control @error('email') is-invalid @enderror"
                                                                    id="emailInput" placeholder="masukan email"
                                                                    value="{{ old('email', $d->email) }}">
                                                                @error('email')
                                                                    <div id="emailFeedback" class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="roleSelect" class="form-label">Role</label>
                                                                <select
                                                                    class="form-select @error('role') is-invalid @enderror"
                                                                    aria-label="Default select example" name="role"
                                                                    id="roleSelect">
                                                                    <option value=""
                                                                        {{ old('role', $d->role) == '' ? 'selected' : '' }}
                                                                        disabled>Pilih Role Yang Diinginkan</option>
                                                                    <option value="admin"
                                                                        {{ old('role', $d->role) == 'admin' ? 'selected' : '' }}>
                                                                        Admin</option>
                                                                    <option value="user"
                                                                        {{ old('role', $d->role) == 'user' ? 'selected' : '' }}>
                                                                        User</option>
                                                                </select>
                                                                @error('role')
                                                                    <div id="roleFeedback" class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="passwordInput"
                                                                    class="form-label">Password</label>
                                                                <input type="password" name="password"
                                                                    class="form-control @error('password') is-invalid @enderror"
                                                                    id="passwordInput"
                                                                    placeholder="Kosongkan jika tidak ingin mengubah password">
                                                                @error('password')
                                                                    <div id="passwordFeedback" class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
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
                                                    <form action="{{ route('user.destroy', $d->id) }}" method="post">
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
