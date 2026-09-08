<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaction;
class TransactionController extends Controller {
    public function index(Request $r){
        $q=$r->user()->transactions()->with('category');
        if($r->filled('type') && in_array($r->type,['income','expense'])) $q->where('type',$r->type);
        if($r->filled('month')) $q->where('transacted_at','like',$r->month.'%');
        if($r->filled('category_id')) $q->where('category_id',$r->category_id);
        if($r->filled('q')){
            $s='%'.$r->q.'%';
            $q->where(function($w) use($s){ $w->where('title','like',$s)->orWhere('note','like',$s); });
        }
        $sort = $r->get('sort','transacted_at');
        $dir = $r->get('dir','desc')==='asc'?'asc':'desc';
        if(in_array($sort,['transacted_at','amount','title'])) $q->orderBy($sort,$dir);
        else $q->orderByDesc('transacted_at');
        $q->orderByDesc('id');
        $txs=$q->paginate(10)->withQueryString();
        $cats=$r->user()->categories()->orderBy('name')->get();
        $totals = [
            'income' => (clone $q)->where('type','income')->sum('amount'),
            'expense'=> (clone $q)->where('type','expense')->sum('amount'),
            'count' => $txs->total(),
        ];
        return view('transactions.index', compact('txs','cats','totals'));
    }
    public function store(Request $r){
        $d=$r->validate([
            'title'=>'required|string|max:120',
            'amount'=>'required|numeric|min:0.01|max:999999999',
            'type'=>'required|in:income,expense',
            'category_id'=>'required|exists:categories,id',
            'transacted_at'=>'required|date',
            'note'=>'nullable|string|max:500',
        ]);
        $cat=$r->user()->categories()->findOrFail($d['category_id']);
        if($cat->type!==$d['type']) return back()->withErrors(['category_id'=>'Category type mismatch.']);
        $d['user_id']=$r->user()->id;
        Transaction::create($d);
        return back()->with('success','Transaction added.');
    }
    public function update(Request $r, Transaction $transaction){
        abort_unless($transaction->user_id==$r->user()->id,403);
        $d=$r->validate([
            'title'=>'required|string|max:120',
            'amount'=>'required|numeric|min:0.01',
            'type'=>'required|in:income,expense',
            'category_id'=>'required|exists:categories,id',
            'transacted_at'=>'required|date',
            'note'=>'nullable|string|max:500',
        ]);
        $cat=$r->user()->categories()->findOrFail($d['category_id']);
        if($cat->type!==$d['type']) return back()->withErrors(['category_id'=>'Category type mismatch.']);
        $transaction->update($d);
        return back()->with('success','Updated.');
    }
    public function destroy(Request $r, Transaction $transaction){
        abort_unless($transaction->user_id==$r->user()->id,403);
        $transaction->delete();
        return back()->with('success','Deleted.');
    }
}
