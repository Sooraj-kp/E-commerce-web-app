<?php

namespace App\Notifications;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $deadline = $this->task->due_at
            ? Carbon::parse($this->task->due_at)->format('d M Y, h:i A')
            : 'No deadline specified';

        $duration = $this->task->estimated_minutes
            ? $this->task->estimated_minutes . ' minutes'
            : 'Not specified';

        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new task has been assigned to you.')
            ->line('Task: ' . $this->task->title)
            ->line('Description: ' . ($this->task->description ?? 'No description provided.'))
            ->line('Deadline: ' . $deadline)
            ->line('Estimated Duration: ' . $duration)
            ->action('View Assigned Task', route('staff.task.show', $this->task->id))
            ->line('Please complete the task within the assigned deadline.')
            ->salutation('Regards, ' . config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
        ];
    }
}
