<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $type = $request->type ?? 'mensual';
        $month = $request->month ?? now()->month;
        $date = $request->date ?? now()->toDateString();
        $year = now()->year;

        $categoryId = $request->category_id;
        $minAmount = $request->min_amount;
        $maxAmount = $request->max_amount;
        $sort = $request->sort ?? 'desc';

        $queryIncome = Income::where('user_id', $userId);
        $queryExpense = Expense::where('user_id', $userId);

        // ======================
        // FILTRO DE PERIODO
        // ======================
        switch ($type) {

            case 'hoy':
                $today = now()->toDateString();

                $queryIncome->whereDate('date', $today);
                $queryExpense->whereDate('date', $today);
                break;

            case 'mensual':
                $queryIncome->whereMonth('date', $month)->whereYear('date', $year);
                $queryExpense->whereMonth('date', $month)->whereYear('date', $year);
                break;

            case 'bimestral':
                $start = Carbon::create($year, $month, 1);
                $end = (clone $start)->addMonth()->endOfMonth();

                $queryIncome->whereBetween('date', [$start, $end]);
                $queryExpense->whereBetween('date', [$start, $end]);
                break;

            case 'trimestral':
                $start = Carbon::create($year, $month, 1);
                $end = (clone $start)->addMonths(2)->endOfMonth();

                $queryIncome->whereBetween('date', [$start, $end]);
                $queryExpense->whereBetween('date', [$start, $end]);
                break;

            case 'semestral':
                $start = Carbon::create($year, $month, 1);
                $end = (clone $start)->addMonths(5)->endOfMonth();

                $queryIncome->whereBetween('date', [$start, $end]);
                $queryExpense->whereBetween('date', [$start, $end]);
                break;

            case 'anual':
                $queryIncome->whereYear('date', $year);
                $queryExpense->whereYear('date', $year);
                break;
        }

        // ======================
        // KPIs
        // ======================
        $totalIncomes = $queryIncome->sum('amount');
        $totalExpenses = $queryExpense->sum('amount');
        $balance = $totalIncomes - $totalExpenses;

        // ======================
        // GRAFICA
        // ======================
        $months = [];
        $incomeData = [];
        $expenseData = [];

        switch ($type) {

            case 'hoy':
                for ($i = 6; $i >= 0; $i--) {
                    $d = Carbon::now()->subDays($i);

                    $months[] = $d->format('d M');

                    $incomeData[] = Income::where('user_id', $userId)
                        ->whereDate('date', $d)
                        ->sum('amount');

                    $expenseData[] = Expense::where('user_id', $userId)
                        ->whereDate('date', $d)
                        ->sum('amount');
                }
                break;

            case 'mensual':
                for ($i = 5; $i >= 0; $i--) {
                    $d = Carbon::create($year, $month, 1)->subMonths($i);

                    $months[] = $d->format('M Y');

                    $incomeData[] = Income::where('user_id', $userId)
                        ->whereMonth('date', $d->month)
                        ->whereYear('date', $d->year)
                        ->sum('amount');

                    $expenseData[] = Expense::where('user_id', $userId)
                        ->whereMonth('date', $d->month)
                        ->whereYear('date', $d->year)
                        ->sum('amount');
                }
                break;

            case 'bimestral':
                for ($i = 2; $i >= 0; $i--) {
                    $start = Carbon::create($year, $month, 1)->subMonths($i * 2);
                    $end = (clone $start)->addMonth()->endOfMonth();

                    $months[] = $start->format('M') . '-' . $end->format('M');

                    $incomeData[] = Income::where('user_id', $userId)
                        ->whereBetween('date', [$start, $end])
                        ->sum('amount');

                    $expenseData[] = Expense::where('user_id', $userId)
                        ->whereBetween('date', [$start, $end])
                        ->sum('amount');
                }
                break;

            case 'trimestral':
                for ($i = 3; $i >= 0; $i--) {
                    $start = Carbon::create($year, 1, 1)->addMonths($i * 3);
                    $end = (clone $start)->addMonths(2)->endOfMonth();

                    $months[] = 'Q' . ($i + 1);

                    $incomeData[] = Income::where('user_id', $userId)
                        ->whereBetween('date', [$start, $end])
                        ->sum('amount');

                    $expenseData[] = Expense::where('user_id', $userId)
                        ->whereBetween('date', [$start, $end])
                        ->sum('amount');
                }
                break;

            case 'semestral':
                for ($i = 1; $i >= 0; $i--) {
                    $start = Carbon::create($year, 1, 1)->addMonths($i * 6);
                    $end = (clone $start)->addMonths(5)->endOfMonth();

                    $months[] = $i == 0 ? 'Ene-Jun' : 'Jul-Dic';

                    $incomeData[] = Income::where('user_id', $userId)
                        ->whereBetween('date', [$start, $end])
                        ->sum('amount');

                    $expenseData[] = Expense::where('user_id', $userId)
                        ->whereBetween('date', [$start, $end])
                        ->sum('amount');
                }
                break;

            case 'anual':
                for ($m = 1; $m <= 12; $m++) {

                    $months[] = Carbon::create()->month($m)->format('M');

                    $incomeData[] = Income::where('user_id', $userId)
                        ->whereMonth('date', $m)
                        ->whereYear('date', $year)
                        ->sum('amount');

                    $expenseData[] = Expense::where('user_id', $userId)
                        ->whereMonth('date', $m)
                        ->whereYear('date', $year)
                        ->sum('amount');
                }
                break;
        }

        // ======================
        // TOP CATEGORIAS
        // ======================
        $topCategoriesQuery = Expense::select(
            'category_id',
            DB::raw('SUM(amount) as total')
        )
            ->where('user_id', $userId);

        if ($categoryId) {
            $topCategoriesQuery->where('category_id', $categoryId);
        }

        if ($minAmount) {
            $topCategoriesQuery->havingRaw('SUM(amount) >= ?', [$minAmount]);
        }

        if ($maxAmount) {
            $topCategoriesQuery->havingRaw('SUM(amount) <= ?', [$maxAmount]);
        }

        $topCategories = $topCategoriesQuery
            ->groupBy('category_id')
            ->orderBy('total', $sort)
            ->with('category')
            ->get();

        $categories = Category::all();

        return view('dashboard-finanzas', compact(
            'totalIncomes',
            'totalExpenses',
            'balance',
            'months',
            'incomeData',
            'expenseData',
            'month',
            'categories',
            'topCategories'
        ));
    }
}
