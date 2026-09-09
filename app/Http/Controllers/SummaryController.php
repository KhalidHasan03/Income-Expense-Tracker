<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class SummaryController extends Controller {
    public function index(Request $r){
        $month=$r->query('month', now()->format('Y-m'));
        $start=Carbon::parse($month.'-01')->startOfMonth();
        $end=(clone $start)->endOfMonth();
        $user=$r->user();
        $base = $user->isAdmin() ? \App\Models\Transaction::query() : $user->transactions();
        $income=(clone $base)->where('type','income')->whereBetween('transacted_at',[$start,$end])->sum('amount');
        $expense=(clone $base)->where('type','expense')->whereBetween('transacted_at',[$start,$end])->sum('amount');
        $byCatIncome=(clone $base)->select('category_id', DB::raw('SUM(amount) as total'))->where('type','income')->whereBetween('transacted_at',[$start,$end])->groupBy('category_id')->with('category')->get();
        $byCatExpense=(clone $base)->select('category_id', DB::raw('SUM(amount) as total'))->where('type','expense')->whereBetween('transacted_at',[$start,$end])->groupBy('category_id')->with('category')->get();
        $months=[];
        for($i=0;$i<24;$i++){ $d=now()->subMonths($i); $months[]=$d->format('Y-m'); }
        $daily = (clone $base)->selectRaw('DATE(transacted_at) as d, SUM(CASE WHEN type="income" THEN amount ELSE 0 END) as inc, SUM(CASE WHEN type="expense" THEN amount ELSE 0 END) as exp')->whereBetween('transacted_at',[$start,$end])->groupBy('d')->orderBy('d')->get();
        $topExpense = $byCatExpense->sortByDesc('total')->first();
        return view('summary.index', compact('month','income','expense','byCatIncome','byCatExpense','months','daily','topExpense'));
    }
}
