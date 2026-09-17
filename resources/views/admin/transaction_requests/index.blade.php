<x-app-layout>
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Transaction Edits <span class="text-xs font-normal px-2 py-1 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">{{ $pendingCount }} pending</span></h1>
            <p class="text-sm text-zinc-500">Manager edits need your approval before they become visible.</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <input name="q" value="{{ request('q') }}" placeholder="Search title…" class="input !w-48">
            <button class="btn btn-primary">Search</button>
            <a href="{{ route('admin.transactions.index') }}" class="btn btn-ghost">Reset</a>
        </form>
    </div>

    @if($requests->isEmpty())
        <div class="card p-12 text-center">
            <div class="text-4xl mb-3">✨</div>
            <div class="font-medium">All caught up</div>
            <p class="text-sm text-zinc-500">No pending edit requests.</p>
        </div>
    @else
    <div class="grid gap-4">
        @foreach($requests as $req)
        <div class="card p-5">
            <div class="flex flex-wrap justify-between gap-3">
                <div>
                    <div class="font-medium">Transaction #{{ $req->transaction->id }} — {{ $req->transaction->title }}</div>
                    <div class="text-xs text-zinc-500">Requested by {{ $req->requester->name }} ({{ $req->requester->email }}) • {{ $req->created_at->diffForHumans() }} • Owner: {{ $req->transaction->user->name ?? $req->transaction->user_id }}</div>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.transactions.approve', $req) }}">@csrf<button class="btn btn-primary btn-sm">Approve</button></form>
                    <form method="POST" action="{{ route('admin.transactions.reject', $req) }}">@csrf<button class="btn btn-ghost btn-sm">Reject</button></form>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <div class="rounded-xl bg-zinc-50 dark:bg-zinc-800 p-4">
                    <div class="text-xs uppercase tracking-widest text-zinc-500 mb-2">Current</div>
                    <div class="space-y-1 text-sm">
                        <div><span class="text-zinc-500">Title:</span> {{ $req->transaction->title }}</div>
                        <div><span class="text-zinc-500">Amount:</span> ${{ number_format($req->transaction->amount,2) }} <span class="text-xs px-1.5 py-0.5 rounded-full {{ $req->transaction->type=='income'?'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300':'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">{{ $req->transaction->type }}</span></div>
                        <div><span class="text-zinc-500">Category:</span> {{ $req->transaction->category->name ?? '-' }} ({{ $req->transaction->category->type ?? '' }})</div>
                        <div><span class="text-zinc-500">Date:</span> {{ $req->transaction->transacted_at->format('Y-m-d') }}</div>
                        <div><span class="text-zinc-500">Note:</span> {{ $req->transaction->note ?? '—' }}</div>
                    </div>
                </div>
                <div class="rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 p-4">
                    <div class="text-xs uppercase tracking-widest text-amber-700 dark:text-zinc-900 mb-2">Proposed (pending)</div>
                    @php $p=$req->payload; $cat=\App\Models\Category::find($p['category_id'] ?? null); @endphp
                    <div class="space-y-1 text-sm dark:text-zinc-900">
                        <div><span class="text-zinc-500 dark:text-zinc-900">Title:</span> <span class="{{ $p['title'] !== $req->transaction->title ? 'font-semibold text-amber-700 dark:text-zinc-900' : '' }}">{{ $p['title'] }}</span></div>
                        <div><span class="text-zinc-500 dark:text-zinc-900">Amount:</span> <span class="{{ (string)$p['amount'] !== (string)$req->transaction->amount ? 'font-semibold text-amber-700 dark:text-zinc-900' : '' }}">${{ number_format($p['amount'],2) }}</span> <span class="text-xs px-1.5 py-0.5 rounded-full {{ $p['type']=='income'?'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300':'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">{{ $p['type'] }}</span></div>
                        <div><span class="text-zinc-500 dark:text-zinc-900">Category:</span> <span class="{{ ($p['category_id'] != $req->transaction->category_id) ? 'font-semibold text-amber-700 dark:text-zinc-900' : '' }}">{{ $cat->name ?? $p['category_id'] }} ({{ $cat->type ?? $p['type'] }})</span></div>
                        <div><span class="text-zinc-500 dark:text-zinc-900">Date:</span> <span class="{{ $p['transacted_at'] != $req->transaction->transacted_at->format('Y-m-d') ? 'font-semibold text-amber-700 dark:text-zinc-900' : '' }}">{{ $p['transacted_at'] }}</span></div>
                        <div><span class="text-zinc-500 dark:text-zinc-900">Note:</span> <span class="{{ ($p['note'] ?? '') !== ($req->transaction->note ?? '') ? 'font-semibold text-amber-700 dark:text-zinc-900' : '' }}">{{ $p['note'] ?? '—' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $requests->links() }}</div>
    @endif
</x-app-layout>
