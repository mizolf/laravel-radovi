<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskApplicationController extends Controller
{
    /**
     * Apply for a task (for students)
     */
    public function apply(Task $task)
    {
        // Check if user is a student
        if (!Auth::user()->isStudent()) {
            abort(403, 'Only students can apply for tasks.');
        }

        // Check if already applied
        $existingApplication = TaskApplication::where('task_id', $task->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingApplication) {
            return redirect()->back()
                ->with('error', 'You have already applied for this task.');
        }

        // Create application
        TaskApplication::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Application submitted successfully!');
    }

    /**
     * Withdraw application (for students)
     */
    public function withdraw(Task $task)
    {
        // Check if user is a student
        if (!Auth::user()->isStudent()) {
            abort(403, 'Unauthorized action.');
        }

        $application = TaskApplication::where('task_id', $task->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$application) {
            return redirect()->back()
                ->with('error', 'Application not found.');
        }

        $application->delete();

        return redirect()->back()
            ->with('success', 'Application withdrawn successfully!');
    }

    /**
     * Show all applications for a specific task (for professors)
     */
    public function showApplications(Task $task)
    {
        // Check if user is the task creator
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $applications = $task->applications()->with('user')->get();

        return view('tasks.applications', compact('task', 'applications'));
    }

    /**
     * Update application status (for professors)
     */
    public function updateStatus(Task $task, TaskApplication $application, Request $request)
    {
        // Check if user is the task creator
        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,accepted,rejected'],
        ]);

        // If accepting a student, check if another student is already accepted
        if ($validated['status'] === 'accepted') {
            $acceptedApplication = $task->applications()
                ->where('status', 'accepted')
                ->where('id', '!=', $application->id)
                ->first();

            if ($acceptedApplication) {
                return redirect()->back()
                    ->with('error', 'Another student has already been accepted for this task. Please reject them first.');
            }

            // Automatically reject all other pending applications
            $task->applications()
                ->where('id', '!=', $application->id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);
        }

        $application->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Application status updated successfully!');
    }
}
