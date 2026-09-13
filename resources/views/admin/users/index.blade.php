<x-app-layout>
    <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">User Approvals <span class="text-xs font-normal px-2 py-1 rounded-full bg-indigo-100 text-indigo-700">{{ $stats['pending'] }} pending</span></h1>
            <p class="text-sm text-zinc-500">{{ $stats['total'] }} total • {{ $stats['approved'] }} approved • Admin can approve/reject and change roles</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <input name="q" value="{{ request('q') }}" placeholder="Search name or email…" class="input !w-48">
            <select name="filter" class="input !w-auto" onchange="this.form.submit()">
                <option value="pending" @selected($filter==='pending')>Pending</option>
                <option value="approved" @selected($filter==='approved')>Approved</option>
                <option value="all" @selected($filter==='all')>All</option>
                <option value="admin" @selected($filter==='admin')>Admins</option>
                <option value="manager" @selected($filter==='manager')>Managers</option>
                <option value="user" @selected($filter==='user')>Users</option>
            </select>
            <button class="btn btn-primary">Filter</button>
        </form>
    </div>

    <div class="flex gap-2 mb-4 text-sm">
        <a href="{{ route('admin.users.index', ['filter'=>'pending']) }}" class="px-3 py-1.5 rounded-full border {{ $filter==='pending'?'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900':'' }}">Pending @if($stats['pending'])({{ $stats['pending'] }})@endif</a>
        <a href="{{ route('admin.users.index', ['filter'=>'approved']) }}" class="px-3 py-1.5 rounded-full border {{ $filter==='approved'?'bg-zinc-900 text-white':'' }}">Approved</a>
        <a href="{{ route('admin.users.index', ['filter'=>'all']) }}" class="px-3 py-1.5 rounded-full border {{ $filter==='all'?'bg-zinc-900 text-white':'' }}">All</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500">
                    <tr>
                        <th class="text-left px-4 py-3">User</th>
                        <th class="text-left px-4 py-3">Role</th>
                        <th class="text-left px-4 py-3">Status</th>
                        <th class="text-left px-4 py-3">Requested</th>
                        <th class="text-right px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($users as $u)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30">
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $u->name }}</div>
                            <div class="text-xs text-zinc-500">{{ $u->email }}</div>
                            @if($u->approvedBy)<div class="text-xs text-zinc-400">Approved by {{ $u->approvedBy->name }}</div>@endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $u->role==='admin'?'bg-indigo-100 text-indigo-700':($u->role==='manager'?'bg-amber-100 text-amber-700':'bg-zinc-100 dark:bg-zinc-800') }}">{{ $u->role }}</span>
                            <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline-flex items-center gap-1 ml-2">@csrf @method('PATCH')
                                <select name="role" class="input !py-1 !px-2 text-xs !h-7" onchange="this.form.submit()">
                                    <option value="user" @selected($u->role==='user')>user</option>
                                    <option value="manager" @selected($u->role==='manager')>manager</option>
                                    <option value="admin" @selected($u->role==='admin')>admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            @if($u->isApproved())
                                <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">approved @if($u->approved_at) • {{ $u->approved_at->diffForHumans() }}@endif</span>
                            @else
                                <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700">pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-zinc-500">{{ $u->created_at->format('Y-m-d') }} • {{ $u->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                @if(! $u->isApproved())
                                    <form method="POST" action="{{ route('admin.users.approve', $u) }}">@csrf<button class="btn btn-primary btn-sm">Approve</button></form>
                                    <form method="POST" action="{{ route('admin.users.reject', $u) }}" onsubmit="return confirm('Reject and delete {{ $u->name }}?')">@csrf @method('DELETE')<button class="btn btn-delete btn-sm">Reject</button></form>
                                @else
                                    @if(auth()->id() !== $u->id)
                                    <form method="POST" action="{{ route('admin.users.reject', $u) }}" onsubmit="return confirm('Remove {{ $u->name }}?')">@csrf @method('DELETE')<button class="btn btn-ghost btn-sm">Remove</button></form>
                                    @else
                                    <span class="text-xs text-zinc-400">You</span>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-12 text-zinc-500">No users for this filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-zinc-100 dark:border-zinc-800">{{ $users->links() }}</div>
    </div>
</x-app-layout>
