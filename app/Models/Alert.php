<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Alert extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'alert_date',
        'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}