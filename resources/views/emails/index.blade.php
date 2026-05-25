<x-layouts::app :title="'Enviar resumen'">

<div class="p-6 flex justify-center bg-gray-50 min-h-screen">

    <div class="w-full max-w-2xl bg-white border rounded-xl shadow p-6">

        <h1 class="text-2xl font-bold mb-1">Enviar resumen financiero</h1>
        <p class="text-sm text-gray-500 mb-6">Selecciona el periodo y envía el reporte</p>

        {{-- ALERTAS --}}
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Enviado',
                    text: "{{ session('success') }}"
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Errores detectados',
                    html: `{!! implode('<br>', $errors->all()) !!}`
                });
            </script>
        @endif

        <form method="POST" action="{{ route('emails.send') }}" class="flex flex-col gap-4">
            @csrf

            {{-- EMAIL --}}
            <div>
                <label class="font-semibold text-sm">Correo</label>
                <input type="email"
                       name="email"
                       required
                       class="w-full border p-2 rounded"
                       placeholder="ejemplo@correo.com">
            </div>

            {{-- TIPO --}}
            <div>
                <label class="font-semibold text-sm">Tipo de reporte</label>
                <select name="type" id="type" class="w-full border p-2 rounded" onchange="toggleInputs()">
                    <option value="day">Diario</option>
                    <option value="month">Mensual</option>
                    <option value="bimester">Bimestral</option>
                    <option value="trimester">Trimestral</option>
                    <option value="semester">Semestral</option>
                    <option value="year">Anual</option>
                </select>
            </div>

            {{-- FECHA (solo day) --}}
            <div id="dateInput">
                <label class="font-semibold text-sm">Fecha</label>
                <input type="date"
                       name="date"
                       class="w-full border p-2 rounded">
            </div>

            {{-- MES (resto) --}}
            <div id="monthInput">
                <label class="font-semibold text-sm">Mes base</label>
                <select name="month" class="w-full border p-2 rounded">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}">
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- AÑO --}}
            <div>
                <label class="font-semibold text-sm">Año</label>
                <select name="year" class="w-full border p-2 rounded">
                    @foreach(range(2023, now()->year + 1) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            {{-- BOTONES --}}
            <div class="flex gap-3 pt-2">

                <a href="{{ route('expenses.index') }}"
                   class="w-1/2 text-center bg-red-500 text-white py-2 rounded hover:bg-red-700">
                    Cancelar
                </a>

                <button id="submitBtn"
                        class="w-1/2 bg-blue-500 text-white py-2 rounded hover:bg-blue-700 flex justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    Enviar
                </button>

            </div>

        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function toggleInputs() {
    const type = document.getElementById('type').value;

    const date = document.getElementById('dateInput');
    const month = document.getElementById('monthInput');

    const dateInput = document.querySelector('[name="date"]');
    const monthInput = document.querySelector('[name="month"]');

    if (type === 'day') {
        date.style.display = 'block';
        month.style.display = 'none';

        dateInput.disabled = false;
        monthInput.disabled = true;
    } else {
        date.style.display = 'none';
        month.style.display = 'block';

        dateInput.disabled = true;
        monthInput.disabled = false;
    }
}

toggleInputs();

// evitar doble submit
document.querySelector('form').addEventListener('submit', function () {
    document.getElementById('submitBtn').disabled = true;
});
</script>

</x-layouts::app>