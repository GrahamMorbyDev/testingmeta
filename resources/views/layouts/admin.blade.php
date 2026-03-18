<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Admin') - {{ config('app.name') }}</title>

    <!-- Tailwind - assumes tailwind is built into app.css -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @livewireStyles
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen">
    <div class="min-h-screen">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="text-lg font-semibold">{{ config('app.name') }}</a>
                    <nav class="hidden sm:flex space-x-2 text-sm text-gray-600">
                        <a href="{{ route('admin.agents.index') }}" class="px-2 py-1 hover:underline">Agents</a>
                    </nav>
                </div>
                <div class="text-sm text-gray-600">
                    <!-- Example auth/links area; adapt to your app -->
                    @auth
                        <span class="mr-4">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:underline">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </header>

        <main class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-6 p-4 rounded bg-green-50 border border-green-200 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
