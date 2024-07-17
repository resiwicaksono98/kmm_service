<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory,HasUuids, SoftDeletes;

    protected $table = 'packages';
    protected $primaryKey = 'id';
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Asia/Jakarta',
        'updated_at' => 'datetime:Asia/Jakarta',
        'deleted_at' => 'datetime:Asia/Jakarta',
    ];

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'package_tasks', 'package_id', 'task_id');
    }
}
