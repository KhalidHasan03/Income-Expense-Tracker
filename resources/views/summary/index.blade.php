<x-app-layout>
<div class="flex flex-wrap items-end justify-between gap-4 mb-6"><div><h1 class="text-2xl font-semibold tracking-tight">Monthly Summary</h1><p class="text-sm text-zinc-500">{{ $month }} • {{ $daily->count() }} active days @if($topExpense) • Top: {{ $topExpense->category->name }} @endif</p></div>
<form method="GET" class="flex items-center gap-2"><select name="month" class="input !w-auto" onchange="this.form.submit()">@foreach($months as $m)<option value="{{ $m }}" @selected($m==$month)>{{ $m }}</option>@endforeach</select><button type="button" onclick="window.print()" class="btn btn-ghost">Print</button></form></div>

@php $savings=$income-$expense; $pct=$income>0?($savings/$income*100):0; @endphp
<div class="grid md:grid-cols-3 gap-4">
<div class="card p-5"><div class="text-xs uppercase tracking-widest text-emerald-600">Income</div><div class="mt-2 text-2xl font-semibold text-emerald-600">${{ number_format($income,2) }}</div><a href="{{ route('transactions.index',['month'=>$month,'type'=>'income']) }}" class="text-xs underline">View income →</a></div>
<div class="card p-5"><div class="text-xs uppercase tracking-widest text-red-600">Expense</div><div class="mt-2 text-2xl font-semibold text-red-600">${{ number_format($expense,2) }}</div><a href="{{ route('transactions.index',['month'=>$month,'type'=>'expense']) }}" class="text-xs underline">View expense →</a></div>
<div class="card p-5"><div class="text-xs uppercase tracking-widest text-zinc-500">Savings</div><div class="mt-2 text-2xl font-semibold {{ $savings>=0?'text-emerald-600':'text-red-600' }}">${{ number_format($savings,2) }}</div><div class="mt-2 h-2 rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden"><div class="h-full {{ $savings>=0?'bg-emerald-500':'bg-red-500' }}" style="width: {{ max(0,min(100,$pct)) }}%"></div></div><div class="text-xs text-zinc-500 mt-1">{{ number_format($pct,1) }}% saved</div></div>
</div>

@if(!$daily->isEmpty())
<div class="card p-5 mt-4"><div class="font-medium mb-3">Daily Flow ({{ $month }})</div><canvas id="daily" height="80"></canvas></div>
@endif

<div class="grid md:grid-cols-2 gap-4 mt-4">
<div class="card p-5"><h3 class="font-medium mb-3">Income by Category</h3><canvas id="inc"></canvas>@if($byCatIncome->isEmpty())<div class="text-sm text-zinc-500 text-center py-6">No income this month • <a href="{{ route('transactions.index') }}" class="underline">Add</a></div>@else<div class="mt-4 space-y-2">@foreach($byCatIncome as $r)<a href="{{ route('transactions.index',['month'=>$month,'category_id'=>$r->category_id]) }}" class="flex justify-between text-sm hover:bg-zinc-50 dark:hover:bg-zinc-800 px-2 py-1 rounded-lg"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full" style="background: {{ $r->category->color }}"></span>{{ $r->category->name }}</span><span class="font-medium">${{ number_format($r->total,2) }} <span class="text-xs text-zinc-500">({{ $income>0?number_format($r->total/$income*100,1):0 }}%)</span></span></a>@endforeach</div>@endif</div>
<div class="card p-5"><h3 class="font-medium mb-3">Expense by Category</h3><canvas id="exp"></canvas>@if($byCatExpense->isEmpty())<div class="text-sm text-zinc-500 text-center py-6">No expense this month</div>@else<div class="mt-4 space-y-2">@foreach($byCatExpense as $r)<a href="{{ route('transactions.index',['month'=>$month,'category_id'=>$r->category_id]) }}" class="flex justify-between text-sm hover:bg-zinc-50 dark:hover:bg-zinc-800 px-2 py-1 rounded-lg"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full" style="background: {{ $r->category->color }}"></span>{{ $r->category->name }}</span><span class="font-medium">${{ number_format($r->total,2) }} <span class="text-xs text-zinc-500">({{ $expense>0?number_format($r->total/$expense*100,1):0 }}%)</span></span></a>@endforeach</div>@endif</div>
</div>

<script>
const inc=@json($byCatIncome->map(fn($r)=>['name'=>$r->category->name,'total'=>(float)$r->total,'color'=>$r->category->color])->values());
const exp=@json($byCatExpense->map(fn($r)=>['name'=>$r->category->name,'total'=>(float)$r->total,'color'=>$r->category->color])->values());
const daily=@json($daily->map(fn($r)=>['d'=>$r->d,'inc'=>(float)$r->inc,'exp'=>(float)$r->exp])->values());
if(inc.length) new Chart(document.getElementById('inc'),{type:'doughnut',data:{labels:inc.map(i=>i.name),datasets:[{data:inc.map(i=>i.total),backgroundColor:inc.map(i=>i.color)}]},options:{plugins:{legend:{position:'bottom'}}}});
if(exp.length) new Chart(document.getElementById('exp'),{type:'doughnut',data:{labels:exp.map(i=>i.name),datasets:[{data:exp.map(i=>i.total),backgroundColor:exp.map(i=>i.color)}]},options:{plugins:{legend:{position:'bottom'}}}});
if(daily.length) new Chart(document.getElementById('daily'),{type:'line',data:{labels:daily.map(d=>d.d),datasets:[{label:'Income',data:daily.map(d=>d.inc),borderColor:'#10b981',tension:.3},{label:'Expense',data:daily.map(d=>d.exp),borderColor:'#ef4444',tension:.3}]},options:{responsive:true,plugins:{legend:{position:'bottom'}},scales:{y:{beginAtZero:true}}}});
</script>
</x-app-layout>
