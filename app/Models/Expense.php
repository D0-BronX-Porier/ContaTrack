<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'description',
        'amount',
        'date',
        'payment_method',
        'is_deductible',
        'accountant_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function toggleDeductible(Expense $expense)
    {
        $expense->is_deductible = !$expense->is_deductible;
        $expense->save();

        return back()->with('success', 'Estado actualizado');
    }
}
