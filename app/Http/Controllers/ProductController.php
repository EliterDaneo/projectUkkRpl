<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $data = Supplier::latest()->paginate(10);

        return view("data.index", compact("data"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("data.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories|min:3',
        ]);

        Supplier::create($request->all());

        return redirect()->route('supplier.index')->with('success', 'Data Berhasil Ditambahkan');
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
    public function edit(string $id)
    {
        $category = Supplier::findOrFail($id);

        return view('data.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|unique:categories|min:3',
        ]);

        $category = Supplier::findOrFail($id);

        $category->update($request->all());

        return redirect()->route('supplier.index')->with('update', 'Data Berhasil Diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Supplier::findOrFail($id);

        $category->delete();

        return redirect()->route('supplier.index')->with('delete', 'Data Berhasil Dihapus');
    }
}
