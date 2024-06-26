<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::query()->latest()->paginate(10);
        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validatedData = $request->validated();

            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validatedData['thumbnail'] = $thumbnailPath;
            }

            $newTeam = Product::create($validatedData);
        });

        return to_route('admin.products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            $validatedData = $request->validated();

            if ($request->hasFile('thumbnail')) {
                Storage::delete("public/$product->thumbnail");

                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validatedData['thumbnail'] = $thumbnailPath;
            }

            $product->update($validatedData);
        });

        return to_route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            $product->delete();
        });

        return redirect()->back();
    }
}
