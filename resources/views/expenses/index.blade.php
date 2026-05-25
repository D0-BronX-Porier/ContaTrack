<x-layouts::app :title="'Gastos'">

    <div class="p-6 flex flex-col gap-6">

        <!-- ALERTAS -->
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6'
                });
            </script>
        @endif

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

        <!-- HEADER -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Gastos</h1>

            <a href="{{ route('expenses.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                + Nuevo
            </a>
        </div>

        <!-- GRID -->
        <div class="grid md:grid-cols-3 gap-4">

            @forelse($expenses as $expense)

                <div class="bg-white border p-4 rounded shadow flex flex-col justify-between">

                    <!-- TOP -->
                    <div>

                        <div class="flex items-center gap-2 mb-2">

                            <div class="w-8 h-8 flex items-center justify-center rounded-full bg-red-100 text-red-600">
                                <i class="fa-solid fa-receipt"></i>
                            </div>

                            <h2 class="font-bold">
                                {{ $expense->description }}
                            </h2>

                        </div>

                        <p class="text-red-600 font-semibold text-lg">
                            ${{ number_format($expense->amount, 2) }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}
                        </p>

                        <p class="text-sm text-gray-600 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-folder text-gray-400"></i>
                            {{ $expense->category->name ?? 'Sin categoría' }}
                        </p>

                        <!-- DEDUCIBLE -->
                        @if(auth()->user()->role === 'admin')

                            <!-- ADMIN: puede cambiar -->
                            <form action="{{ route('expenses.toggleDeductible', $expense) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    class="mt-2 text-xs px-2 py-1 rounded flex items-center gap-1
                                    {{ $expense->is_deductible ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                    
                                    @if($expense->is_deductible)
                                        <i class="fa-solid fa-check"></i> Deducible
                                    @else
                                        <i class="fa-solid fa-xmark"></i> No deducible
                                    @endif

                                </button>
                            </form>

                        @else

                            <!-- USER: solo visual -->
                            @if($expense->is_deductible)
                                <span class="inline-block mt-2 text-xs px-2 py-1 rounded bg-green-100 text-green-700">
                                    <i class="fa-solid fa-check"></i> Deducible
                                </span>
                            @else
                                <span class="inline-block mt-2 text-xs px-2 py-1 rounded bg-red-100 text-red-600">
                                    <i class="fa-solid fa-xmark"></i> No deducible
                                </span>
                            @endif

                        @endif

                    </div>

                    <!-- ACTIONS ICONOS -->
                    <div class="flex justify-between mt-4 text-lg">

                        <!-- EDIT -->
                        <a href="{{ route('expenses.edit', $expense) }}"
                           class="text-yellow-600 hover:text-yellow-700">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <!-- DELETE -->
                        <form id="delete-form-{{ $expense->id }}"
                            action="{{ route('expenses.destroy', $expense) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="button"
                                class="text-red-600 hover:text-red-700"
                                onclick="confirmDelete({{ $expense->id }})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                    </div>

                </div>

            @empty
                <div class="col-span-3 text-center text-gray-500">
                    No hay gastos registrados
                </div>
            @endforelse

        </div>

    </div>

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: '¿Eliminar gasto?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>

</x-layouts::app>