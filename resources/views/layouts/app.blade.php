<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name','Expense Tracker') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>(function(){const t=localStorage.getItem('theme');if(t==='dark'||(!t&&window.matchMedia('(prefers-color-scheme:dark)').matches))document.documentElement.classList.add('dark');})();</script>
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased transition-colors duration-200">
<div class="min-h-screen flex">
<aside class="hidden md:flex w-[260px] shrink-0 flex-col border-r border-zinc-200 dark:border-zinc-800 bg-white/80 dark:bg-zinc-900/60 backdrop-blur sticky top-0 h-screen">
<div class="px-6 py-6 flex items-center gap-3">
<div class="w-9 h-9 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 grid place-items-center font-bold">₮</div>
<div><div class="font-semibold leading-none">Expense Tracker</div><div class="text-xs text-zinc-500">Minimal Premium</div></div>
</div>
<nav class="px-3 space-y-1 text-sm">
<a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">◧ Dashboard</a>
<a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('transactions.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">⇅ Transactions</a>
<a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('categories.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">⊞ Categories</a>
<a href="{{ route('summary.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('summary.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">▥ Monthly Summary</a>
<a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('reports.*') ? 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900' : 'hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">📊 Reports</a>
</nav>
<div class="mt-auto p-4 border-t border-zinc-200 dark:border-zinc-800 space-y-2">
<button onclick="toggleTheme()" id="theme-toggle" class="w-full btn btn-ghost justify-between !min-h-[40px] !py-2 text-xs"><span class="flex items-center gap-2"><span id="theme-icon">○</span><span id="theme-label">Dark Mode</span></span><span class="w-9 h-5 rounded-full bg-zinc-200 dark:bg-zinc-700 relative transition"><span id="theme-dot" class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow transition-all dark:translate-x-4"></span></span></button>
<div class="card p-3 flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 grid place-items-center text-sm">{{ substr(auth()->user()->name??'U',0,1) }}</div>
<div class="text-sm min-w-0"><div class="font-medium leading-none truncate">{{ auth()->user()->name??'Guest' }}</div><div class="text-xs text-zinc-500 truncate">{{ auth()->user()->email??'' }}</div></div>
</div>
<a href="{{ route('profile.edit') }}" class="btn btn-ghost w-full justify-center mt-2 text-xs">Profile</a>
<form method="POST" action="{{ route('logout') }}" class="mt-2">@csrf<button class="w-full btn btn-ghost justify-center">Log Out</button></form>
</div>
</aside>
<div class="flex-1 min-w-0">
<header class="md:hidden sticky top-0 z-20 bg-white/80 dark:bg-zinc-900/80 backdrop-blur border-b border-zinc-200 dark:border-zinc-800 px-4 py-3 flex items-center justify-between">
<div class="flex items-center gap-2"><button onclick="document.getElementById('mobnav').classList.toggle('hidden')" class="w-8 h-8 rounded-lg border dark:border-zinc-700 grid place-items-center">≡</button><span class="font-semibold">Expense Tracker</span></div>
<div class="flex gap-2 text-sm items-center"><button onclick="toggleTheme()" class="w-8 h-8 rounded-full border dark:border-zinc-700 grid place-items-center text-xs" id="theme-icon-mob">◐</button><a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-full bg-zinc-900 dark:bg-white text-white dark:text-zinc-900">App</a></div>
</header>
<div id="mobnav" class="hidden md:hidden border-b bg-white dark:bg-zinc-900 px-4 py-3 space-y-1">
<a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('dashboard')?'bg-zinc-900 text-white':'' }}">Dashboard</a>
<a href="{{ route('transactions.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('transactions.*')?'bg-zinc-900 text-white':'' }}">Transactions</a>
<a href="{{ route('categories.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('categories.*')?'bg-zinc-900 text-white':'' }}">Categories</a>
<a href="{{ route('summary.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('summary.*')?'bg-zinc-900 text-white':'' }}">Summary</a>
<a href="{{ route('reports.index') }}" class="block px-3 py-2 rounded-xl {{ request()->routeIs('reports.*')?'bg-zinc-900 text-white':'' }}">Reports</a>
<form method="POST" action="{{ route('logout') }}">@csrf<button class="w-full text-left px-3 py-2">Logout</button></form>
</div>
<main class="max-w-6xl mx-auto px-4 md:px-8 py-6 md:py-8">
<div id="toast-success" class="hidden mb-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-3 text-sm flex justify-between"><span id="toast-msg">{{ session('success') }}</span><button onclick="this.parentElement.classList.add('hidden')" class="ml-4">✕</button></div>
@if(session('success'))<script>document.addEventListener('DOMContentLoaded',()=>{const t=document.getElementById('toast-success');t.classList.remove('hidden');setTimeout(()=>t.classList.add('hidden'),3500)});</script>@endif
@if(isset($errors) && $errors->any())<div class="mb-4 rounded-xl bg-red-50 text-red-700 border border-red-200 px-4 py-3 text-sm"><ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
{{ $slot }}
</main>
</div>
</div>
<script>
function toggleTheme(){
  const isDark=document.documentElement.classList.toggle('dark');
  localStorage.setItem('theme',isDark?'dark':'light');
  updateThemeUI();
}
function updateThemeUI(){
  const isDark=document.documentElement.classList.contains('dark');
  const label=document.getElementById('theme-label');
  const icon=document.getElementById('theme-icon');
  const mob=document.getElementById('theme-icon-mob');
  if(label) label.textContent=isDark?'Light Mode':'Dark Mode';
  if(icon) icon.textContent=isDark?'☀':'○';
  if(mob) mob.textContent=isDark?'☀':'◐';
}
document.addEventListener('DOMContentLoaded',updateThemeUI);
</script>
</body>
</html>
