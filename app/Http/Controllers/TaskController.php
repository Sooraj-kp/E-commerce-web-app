<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Support\Facades\File;


class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('assignedStaff')->latest()->get();

        return view('admin.tasks.index', compact('tasks'));
    }
    public function create()
    {
        $staff = User::where('role', 'staff')->get();

        return view('admin.tasks.create', compact('staff'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'due_at' => 'nullable|date',
            'estimated_minutes' => 'nullable|integer|min:1',
        ]);

        $task=Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'due_at' => $request->due_at,
            'estimated_minutes' => $request->estimated_minutes,
            'status' => 'pending',
        ]);

        $staff=User::findorFail($request->assigned_to);

        $staff->notify(new TaskAssignedNotification($task));

        return redirect('/admin/tasks')
            ->with('success', 'Task created successfully.');
    }
    public function show($id){
        $task=Task::with(['assignedStaff','attachments'])->findOrFail($id);
        return view('admin.tasks.show',compact('task'));

    }
    public function edit($id){
        $task=Task::findOrFail($id);
        $staff=User::where('role','staff')->get();
        return view('admin.tasks.edit',compact('task','staff'));
    }
    public function update(Request $request,$id){
        $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'assigned_to'=>'required|exists:users,id',
            'due_at'=>'nullable|date',
            'estimated_minutes'=>'nullable|integer|min:1',
        ]);
        $task= Task::findOrFail($id);
        $task->update([
                'title'=>$request->title,
                'description'=>$request->description,
                'assigned_to'=>$request->assigned_to,
                'due_at'=>$request->due_at,
                'estimated_minutes'=>$request->estimated_minutes,
        ]);
        return redirect('/admin/tasks')->with('success','Task updated Successfully.');

    }
    public function approve($id)
{
    $task = Task::findOrFail($id);

    if ($task->status !== 'submitted') {
        return redirect('/admin/tasks')
            ->with('error', 'Only submitted tasks can be approved.');
    }

    $task->update([
        'status' => 'completed',
        'completed_at' => now(),
    ]);

    return redirect('/admin/tasks/' . $task->id)
        ->with('success', 'Task approved successfully.');
    }
    public function requestRevision(Request $request,$id){
        $request->validate([
            'admin_feedback'=>'required|string',
        ]);
        $task= Task::findOrFail($id);
        if($task->status !== 'submitted'){
            return redirect('/admin/tasks/'.$task->id)->with('error','Only submitted tasks can be sent for revision');
        }
        $task->update([
            'status'=>'needs_revision',
            'admin_feedback'=>$request->admin_feedback,


        ]);
        return redirect('/admin/tasks/'.$task->id)->with('success','Revision  requested success');
    }

public function destroy($id)
{
    // Find the task and load its related attachments
    $task = Task::with('attachments')->findOrFail($id);

    // Collect image filenames before deleting the task
    $imagePaths = $task->attachments->pluck('file_path');

   
    $task->delete();

    
    foreach ($imagePaths as $imageName) {

        $imagePath = public_path('image_uploads/' . $imageName);

        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }

    return redirect('/admin/tasks')
        ->with('success', 'Task deleted successfully.');
}

    
}
