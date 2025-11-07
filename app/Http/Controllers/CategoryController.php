<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::with('products');
        return response()->json($categories);
    }
public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
    ]);
    $category = Category::create($validatedData);

    return response()->json([
        'success' => true,
        'message' => 'Category created successfully!',
        'data' => $category
    ], 201);
}

}       
