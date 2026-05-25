<x-layouts::app :title="'Nuevo Gasto'">

    <div class="p-6 flex flex-col gap-6">

        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`
                });
            </script>
        @endif

        <h1 class="text-2xl font-bold">Nuevo Gasto</h1>

        <div class="max-w-xl bg-white p-6 rounded-xl shadow border">

            <form action="{{ route('expenses.store') }}"
                  method="POST"
                  class="flex flex-col gap-4">

                @csrf

                <!-- DESCRIPCIÓN -->
                <input type="text"
                       name="description"
                       required
                       placeholder="Descripción"
                       class="border p-2 rounded">

                <!-- MONTO (🔥 SIN NEGATIVOS) -->
                <input type="number"
                       name="amount"
                       step="0.01"
                       min="0"
                       required
                       placeholder="Monto"
                       class="border p-2 rounded">

                <!-- FECHA -->
                <input type="date"
                       name="date"
                       required
                       class="border p-2 rounded">

                <!-- CATEGORÍA -->
                <select name="category_id"
                        required
                        class="border p-2 rounded">

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>

                <!-- DEDUCIBLE SOLO ADMIN -->
                @if(auth()->user()->role === 'admin')
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_deductible" value="1">
                        Gasto deducible
                    </label>
                @endif

                <!-- BOTONES -->
                <div class="flex gap-2 pt-2">

                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Guardar
                    </button>

                    <a href="{{ route('expenses.index') }}"
                       class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</x-layouts::app>