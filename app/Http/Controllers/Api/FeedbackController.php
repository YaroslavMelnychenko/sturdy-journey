<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback as FeedbackRequests;
use App\Models;
use App\Notifications\Feedback\NewFeedbackNotification;
use Illuminate\Support\Facades\Notification;
use Response;

class FeedbackController extends Controller
{
    public function createFeedback(FeedbackRequests\CreateRequest $request)
    {
        $name = $request->name;
        $email = $request->email;
        $short_description = $request->short_description;

        $feedback_email = nova_get_settings(['feedback_email'])['feedback_email'] ?? null;

        $feedback = Models\Feedback::create([
            'name' => $name,
            'email' => $email,
            'short_description' => $short_description,
        ]);

        if ($feedback_email !== null) {
            Notification::route('mail', $feedback_email)
                ->notify(new NewFeedbackNotification($feedback));
        }

        return Response::send(true);
    }
}
