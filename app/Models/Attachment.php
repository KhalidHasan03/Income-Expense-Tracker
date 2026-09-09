<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Attachment extends Model {
    use HasFactory;
    protected $fillable = ['user_id','transaction_id','file_path','file_name','mime','size'];
    public function user(){ return $this->belongsTo(User::class); }
    public function transaction(){ return $this->belongsTo(Transaction::class); }
    public function url(){ return \Storage::disk('public')->url($this->file_path); }
}
