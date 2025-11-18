<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request) 
    {
        $routeName = $request->route() ? $request->route()->getName() : null;

        // For store route
        if ($routeName === 'products.store') {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'price' => $this->price,
                'message' => 'Product created successfully!',
            ];
        }

        // For show route
        if ($routeName === 'products.show') {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'price' => $this->price,
            ];
        }
        if ($routeName === 'products.index') {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'price' => $this->price,
                'message' => 'Product retrieved successfully!',
            ];
        }
    }
}
