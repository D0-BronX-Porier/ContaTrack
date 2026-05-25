<x-layouts::app :title="'Editar Gasto'">

    <div class="p-6 flex flex-col gap-6">

        <!-- ALERTAS -->
        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonColor: '#d33'
                });
            </script>
        @endif

        <h1 class="text-2xl font-bold">Editar Gasto</h1>

        <div class="max-w-xl bg-white p-6 rounded-xl shadow border">

            <form action="{{ route('expenses.update', $expense) }}"
                  method="POST"
                  class="flex flex-col gap-4">

                @csrf
                @method('PUT')

                <!-- DESCRIPCIÓN -->
                <div>
                    <label class="block mb-1">Descripción</label>
                    <input type="text"
                           name="description"
                           value="{{ $expense->description }}"
                           required
                           class="w-full border rounded p-2">
                </div>

                <!-- MONTO (🔥 SIN NEGATIVOS) -->
                <div>
                    <label class="block mb-1">Monto</label>
                    <input type="number"
                           name="amount"
                           step="0.01"
                           min="0"
                           value="{{ $expense->amount }}"
                           required
                           class="w-full border rounded p-2">
                </div>

                <!-- FECHA -->
                <div>
                    <label class="block mb-1">Fecha</label>
                    <input type="date"
                           name="date"
                           value="{{ $expense->date }}"
                           required
                           class="w-full border rounded p-2">
                </div>

                <!-- CATEGORÍA -->
                <div>
                    <label class="block mb-1">Categoría</label>
                    <select name="category_id"
                            required
                            class="w-full border rounded p-2">

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- DEDUCIBLE (SOLO ADMIN) -->
                @if(auth()->user()->role === 'admin')
                    <div class="flex items-center justify-between border p-3 rounded bg-gray-50">

                        <div>
                            <p class="font-semibold">Gasto deducible</p>
                            <p class="text-sm text-gray-500">
                                Actívalo si aplica para impuestos
                            </p>
                        </div>

                        <label class="flex items-center gap-2 cursor-pointer">

                            <input type="checkbox"
                                   name="is_deductible"
                                   value="1"
                                   {{ $expense->is_deductible ? 'checked' : '' }}>

                        </label>

                    </div>
                @endif

                <!-- BOTONES -->
                <div class="flex gap-2 pt-2">

                    <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                        Actualizar
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