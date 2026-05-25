<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;

class IncomeController extends Controller
{
    public function index()
    {
        $incomes = Income::where('user_id', request()->user()->id)
            ->latest()
            ->get();

        return view('incomes.index', compact('incomes'));
    }

    public function create()
    {
        return view('incomes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
        ]);

        Income::create([
            'user_id' => $request->user()->id,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('incomes.index')
            ->with('success', 'Ingreso creado correctamente');
    }

    public function edit(Income $income)
    {
        // 🔒 Seguridad
        if ($income->user_id !== request()->user()->id) {
            abort(403);
        }

        return view('incomes.edit', compact('income'));
    }

    public function update(Request $request, Income $income)
    {
        if ($income->user_id !== request()->user()->id) {
            abort(403);
        }

        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
        ]);

        $income->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ]);

        return redirect()->route('incomes.index')
            ->with('success', 'Ingreso actualizado correctamente');
    }

    public function destroy(Income $income)
    {
        if ($income->user_id !== request()->user()->id) {
            abort(403);
        }

        $income->delete();

        return redirect()->route('incomes.index')
            ->with('success', 'Ingreso eliminado correctamente');
    }
}