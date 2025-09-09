<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [

        'id' => $this->id,
        'user_id' => $this->user_id,
        'admin_level' => $this->admin_level,
        'permissions' => $this->permissions,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
        // Include related user data if needed
        'user' => new UserResource($this->whenLoaded('user')),
       ];
    }
}
