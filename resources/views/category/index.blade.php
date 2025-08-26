@extends('layouts.app', ['title' => 'Data Kategori'])

@section('content')
    <h2>halo from category</h2>
    <a href="{{ route('category.create') }}" class="btn btn-primary"> Tambah Kategori</a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $d)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $d->name }}</td>
                    <td>
                        <a href="{{ route('category.edit', $d->id) }}"> Edit</a>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#hapus-{{ $d->id }}">
                            Hapus
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="hapus-{{ $d->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah anda yakin ingin menghapus data ini <strong>{{ $d->name }}</strong>
                                        </p>
                                    </div>
                                    <form action="{{ route('category.destroy', $d->id) }}" method="post">
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
                    <td colspan="3" class="text-center">Data Kosong</td>
                </tr>
            @endforelse

        </tbody>
    </table>
@endsection
