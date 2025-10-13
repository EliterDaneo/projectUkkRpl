<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shopping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    // API: Proses pembayaran dan simpan transaksi (AJAX POST)
    public function processTransaction(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
            'payment_amount' => 'required|integer|min:0',
            'total_price' => 'required|integer|min:0',
        ]);

        if ($request->payment_amount < $request->total_price) {
            return response()->json(['success' => false, 'message' => 'Jumlah pembayaran kurang dari total harga.'], 400);
        }

        $change = $request->payment_amount - $request->total_price;

        DB::beginTransaction();
        try {
            foreach ($request->cart as $item) {
                // Gunakan lockForUpdate untuk mencegah race condition pada stok
                $product = Product::lockForUpdate()->find($item['product_id']);

                if ($product->stock < $item['qty']) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Stok produk ' . $product->name . ' tidak mencukupi.'], 400);
                }

                // Subtotal dihitung sederhana di sini (diskon/ppn 0)
                $subtotal = $product->price * $item['qty'];

                Shopping::create([
                    'user_id' => Auth::id(), // Asumsi user telah login
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'discount' => 0,
                    'ppn' => 0,
                    'total_price' => $subtotal,
                    'total_back' => $change,
                ]);

                // Kurangi stok
                $product->stock -= $item['qty'];
                $product->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil!', 'change' => $change]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()], 500);
        }
    }
}
