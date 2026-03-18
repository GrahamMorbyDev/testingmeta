<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - Task Manager</title>

    {{-- Tailwind CDN for quick admin UI (replace with compiled assets in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Livewire styles --}}
    @if (class_exists(\Livewire\Livewire::class))
        @livewireStyles
    @endif

    {{-- Alpine.js for small interactions --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold leading-tight">@yield('page_title', 'Admin Dashboard')</h1>
            </div>
        </header>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @if (class_exists(\Livewire\Livewire::class))
        @livewireScripts
    @endif
</body>
</html>
