<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request) 
    {
        $routeName = $request->route() ? $request->route()->getName() : null;

        if ($routeName === 'products.store') {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'message' => 'Product created successfully!',
        ];
    }
    if (in_array($routeName, ['products.index', 'products.show'])) {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category' => $this->category ? $this->category->name : null,
            'user' => $this->user ? $this->user->name : null,
        ];
    }
}
}