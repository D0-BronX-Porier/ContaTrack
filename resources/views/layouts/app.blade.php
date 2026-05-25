<x-layouts::app.sidebar :title="$title ?? null">

    <!-- 🔥 FORZAMOS MODO CLARO SOLO EN CONTENIDO -->
   <flux:main class="!bg-white !text-black">
    {{ $slot }}
</flux:main>

</x-layouts::app.sidebar>