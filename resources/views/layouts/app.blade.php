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

<nav class="bg-white dark:bg-gray-800 shadow sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <a
                href="/"
                class="font-bold text-xl text-orange-500">

                Lontong Balap Cak Gendut

            </a>

            {{-- Desktop Menu --}}
            <div
                class="hidden md:flex items-center gap-6">

                <a
                    href="/#menu"
                    class="text-gray-700 dark:text-gray-300 hover:text-orange-500 transition">

                    Menu

                </a>

                <a
                    href="/catering"
                    class="text-gray-700 dark:text-gray-300 hover:text-orange-500 transition">

                    Catering

                </a>

                <a
                    href="{{ route('orders.track') }}"
                    class="text-gray-700 dark:text-gray-300 hover:text-orange-500 transition">

                    Cek Pesanan

                </a>

                <a
                    href="{{ url('/helpdesk') }}"
                    class="text-gray-700 dark:text-gray-300 hover:text-orange-500 transition">

                    Helpdesk

                </a>

                <a
                    href="{{ route('helpdesk.search.form') }}"
                    class="text-gray-700 dark:text-gray-300 hover:text-orange-500 transition">

                    Cari Tiket

                </a>

                <a
                    href="{{ route('feedback.create') }}"
                    class="text-gray-700 dark:text-gray-300 hover:text-orange-500 transition">

                    Kritik & Saran

                </a>

            </div>

            {{-- Right Side --}}
            <div class="flex items-center gap-3">

                <button
                    id="theme-toggle"
                    class="px-3 py-2 rounded-lg
                           bg-gray-200 dark:bg-gray-700">

                    🌙

                </button>

                @auth

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="hidden md:inline-flex
                               text-orange-500 font-semibold">

                        Dashboard

                    </a>

                @endauth

                {{-- Mobile Menu Button --}}
                <button
                    id="mobile-menu-button"
                    class="md:hidden p-2 rounded-lg
                           bg-gray-200 dark:bg-gray-700">

                    ☰

                </button>

            </div>

        </div>

    </div>

    {{-- Mobile Menu --}}
    <div
        id="mobile-menu"
        class="hidden md:hidden border-t
               border-gray-200 dark:border-gray-700
               bg-white dark:bg-gray-800">

        <div class="px-6 py-4 space-y-3">

            <a
                href="/#menu"
                class="block text-gray-700 dark:text-gray-300 hover:text-orange-500">

                 Menu

            </a>

            <a
                href="/catering"
                class="block text-gray-700 dark:text-gray-300 hover:text-orange-500">

                 Catering

            </a>

            <a
                href="{{ route('orders.track') }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-orange-500">

                 Cek Pesanan

            </a>

            <a
                href="{{ url('/helpdesk') }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-orange-500">

                 Helpdesk

            </a>

            <a
                href="{{ route('helpdesk.search.form') }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-orange-500">

                 Cari Tiket

            </a>

            <a
                href="{{ route('feedback.create') }}"
                class="block text-gray-700 dark:text-gray-300 hover:text-orange-500">

                 Kritik & Saran

            </a>

            @auth

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="block text-orange-500 font-semibold">

                     Dashboard Admin

                </a>

            @endauth

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

<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-6 py-12">

        <div class="grid md:grid-cols-4 gap-8">

            <!-- Tentang -->
            <div>
                <h3 class="text-lg font-bold text-white mb-4">
                    Lontong Balap Cak Gendut
                </h3>

                <p class="text-sm leading-relaxed">
                    Menyajikan lontong balap
                autentik dengan cita rasa
                khas Surabaya sejak lama.
                </p>
            </div>

            <!-- Navigasi -->
            <div>
                <h3 class="text-lg font-bold text-white mb-4">
                    Navigasi
                </h3>

                <ul class="space-y-2">
                    <li>
                        <a href="/"
                           class="hover:text-orange-400">
                            Beranda
                        </a>
                    </li>

                    <li>
                        <a href="/#menu"
                           class="hover:text-orange-400">
                            Daftar Menu
                        </a>
                    </li>

                    <li>
                        <a href="/catering"
                           class="hover:text-orange-400">
                            Daftar Paket Catering
                        </a>
                    </li>

                    <li>
                        <a href="/cek-pesanan"
                           class="hover:text-orange-400">
                            Lacak Pesanan
                        </a>
                    </li>

                   
                </ul>
            </div>

            <!-- Bantuan -->
            <div>
                <h3 class="text-lg font-bold text-white mb-4">
                    Bantuan
                </h3>

                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/helpdesk') }}"
                           class="hover:text-orange-400">
                            Helpdesk
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('helpdesk.search.form') }}"
                        class="hover:text-orange-400">
                            Lacak Tiket
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('feedback.create') }}"
                        class="hover:text-orange-400">
                            Kritik & Saran
                        </a>
                    </li>

                    
               
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h3 class="text-lg font-bold text-white mb-4">
                    Kontak
                </h3>

                <ul class="space-y-2 text-sm">
                    <li>
                        📍 Jln. Embong Malang 38, Surabaya
                    </li>

                    <li>
                        📞 (031) 12345678
                    </li>

                    <li>
                        ✉️ arvotech@gmail.com
                    </li>
                </ul>
            </div>

        </div>

        <hr class="border-gray-700 my-8">

        <div class="flex flex-col md:flex-row justify-between items-center">

            <p class="text-sm">
                © {{ date('Y') }}
                Lontong Balap Cak Gendut.
                Seluruh Hak Cipta Dilindungi.
            </p>

            <div class="flex gap-4 mt-4 md:mt-0">

                <a href="#"
                   class="hover:text-orange-400">
                    Facebook
                </a>

                <a href="#"
                   class="hover:text-orange-400">
                    Instagram
                </a>

                <a href="#"
                   class="hover:text-orange-400">
                    WhatsApp
                </a>

            </div>

        </div>

    </div>
</footer>

</body>


</html>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const button =
        document.getElementById(
            'mobile-menu-button'
        );

    const menu =
        document.getElementById(
            'mobile-menu'
        );

    button.addEventListener(
        'click',
        function () {

            menu.classList.toggle('hidden');

        }
    );

});

</script>