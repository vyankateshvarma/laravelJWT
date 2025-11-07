<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return response()->json([
        'status' => true,
        'data' => $products
    ]);
    }
    public function store(Request $request){
        $data=$request->validate([
            "name"=> "required|string",
            "description"=> "nullable|string",   
            "price"=> "required|numeric",
            "image"=> "nullable|string",
            "stock"=> "nullable|integer",
            "category_id"=> "required|exists:categories,id",
        ]);
        $data['user_id'] = Auth::id();   //get currently loggined user id
        $product=Product::create($data);
        return response()->json(["Stored"=>true,"data"=>$data]);
       }
    public function show($id){
        $product=Product::find($id);
        if (!$product) return response()->json(["error "=>"Product Not Found"],404);
        return response()->json([$product]);
        }
        public function update(Request $request, $id){
            $data=$request->validate([
                "name"=> "sometimes|string",
                "description"=> "nullable|string",   
                "price"=> "requiered|numeric",
                "image"=> "nullable|string",
                "stock"=> "nullable|integer",
        ]);
        $product=Product::update($$data);
        return response()->json(["Successfully updated"=>true,"data"=>$data]);
    }
    public function destroy($id){
        $product=Product::find($id);
        if (!$product) return response()->json(["Error"=> "Product Not Found"],404);

        $product->delete();
        return response()->json(["Success"=>true,"messege"=>"Product Deleted"]);
    }
}