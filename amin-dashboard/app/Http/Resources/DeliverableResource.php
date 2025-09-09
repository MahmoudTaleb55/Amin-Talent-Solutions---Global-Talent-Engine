<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliverableResource extends JsonResource
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
            'content' => $this->content,
            'submitted_on' => $this->submitted_on,
            'status' => $this->status,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Include relationships when loaded
            'assignment' => new AssignmentResource($this->whenLoaded('assignment')),
            'status_update' => new StatusUpdateResource($this->whenLoaded('statusUpdate')),
        ];
    }
}
