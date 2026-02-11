<?php

namespace App\Http\Resources\AuthResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status'=>true,
            'token' => $this->resource['access_token'],
            'refresh_token' => $this->resource['refresh_token'],
            'user' => UserResource::make($this->resource['user'])
        ];
    }
}
