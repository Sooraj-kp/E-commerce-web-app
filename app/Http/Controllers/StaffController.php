<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('role', 'staff')->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'staff',
        ]);

        return redirect('/staff')
            ->with('success', 'Staff account created successfully.');
    }
    public function edit($id) {
        $staff=User::where('role','staff')->findOrFail($id);
        return view('admin.staff.edit',compact('staff'));
    }
    public function update( Request $request, $id) {
        $staff=User::where('role','staff')->findOrFail($id);

        $validated=$request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email,'.$staff->id,

        ]);
        $staff->update([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
        ]);
        return redirect('/staff')->with('success','staff details update successfully.');

    } 
    /*public function destroy($id){
        $staff=Task::where('role','staff')->findOrFail($id);
        $hasAssignedTasks=Task::where('assigned_to',$staff->id)->exists();
        $hasUploadedProof=Task::where('uploaded_by',$staff->id)->exists();

        if($hasAssignedTasks || $hasUploadedProof){
            return redirect('/staff')->with('error','This member  has  task records  or proof  images .Resolve  these Records before deleteing  the account');


        }
        $staff->delete();
        return redirect('/staff')->with('success','Staff member deleted successfully'); 

    }*/
}