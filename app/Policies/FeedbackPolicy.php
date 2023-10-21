<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Feedback;

class FeedbackPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return true;
    }

    public function view(Admin $admin, Feedback $feedback): bool
    {
        return true;
    }

    public function create(Admin $admin): bool
    {
        return false;
    }

    public function update(Admin $admin, Feedback $feedback): bool
    {
        return false;
    }

    public function delete(Admin $admin, Feedback $feedback): bool
    {
        return true;
    }
}
