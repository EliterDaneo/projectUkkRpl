<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'a' => 'required|numeric|exists:categories,id',
            'b' => 'required|numeric|exists:suppliers,id',
            'c' => 'required|string|min:3',
            'd' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'e' => 'required|numeric|min:3',
            'f' => 'required|numeric|min:3',
            'g' => 'required|string|min:3',
        ], [
            'a.required' => 'Data Tidak Ditemukan di Kategori',
        ]);

        // dd($request->all());

        // Product::create($request->all());

        Product::create([
            'category_id' => $request->a,
            'supplier_id' => $request->b,
            'name' => $request->c,
            'price' => $request->e,
            'stock' => $request->f,
            'description' => $request->g,
            // 'user_id' => Auth::user()->id,
            // 'user_id' => 1,
            'slug' => Str::slug($request->c, '-'),

            //andi apriliano -> andi-apriliano -> seo
        ]);

        return redirect()->route('product.index')->with('success', 'Data Berhasil ditambahkan');
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
        $product = Product::where('slug', '=', $slug)->first();

        echo $product;
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('product.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
