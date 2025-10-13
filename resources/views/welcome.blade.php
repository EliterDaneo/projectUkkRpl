@extends('layouts.app', ['title' => 'Halaman Kasir'])

@section('content')
    {{-- Container utama kasir --}}
    <div class="container-fluid mt-4">
        <div class="row">
            {{-- Kolom Kategori (Kiri: col-md-3) --}}
            <div class="col-md-3">
                <div class="card shadow p-3 mb-5 bg-light rounded">
                    <h4 class="mb-3">Daftar Kategori</h4>
                    <div class="list-group" id="category-list">
                        @foreach ($categories as $category)
                            <button type="button" class="list-group-item list-group-item-action category-item"
                                data-category-id="{{ $category->id }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Kolom Transaksi (Kanan: col-md-9) --}}
            <div class="col-md-9">
                <div class="card shadow p-3">
                    <h4 class="mb-4">Keranjang Transaksi</h4>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th style="width: 120px;">Qty</th>
                                    <th>Harga Satuan</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cart-body">
                                {{-- Item keranjang akan di-inject oleh JavaScript --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Total dan Pembayaran --}}
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Total Belanja: <span class="badge bg-primary fs-5" id="total-price-display">Rp 0</span></h5>
                            <input type="hidden" id="total-price-value" value="0">
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment-amount" class="form-label">Jumlah Pembayaran (Tunai)</label>
                                <input type="number" id="payment-amount" class="form-control" value="0"
                                    min="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kembalian</label>
                                <p class="form-control-static fs-4 text-success" id="change-amount-display">Rp 0</p>
                            </div>

                            <button class="btn btn-success w-100" id="process-payment-btn" disabled>
                                Proses Pembayaran
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal (Popup) untuk Daftar Produk --}}
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Pilih Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row" id="products-container">
                        {{-- Daftar produk diisi oleh AJAX/JS --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Sertakan jQuery dan Bootstrap JS di layout utama atau di sini --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Keranjang disimpan di LocalStorage
            let cart = JSON.parse(localStorage.getItem('kasir_cart')) || [];

            // Fungsi Pembantu: Format Angka ke Rupiah
            function formatRupiah(number) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            // Fungsi Pembantu: Simpan Keranjang ke LocalStorage
            function saveCart() {
                localStorage.setItem('kasir_cart', JSON.stringify(cart));
            }

            // Fungsi Utama: Hitung Ulang Total Harga dan Kembalian
            function calculateTotals() {
                let total = 0;
                cart.forEach(item => {
                    item.subtotal = item.price * item.qty;
                    total += item.subtotal;
                });

                const payment = parseInt($('#payment-amount').val()) || 0;
                const change = Math.max(0, payment - total);

                $('#total-price-value').val(total);
                $('#total-price-display').text(formatRupiah(total));
                $('#change-amount-display').text(formatRupiah(change));

                // Enable/Disable tombol Pembayaran
                const isReadyToPay = total > 0 && payment >= total;
                $('#process-payment-btn').prop('disabled', !isReadyToPay);

                saveCart();
            }

            // Fungsi Utama: Render Ulang Tabel Keranjang
            function renderCart() {
                const $cartBody = $('#cart-body');
                $cartBody.empty();

                if (cart.length === 0) {
                    $cartBody.html(
                    '<tr><td colspan="6" class="text-center text-muted">Keranjang kosong.</td></tr>');
                    calculateTotals();
                    return;
                }

                cart.forEach((item, index) => {
                    const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.name}</td>
                    <td>
                        <input type="number" min="1" class="form-control form-control-sm text-center cart-qty-input"
                            data-index="${index}" value="${item.qty}">
                    </td>
                    <td>${formatRupiah(item.price)}</td>
                    <td>${formatRupiah(item.subtotal)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger remove-item-btn" data-index="${index}">Hapus</button>
                    </td>
                </tr>
            `;
                    $cartBody.append(row);
                });

                calculateTotals();
            }

            // === EVENT HANDLERS ===

            // 1. Klik Kategori: Ambil produk via AJAX dan tampilkan Modal
            $('#category-list').on('click', '.category-item', function() {
                const categoryId = $(this).data('category-id');
                const categoryName = $(this).text().trim();
                const $productsContainer = $('#products-container');

                $('#productModalLabel').text(`Memuat Produk Kategori: ${categoryName}...`);
                $productsContainer.html('<p class="text-center">Memuat...</p>');
                $('#productModal').modal('show'); // Tampilkan Popup

                $.ajax({
                    url: `/api/products/${categoryId}`,
                    method: 'GET',
                    success: function(products) {
                        let html = '';
                        if (products.length === 0) {
                            html =
                                '<p class="text-center text-muted">Tidak ada produk tersedia dalam kategori ini.</p>';
                        } else {
                            products.forEach(product => {
                                html += `
                            <div class="col-md-4 mb-3">
                                <div class="card h-100 shadow-sm border-primary">
                                    <div class="card-body">
                                        <h5 class="card-title">${product.name}</h5>
                                        <p class="card-text text-muted">Stok: ${product.stock}</p>
                                        <p class="card-text fs-5 text-danger">${formatRupiah(product.price)}</p>
                                        <button class="btn btn-sm btn-primary w-100 add-to-cart-btn"
                                            data-id="${product.id}"
                                            data-name="${product.name}"
                                            data-price="${product.price}"
                                            ${product.stock === 0 ? 'disabled' : ''}>
                                            Pilih
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                            });
                        }
                        $('#productModalLabel').text(`Pilih Produk Kategori: ${categoryName}`);
                        $productsContainer.html(html);
                    },
                    error: function() {
                        $('#productModalLabel').text('Gagal memuat produk.');
                        $productsContainer.html(
                            '<p class="text-center text-danger">Terjadi kesalahan.</p>');
                    }
                });
            });

            // 2. Klik Pilih Produk (di Modal): Tambah ke Keranjang
            $('#products-container').on('click', '.add-to-cart-btn', function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productPrice = $(this).data('price');

                const existingItem = cart.find(item => item.product_id === productId);

                if (existingItem) {
                    existingItem.qty++;
                } else {
                    cart.push({
                        product_id: productId,
                        name: productName,
                        price: productPrice,
                        qty: 1,
                        subtotal: productPrice
                    });
                }

                renderCart(); // Update tampilan transaksi
                $('#productModal').modal('hide'); // Tutup Popup
            });

            // 3. Ubah Kuantitas di Keranjang
            $('#cart-body').on('change', '.cart-qty-input', function() {
                const index = $(this).data('index');
                let newQty = parseInt($(this).val());

                if (isNaN(newQty) || newQty < 1) {
                    newQty = 1;
                    $(this).val(newQty);
                }

                cart[index].qty = newQty;
                renderCart();
            });

            // 4. Hapus Item dari Keranjang
            $('#cart-body').on('click', '.remove-item-btn', function() {
                const index = $(this).data('index');
                cart.splice(index, 1);
                renderCart();
            });

            // 5. Ubah Jumlah Pembayaran
            $('#payment-amount').on('input', calculateTotals);

            // 6. Proses Pembayaran (AJAX POST)
            $('#process-payment-btn').on('click', function() {
                const total = $('#total-price-value').val();
                const payment = $('#payment-amount').val();

                if (cart.length === 0 || payment < total) {
                    alert('Keranjang kosong atau jumlah pembayaran kurang!');
                    return;
                }

                const transactionData = {
                    _token: '{{ csrf_token() }}',
                    cart: cart.map(item => ({
                        product_id: item.product_id,
                        qty: item.qty,
                    })),
                    total_price: total,
                    payment_amount: payment
                };

                $('#process-payment-btn').prop('disabled', true).text('Memproses...');

                $.ajax({
                    url: '{{ route('api.transaction.process') }}',
                    method: 'POST',
                    data: transactionData,
                    success: function(response) {
                        alert(`Transaksi Sukses! Kembalian: ${formatRupiah(response.change)}`);

                        // Reset keranjang dan UI
                        cart = [];
                        localStorage.removeItem('kasir_cart');
                        $('#payment-amount').val(0);
                        renderCart();
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr
                            .responseJSON.message :
                            'Terjadi kesalahan saat memproses transaksi.';
                        alert(`Gagal Transaksi: ${errorMessage}`);
                    },
                    complete: function() {
                        $('#process-payment-btn').text('Proses Pembayaran');
                        calculateTotals();
                    }
                });
            });

            // Inisialisasi: Muat keranjang saat halaman dimuat
            renderCart();
        });
    </script>
@endpush
