<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusUpdateResource extends JsonResource
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
            'assignment_id' => $this->assignment_id,
            'status' => $this->status,
            'updated_on' => $this->updated_on,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Include relationship when loaded
            'deliverable' => new DeliverableResource($this->whenLoaded('deliverable')),
        ];
    }
}
