<?php

namespace App\Http\Controllers;

use App\Models\task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Task::query();

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_completed', $request->status === 'completed');
        }

        $tasks = $query->orderBy('due_date', 'asc')->get();

        // Tambahkan 3 baris variabel statistik ini sebelum baris return view
        $totalTasks = Task::count();
        $completedTasks = Task::where('is_completed', true)->count();
        $pendingTasks = Task::where('is_completed', false)->count();

        return view('tasks', compact('tasks', 'totalTasks', 'completedTasks', 'pendingTasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:225',
            'priority' => 'required|in:Low,Medium,High',
            'due_date' => 'nullable|date',
        ]);

        task::create($request->all());

        return redirect()->route('tasks.index');
    }

    public function toggle(task $task)
    {
        $task->update(['is_completed' => !$task->is_completed]);
        return redirect()->route('tasks.index');
    }

    public function destroy(task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }

}
