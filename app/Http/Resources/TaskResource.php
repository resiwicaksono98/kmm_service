<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TaskResource extends JsonResource
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
            'name' => $this->resource->name,
            'icon' => $this->resource->icon ? url(Storage::url($this->resource->icon)) : null,
            'durationOfWork' => round($this->resource->duration_of_work),
            'createdAt' => $this->resource->created_at,
        ];
    }
}
