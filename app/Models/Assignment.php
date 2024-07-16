<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use HasFactory,SoftDeletes,HasUuids;

    protected $table = 'assignments';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function reservation() : BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    public function worker() : BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }
}
