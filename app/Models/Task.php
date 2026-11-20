<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'due_at',
        'estimated_minutes',
        'started_at',
        'submitted_at',
        'completed_at',
        'status',
        'admin_feedback',
        'submission_note',
    ];

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}