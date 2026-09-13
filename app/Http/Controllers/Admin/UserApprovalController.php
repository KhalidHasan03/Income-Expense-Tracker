<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'pending');
        $q = User::query()->with('approvedBy')->orderByDesc('created_at');

        if ($filter === 'pending') {
            $q->where('is_approved', false);
        } elseif ($filter === 'approved') {
            $q->where('is_approved', true);
        } elseif ($filter === 'admin') {
            $q->where('role', 'admin');
        } elseif ($filter === 'manager') {
            $q->where('role', 'manager');
        } elseif ($filter === 'user') {
            $q->where('role', 'user');
        }

        if ($request->filled('q')) {
            $s = '%'.$request->q.'%';
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', $s)->orWhere('email', 'like', $s);
            });
        }

        $users = $q->paginate(15)->withQueryString();
        $stats = [
            'pending' => User::where('is_approved', false)->count(),
            'approved' => User::where('is_approved', true)->count(),
            'total' => User::count(),
        ];

        return view('admin.users.index', compact('users', 'stats', 'filter'));
    }

    public function approve(User $user)
    {
        if ($user->isApproved()) {
            return back()->with('success', $user->name.' is already approved.');
        }

        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', $user->name.' approved as '.$user->role.'.');
    }

    public function reject(User $user)
    {
        if ($user->isAdmin() && User::where('role', 'admin')->where('is_approved', true)->count() <= 1 && $user->isApproved()) {
            return back()->withErrors(['user' => 'Cannot reject the last approved admin.']);
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', $name.' rejected and removed.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => ['required', 'in:admin,manager,user']]);

        $user->update(['role' => $request->role]);

        return back()->with('success', $user->name.' role changed to '.$user->role.'.');
    }
}
