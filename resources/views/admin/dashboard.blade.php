@extends('layouts.app', ['title' => 'Halaman Kasir Interaktif'])

@section('content')

    <div class="alert alert-success mt-4" role="alert">
        Selamat datang : {{ Auth::user()->name }} role anda adalah
        <strong>{{ Auth::user()->role }}</strong>
    </div>

    @if (Auth::user()->role == 'admin')
        <h3>Statistik Transaksi Harian</h3>
        <div style="margin-bottom: 20px;">
            <label for="report-date">Pilih Tanggal:</label>
            <input type="date" id="report-date" value="{{ $todayDate }}" onchange="fetchDataAndRenderChart()">
        </div>

        <div style="width: 90%; max-width: 900px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
            <canvas id="dailyReportChart"></canvas>
        </div>
    @else
        <div class="container-fluid mt-4">
            {{-- Tombol Laporan --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="m-0">Sistem Kasir (POS)</h2>
                <a href="{{ route('kasir.report.pdf') }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-file-pdf"></i> Generate Laporan Penjualan
                </a>
            </div>
            <hr>

            <div class="row">
                {{-- Kolom Kategori (Kiri: col-md-3) --}}
                <div class="col-md-3">
                    <div class="card shadow p-3 mb-5 bg-light rounded">
                        <h4 class="mb-3">Daftar Kategori</h4>
                        <div class="list-group" id="category-list">
                            {{-- Kategori akan diambil dari $categories yang di-pass dari Controller --}}
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

                        {{-- Total, PPN, dan Pembayaran --}}
                        <div class="row">
                            <div class="col-md-6">
                                {{-- Input PPN yang Fleksibel --}}
                                <div class="mb-3">
                                    <label for="ppn-rate" class="form-label">Persentase PPN (%)</label>
                                    <input type="number" id="ppn-rate" class="form-control" value="11" min="0"
                                        max="100">
                                </div>

                                {{-- Tampilan Rincian Harga --}}
                                <h5 class="mt-3">Total Harga (Base): <span class="badge bg-secondary"
                                        id="total-price-base-display">Rp 0</span></h5>
                                <h5>PPN <span id="ppn-rate-display">11</span>%: <span class="badge bg-warning text-dark"
                                        id="ppn-amount-display">Rp 0</span></h5>
                                <hr>
                                <h5 class="text-success">TOTAL BAYAR: <span class="badge bg-success fs-5"
                                        id="total-price-final-display">Rp 0</span></h5>

                                <input type="hidden" id="total-price-base-value" value="0">
                                <input type="hidden" id="total-price-final-value" value="0">
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment-amount" class="form-label">Jumlah Pembayaran (Uang Tunai)</label>
                                    <input type="number" id="payment-amount" class="form-control" value="0"
                                        min="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kembalian</label>
                                    <p class="form-control-static fs-4 text-danger" id="change-amount-display">Rp 0</p>
                                </div>

                                <button class="btn btn-primary w-100" id="process-payment-btn" disabled>
                                    <i class="fas fa-money-bill-wave"></i> Proses Pembayaran
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

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

@push('js')
    {{-- Pastikan jQuery dan Bootstrap JS sudah dimuat di layout utama Anda --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

    <script>
        // Ambil data dari PHP/Laravel dan konversi ke JavaScript Object
        const initialChartData = @json($initialChartData);
        const apiRoute = "{{ route('daily.report.api') }}"; // Asumsi Anda membuat route bernama 'daily.report.api'
    </script>
    <script>
        let dailyChart = null;

        // Konfigurasi dasar Chart.js
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false, // Penting untuk kontrol ukuran div
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Jam Dalam Sehari'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Pendapatan (Rp)'
                    },
                    grid: {
                        drawOnChartArea: true
                    }
                },
                y2: {
                    type: 'linear',
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Transaksi'
                    },
                    grid: {
                        drawOnChartArea: false
                    },
                    min: 0
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Tren Belanja Harian',
                    font: {
                        size: 18
                    }
                },
                legend: {
                    position: 'top'
                }
            }
        };

        /**
         * Merender atau memperbarui grafik.
         */
        function renderChart(data) {
            const ctx = document.getElementById('dailyReportChart').getContext('2d');

            chartOptions.plugins.title.text = 'Tren Belanja Harian - ' + data.date;

            if (dailyChart) {
                dailyChart.data.labels = data.labels;
                dailyChart.data.datasets = data.datasets;
                dailyChart.options.plugins.title.text = 'Tren Belanja Harian - ' + data.date;
                dailyChart.update();
            } else {
                dailyChart = new Chart(ctx, {
                    type: 'bar', // Tipe default, di-override oleh datasets
                    data: {
                        labels: data.labels,
                        datasets: data.datasets
                    },
                    options: chartOptions
                });
            }
        }

        /**
         * Mengambil data dari API saat tanggal diubah.
         */
        async function fetchDataAndRenderChart() {
            const date = document.getElementById('report-date').value;

            try {
                const response = await fetch(`${apiRoute}?date=${date}`);
                if (!response.ok) {
                    throw new Error('Gagal mengambil data laporan');
                }
                const chartData = await response.json();
                renderChart(chartData);
            } catch (error) {
                console.error("Error fetching data:", error);
                // Anda bisa menampilkan pesan error yang lebih menarik di sini
            }
        }

        // 1. Inisialisasi grafik dengan data yang dibawa dari Controller
        document.addEventListener('DOMContentLoaded', () => {
            renderChart(initialChartData);
        });
    </script>
    <script>
        $(document).ready(function() {
            // Keranjang disimpan di LocalStorage
            let cart = JSON.parse(localStorage.getItem('kasir_cart')) || [];

            // Fungsi Pembantu: Format Angka ke Rupiah
            function formatRupiah(number) {
                // Hanya tampilkan 'Rp' jika nilainya valid
                if (typeof number !== 'number' || isNaN(number)) {
                    return 'Rp 0';
                }
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
            }

            // Fungsi Pembantu: Simpan Keranjang ke LocalStorage
            function saveCart() {
                localStorage.setItem('kasir_cart', JSON.stringify(cart));
            }

            // Fungsi Utama: Hitung Ulang Total Harga, PPN, dan Kembalian
            function calculateTotals() {
                let totalBase = 0;

                cart.forEach(item => {
                    item.subtotal = item.price * item.qty;
                    totalBase += item.subtotal;
                });

                const ppnRate = parseFloat($('#ppn-rate').val()) || 0;

                // Perhitungan PPN
                const ppnAmount = Math.round(totalBase * (ppnRate / 100));
                const totalFinal = totalBase + ppnAmount;

                const payment = parseInt($('#payment-amount').val()) || 0;
                const change = Math.max(0, payment - totalFinal);

                // Update Tampilan
                $('#total-price-base-value').val(totalBase);
                $('#total-price-final-value').val(totalFinal);

                $('#total-price-base-display').text(formatRupiah(totalBase));
                $('#ppn-rate-display').text(ppnRate);
                $('#ppn-amount-display').text(formatRupiah(ppnAmount));
                $('#total-price-final-display').text(formatRupiah(totalFinal));
                $('#change-amount-display').text(formatRupiah(change));

                // Enable/Disable tombol Pembayaran
                const isReadyToPay = totalFinal > 0 && payment >= totalFinal;
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

                // Tampilkan Popup segera setelah diklik
                $('#productModalLabel').text(`Memuat Produk Kategori: ${categoryName}...`);
                $productsContainer.html('<p class="text-center">Memuat...</p>');
                $('#productModal').modal('show');

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
                        subtotal: productPrice // Akan dihitung ulang di calculateTotals
                    });
                }

                renderCart();
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

            // 5. Ubah Jumlah Pembayaran & PPN Rate
            $('#payment-amount, #ppn-rate').on('input', calculateTotals);

            // 6. Proses Pembayaran (AJAX POST)
            $('#process-payment-btn').on('click', function() {
                const totalFinal = $('#total-price-final-value').val();
                const payment = $('#payment-amount').val();
                const ppnRate = $('#ppn-rate').val();
                const totalBase = $('#total-price-base-value').val();

                if (cart.length === 0 || payment < totalFinal) {
                    alert('Keranjang kosong atau jumlah pembayaran kurang!');
                    return;
                }

                const transactionData = {
                    _token: '{{ csrf_token() }}',
                    cart: cart.map(item => ({
                        product_id: item.product_id,
                        qty: item.qty,
                    })),
                    total_price_base: totalBase,
                    total_price_final: totalFinal,
                    ppn_percentage: ppnRate,
                    payment_amount: payment
                };

                $('#process-payment-btn').prop('disabled', true).text('Memproses...');

                $.ajax({
                    url: '{{ route('api.transaction.process') }}',
                    method: 'POST',
                    data: transactionData,
                    success: function(response) {
                        alert(`Transaksi Sukses! Kembalian: ${formatRupiah(response.change)}`);

                        // Reset
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
