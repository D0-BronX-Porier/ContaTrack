<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Income;
use App\Models\Expense;

class PdfController extends Controller
{
    public function generar(Request $request)
    {
        $userId = auth()->id();

        $type = $request->type ?? 'mensual';
        $month = $request->month ?? now()->month;
        $year = now()->year;

        // Queries base
        $queryIncome = Income::where('user_id', $userId);
        $queryExpense = Expense::where('user_id', $userId);

        $periodo = '';

        switch ($type) {

            case 'diario':
                $queryIncome->whereDate('date', now());
                $queryExpense->whereDate('date', now());
                $periodo = 'Hoy (' . now()->format('d/m/Y') . ')';
                break;

            case 'mensual':
                $queryIncome->whereMonth('date', $month)
                            ->whereYear('date', $year);

                $queryExpense->whereMonth('date', $month)
                             ->whereYear('date', $year);

                $periodo = \Carbon\Carbon::create()->month($month)->translatedFormat('F') . " $year";
                break;

            case 'anual':
                $queryIncome->whereYear('date', $year);
                $queryExpense->whereYear('date', $year);
                $periodo = "Año $year";
                break;
        }

        $totalIncomes = $queryIncome->sum('amount');
        $totalExpenses = $queryExpense->sum('amount');
        $balance = $totalIncomes - $totalExpenses;

        // 🔥 (opcional PRO) obtener movimientos
        $incomes = $queryIncome->get();
        $expenses = $queryExpense->get();

        $pdf = Pdf::loadView('pdf.resumen', [
            'totalIncomes' => $totalIncomes,
            'totalExpenses' => $totalExpenses,
            'balance' => $balance,
            'periodo' => $periodo,
            'incomes' => $incomes,
            'expenses' => $expenses,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("reporte-$type.pdf");
    }
}