<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResumenFinanciero;
use App\Models\Income;
use App\Models\Expense;
use Carbon\Carbon;

class MailController extends Controller
{
    public function index()
    {
        return view('emails.index');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'type' => 'required|in:day,month,bimester,trimester,semester,year',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'date' => 'nullable|date',
        ]);

        $userId = auth()->id();
        $type = $validated['type'];
        $month = $validated['month'] ?? now()->month;
        $year = $validated['year'];

        $start = null;
        $end = null;

        // VALIDACIÓN INTELIGENTE
        if ($type === 'day' && empty($validated['date'])) {
            return back()->withErrors(['date' => 'La fecha es obligatoria para reporte diario']);
        }

        if ($type !== 'day' && empty($validated['month'])) {
            return back()->withErrors(['month' => 'El mes es obligatorio para este tipo de reporte']);
        }

        // RANGOS
        switch ($type) {

            case 'day':
                $date = Carbon::parse($validated['date']);
                $start = $date->startOfDay();
                $end = $date->endOfDay();
                break;

            case 'month':
                $start = Carbon::create($year, $month, 1)->startOfMonth();
                $end = (clone $start)->endOfMonth();
                break;

            case 'bimester':
                $startMonth = $month <= 2 ? 1 : ($month <= 4 ? 3 : ($month <= 6 ? 5 : ($month <= 8 ? 7 : ($month <= 10 ? 9 : 11))));
                $start = Carbon::create($year, $startMonth, 1)->startOfMonth();
                $end = (clone $start)->addMonths(1)->endOfMonth();
                break;

            case 'trimester':
                $startMonth = ((int)(($month - 1) / 3) * 3) + 1;
                $start = Carbon::create($year, $startMonth, 1)->startOfMonth();
                $end = (clone $start)->addMonths(2)->endOfMonth();
                break;

            case 'semester':
                $startMonth = $month <= 6 ? 1 : 7;
                $start = Carbon::create($year, $startMonth, 1)->startOfMonth();
                $end = (clone $start)->addMonths(5)->endOfMonth();
                break;

            case 'year':
                $start = Carbon::create($year, 1, 1)->startOfYear();
                $end = Carbon::create($year, 12, 31)->endOfYear();
                break;
        }

        // CONSULTAS SEGURAS
        $totalIncomes = Income::where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->sum('amount');

        $totalExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->sum('amount');

        $balance = $totalIncomes - $totalExpenses;

        $labels = [
            'day' => 'Diario',
            'month' => 'Mensual',
            'bimester' => 'Bimestral',
            'trimester' => 'Trimestral',
            'semester' => 'Semestral',
            'year' => 'Anual',
        ];

        Mail::to($validated['email'])->send(
            new ResumenFinanciero(
                $totalIncomes,
                $totalExpenses,
                $balance,
                $labels[$type],
                $start,
                $end
            )
        );

        return back()->with('success', 'Correo enviado correctamente');
    }
}