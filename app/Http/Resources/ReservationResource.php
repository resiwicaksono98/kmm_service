<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'uniqueNumber' => $this->resource->unique_number,
            'date' => $this->resource->date,
            'session' => $this->resource->session,
            'status' => $this->resource->status,
            'package' => new PackageResource($this->resource->package),
            'user' => new UserResource($this->resource->user),
            'workerName' => $this->resource->assignment->worker->full_name ?? null,
            'note' => $this->resource->note,
            'createdAt' => Carbon::parse($this->resource->created_at)->timezone('Asia/Jakarta')->toDateTimeString(),
        ];
    }
}
