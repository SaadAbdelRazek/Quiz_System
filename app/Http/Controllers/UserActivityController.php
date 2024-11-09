<?php
namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function deleteAllActivities()
    {
        // Deletes all records from the user_activities table
        UserActivity::truncate();

        return redirect()->back()->with('success', 'All activities have been deleted successfully.');
    }
}

