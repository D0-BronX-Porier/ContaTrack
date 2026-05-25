<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Category;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', request()->user()->id)->get();

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = Category::where('type', 'expense')->get();

        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0', // 🔥 NO NEGATIVOS
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id'
        ], [
            'amount.min' => 'El monto no puede ser negativo'
        ]);

        Expense::create([
            'user_id' => $request->user()->id,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'is_deductible' => $request->has('is_deductible'),
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Gasto creado correctamente');
    }

    public function edit(Expense $expense)
    {
        $categories = Category::where('type', 'expense')->get();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0', // 🔥 NO NEGATIVOS
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id'
        ], [
            'amount.min' => 'El monto no puede ser negativo'
        ]);

        $expense->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'is_deductible' => $request->has('is_deductible'),
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Gasto actualizado correctamente');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Gasto eliminado correctamente');
    }
}