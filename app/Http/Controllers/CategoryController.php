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
    ], 200);
    }
    public function update(Request $request,$id){
        $validatedData = $request->validate([
            'name'=> 'required|string|max:255',
            ]);
            $category = Category::find($id);
            if(!$category){
                return response()->json([
                    'success'=> false,
                    'message'=> 'Failed To Update'
                    ],404);
            }
            $category->update($validatedData);              
            return response()->json([
                'success'=> true,
                'message'=> 'Updated Successfully',
                'data'=> $category
                ],0);
    }
    public function destroy($id){
        $category = Category::find($id);
        $category->delete();
        return response()->json([
            'success'=> true,
            'message'=> 'Deleted Successfully'
            ],200);
    }       
}