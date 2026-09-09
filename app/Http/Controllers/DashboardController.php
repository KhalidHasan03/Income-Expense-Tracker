<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller {
    public function index(Request $r){
        $user=$r->user();
        $month=$r->query('month', now()->format('Y-m'));
        $start=Carbon::parse($month.'-01')->startOfMonth();
        $end=(clone $start)->endOfMonth();
        $base = $user->isAdmin() ? \App\Models\Transaction::query() : $user->transactions();
        $incomeM = (clone $base)->where('type','income')->whereBetween('transacted_at',[$start,$end])->sum('amount');
        $expenseM = (clone $base)->where('type','expense')->whereBetween('transacted_at',[$start,$end])->sum('amount');
        $balanceBase = $user->isAdmin() ? \App\Models\Transaction::query() : $user->transactions();
        $balance = (clone $balanceBase)->where('type','income')->sum('amount') - (clone $balanceBase)->where('type','expense')->sum('amount');
        $recent = (clone $base)->with(['category','user'])->orderByDesc('transacted_at')->orderByDesc('id')->take(6)->get();
        $byCat = (clone $base)->select('category_id', DB::raw('SUM(amount) as total'))->where('type','expense')->whereBetween('transacted_at',[$start,$end])->groupBy('category_id')->with('category')->get();
        $trend=[];
        for($i=5;$i>=0;$i--){
            $d=now()->subMonths($i);
            $s=$d->copy()->startOfMonth(); $e=$d->copy()->endOfMonth();
            $tb = $user->isAdmin() ? \App\Models\Transaction::query() : $user->transactions();
            $inc=(clone $tb)->where('type','income')->whereBetween('transacted_at',[$s,$e])->sum('amount');
            $exp=(clone $tb)->where('type','expense')->whereBetween('transacted_at',[$s,$e])->sum('amount');
            $trend[]=['label'=>$d->format('M'),'income'=>(float)$inc,'expense'=>(float)$exp];
        }
        $allForStats = $user->isAdmin() ? \App\Models\Transaction::query() : $user->transactions();
        $totals=['txCount'=>(clone $allForStats)->count(),'catCount'=>\App\Models\Category::count(),'avgExpense'=>(clone $allForStats)->where('type','expense')->whereBetween('transacted_at',[$start,$end])->avg('amount')??0];
        return view('dashboard', compact('incomeM','expenseM','balance','recent','byCat','trend','month','totals'));
    }
}
