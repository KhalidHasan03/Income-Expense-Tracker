<x-app-layout>
<div class="flex flex-wrap items-end justify-between gap-4 mb-6">
<div><h1 class="text-2xl font-semibold tracking-tight">Dashboard</h1><p class="text-sm text-zinc-500">Overview for {{ $month }} • {{ $totals['txCount'] }} transactions • {{ $totals['catCount'] }} categories</p></div>
<form method="GET" class="flex items-center gap-2"><input type="month" name="month" value="{{ $month }}" class="input !w-auto" onchange="this.form.submit()"><a href="{{ route('transactions.index') }}" class="btn btn-primary">+ Transaction</a><a href="{{ route('categories.index') }}" class="btn btn-ghost">+ Category</a></form>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
<a href="{{ route('summary.index',['month'=>$month]) }}" class="card p-5 hover:shadow-md transition"><div class="text-xs uppercase tracking-widest text-zinc-500">Balance</div><div class="mt-2 text-2xl font-semibold">${{ number_format($balance,2) }}</div><div class="text-xs text-zinc-500 mt-1">All time • click for summary</div></a>
<a href="{{ route('transactions.index',['month'=>$month,'type'=>'income']) }}" class="card p-5 hover:shadow-md transition"><div class="text-xs uppercase tracking-widest text-emerald-600">Income</div><div class="mt-2 text-2xl font-semibold text-emerald-600">+${{ number_format($incomeM,2) }}</div><div class="text-xs text-zinc-500 mt-1">This month</div></a>
<a href="{{ route('transactions.index',['month'=>$month,'type'=>'expense']) }}" class="card p-5 hover:shadow-md transition"><div class="text-xs uppercase tracking-widest text-red-600">Expense</div><div class="mt-2 text-2xl font-semibold text-red-600">-${{ number_format($expenseM,2) }}</div><div class="text-xs text-zinc-500 mt-1">Savings ${{ number_format($incomeM-$expenseM,2) }}</div></a>
<div class="card p-5"><div class="text-xs uppercase tracking-widest text-zinc-500">Avg Expense</div><div class="mt-2 text-2xl font-semibold">${{ number_format($totals['avgExpense'],2) }}</div><div class="text-xs text-zinc-500 mt-1">Per transaction this month</div></div>
</div>

<div class="grid md:grid-cols-3 gap-4 mt-4">
<div class="card p-5 md:col-span-2"><div class="flex justify-between items-center mb-4"><div class="font-medium">6-Month Trend</div><span class="text-xs text-zinc-500">Bar • Income vs Expense</span></div><canvas id="trend" height="120"></canvas></div>
<div class="card p-5"><div class="font-medium mb-4">Expense by Category</div><canvas id="cats" height="180"></canvas>@if($byCat->isEmpty())<div class="text-sm text-zinc-500 text-center py-8">No expense this month<br><a href="{{ route('transactions.index') }}" class="underline">Add transaction</a></div>@else<div class="mt-3 space-y-2">@foreach($byCat as $r)<div class="flex justify-between text-sm"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full" style="background: {{ $r->category->color }}"></span>{{ $r->category->name }}</span><span class="font-medium">${{ number_format($r->total,2) }}</span></div>@endforeach</div>@endif</div>
</div>

<div class="card p-5 mt-4">
<div class="flex items-center justify-between mb-4"><div class="font-medium">Recent Transactions</div><a href="{{ route('transactions.index') }}" class="text-sm underline">View all →</a></div>
<div class="divide-y divide-zinc-100 dark:divide-zinc-800">
@forelse($recent as $t)<div class="py-3 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-800/40 px-2 rounded-xl">
<div class="flex items-center gap-3 min-w-0"><span class="w-9 h-9 rounded-xl grid place-items-center text-xs font-semibold shrink-0" style="background: {{ $t->category->color??'#eee' }}20; border:1px solid {{ $t->category->color??'#ddd' }}40">{{ substr($t->category->name??'?',0,1) }}</span><div class="min-w-0"><div class="font-medium text-sm truncate">{{ $t->title }}</div><div class="text-xs text-zinc-500 truncate">{{ $t->category->name }} • {{ $t->transacted_at->format('M d, Y') }}</div></div></div>
<div class="flex items-center gap-2 shrink-0"><span class="text-sm font-semibold {{ $t->type=='income'?'text-emerald-600':'text-red-600' }}">{{ $t->type=='income'?'+':'-' }}${{ number_format($t->amount,2) }}</span><a href="{{ route('transactions.index',['q'=>$t->title]) }}" class="text-xs px-2 py-1 rounded-full border">View</a></div>
</div>@empty<div class="text-sm text-zinc-500 py-10 text-center">No transactions yet.<br><a href="{{ route('transactions.index') }}" class="btn btn-primary mt-3">Add your first</a></div>@endforelse
</div>
</div>

<script>
const trend=@json($trend);
new Chart(document.getElementById('trend'),{type:'bar',data:{labels:trend.map(t=>t.label),datasets:[{label:'Income',data:trend.map(t=>t.income),backgroundColor:'#10b981'},{label:'Expense',data:trend.map(t=>t.expense),backgroundColor:'#ef4444'}]},options:{responsive:true,plugins:{legend:{position:'bottom'}},scales:{y:{beginAtZero:true}}}});
const cats=@json($byCat->map(fn($r)=>['name'=>$r->category->name,'total'=>(float)$r->total,'color'=>$r->category->color]));
if(cats.length) new Chart(document.getElementById('cats'),{type:'doughnut',data:{labels:cats.map(c=>c.name),datasets:[{data:cats.map(c=>c.total),backgroundColor:cats.map(c=>c.color)}]},options:{plugins:{legend:{position:'bottom'}}}});
</script>
</x-app-layout>
