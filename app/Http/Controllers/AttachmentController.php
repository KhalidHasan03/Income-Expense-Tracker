<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
class AttachmentController extends Controller {
    public function store(Request $r, Transaction $transaction){
        if($r->user()->isManager()) abort_unless($transaction->user_id==$r->user()->id,403);
        $r->validate(['attachment'=>'required|file|mimes:jpg,jpeg,png,pdf,webp|max:5120']);
        if($transaction->attachments()->count()>=3) return back()->withErrors(['attachment'=>'Max 3 attachments per transaction']);
        $file=$r->file('attachment');
        $path=$file->store('attachments/'.$r->user()->id.'/'.$transaction->id,'public');
        Attachment::create([
            'user_id'=>$r->user()->id,
            'transaction_id'=>$transaction->id,
            'file_path'=>$path,
            'file_name'=>$file->getClientOriginalName(),
            'mime'=>$file->getMimeType(),
            'size'=>$file->getSize(),
        ]);
        return back()->with('success','Attachment added');
    }
    public function destroy(Request $r, Attachment $attachment){
        if($r->user()->isManager()) abort_unless($attachment->user_id==$r->user()->id,403);
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
        return back()->with('success','Attachment removed');
    }
    public function show(Request $r, Attachment $attachment){
        if($r->user()->isManager()) abort_unless($attachment->user_id==$r->user()->id,403);
        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
}
