<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransactionUpdateRequest;
use Illuminate\Http\Request;

class TransactionApprovalController extends Controller
{
    public function index(Request $request)
    {
        $q = TransactionUpdateRequest::with(['transaction.category','transaction.user','requester'])
            ->where('status','pending')
            ->latest();

        if ($request->filled('q')) {
            $s = '%'.$request->q.'%';
            $q->whereHas('transaction', function($w) use($s){
                $w->where('title','like',$s);
            });
        }

        $requests = $q->paginate(15)->withQueryString();
        $pendingCount = TransactionUpdateRequest::where('status','pending')->count();

        return view('admin.transaction_requests.index', compact('requests','pendingCount'));
    }

    public function approve(TransactionUpdateRequest $request)
    {
        abort_unless($request->status==='pending', 400, 'Already reviewed.');

        $payload = $request->payload;
        $transaction = $request->transaction;

        $cat = \App\Models\Category::find($payload['category_id'] ?? null);
        if($cat && $cat->type !== $payload['type']){
            return back()->withErrors(['payload'=>'Category type mismatch for pending request.']);
        }

        $transaction->update($payload);

        $request->update([
            'status'=>'approved',
            'reviewed_by'=>auth()->id(),
            'reviewed_at'=>now(),
        ]);

        TransactionUpdateRequest::where('transaction_id',$transaction->id)
            ->where('id','!=',$request->id)
            ->where('status','pending')
            ->update(['status'=>'rejected','reviewed_by'=>auth()->id(),'reviewed_at'=>now()]);

        return back()->with('success','Update approved and applied to transaction #'.$transaction->id);
    }

    public function reject(TransactionUpdateRequest $request)
    {
        abort_unless($request->status==='pending', 400, 'Already reviewed.');

        $request->update([
            'status'=>'rejected',
            'reviewed_by'=>auth()->id(),
            'reviewed_at'=>now(),
        ]);

        return back()->with('success','Update request rejected.');
    }
}
