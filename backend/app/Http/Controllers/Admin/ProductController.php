<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Traits\UploadFile;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\UpdateProductRequest;
 
class ProductController extends Controller
{
    use UploadFile;

    public function index()
    {
        // Check if request expects JSON (API call)
        if (request()->expectsJson() || request()->is('api/*')) {
            $products = Product::with(['colors', 'sizes'])->latest()->get();
            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products
            ]);
        }

        // Return view for web interface
        return view('admin.products.index')->with([
            'products' => Product::with(['colors', 'sizes'])->latest()->get()
        ]);
    }

    public function create(): View
    {
        $colors = Color::all();
        $sizes = Size::all();

        return view('admin.products.create')->with([
            'colors' => $colors,
            'sizes' => $sizes
        ]);
    }

    public function store(AddProductRequest $request)
    {
        if ($request->validated()) {
            $data = $request->all();
            $data['thumbnail'] = $this->saveImage($request->file('thumbnail'));

            $imageFields = ['first_image', 'second_image', 'third_image'];
            foreach ($imageFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = $this->saveImage($request->file($field));
                }
            }

            $data['slug'] = Str::slug($request->name);

            $product = Product::create($data);
            $product->colors()->sync($request->color_id);
            $product->sizes()->sync($request->size_id);

            // Load relationships for response
            $product->load(['colors', 'sizes']);

            // Check if request expects JSON (API call)
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product has been created successfully',
                    'data' => $product
                ], 201);
            }

            // Return redirect for web interface
            return redirect()->route('admin.products.index')->with([
                'success' => 'Product has been added successfully'
            ]);
        }
    }

    public function show(Product $product)
    {
        // Check if request expects JSON (API call)
        if (request()->expectsJson() || request()->is('api/*')) {
            $product->load(['colors', 'sizes']);
            return response()->json([
                'success' => true,
                'message' => 'Product retrieved successfully',
                'data' => $product
            ]);
        }

        // You can add a view here if needed for web interface
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit')->with([
            'colors' => Color::all(),
            'sizes' => Size::all(),
            'product' => $product
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($request->validated()) {
            $data = $request->all();

            $imageFields = [
                'thumbnail',
                'first_image',
                'second_image',
                'third_image'
            ];

            foreach ($imageFields as $field) {
                if ($request->has($field)) {
                    $this->removeFile($product->$field);
                    $data[$field] = $this->saveImage($request->file($field));
                }
            }

            $data['slug'] = Str::slug($request->name);
            $data['status'] = $request->status;

            $product->update($data);
            $product->colors()->sync($request->color_id);
            $product->sizes()->sync($request->size_id);

            // Load relationships for response
            $product->load(['colors', 'sizes']);

            // Check if request expects JSON (API call)
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product has been updated successfully',
                    'data' => $product
                ]);
            }

            // Return redirect for web interface
            return redirect()->route('admin.products.index')->with([
                'success' => 'Product has been updated successfully'
            ]);
        }
    }

    public function destroy(Product $product)
    {
        $imageFields = ['thumbnail', 'first_image', 'second_image', 'third_image'];
        foreach ($imageFields as $field) {
            $this->removeFile($product->$field);
        }

        $product->delete();

        // Check if request expects JSON (API call)
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Product has been deleted successfully'
            ]);
        }

        // Return redirect for web interface
        return redirect()->route('admin.products.index')->with([
            'success' => 'Product has been deleted successfully'
        ]);
    }
}