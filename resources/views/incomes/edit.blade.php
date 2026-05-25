<x-layouts::app :title="'Editar Ingreso'">

<form action="{{ route('incomes.update', $income) }}" method="POST" class="p-6 flex flex-col gap-4">
    @csrf
    @method('PUT')

    <input type="text" name="description"
        value="{{ old('description', $income->description) }}"
        class="border p-2 rounded">

    @error('description')
        <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror

    <input type="number" step="0.01" name="amount"
        value="{{ old('amount', $income->amount) }}"
        class="border p-2 rounded">

    @error('amount')
        <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror

    <input type="date" name="date"
        value="{{ old('date', $income->date) }}"
        class="border p-2 rounded">

    @error('date')
        <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror

    <!-- BOTONES -->
    <div class="flex gap-2 pt-2">

        <button class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
            Actualizar
        </button>

        <a href="{{ route('incomes.index') }}"
           class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center gap-2">
            <i class="fa-solid fa-xmark"></i>
            Cancelar
        </a>

    </div>

</form>

</x-layouts::app>