<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        $dups = DB::table('categories')
            ->select('name','type', DB::raw('COUNT(*) as cnt'), DB::raw('MIN(id) as keep_id'))
            ->groupBy('name','type')
            ->having('cnt','>',1)
            ->get();
        foreach($dups as $d){
            $ids = DB::table('categories')->where('name',$d->name)->where('type',$d->type)->where('id','!=',$d->keep_id)->pluck('id');
            foreach($ids as $dupId){
                DB::table('transactions')->where('category_id',$dupId)->update(['category_id'=>$d->keep_id]);
                DB::table('categories')->where('id',$dupId)->delete();
            }
        }
        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropUnique(['user_id','name','type']);
            });
        } catch(\Throwable $e) {}
        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->unique(['name','type']);
            });
        } catch(\Throwable $e) {
            if(!str_contains($e->getMessage(),'already exists')) throw $e;
        }
    }
    public function down(): void {
        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropUnique(['name','type']);
            });
        } catch(\Throwable $e) {}
        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->unique(['user_id','name','type']);
            });
        } catch(\Throwable $e) {}
    }
};
