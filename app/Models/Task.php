<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $guarded = [];

    protected $casts = [
        'icon' => 'string',
        'created_at' => 'datetime:Asia/Jakarta',
        'updated_at' => 'datetime:Asia/Jakarta',
        'deleted_at' => 'datetime:Asia/Jakarta',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_tasks', 'task_id', 'package_id');
    }
}
