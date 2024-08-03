<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ReportResource extends JsonResource
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
            'startDate' => $this->resource->start_date,
            'endDate' => $this->resource->start_date,
            'fileUrl' => $this->resource->file_url ? url(Storage::url($this->resource->file_url)) : null,
            'createdAt' => Carbon::parse($this->resource->created_at)->timezone('Asia/Jakarta')->toDateTimeString(),
        ];
    }
}
