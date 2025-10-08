<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Product::with(['category', 'user', 'supplier'])->paginate(10);
        // dd($data);
        return view("product.index", compact("data"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view("product.create", compact("categories", "suppliers"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'a' => 'required|numeric|exists:categories,id', // category_id
            'b' => 'required|numeric|exists:suppliers,id', // supplier_id
            'c' => 'required|string|min:3|max:255',        // name
            'd' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // image
            'e' => 'required|numeric|min:1',              // price (min 1, assuming price > 0)
            'f' => 'required|numeric|min:0',              // stock (min 0)
            'g' => 'required|string|min:3',               // description
        ], [
            'a.required' => 'Wajib memilih Kategori.',
            'a.numeric'  => 'Pilihan Kategori tidak valid.',
            'a.exists'   => 'Data Kategori tidak ditemukan.',
            // Add more specific Indonesian messages for other fields for a better UX
            'b.required' => 'Wajib memilih Suplier.',
            'c.required' => 'Nama Produk wajib diisi.',
            'e.min'      => 'Harga harus minimal 1.',
            // ... and so on for all fields
        ]);

        $imagePath = null;

        // 2. Handle file upload
        if ($request->hasFile('d')) {
            $image = $request->file('d');
            // 'public' is the disk name, e.g., defined in config/filesystems.php
            $storedPath = $image->storeAs('images/products', $image->hashName(), 'public');

            // Use the relative path for the database
            // Note: The correct path for the DB should exclude the disk name, 
            // using the path relative to the 'public' disk root.
            $imagePath = 'images/products/' . $image->hashName();
        }

        // 3. Create Product
        Product::create([
            'category_id' => $validated['a'], // Using validated data is safer
            'supplier_id' => $validated['b'],
            'name'        => $validated['c'],
            'price'       => $validated['e'],
            'stock'       => $validated['f'],
            'description' => $validated['g'],
            'user_id'     => Auth::user()->id,
            'image'       => $imagePath,
            'slug'        => Str::slug($validated['c'], '-'),
        ]);

        // 4. Redirect with success
        return redirect()->route('product.index')->with('success', 'Data Berhasil ditambahkan. Produk baru sudah tersedia.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        // Pastikan Anda juga mengirim data categories dan suppliers ke view
        // Anda perlu menyesuaikan ini dengan cara Anda mengambil data categories dan suppliers
        $categories = Category::all(); // Contoh: Ganti dengan cara Anda mengambil Category
        $suppliers = Supplier::all();   // Contoh: Ganti dengan cara Anda mengambil Supplier

        $product = Product::where('slug', $slug)->firstOrFail();

        return view('product.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'a' => 'required|numeric|exists:categories,id', // category_id
            'b' => 'required|numeric|exists:suppliers,id', // supplier_id
            'c' => 'required|string|min:3', // name
            // 'd' (image) is now optional for update, but still validated if present
            'd' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|nullable',
            'e' => 'required|numeric|min:1', // price (diasumsikan minimal 1)
            'f' => 'required|numeric|min:0', // stock (diasumsikan minimal 0)
            'g' => 'required|string|min:3', // description
        ], [
            'a.required' => 'Wajib memilih Kategori.',
            'a.exists'   => 'Data Kategori tidak ditemukan.',
            // Tambahkan pesan custom untuk validasi lainnya di sini
        ]);

        $product = Product::findOrFail($id);

        $data = [
            'category_id' => $request->a,
            'supplier_id' => $request->b,
            'name'        => $request->c,
            'price'       => $request->e,
            'stock'       => $request->f,
            'description' => $request->g,
            'user_id'     => Auth::user()->id,
            'slug'        => Str::slug($request->c, '-'),
        ];

        // Handle file upload/update
        if ($request->hasFile('d')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('d');
            // Simpan gambar baru
            $image->storeAs('images/products', $image->hashName(), 'public');

            // Simpan path ke database
            $data['image'] = 'images/products/' . $image->hashName();
        }

        // Update data produk
        $product->update($data);

        return redirect()->route('product.index')->with('success', 'Data Produk Berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        // 1. Hapus gambar terkait dari storage (disk 'public')
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // 2. Hapus data produk dari database
        $product->delete();

        // 3. Redirect dengan pesan sukses
        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus!');
    }
}
