<x-mail::message>

# 📊 Resumen Financiero

Tipo: {{ $label }}

Periodo:
{{ $start->format('d/m/Y') }} - {{ $end->format('d/m/Y') }}

<x-mail::panel>
💰 Ingresos: ${{ number_format($totalIncomes, 2) }}  
💸 Gastos: ${{ number_format($totalExpenses, 2) }}  
📈 Balance: ${{ number_format($balance, 2) }}
</x-mail::panel>

@if($balance >= 0)
🟢 Buen manejo financiero
@else
🔴 Gastos mayores a ingresos
@endif

<x-mail::button :url="config('app.url')">
Ir al sistema
</x-mail::button>

Gracias por usar tu app financiera 💼

</x-mail::message>