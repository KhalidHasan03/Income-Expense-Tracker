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
        $base=$user->transactions();
        $incomeM = (clone $base)->where('type','income')->whereBetween('transacted_at',[$start,$end])->sum('amount');
        $expenseM = (clone $base)->where('type','expense')->whereBetween('transacted_at',[$start,$end])->sum('amount');
        $balance = $user->transactions()->where('type','income')->sum('amount') - $user->transactions()->where('type','expense')->sum('amount');
        $recent = $user->transactions()->with('category')->latest('transacted_at')->take(6)->get();
        $byCat = $user->transactions()->select('category_id', DB::raw('SUM(amount) as total'))->where('type','expense')->whereBetween('transacted_at',[$start,$end])->groupBy('category_id')->with('category')->get();
        $trend=[];
        for($i=5;$i>=0;$i--){
            $d=now()->subMonths($i);
            $s=$d->copy()->startOfMonth(); $e=$d->copy()->endOfMonth();
            $inc=$user->transactions()->where('type','income')->whereBetween('transacted_at',[$s,$e])->sum('amount');
            $exp=$user->transactions()->where('type','expense')->whereBetween('transacted_at',[$s,$e])->sum('amount');
            $trend[]=['label'=>$d->format('M'),'income'=>(float)$inc,'expense'=>(float)$exp];
        }
        $totals=['txCount'=>$user->transactions()->count(),'catCount'=>$user->categories()->count(),'avgExpense'=>$user->transactions()->where('type','expense')->whereBetween('transacted_at',[$start,$end])->avg('amount')??0];
        return view('dashboard', compact('incomeM','expenseM','balance','recent','byCat','trend','month','totals'));
    }
}
