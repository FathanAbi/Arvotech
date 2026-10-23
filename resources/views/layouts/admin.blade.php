<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin Panel')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg">

        <div class="p-6 border-b dark:border-gray-700">

            <h1 class="text-xl font-bold">
                Admin Panel
            </h1>

            <p class="text-sm text-gray-500">
                Lontong Balap Mas Rusdi
            </p>

        </div>

        <nav class="p-4 space-y-2">

            <a
                href="{{ route('admin.dashboard') }}"
                class="block px-4 py-3 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-700">

                Dashboard

            </a>

            <a
                href="{{ route('admin.menus.index') }}"
                class="block px-4 py-3 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-700">

                Kelola Menu

            </a>

            <a
                href="{{ route('admin.catering-packages.index') }}"
                class="block px-4 py-3 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-700">

                Paket Catering

            </a>

        </nav>

    </aside>

    <!-- Content -->
    <main class="flex-1 p-8">

        @yield('content')

    </main>

</div>

</body>
</html>