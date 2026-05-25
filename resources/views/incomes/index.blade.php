<x-layouts::app :title="'Ingresos'">

<div class="p-6 flex flex-col gap-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Ingresos</h1>

        <a href="{{ route('incomes.create') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Nuevo
        </a>
    </div>

    <!-- GRID -->
    <div class="grid md:grid-cols-3 gap-4">

        @forelse($incomes as $income)

            <div class="bg-white border p-4 rounded shadow flex flex-col justify-between">

                <div>
                    <h2 class="font-bold">{{ $income->description }}</h2>

                    <p class="text-green-600 font-semibold">
                        ${{ number_format($income->amount, 2) }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($income->date)->format('d/m/Y') }}
                    </p>
                </div>

                <!-- ACTIONS -->
                <div class="flex justify-end gap-4 mt-4">

                    <!-- EDIT -->
                    <a href="{{ route('incomes.edit', $income) }}"
                       class="text-yellow-500 hover:text-yellow-600">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>

                    <!-- DELETE -->
                    <form id="delete-{{ $income->id }}"
                          action="{{ route('incomes.destroy', $income) }}"
                          method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button"
                                onclick="confirmDelete({{ $income->id }})"
                                class="text-red-600 hover:text-red-700">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                </div>

            </div>

        @empty
            <div class="col-span-3 text-center text-gray-500">
                No hay ingresos registrados
            </div>
        @endforelse

    </div>
</div>

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: '¿Eliminar ingreso?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-' + id).submit();
        }
    });
}
</script>

</x-layouts::app>