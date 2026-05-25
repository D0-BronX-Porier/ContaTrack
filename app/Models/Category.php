<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Expense;

class Category extends Model
{
    protected $fillable = [
        'name',
        'type'
    ];

    public function expenses() {
        return $this->hasMany(Expense::class);
    }
}