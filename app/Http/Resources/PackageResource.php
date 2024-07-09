<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PackageResource extends JsonResource
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
            'estimatedTime' => $this->resource->estimated_time,
            'image' => $this->resource->image ? url(Storage::url($this->resource->image)) : null,
            'description' => $this->resource->description,
            'tasks' => TaskResource::collection($this->resource->tasks),
            'createdAt' => $this->resource->createdAt
        ];
    }
}
