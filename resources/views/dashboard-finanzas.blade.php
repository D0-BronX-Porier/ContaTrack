<x-layouts::app :title="'Dashboard Finanzas'">

    <div class="p-6 flex flex-col gap-6 text-black bg-gray-50 min-h-screen">

        <!-- HEADER -->
        <div class="flex flex-col gap-3">

            <h2 class="text-lg font-semibold">Filtros del Dashboard</h2>

            <div class="flex flex-wrap justify-between items-center gap-4">

                <!-- FILTROS -->
                <form method="GET" class="flex gap-4 items-end flex-wrap">

                    <div class="flex flex-col">
                        <label class="text-sm text-gray-600 mb-1">Periodo</label>
                        <select name="type" onchange="this.form.submit()"
                            class="border p-2 rounded bg-white">

                            <option value="hoy" {{ request('type') == 'hoy' ? 'selected' : '' }}>Hoy</option>
                            <option value="mensual" {{ request('type') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                            <option value="bimestral" {{ request('type') == 'bimestral' ? 'selected' : '' }}>Bimestral</option>
                            <option value="trimestral" {{ request('type') == 'trimestral' ? 'selected' : '' }}>Trimestral</option>
                            <option value="semestral" {{ request('type') == 'semestral' ? 'selected' : '' }}>Semestral</option>
                            <option value="anual" {{ request('type') == 'anual' ? 'selected' : '' }}>Anual</option>

                        </select>
                    </div>

                    @if (request('type') !== 'anual' && request('type') !== 'hoy')
                        <div class="flex flex-col">
                            <label>Mes base</label>
                            <select name="month" onchange="this.form.submit()"
                                class="border p-2 rounded bg-white">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}"
                                        {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                </form>

                <!-- ACCIONES -->
                <div class="flex gap-3 flex-wrap items-center">

                    <a href="{{ route('expenses.create') }}"
                        class="px-4 py-2 rounded-lg shadow bg-green-500 text-white hover:bg-green-700 transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        Nuevo Gasto
                    </a>

                    <a href="{{ route('pdf.generar', [
                        'type' => request('type'),
                        'month' => request('month'),
                        'date' => request('date'),
                    ]) }}"
                        class="px-4 py-2 rounded-lg shadow bg-red-500 text-white hover:bg-red-700 transition flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i>
                        PDF
                    </a>

                    <!-- 🔥 BOTÓN NUEVO -->
                    <button type="button"
                        onclick="forceRenderChart()"
                        class="px-4 py-2 rounded-lg shadow bg-orange-500 text-white hover:bg-orange-600 transition flex items-center gap-2">
                        <i class="fa-solid fa-chart-line"></i>
                        Cargar gráfica
                    </button>

                </div>
            </div>
        </div>

        <!-- KPIs -->
        <div class="grid md:grid-cols-3 gap-6">

            <div class="bg-white border p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h2 class="text-gray-600">Ingresos</h2>
                <p class="text-2xl text-green-600 font-semibold">
                    ${{ number_format($totalIncomes, 2) }}
                </p>
            </div>

            <div class="bg-white border p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h2 class="text-gray-600">Gastos</h2>
                <p class="text-2xl text-red-600 font-semibold">
                    ${{ number_format($totalExpenses, 2) }}
                </p>
            </div>

            <div class="bg-white border p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h2 class="text-gray-600">Balance</h2>
                <p class="text-2xl font-semibold {{ $balance >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                    ${{ number_format($balance, 2) }}
                </p>
            </div>

        </div>

        <!-- GRAFICA -->
        <div class="bg-white border p-6 rounded-xl shadow-sm h-96">
            <canvas id="financeChart"></canvas>
        </div>

        <!-- CATEGORIAS (NO TOCADO) -->
        <div class="bg-white border p-4 rounded-xl shadow-sm">

            <div class="flex flex-wrap justify-between items-center gap-3 mb-3">

                <h2 class="font-semibold">Gastos por Categoría</h2>

                <form method="GET" class="flex gap-2 flex-wrap">

                    <input type="hidden" name="type" value="{{ request('type') }}">
                    <input type="hidden" name="month" value="{{ request('month') }}">

                    <select name="category_id" onchange="this.form.submit()" class="border p-2 rounded">
                        <option value="">Todas</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number" name="min_amount" placeholder="Min"
                        value="{{ request('min_amount') }}"
                        class="border p-2 rounded w-24">

                    <input type="number" name="max_amount" placeholder="Max"
                        value="{{ request('max_amount') }}"
                        class="border p-2 rounded w-24">

                    <select name="sort" onchange="this.form.submit()" class="border p-2 rounded">
                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Mayor</option>
                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Menor</option>
                    </select>

                    <button class="bg-blue-600 text-white px-3 rounded hover:bg-blue-700">
                        Filtrar
                    </button>

                </form>
            </div>

            @forelse($topCategories as $cat)
                <div class="flex justify-between border-b py-2">
                    <span>{{ $cat->category->name ?? 'Sin categoría' }}</span>
                    <span class="font-semibold">${{ number_format($cat->total, 2) }}</span>
                </div>
            @empty
                <p class="text-gray-500">No hay datos</p>
            @endforelse

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let chart = null;

        const labels = @json($months ?? []);
        const incomes = @json($incomeData ?? []);
        const expenses = @json($expenseData ?? []);

        function buildChart() {
            const canvas = document.getElementById('financeChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Ingresos',
                            data: incomes,
                            borderColor: '#16a34a',
                            tension: 0.3
                        },
                        {
                            label: 'Gastos',
                            data: expenses,
                            borderColor: '#dc2626',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // 🔥 BOTÓN GLOBAL
        window.forceRenderChart = function () {
            console.log('🔥 Render manual');
            buildChart();
        };

        // auto load
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(buildChart, 300);
        });
    </script>

</x-layouts::app>