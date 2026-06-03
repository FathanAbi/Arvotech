<!DOCTYPE html>
<html
    lang="id"
    class="scroll-smooth">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Lontong Balap Cak Gendut
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body
class="bg-gray-100 text-gray-900
dark:bg-gray-900
dark:text-gray-100
transition-colors duration-300">

<nav
class="bg-white dark:bg-gray-800 shadow">

    <div
    class="max-w-7xl mx-auto px-6">

        <div
        class="flex justify-between h-16 items-center">

            <a
            href="/"
            class="font-bold text-xl">

                Lontong Balap Cak Gendut

            </a>

            <a
                href="{{ route('orders.track') }}"
                class="text-gray-700 dark:text-gray-300 hover:text-orange-500">

                Cek Pesanan

            </a>

            <div
            class="flex items-center gap-4">

                <button
                    id="theme-toggle"
                    class="px-3 py-2 rounded-lg
                    bg-gray-200
                    dark:bg-gray-700">

                    🌙

                </button>

                @auth

                <a
                href="{{ route('admin.dashboard') }}"
                class="text-orange-500">

                    Dashboard

                </a>

                @endauth

            </div>

        </div>

    </div>

</nav>

<main>

    @yield('content')

</main>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const themeToggle =
        document.getElementById('theme-toggle');

    const html =
        document.documentElement;

    if (localStorage.getItem('theme') === 'dark') {
        html.classList.add('dark');
    }

    themeToggle.addEventListener('click', () => {

        html.classList.toggle('dark');

        localStorage.setItem(
            'theme',
            html.classList.contains('dark')
                ? 'dark'
                : 'light'
        );
    });

});
</script>

</body>
</html>