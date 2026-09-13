<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="card p-8 text-center">
            <div class="w-16 h-16 rounded-2xl bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 grid place-items-center text-2xl mx-auto">⏳</div>
            <h1 class="mt-4 text-2xl font-semibold">Thanks for joining, {{ explode(' ', auth()->user()->name)[0] }}! 🙌</h1>
            <p class="mt-2 text-sm text-zinc-500">
                You signed up as <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ ucfirst(auth()->user()->role) }}</span> — we're reviewing your request.
                You'll get full access as soon as an admin approves you, usually within a few hours.
            </p>
            <div class="mt-4 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 px-4 py-3 text-sm text-amber-700 dark:text-amber-300 text-left">
                <div class="font-medium">What happens next?</div>
                <ul class="list-disc pl-5 mt-1 space-y-1">
                    <li>An admin reviews your request in <span class="font-medium">Team → User Approvals</span></li>
                    <li>Once approved, just refresh — you'll land right in your dashboard</li>
                    <li>Feel free to update your <a href="{{ route('profile.edit') }}" class="underline">profile</a> while you wait</li>
                </ul>
            </div>
            <div class="mt-6 flex justify-center gap-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-ghost">Edit Profile</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-primary">Log Out</button></form>
            </div>
            <p class="mt-4 text-xs text-zinc-500">Already approved? <a href="{{ route('dashboard') }}" class="underline">Refresh dashboard</a></p>
        </div>
    </div>
</x-app-layout>
