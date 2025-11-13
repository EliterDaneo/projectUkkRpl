<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shopping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ShoppingController extends Controller
{
    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->select('id', 'name', 'price', 'stock')
            ->get();

        return response()->json($products);
    }

    private function diskon($qty)
    {
        $diskon = 0;
        if($qty > 10){
            $diskon = 10000;
        }elseif ($qty > 5) {
            $diskon = 5000
        }else {
            $diskon = 0;
        }
    }

    // API: Proses pembayaran dan simpan transaksi (AJAX POST)
    public function processTransaction(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
            'total_price_base' => 'required|integer|min:0', // Total sebelum PPN
            'total_price_final' => 'required|integer|min:0', // Total setelah PPN
            'ppn_percentage' => 'required|integer|min:0', // PPN fleksibel dari JS
            'payment_amount' => 'required|integer|min:0',
        ]);

        if ($request->payment_amount < $request->total_price_final) {
            return response()->json(['success' => false, 'message' => 'Jumlah pembayaran kurang dari total harga final.'], 400);
        }

        $change = $request->payment_amount - $request->total_price_final;
        $ppnRate = $request->ppn_percentage; // Ambil PPN dari request (default 11)

        DB::beginTransaction();
        try {
            foreach ($request->cart as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product || $product->stock < $item['qty']) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Stok produk ' . ($product->name ?? 'ID ' . $item['product_id']) . ' tidak mencukupi.'], 400);
                }

                $basePrice = $product->price * $item['qty'];
                $ppnAmount = round($basePrice * ($ppnRate / 100));
                $finalPricePerItem = $basePrice + $ppnAmount;

                Shopping::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'discount' => 0,
                    'ppn' => $ppnRate, // Simpan persentase PPN
                    'total_price' => $finalPricePerItem, // Harga final per item termasuk PPN
                    'total_back' => $change,
                ]);

                $product->stock -= $item['qty'];
                $product->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil!', 'change' => $change]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan transaksi: Terjadi kesalahan server.'], 500);
        }
    }

    // Fungsi baru: Membuat dan Mendownload Laporan PDF
    public function generateReportPdf(Request $request)
    {
        // PENTING: Instal paket dompdf terlebih dahulu:
        // composer require barryvdh/laravel-dompdf

        // Ambil data transaksi dari database
        $transactions = Shopping::with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'title' => 'Laporan Penjualan Kasir',
            'date' => date('d/m/Y'),
            'transactions' => $transactions,
            'total_omzet' => $transactions->sum('total_price'),
        ];

        // Pastikan Anda membuat view 'cashier.report_pdf'
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('laporan.pdf', $data);

        return $pdf->download('laporan_penjualan_' . date('Ymd_His') . '.pdf');
    }
}
