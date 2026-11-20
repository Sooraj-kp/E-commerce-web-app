<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class StaffDashboardController extends Controller
{
    public function index(){
        $staffId=auth()->id();
        $taskQuery=Task::where('assigned_to',$staffId);
        $totalTasks=(clone $taskQuery)->count();
        $pendingTasks=(clone $taskQuery)->where('status','pending')->count();
        $submittedTasks=(clone $taskQuery)->where('status','submitted')->count();
        $inprogressTasks=(clone $taskQuery)->where('status','in_progress')->count();
        $completedTasks=(clone $taskQuery)->where('status','completed')->count();
        $revisionTasks=(clone $taskQuery)->where('status','needs_revision')->count();

        $recentTasks=(clone $taskQuery)->latest()->take(5)->get();

        $upcomingTasks=(clone $taskQuery)->whereNotNull('due_at')->whereDate('due_at','>=',now()->toDateString())->where('status','!=','completed')
        ->orderBy('due_at')->take(5)->get();

        return view('staff.dashboard',compact(
            'totalTasks',
            'pendingTasks',
            'submittedTasks',
            'completedTasks',
            'revisionTasks',
            'recentTasks',
            'upcomingTasks',
            'inprogressTasks'
        ));
    }
}

