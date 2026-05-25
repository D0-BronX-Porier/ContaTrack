<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        color: #333;
    }

    h1 {
        text-align: center;
        color: #111;
    }

    .box {
        margin-top: 10px;
        padding: 10px;
        border: 1px solid #ccc;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th, td {
        border: 1px solid #ccc;
        padding: 6px;
        text-align: left;
    }

    th {
        background: #f2f2f2;
    }

    .totales {
        margin-top: 20px;
    }

    .totales p {
        font-size: 14px;
        margin: 5px 0;
    }

    .positivo {
        color: green;
    }

    .negativo {
        color: red;
    }
</style>

</head>

<body>

<h1>Resumen Financiero</h1>

<p>Periodo: {{ $periodo }}</p>
<p>Generado: {{ now()->format('d/m/Y H:i') }}</p>

<div class="box">
    <strong>Usuario:</strong> {{ auth()->user()->name }}
</div>

<!-- 📊 TABLA -->
<table>
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Monto</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Ingresos</td>
            <td>${{ number_format($totalIncomes, 2) }}</td>
        </tr>
        <tr>
            <td>Gastos</td>
            <td>${{ number_format($totalExpenses, 2) }}</td>
        </tr>
    </tbody>
</table>

<!-- 💰 TOTALES -->
<div class="totales">
    <p><strong>Balance:</strong> 
        <span class="{{ $balance >= 0 ? 'positivo' : 'negativo' }}">
            ${{ number_format($balance, 2) }}
        </span>
    </p>
</div>

</body>
</html>