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
            'id' => $this->resource->id,
            'reservation' => ReservationResource::make($this->reservation),
            'worker' => UserResource::make($this->worker),
            'status' => $this->resource->status,
            'date' => $this->resource->date,
            'startTime' => $this->resource->start_time,
            'endTime' => $this->resource->end_time,
            'createdAt' => $this->resource->created_at
        ];
    }
}
