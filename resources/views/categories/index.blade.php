<x-app-layout>
<div class="flex flex-wrap items-end justify-between gap-4 mb-6">
<div><h1 class="text-2xl font-semibold tracking-tight">Categories <span class="text-xs font-normal px-2 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800">Global • visible to all</span></h1><p class="text-sm text-zinc-500">{{ $stats['total'] }} total • <span class="text-emerald-600">{{ $stats['income'] }} income</span> • <span class="text-red-600">{{ $stats['expense'] }} expense</span> @if(auth()->user()->isManager()) • <span class="text-amber-600">View-only for Managers</span> @endif</p></div>
<form method="GET" class="flex items-center gap-2">
<input name="q" value="{{ request('q') }}" placeholder="Search categories…" class="input !w-44" oninput="if(this.value==='')this.form.submit()">
<select name="type" class="input !w-auto" onchange="this.form.submit()"><option value="">All types</option><option value="income" @selected(request('type')=='income')>Income</option><option value="expense" @selected(request('type')=='expense')>Expense</option></select>
@if(request()->filled('q') || request()->filled('type'))<a href="{{ route('categories.index') }}" class="btn btn-ghost">Reset</a>@endif
</form>
</div>

@if(auth()->user()->isManager())
<div class="card p-4 mb-6 bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-800"><div class="text-sm text-amber-700 dark:text-amber-300">🔒 You are logged in as <strong>Manager</strong> — you can view categories but cannot create, edit or delete. Contact an Admin for changes.</div></div>
@else
<div class="card p-5 mb-6">
<h3 class="font-medium mb-3">Create Category</h3>
<form method="POST" action="{{ route('categories.store') }}" class="grid md:grid-cols-5 gap-3 items-end">@csrf
<div><label class="text-xs font-medium">Name *</label><input name="name" value="{{ old('name') }}" required maxlength="60" placeholder="e.g. Groceries" class="input @error('name') ring-2 ring-red-400 @enderror">@error('name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
<div><label class="text-xs font-medium">Type *</label><select name="type" class="input">@error('type') ring-2 ring-red-400 @enderror<option value="expense" @selected(old('type')=='expense')>Expense</option><option value="income" @selected(old('type')=='income')>Income</option></select>@error('type')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
<div><label class="text-xs font-medium">Color</label><input type="color" name="color" value="{{ old('color','#6366f1') }}" class="input !p-1 h-[46px]"></div>
<div><label class="text-xs font-medium">Icon</label><input name="icon" value="{{ old('icon') }}" placeholder="tag / cash / food" class="input"></div>
<div><button class="btn btn-primary w-full justify-center">Create</button></div>
</form>
</div>
@endif

<div class="flex gap-2 mb-4 text-sm">
<a href="{{ route('categories.index') }}" class="px-3 py-1.5 rounded-full border {{ !request('type')?'bg-zinc-900 text-white':'' }}">All</a>
<a href="{{ route('categories.index',['type'=>'expense']) }}" class="px-3 py-1.5 rounded-full border {{ request('type')=='expense'?'bg-zinc-900 text-white':'' }}">Expense</a>
<a href="{{ route('categories.index',['type'=>'income']) }}" class="px-3 py-1.5 rounded-full border {{ request('type')=='income'?'bg-zinc-900 text-white':'' }}">Income</a>
</div>

@if($cats->isEmpty())
<div class="card p-12 text-center"><div class="text-4xl mb-3">⊞</div><div class="font-medium">No categories found</div><p class="text-sm text-zinc-500">Try different search or create a new one.</p></div>
@else
<div class="grid md:grid-cols-2 gap-4">
@foreach($cats as $c)
<div class="card p-4 flex items-center justify-between hover:shadow-md transition">
<div class="flex items-center gap-3 min-w-0"><span class="w-10 h-10 rounded-xl grid place-items-center font-bold text-white shrink-0" style="background: {{ $c->color }}">{{ substr($c->name,0,1) }}</span>
<div class="min-w-0"><div class="font-medium text-sm truncate">{{ $c->name }} <span class="ml-2 text-xs px-2 py-0.5 rounded-full {{ $c->type=='income'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-700' }}">{{ $c->type }}</span></div><div class="text-xs text-zinc-500">{{ $c->transactions_count }} transactions • {{ $c->created_at->diffForHumans() }} • <span class="text-zinc-400">by {{ $c->user->name ?? 'System' }}</span></div></div></div>
<div class="flex gap-1 shrink-0">
@if(auth()->user()->isManager())
<span class="text-xs px-2 py-1 text-zinc-400">No permission</span>
@else
<button onclick="openCatEdit({{ $c->id }})" class="btn btn-edit btn-sm">Edit</button>
<form method="POST" action="{{ route('categories.destroy',$c) }}" onsubmit="return confirm('Delete {{ $c->name }}? Only if no transactions.')">@csrf @method('DELETE')<button class="btn btn-delete btn-sm">Delete</button></form>
@endif
</div>
</div>

<dialog id="cat-{{ $c->id }}" class="rounded-2xl p-0 backdrop:bg-black/30 w-full max-w-md">
<form method="POST" action="{{ route('categories.update',$c) }}" class="p-6 space-y-4">@csrf @method('PUT')
<h3 class="font-semibold">Edit {{ $c->name }}</h3>
<div><label class="text-xs">Name</label><input name="name" value="{{ $c->name }}" required class="input"></div>
<div class="grid grid-cols-2 gap-3"><div><label class="text-xs">Type</label><select name="type" class="input"><option value="expense" @selected($c->type=='expense')>Expense</option><option value="income" @selected($c->type=='income')>Income</option></select></div><div><label class="text-xs">Color</label><input type="color" name="color" value="{{ $c->color }}" class="input !p-1 h-[46px]"></div></div>
@if(auth()->user()->isAdmin())
<div class="flex justify-end gap-2"><button type="button" onclick="this.closest('dialog').close()" class="btn btn-ghost">Cancel</button><button class="btn btn-info">Update</button></div>
@else
<div class="text-xs text-amber-600 text-center">Managers cannot update categories</div>
@endif
</form>
</dialog>
@endforeach
</div>
@endif
<script>function openCatEdit(id){document.getElementById('cat-'+id).showModal()}</script>
</x-app-layout>
