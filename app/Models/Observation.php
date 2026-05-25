<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Observation extends Model
{
    protected $fillable = [
        'accountant_id',
        'user_id',
        'message'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function accountant() {
        return $this->belongsTo(User::class, 'accountant_id');
    }
}