<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Testing\Assert;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids,SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'worker_id', 'id')->where('role', 'worker');
    }

    public function myAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'worker_id', 'id')->where('worker_id', $this->id);
    }
}
