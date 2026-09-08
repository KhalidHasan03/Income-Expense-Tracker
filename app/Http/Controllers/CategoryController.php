<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller {
    public function index(Request $r){
        $q=$r->user()->categories()->withCount('transactions');
        if($r->filled('q')) $q->where('name','like','%'.$r->q.'%');
        if($r->filled('type') && in_array($r->type,['income','expense'])) $q->where('type',$r->type);
        $cats=$q->orderBy('type')->orderBy('name')->get();
        $stats=['total'=>$cats->count(),'income'=>$cats->where('type','income')->count(),'expense'=>$cats->where('type','expense')->count()];
        return view('categories.index', compact('cats','stats'));
    }
    public function store(Request $r){
        $d=$r->validate(['name'=>'required|string|max:60','type'=>'required|in:income,expense','color'=>'nullable|string|max:7','icon'=>'nullable|string|max:30']);
        $d['user_id']=$r->user()->id;
        $d['color']=$d['color']??'#6366f1';
        $d['icon']=$d['icon']??'tag';
        try{ Category::create($d); } catch(\Illuminate\Database\QueryException $e){ return back()->withErrors(['name'=>'Category already exists for this type.']); }
        return back()->with('success','Category created.');
    }
    public function update(Request $r, Category $category){
        abort_unless($category->user_id==$r->user()->id,403);
        $d=$r->validate(['name'=>'required|string|max:60','type'=>'required|in:income,expense','color'=>'nullable|string|max:7']);
        $category->update($d);
        return back()->with('success','Updated.');
    }
    public function destroy(Request $r, Category $category){
        abort_unless($category->user_id==$r->user()->id,403);
        if($category->transactions()->exists()) return back()->withErrors(['category'=>'Cannot delete category with transactions.']);
        $category->delete();
        return back()->with('success','Deleted.');
    }
}
