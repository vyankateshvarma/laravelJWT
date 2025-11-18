<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request)
    {
        $routeName = $request->route() ? $request->route()->getName() : null;

        if ($routeName === "auth.login") {
            return [
                "id" => $this->id,
                "name" => $this->name,
                "email" => $this->email,
                "message" => "Welcome user!",
            ];
        }

        if ($routeName === "auth.register") {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
            ];
        }

        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
        ];
    }
}
