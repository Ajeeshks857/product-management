<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductApiController extends Controller
{

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $page    = $request->input('page', 1);

            $cacheKey = 'products_page_' . $page;

            if (Cache::has($cacheKey)) {
                $products = Cache::get($cacheKey);
            } else {
                $products = Product::with('variants')->whereNull('deleted_at')->orderBy('id', 'desc')->paginate($perPage, ['*'], 'page', $page);
                Cache::put($cacheKey, $products, now()->addMinutes(30));
            }

            $data = $products->map(function ($product) {
                $variantsData = $product->variants->map(function ($variant) {
                    return [
                        'size'  => $variant->size,
                        'color' => $variant->color,
                    ];
                });

                return [
                    'id'          => $product->id,
                    'title'       => $product->title,
                    'description' => $product->description,
                    'slug'        => $product->slug,
                    'main_image'  => $product->main_image,
                    'variants'    => $variantsData,
                ];
            });

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            Log::error('Error occurred while fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching products.'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title'            => 'required|string|max:255',
                'description'      => 'required|string',
                'mainImage'        => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'variants'         => 'array',
                'variants.*.size'  => 'required|string',
                'variants.*.color' => 'required|string',
            ]);
            $slug = Str::slug($validatedData['title']);

            $product        = new Product();
            $product->title = $validatedData['title'];
            $product->slug  = Str::slug($validatedData['title']);

            $product->description = $validatedData['description'];

            if ($request->hasFile('mainImage')) {
                $imagePath           = $request->file('mainImage')->store('products', 'public');
                $product->main_image = $imagePath;
            }

            $product->save();

            if (isset($validatedData['variants'])) {
                foreach ($validatedData['variants'] as $variantData) {
                    $variant             = new ProductVariant();
                    $variant->size       = $variantData['size'];
                    $variant->color      = $variantData['color'];
                    $variant->product_id = $product->id;
                    $variant->save();
                }
            }

            Cache::flush();

            return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
        } catch (\Exception $e) {
            \Log::error('Error occurred while creating product: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while creating the product.'], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validatedData = $request->validate([
                'title'            => 'required|string|max:255',
                'description'      => 'required|string',
                'variants'         => 'array',
                'variants.*.id'    => 'nullable|integer',
                'variants.*.size'  => 'required|string',
                'variants.*.color' => 'required|string',
            ]);

            $product->title       = $validatedData['title'];
            $product->description = $validatedData['description'];

            if ($request->hasFile('mainImage')) {
                $imagePath           = $request->file('mainImage')->store('products', 'public');
                $product->main_image = $imagePath;
            }

            $product->save();

            $existingVariantIds = collect($validatedData['variants'])->pluck('id')->filter();

            if ($existingVariantIds->isNotEmpty()) {
                $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
            } else {
                $product->variants()->delete();
            }

            foreach ($validatedData['variants'] as $variantData) {
                $variant             = $product->variants()->findOrNew($variantData['id']);
                $variant->product_id = $product->id;
                $variant->size       = $variantData['size'];
                $variant->color      = $variantData['color'];
                $variant->save();
            }

            Cache::flush();

            return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $e->getMessage();
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            return $e->getMessage();

            return response()->json(['error' => 'An error occurred while updating the product.'], 500);
        }

    }

    public function destroy(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            Cache::flush();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            \Log::error('Error occurred while deleting product: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while deleting the product.'], 500);
        }
    }
    public function getItem(Request $request, $id)
    {
        try {

            $product = Product::with('variants')->whereNull('deleted_at')->findOrFail($id);
            return response()->json(['product' => $product], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found.'], 404);
        } catch (\Exception $e) {
            \Log::error('Error occurred while fetching product: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching the product.'], 500);
        }

    }

}
