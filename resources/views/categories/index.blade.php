<x-layouts::app :title="'Categorías'">

<div class="p-6 flex flex-col gap-6">

    <!-- ALERTAS -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: "{{ session('success') }}"
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        </script>
    @endif

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Categorías</h1>

        <a href="{{ route('categories.create') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            + Nueva
        </a>
    </div>

    <!-- SEARCH -->
    <form method="GET" class="flex gap-2">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Buscar categoría..."
               class="border p-2 rounded w-full">

        <button class="bg-blue-500 text-white px-4 py-2 rounded">
            Buscar
        </button>

        <a href="{{ route('categories.index') }}"
           class="bg-gray-400 text-white px-4 py-2 rounded">
            Limpiar
        </a>
    </form>

    <!-- GRID -->
    <div class="grid md:grid-cols-3 gap-4">

        @forelse($categories as $category)

            <div class="bg-white border rounded-lg p-4 shadow flex flex-col justify-between">

                <!-- TOP -->
                <div>
                    <h2 class="font-bold text-lg">
                        {{ $category->name }}
                    </h2>

                    @if($category->type === 'income')
                        <span class="text-green-600 text-sm font-semibold">
                            Ingreso
                        </span>
                    @else
                        <span class="text-red-600 text-sm font-semibold">
                            Gasto
                        </span>
                    @endif
                </div>

                <!-- ACTIONS -->
                <div class="flex justify-end gap-3 mt-4">

                    <!-- EDIT -->
                    <a href="{{ route('categories.edit', $category) }}"
                       class="text-yellow-500 hover:text-yellow-600">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    <!-- DELETE -->
                    <form id="delete-{{ $category->id }}"
                          action="{{ route('categories.destroy', $category) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button"
                                onclick="confirmDelete({{ $category->id }})"
                                class="text-red-600 hover:text-red-700">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                </div>

            </div>

        @empty
            <div class="col-span-3 text-center text-gray-500">
                No hay categorías registradas
            </div>
        @endforelse

    </div>

</div>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: '¿Eliminar categoría?',
        text: "No podrás revertir esto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-' + id).submit();
        }
    });
}
</script>

</x-layouts::app>