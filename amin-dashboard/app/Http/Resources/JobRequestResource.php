<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobRequestResource extends JsonResource
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
            'company_id' => $this->company_id,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Include relationships when loaded
            'company' => new CompanyResource($this->whenLoaded('company')),
            'assignments' => AssignmentResource::collection($this->whenLoaded('assignments')),
        ];
    }
}
