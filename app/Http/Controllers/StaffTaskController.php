<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Services\ImageService;

class StaffTaskController extends Controller
{
    public function index(){
        $tasks=Task::where('assigned_to',auth()->id())->latest()->get();
        return view('staff.task.index',compact('tasks'));

    }
    public function show($id){
        $tasks=Task::where('assigned_to',auth()->id())->findOrFail($id);

        return view('staff.task.show',compact('tasks'));
    }
    public function start($id){
        $tasks=Task::where('assigned_to',auth()->id())->findOrFail($id);

        if($tasks->status !== 'pending'){
            return redirect()->route('staff.task.show',$tasks->id)->with('error','Only pending tasks can be  started');

        }
        $tasks->update([
            'status'=>'in_progress',  
            'started_at'=>now(),
        ]);
        return redirect()->route('staff.task.show',$tasks->id)->with('Success','Task Started successfully');
    }
    public function submit(Request $request,ImageService $imageService,$id){
        $tasks=Task::where('assigned_to',auth()->id())->findOrFail($id);

        if(!in_array($tasks->status,['in_progress','needs_revision'],true)){
            return redirect()->route('staff.task.show',$tasks->id)->with('error','This task cannot be submitted in its current status');
        }

        $validated=$request->validate([
            'submission_note'=>'required|string|max:2000',
            'attachments'=>'required|array|min:1',
            'attachments.*'=>'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        foreach($request->file('attachments') as $image){
            $imageName=$imageService->compressAndStore($image);

            TaskAttachment::create([
                'task_id'=>$tasks->id,
                'uploaded_by'=>auth()->id(),
                'file_path'=>$imageName,
                'file_name'=>$image->getClientOriginalName(),
                'file_type'=>'image/webp',
            ]);
        }
        $tasks->update([
            'submission_note'=>$validated['submission_note'],
            'submitted_at'=>now(),
            'status'=>'submitted',
        ]);

        return redirect()->route('staff.task.show',$tasks->id)->with('Success','Task submitted successfully');


    }
}
