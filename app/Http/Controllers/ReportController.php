<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportController extends Controller {
    public function index(Request $r){
        $from = $r->query('from', now()->startOfMonth()->toDateString());
        $to = $r->query('to', now()->endOfMonth()->toDateString());
        try { Carbon::parse($from); Carbon::parse($to); } catch(\Exception $e){ $from=now()->startOfMonth()->toDateString(); $to=now()->endOfMonth()->toDateString(); }
        if($from>$to){ [$from,$to]=[$to,$from]; }
        $user=$r->user();
        $base = $user->isAdmin() ? \App\Models\Transaction::query() : $user->transactions();
        $q=(clone $base)->with(['category','attachments','user'])->whereBetween('transacted_at',[$from,$to]);
        if($r->filled('type') && in_array($r->type,['income','expense'])) $q->where('type',$r->type);
        if($r->filled('category_id')) $q->where('category_id',$r->category_id);
        if($r->filled('q')){
            $s='%'.$r->q.'%';
            $q->where(function($w) use($s){ $w->where('title','like',$s)->orWhere('note','like',$s); });
        }
        $txs=$q->orderByDesc('transacted_at')->orderByDesc('id')->paginate(15)->withQueryString();
        $income=(clone $q)->where('type','income')->sum('amount');
        $expense=(clone $q)->where('type','expense')->sum('amount');
        $balance=$income-$expense;
        $byCat = (clone $base)->select('category_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as cnt'))->whereBetween('transacted_at',[$from,$to])->groupBy('category_id')->with('category')->get()->sortByDesc('total');
        $cats=\App\Models\Category::orderBy('name')->get();
        return view('reports.index', compact('txs','from','to','income','expense','balance','byCat','cats'));
    }
    public function export(Request $r){
        $format=$r->query('format','csv');
        $from=$r->query('from', now()->startOfMonth()->toDateString());
        $to=$r->query('to', now()->endOfMonth()->toDateString());
        $user=$r->user();
        $base2 = $user->isAdmin() ? \App\Models\Transaction::query() : $user->transactions();
        $q=(clone $base2)->with(['category','user','attachments'])->whereBetween('transacted_at',[$from,$to]);
        if($r->filled('type') && in_array($r->type,['income','expense'])) $q->where('type',$r->type);
        if($r->filled('category_id')) $q->where('category_id',$r->category_id);
        if($r->filled('q')){ $s='%'.$r->q.'%'; $q->where(function($w) use($s){ $w->where('title','like',$s)->orWhere('note','like',$s); }); }
        $data=$q->orderByDesc('transacted_at')->orderByDesc('id')->get();
        $income=$data->where('type','income')->sum('amount');
        $expense=$data->where('type','expense')->sum('amount');
        if($format==='pdf'){
            $pdf=Pdf::loadView('reports.pdf', compact('data','from','to','income','expense'));
            return $pdf->download('report-'.$from.'-'.$to.'.pdf');
        }
        $headers=['Content-Type'=>'text/csv','Content-Disposition'=>'attachment; filename="report-'.$from.'-'.$to.'.csv"'];
        $callback=function() use($data){
            $h=fopen('php://output','w');
            fputcsv($h,['Date','Title','Category','Type','Amount','Note','Attachments']);
            foreach($data as $t){
                fputcsv($h,[$t->transacted_at->format('Y-m-d'),$t->title,$t->category->name??'-',$t->type,number_format($t->amount,2),$t->note,$t->attachments->count()]);
            }
            fclose($h);
        };
        return response()->stream($callback,200,$headers);
    }
}
