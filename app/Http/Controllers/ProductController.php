<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;

class ProductController extends Controller

{
    public function index(){
        $products = Product::with('user:id,name')->get();
        return ProductResource::collection($products);
    }
   public function store(Request $request)
{
    $data = $request->validate([
            "name"=> "required|string",
            "description"=> "nullable|string",   
            "price"=> "required|numeric",
            "image"=> "nullable|string",
            "stock"=> "nullable|integer",
            "category_id"=> "required|exists:categories,id"
        ]); 
    $data['user_id'] = Auth::id();

    $product = Product::create($data);

    return response()->json([
        "Stored" => true,
        "data" => new ProductResource($product),
    ], 200);
}

    public function show($id){
        $product=Product::find($id);        
        if (!$product) 
            return response()->json(["error "=>"Product Not Found"],404);
        return new ProductResource($product);
        }
        public function update(Request $request, $id)
{
    $data = $request->validate([
        "name" => "sometimes|string",
        "description" => "nullable|string",   
        "price" => "required|numeric",
        "image" => "nullable|string",
        "stock" => "nullable|integer",
    ]);
    $product = Product::find($id);
    if (!$product) {
        return response()->json(["error" => "Product not found"], 404);
    }

    $product->update($data);

    return response()->json([
        "success" => true,
        "message" => "Product updated successfully",
        "data" => $product
    ]);
}

    public function destroy($id){
        $product=Product::find($id);
        if (!$product) return response()->json(["Error"=> "Product Not Found"],404);

        $product->delete();
        return response()->json(["success"=>true,"message"=>"Product Deleted"],200);
    }
} 