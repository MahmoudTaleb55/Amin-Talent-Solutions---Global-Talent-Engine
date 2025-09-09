<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
            'job_request_id' => $this->job_request_id,
            'freelancer_id' => $this->freelancer_id,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Include relationships when loaded
            'freelancer' => new FreelancerResource($this->whenLoaded('freelancer')),
            'job_request' => new JobRequestResource($this->whenLoaded('jobRequest')),
            'deliverable' => new DeliverableResource($this->whenLoaded('deliverable')),
        ];
    }
}
