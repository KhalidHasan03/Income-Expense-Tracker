<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name','Expense Tracker') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            const t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme:dark)').matches)) document.documentElement.classList.add('dark');
        })();
    </script>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased transition-colors duration-200">
    <div class="min-h-screen flex">
        <aside class="hidden md:flex w-[260px] shrink-0 flex-col border-r border-zinc-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900/60 backdrop-blur sticky top-0 h-screen">
            <div class="px-6 py-6 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 grid place-items-center font-bold">₮</div>
                <div>
                    <div class="font-semibold leading-none">Expense Tracker</div>
                    <div class="text-xs text-zinc-500">Minimal Premium</div>
                </div>
            </div>
            <nav class="px-3 space-y-1 text-sm">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">◧ Dashboard</a>
                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('transactions.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">⇅ Transactions</a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('categories.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">⊞ Categories</a>
                <a href="{{ route('summary.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('summary.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">▥ Monthly Summary</a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('reports.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">📊 Reports</a>
                @if(auth()->user()->isAdmin())<a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">👥 User Approvals</a>@endif
                @if(auth()->user()->isAdmin())<a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.transactions.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">✏️ Edit Approvals</a>@endif
            </nav>
            <div class="mt-auto p-4 border-t border-zinc-200 dark:border-zinc-800">
                @if(!auth()->user()->isApproved())<div class="rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 px-3 py-2 text-xs text-amber-700 dark:text-amber-300">⏳ Awaiting admin approval — <a href="{{ route('pending') }}" class="underline">View status</a></div>@endif
            </div>
        </aside>
        <div class="flex-1 min-w-0">
            <header class="hidden md:flex sticky top-0 z-20 bg-white/80 dark:bg-zinc-900/80 backdrop-blur border-b border-zinc-200 dark:border-zinc-800 px-6 py-3 items-center justify-end gap-4">
                <button onclick="toggleTheme()" class="flex items-center gap-2 text-xs px-3 py-2 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800 transition"><span id="theme-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg></span><span id="theme-label">Dark Mode</span></button>
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-zinc-100 dark:hover:bg-zinc-800 transition cursor-pointer">
                        <div class="w-8 h-8 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 grid place-items-center text-sm">{{ substr(auth()->user()->name??'U',0,1) }}</div>
                        <div class="text-sm min-w-0 text-left">
                            <div class="font-medium leading-none truncate">{{ auth()->user()->name??'Guest' }}</div>
                            <div class="text-xs text-zinc-500 truncate">{{ auth()->user()->email??'' }}</div>
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-lg py-1 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-transparent dark:hover:bg-transparent transition">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="w-full text-left px-4 py-2 text-sm hover:bg-transparent dark:hover:bg-transparent transition">Log Out</button></form>
                    </div>
                </div>
            </header>
            <header class="md:hidden sticky top-0 z-20 bg-white/80 dark:bg-zinc-900/80 backdrop-blur border-b border-zinc-200 dark:border-zinc-800 px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2"><button onclick="document.getElementById('mobnav').classList.toggle('hidden')" class="w-8 h-8 rounded-lg border dark:border-zinc-700 grid place-items-center">≡</button><span class="font-semibold">Expense Tracker</span></div>
                <div class="flex gap-2 text-sm items-center"><button onclick="toggleTheme()" class="w-8 h-8 rounded-full border dark:border-zinc-700 grid place-items-center" id="theme-icon-mob"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg></button><a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900">App</a></div>
            </header>
            <div id="mobnav" class="hidden md:hidden border-b bg-white dark:bg-zinc-900 px-4 py-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('dashboard')?'bg-zinc-900 text-white':'' }}">Dashboard</a>
                <a href="{{ route('transactions.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('transactions.*')?'bg-zinc-900 text-white':'' }}">Transactions</a>
                <a href="{{ route('categories.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('categories.*')?'bg-zinc-900 text-white':'' }}">Categories</a>
                <a href="{{ route('summary.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('summary.*')?'bg-zinc-900 text-white':'' }}">Summary</a>
                <a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('reports.*')?'bg-zinc-900 text-white':'' }}">Reports</a>
                @if(auth()->user()->isAdmin())<a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('admin.users.*')?'bg-zinc-900 text-white':'' }}">User Approvals</a>@endif
                @if(auth()->user()->isAdmin())<a href="{{ route('admin.transactions.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('admin.transactions.*')?'bg-zinc-900 text-white':'' }}">Edit Approvals</a>@endif
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="w-full text-left px-3 py-2">Logout</button></form>
            </div>
            <main class="max-w-6xl mx-auto px-4 md:px-8 py-6 md:py-8">
                <div id="toast-success" class="hidden mb-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-3 text-sm flex justify-between"><span id="toast-msg">{{ session('success') }}</span><button onclick="this.parentElement.classList.add('hidden')" class="ml-4">✕</button></div>
                @if(session('success'))<script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const t = document.getElementById('toast-success');
                        t.classList.remove('hidden');
                        setTimeout(() => t.classList.add('hidden'), 3500)
                    });
                </script>@endif
                @if(isset($errors) && $errors->any())<div class="mb-4 rounded-xl bg-red-50 text-red-700 border border-red-200 px-4 py-3 text-sm">
                    <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>@endif
                {{ $slot }}
            </main>
        </div>
    </div>
    <script>
        function toggleTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeUI();
        }

        function updateThemeUI() {
            const isDark = document.documentElement.classList.contains('dark');
            const label = document.getElementById('theme-label');
            const icon = document.getElementById('theme-icon');
            const mob = document.getElementById('theme-icon-mob');
            const moon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';
            const sun = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>';
            if (label) label.textContent = isDark ? 'Light Mode' : 'Dark Mode';
            if (icon) icon.innerHTML = isDark ? sun : moon;
            if (mob) mob.innerHTML = isDark ? sun : moon;
        }
        document.addEventListener('DOMContentLoaded', updateThemeUI);
    </script>
</body>

</html>
