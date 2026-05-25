<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen bg-white text-black">

    <!-- SIDEBAR -->
    <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-white">

        <!-- LOGO -->
        <flux:sidebar.header>

            <flux:sidebar.item class="!text-zinc-800 hover:!bg-zinc-100 rounded-lg flex items-center gap-2"
                icon="">

                <!-- ICONO LOGO -->
                <i class="fa-solid fa-circle-nodes text-blue-600"></i>

                <!-- BRAND -->
                <span class="text-lg font-semibold">
                    <span class="text-blue-600">Conta</span><span class="text-zinc-800">Track</span>
                </span>

            </flux:sidebar.item>

        </flux:sidebar.header>

        <!-- FINANZAS -->
        <flux:sidebar.group :heading="__('Finanzas')" class="grid text-zinc-500">

            <flux:sidebar.item class="!text-zinc-800 hover:!bg-zinc-100 rounded-lg" icon="chart-bar"
                :href="route('dashboard.finanzas')" :current="request()->routeIs('dashboard.finanzas')" wire:navigate>
                Dashboard
            </flux:sidebar.item>

            <flux:sidebar.item class="!text-zinc-800 hover:!bg-zinc-100 rounded-lg" icon="currency-dollar"
                :href="route('expenses.index')" :current="request()->routeIs('expenses.*')" wire:navigate>
                Gastos
            </flux:sidebar.item>

            <flux:sidebar.item class="!text-zinc-800 hover:!bg-zinc-100 rounded-lg" icon="banknotes"
                :href="route('incomes.index')" :current="request()->routeIs('incomes.*')" wire:navigate>
                Ingresos
            </flux:sidebar.item>

            <flux:sidebar.item class="!text-zinc-800 hover:!bg-zinc-100 rounded-lg" icon="tag"
                :href="route('categories.index')" :current="request()->routeIs('categories.*')" wire:navigate>
                Categorías
            </flux:sidebar.item>
            <flux:sidebar.item icon="envelope" :href="route('emails.index')" :current="request()->routeIs('emails.*')"
                wire:navigate>
                Correos
            </flux:sidebar.item>

        </flux:sidebar.group>

        <!--  SISTEMA -->


        <flux:spacer />

        <!-- 🔹 LINKS -->


        <!-- 🔹 USER -->
        <x-desktop-user-menu class="hidden lg:block !text-zinc-800" :name="auth()->user()->name" />

    </flux:sidebar>

    <!--  MOBILE HEADER -->
    <flux:header class="lg:hidden bg-white text-black">

        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">

            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu class="bg-white text-black">

                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5">

                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1">
                                <flux:heading class="truncate text-black">
                                    {{ auth()->user()->name }}
                                </flux:heading>

                                <flux:text class="truncate text-gray-600">
                                    {{ auth()->user()->email }}
                                </flux:text>
                            </div>

                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.item class="text-black" :href="route('profile.edit')" icon="cog" wire:navigate>
                    Settings
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item class="text-black w-full cursor-pointer" as="button" type="submit"
                        icon="arrow-right-start-on-rectangle">
                        Log out
                    </flux:menu.item>
                </form>

            </flux:menu>

        </flux:dropdown>

    </flux:header>

    <!-- 🔥 CONTENIDO -->
    {{ $slot }}

    <!-- 🔔 TOAST -->
    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts

</body>

</html>
