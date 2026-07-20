<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link  rel="icon" type="image/png" href="{{ asset('upload/frontent/favicon2.jpg') }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen overflow-hidden">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div id="app">

        @if(!request()->routeIs('login') && !request()->routeIs('register'))
        <!-- Navbar -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between items-center h-16">

                    <!-- Logo -->
                    <div>
                        <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>

                    <!-- Right Menu -->
                    <div class="flex items-center space-x-4">

                        @guest

                        @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                            class="text-gray-700 hover:text-blue-600">
                            Login
                        </a>
                        @endif

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="text-gray-700 hover:text-blue-600">
                            Register
                        </a>
                        @endif

                        @else

                        <!-- Dropdown -->
                        <div class="relative group">

                            <button class="flex items-center text-gray-700 hover:text-blue-600 focus:outline-none">
                                {{ Auth::user()->name }}

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 ml-1"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div class="absolute right-0 mt-0 w-48 bg-white border rounded-lg shadow-lg
                hidden group-hover:block group-focus-within:block z-50">

                                <a href="{{ route('logout') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100"
                                    onclick="event.preventDefault();
           document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form"
                                    action="{{ route('logout') }}"
                                    method="POST"
                                    class="hidden">
                                    @csrf
                                </form>

                            </div>
                        </div>

                        @endguest

                    </div>
                </div>
            </div>
        </nav>
        @endif
        <!-- Main Content -->
        <main class="py-6 px-4">
            @yield('content')
        </main>

    </div>

</body>

</html>
