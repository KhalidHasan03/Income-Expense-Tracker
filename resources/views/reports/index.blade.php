<x-app-layout>
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Reports @if(auth()->user()->isAdmin())<span class="text-xs font-normal px-2 py-1 rounded-full bg-indigo-100 text-indigo-700">Admin • all users</span>@else<span class="text-xs font-normal px-2 py-1 rounded-full bg-amber-100 text-amber-700">Manager • own only</span>@endif</h1>
            <p class="text-sm text-zinc-500">{{ \Carbon\Carbon::parse($from)->format('M d, Y') }} — {{ \Carbon\Carbon::parse($to)->format('M d, Y') }} • Income ${{ number_format($income,2) }} • Expense ${{ number_format($expense,2) }} • Balance ${{ number_format($balance,2) }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.export', array_merge(request()->all(),['format'=>'csv'])) }}" class="btn btn-ghost btn-sm">Export CSV</a>
            <a href="{{ route('reports.export', array_merge(request()->all(),['format'=>'pdf'])) }}" class="btn btn-primary btn-sm">Export PDF</a>
        </div>
    </div>

    <div class="grid md:grid-cols-4 gap-4 mb-4">
        <div class="card p-4">
            <div class="text-xs uppercase tracking-widest text-emerald-600">Income</div>
            <div class="text-xl font-semibold text-emerald-600">${{ number_format($income,2) }}</div>
        </div>
        <div class="card p-4">
            <div class="text-xs uppercase tracking-widest text-red-600">Expense</div>
            <div class="text-xl font-semibold text-red-600">${{ number_format($expense,2) }}</div>
        </div>
        <div class="card p-4">
            <div class="text-xs uppercase tracking-widest text-zinc-500">Balance</div>
            <div class="text-xl font-semibold {{ $balance>=0?'text-emerald-600':'text-red-600' }}">${{ number_format($balance,2) }}</div>
        </div>
        <div class="card p-4">
            <div class="text-xs uppercase tracking-widest text-zinc-500">Records</div>
            <div class="text-xl font-semibold">{{ $txs->total() }}</div>
        </div>
    </div>

    <form method="GET" class="card p-4 mb-4 grid md:grid-cols-6 gap-3 items-end">
        <input type="date" name="from" value="{{ $from }}" class="input">
        <input type="date" name="to" value="{{ $to }}" class="input">
        <select name="type" class="input">
            <option value="">All types</option>
            <option value="income" @selected(request('type')=='income' )>Income</option>
            <option value="expense" @selected(request('type')=='expense' )>Expense</option>
        </select>
        <select name="category_id" class="input">
            <option value="">All categories</option>@foreach($cats as $cat)<option value="{{ $cat->id }}" @selected(request('category_id')==$cat->id)>{{ $cat->name }}</option>@endforeach
        </select>
        <input name="q" value="{{ request('q') }}" placeholder="Search…" class="input">
        <div class="flex gap-2"><button class="btn btn-primary flex-1 justify-center btn-sm !min-h-[46px]">Filter</button><a href="{{ route('reports.index') }}" class="btn btn-ghost btn-sm !min-h-[46px]">Reset</a></div>
    </form>

    <div class="grid md:grid-cols-2 gap-4 mb-4">
        <div class="card p-5">
            <h3 class="font-medium mb-3">Breakdown by Category</h3>
            @if($byCat->isEmpty())<div class="text-sm text-zinc-500 text-center py-6">No data for range</div>
            @else<canvas id="repChart" height="160"></canvas>
            <div class="mt-3 space-y-1">@foreach($byCat as $b)<div class="flex justify-between text-sm"><span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full" style="background: {{ $b->category->color ?? '#6366f1' }}"></span>{{ $b->category->name ?? '—' }}</span><span>${{ number_format($b->total,2) }} ({{ $b->cnt }})</span></div>@endforeach</div>
            @endif
        </div>
        <div class="card p-5">
            <h3 class="font-medium mb-3">Details</h3>
            <p class="text-sm text-zinc-500">All transactions are ordered <strong>newest first (DESC)</strong> — last transaction appears on top. Use Export for offline records.</p>
            <div class="mt-4 flex gap-2 flex-wrap">@foreach($cats->take(8) as $c)<span class="text-xs px-2 py-1 rounded-full border" style="border-color: {{ $c->color }}">{{ $c->name }}</span>@endforeach</div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">Date</th>
                        <th class="text-left px-4 py-3">Title</th>
                        <th class="text-left px-4 py-3">Category</th>@if(auth()->user()->isAdmin())<th class="text-left px-4 py-3">Owner</th>@endif<th class="text-right px-4 py-3">Amount</th>
                        <th class="text-center px-4 py-3">Att</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($txs as $t)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $t->transacted_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $t->title }}</div>
                            <div class="text-xs text-zinc-500">{{ \Illuminate\Support\Str::limit($t->note,40) }}</div>
                        </td>
                        <td class="px-4 py-3"><span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full" style="background: {{ $t->category->color ?? '#6366f1' }}"></span>{{ $t->category->name ?? '-' }}</span> <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full {{ $t->type=='income'?'bg-emerald-100 text-emerald-700':'bg-red-100 text-red-700' }}">{{ $t->type }}</span></td>
                        @if(auth()->user()->isAdmin())<td class="px-4 py-3 text-xs"><span class="px-2 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800">{{ $t->user->name ?? $t->user_id }}</span></td>@endif
                        <td class="px-4 py-3 text-right font-semibold {{ $t->type=='income'?'text-emerald-600':'text-red-600' }}">{{ $t->type=='income'?'+':'-' }}${{ number_format($t->amount,2) }}</td>
                        <td class="px-4 py-3 text-center">@if($t->attachments->count())<span class="text-xs px-2 py-1 rounded-full bg-sky-100 text-sky-700"> {{ $t->attachments->count() }}</span>@else<span class="text-xs text-zinc-400">—</span>@endif</td>
                    </tr>
                    @empty<tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" class="text-center py-12 text-zinc-500">No records for selected filters.</td>
                    </tr>@endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800">{{ $txs->links() }}</div>
    </div>

    <script>
        const byCat = @json($byCat->map(fn($r) => ['name' => $r->category?->name ?? '?', 'total' => (float) $r->total, 'color' => $r->category?->color ?? '#6366f1'])->values());
        if (byCat.length) new Chart(document.getElementById('repChart'), {
            type: 'doughnut',
            data: {
                labels: byCat.map(b => b.name),
                datasets: [{
                    data: byCat.map(b => b.total),
                    backgroundColor: byCat.map(b => b.color)
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</x-app-layout>
