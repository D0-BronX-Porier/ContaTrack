<x-layouts::app :title="'Nueva Categoría'">

<div class="p-6 flex flex-col gap-6">

    <h1 class="text-2xl font-bold">Nueva Categoría</h1>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        </script>
    @endif

    <div class="max-w-xl bg-white border rounded-lg p-6 shadow">

        <form action="{{ route('categories.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <div>
                <label class="block mb-1">Nombre</label>
                <input type="text"
                       name="name"
                       class="w-full border p-2 rounded"
                       required>
            </div>

            <div>
                <label class="block mb-1">Tipo</label>
                <select name="type" class="w-full border p-2 rounded" required>
                    <option value="expense">Gasto</option>
                    <option value="income">Ingreso</option>
                </select>
            </div>

            <div class="flex gap-2 pt-2">

                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Guardar
                </button>

                <a href="{{ route('categories.index') }}"
                   class="bg-red-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</div>

</x-layouts::app>