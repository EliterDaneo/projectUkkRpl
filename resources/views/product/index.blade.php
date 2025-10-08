@extends('layouts.app', ['title' => 'Data Produk'])

@section('content')
    {{-- 1. Notifikasi/Alerts yang Disederhanakan --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('update'))
        <div class="alert alert-primary alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i> {{ session('update') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (session('delete'))
        {{-- Jika ingin menggunakan flash session 'delete' --}}
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-x-octagon-fill me-2"></i> {{ session('delete') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tombol Tambah Produk --}}
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('product.create') }}" class="btn btn-primary mt-3 mb-3 shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk
        </a>
        {{-- Jika ada fitur lain seperti Search/Export bisa ditambahkan di sini --}}
    </div>

    <div class="card shadow-lg mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Data Produk</h5>
        </div>

        <div class="card-body">
            {{-- Tambahkan table-responsive untuk tampilan di perangkat mobile --}}
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center">No</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Supplier</th>
                            <th scope="col">Stok</th>
                            <th scope="col">Harga Satuan</th>
                            <th scope="col" width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $d)
                            <tr>
                                <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                                <td>
                                    @if ($d->image)
                                        <img src="{{ asset('storage/' . $d->image) }}" alt="foto produk"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <i class="bi bi-image-fill text-muted" style="font-size: 2rem;"></i>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $d->name }}</strong>
                                    <p class="text-muted small mb-0 mt-1" style="max-height: 40px; overflow: hidden;">
                                        {!! Str::limit(strip_tags($d->description), 50, '...') !!}
                                    </p>
                                </td>
                                <td>{{ $d->category->name ?? '-' }}</td>
                                <td>{{ $d->supplier->name ?? '-' }}</td>
                                <td>
                                    <span
                                        class="badge rounded-pill bg-{{ $d->stock > 10 ? 'success' : ($d->stock > 0 ? 'warning' : 'danger') }}">
                                        {{ $d->stock }} Pcs
                                    </span>
                                </td>
                                <td>Rp. **{{ number_format($d->price, 0, ',', '.') }}**</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        {{-- Tombol Edit --}}
                                        <a class="btn btn-sm btn-outline-warning"
                                            href="{{ route('product.edit', $d->slug) }}" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

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
                                                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah anda yakin ingin menghapus data ini
                                                            <strong>{{ $d->name }}</strong>
                                                        </p>
                                                    </div>
                                                    <form action="{{ route('product.destroy', $d->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-outline-danger"><i
                                                                    class="bi bi-trash"></i> Hapus</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-box-seam me-2"></i> Belum ada data produk yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginasi --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
