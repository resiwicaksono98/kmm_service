<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'reservations';
    protected $primaryKey = 'id';
    protected $guarded = [];

    protected $casts = [
        'deleted_at' => 'datetime:Asia/Jakarta',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->date;
            $model->updated_at = $model->date;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id', 'id');
    }

    public function assignment()
    {
        return $this->hasOne(Assignment::class, 'reservation_id', 'id');
    }
}
