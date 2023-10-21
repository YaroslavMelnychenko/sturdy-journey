<?php

namespace App\Notifications\Feedback;

use App\Models;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NewFeedbackNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Models\Feedback $feedback
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(config('app.name').' | '.__('New feedback received'))
            ->greeting(__('New feedback').':')
            ->line(new HtmlString('<b>'.__('Name')."</b>: {$this->feedback->name}"))
            ->line(new HtmlString('<b>'.__('Email')."</b>: {$this->feedback->email}"))
            ->line(new HtmlString('<b>'.__('Short description')."</b>: {$this->feedback->short_description}"));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'feedback_id' => $this->feedback->id,
        ];
    }
}
