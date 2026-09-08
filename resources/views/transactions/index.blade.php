<x-app-layout>
<div class="flex flex-wrap gap-3 items-end justify-between mb-6">
<div><h1 class="text-2xl font-semibold tracking-tight">Transactions</h1><p class="text-sm text-zinc-500">{{ $totals['count'] }} found • Income ${{ number_format($totals['income'],2) }} • Expense ${{ number_format($totals['expense'],2) }}</p></div>
<button onclick="document.getElementById('addTx').showModal()" class="btn btn-primary">+ Add</button>
</div>

<form method="GET" class="card p-4 mb-4 grid md:grid-cols-5 gap-3 items-end">
<input name="q" value="{{ request('q') }}" placeholder="Search title or note…" class="input">
<select name="type" class="input"><option value="">All types</option><option value="income" @selected(request('type')=='income')>Income</option><option value="expense" @selected(request('type')=='expense')>Expense</option></select>
<select name="category_id" class="input"><option value="">All categories</option>@foreach($cats as $cat)<option value="{{ $cat->id }}" @selected(request('category_id')==$cat->id)>{{ $cat->name }} ({{ $cat->type }})</option>@endforeach</select>
<input type="month" name="month" value="{{ request('month') }}" class="input">
<div class="flex gap-2"><button class="btn btn-primary flex-1 justify-center">Filter</button><a href="{{ route('transactions.index') }}" class="btn btn-ghost">Reset</a></div>
</form>

<dialog id="addTx" class="rounded-2xl p-0 backdrop:bg-black/30 w-full max-w-2xl">
<div class="p-6">
<h3 class="font-semibold text-lg mb-4">Add Transaction</h3>
<form method="POST" action="{{ route('transactions.store') }}" class="grid md:grid-cols-2 gap-3">@csrf
<div><label class="text-xs font-medium">Title *</label><input name="title" value="{{ old('title') }}" required placeholder="e.g. Grocery run" class="input @error('title') ring-2 ring-red-400 @enderror">@error('title')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
<div><label class="text-xs font-medium">Amount *</label><input name="amount" value="{{ old('amount') }}" required type="number" step="0.01" min="0.01" placeholder="0.00" class="input @error('amount') ring-2 ring-red-400 @enderror">@error('amount')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
<div><label class="text-xs font-medium">Type *</label><select name="type" required class="input" id="add-type" onchange="filterCats(this.value,'add-cat')"><option value="expense">Expense</option><option value="income">Income</option></select>@error('type')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
<div><label class="text-xs font-medium">Category *</label><select name="category_id" required class="input" id="add-cat">@foreach($cats as $cat)<option value="{{ $cat->id }}" data-type="{{ $cat->type }}">{{ $cat->name }} ({{ $cat->type }})</option>@endforeach</select>@error('category_id')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
<div><label class="text-xs font-medium">Date *</label><input name="transacted_at" required type="date" value="{{ old('transacted_at', now()->toDateString()) }}" class="input @error('transacted_at') ring-2 ring-red-400 @enderror">@error('transacted_at')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
<div><label class="text-xs font-medium">Note</label><input name="note" value="{{ old('note') }}" placeholder="Optional" class="input"></div>
<div class="md:col-span-2 flex justify-end gap-2 mt-2"><button type="button" onclick="this.closest('dialog').close()" class="btn btn-ghost">Cancel</button><button class="btn btn-primary">Save</button></div>
</form>
@if($cats->isEmpty())<p class="text-xs text-amber-600 mt-3">Please create a category first in <a href="{{ route('categories.index') }}" class="underline">Categories</a>.</p>@endif
</div>
</dialog>

<div class="card overflow-hidden">
<div class="flex items-center justify-between px-4 py-3 border-b border-zinc-100 dark:border-zinc-800 text-xs text-zinc-500">
<span>Sort: <a href="{{ request()->fullUrlWithQuery(['sort'=>'transacted_at','dir'=>request('dir')=='asc'?'desc':'asc']) }}" class="underline">Date {{ request('dir')=='asc'?'↑':'↓' }}</a> • <a href="{{ request()->fullUrlWithQuery(['sort'=>'amount','dir'=>request('dir')=='asc'?'desc':'asc']) }}" class="underline">Amount</a></span>
<span>{{ $txs->firstItem() ?? 0 }}-{{ $txs->lastItem() ?? 0 }} of {{ $txs->total() }}</span>
</div>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500"><tr><th class="text-left px-4 py-3">Title</th><th class="text-left px-4 py-3">Category</th><th class="text-left px-4 py-3">Date</th><th class="text-right px-4 py-3">Amount</th><th class="px-4 py-3 text-right">Actions</th></tr></thead>
<tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
@forelse($txs as $t)
<tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
<td class="px-4 py-3"><div class="font-medium">{{ $t->title }}</div>@if($t->note)<div class="text-xs text-zinc-500 truncate max-w-[220px]">{{ Str::limit($t->note,40) }}</div>@endif</td>
<td class="px-4 py-3"><span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full" style="background: {{ $t->category->color }}"></span>{{ $t->category->name }}</span> <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full {{ $t->type=='income'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-700' }}">{{ $t->type }}</span></td>
<td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $t->transacted_at->format('Y-m-d') }}</td>
<td class="px-4 py-3 text-right font-semibold {{ $t->type=='income'?'text-emerald-600':'text-red-600' }}">{{ $t->type=='income'?'+':'-' }}${{ number_format($t->amount,2) }}</td>
<td class="px-4 py-3 text-right"><div class="flex justify-end gap-1">
<button onclick="openEdit({{ $t->id }})" class="text-xs px-2 py-1 rounded-full border hover:bg-zinc-100 dark:hover:bg-zinc-800">Edit</button>
<form method="POST" action="{{ route('transactions.destroy',$t) }}" onsubmit="return confirm('Delete {{ $t->title }}?')">@csrf @method('DELETE')<button class="text-xs px-2 py-1 rounded-full bg-red-50 text-red-600 hover:bg-red-100">Delete</button></form>
</div></td>
</tr>

<dialog id="edit-{{ $t->id }}" class="rounded-2xl p-0 backdrop:bg-black/30 w-full max-w-2xl">
<div class="p-6">
<h3 class="font-semibold mb-4">Edit Transaction</h3>
<form method="POST" action="{{ route('transactions.update',$t) }}" class="grid md:grid-cols-2 gap-3">@csrf @method('PUT')
<input name="title" value="{{ $t->title }}" required class="input my-2">
<input name="amount" value="{{ $t->amount }}" required type="number" step="0.01" class="input my-2">
<select name="type" required class="input my-2 edit-type" onchange="filterCats(this.value,'edit-cat-{{ $t->id }}')"><option value="expense" @selected($t->type=='expense')>Expense</option><option value="income" @selected($t->type=='income')>Income</option></select>
<select name="category_id" required class="input my-2" id="edit-cat-{{ $t->id }}">@foreach($cats as $cat)<option value="{{ $cat->id }}" data-type="{{ $cat->type }}" @selected($cat->id==$t->category_id)>{{ $cat->name }} ({{ $cat->type }})</option>@endforeach</select>
<input name="transacted_at" value="{{ $t->transacted_at->format('Y-m-d') }}" required type="date" class="input my-2">
<input name="note" value="{{ $t->note }}" placeholder="Note" class="input my-2">
<div class="md:col-span-2 flex justify-end gap-2"><button type="button" onclick="this.closest('dialog').close()" class="btn btn-ghost">Cancel</button><button class="btn btn-primary">Update</button></div>
</form>
</div>
</dialog>

@empty<tr><td colspan="5" class="text-center py-16"><div class="text-3xl mb-2">∅</div><div class="font-medium">No transactions</div><p class="text-sm text-zinc-500">Adjust filters or add a new transaction.</p></td></tr>@endforelse
</tbody>
</table>
</div>
<div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800">{{ $txs->links() }}</div>
</div>

<script>
function filterCats(type, selectId){
  const sel=document.getElementById(selectId);
  [...sel.options].forEach(o=>{ o.hidden = o.dataset.type !== type; });
  const visible=[...sel.options].filter(o=>!o.hidden);
  if(visible.length && sel.selectedOptions[0]?.hidden) sel.value=visible[0].value;
}
function openEdit(id){ document.getElementById('edit-'+id).showModal(); const s=document.querySelector('#edit-'+id+' .edit-type'); if(s) filterCats(s.value,'edit-cat-'+id); }
document.addEventListener('DOMContentLoaded',()=>{ const a=document.getElementById('add-type'); if(a) filterCats(a.value,'add-cat'); });
</script>
</x-app-layout>
