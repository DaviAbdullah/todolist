<?php

namespace App\Http\Controllers;

use app\models\task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = task::query();

        //filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_completed', $request->status === 'completed');
        }

        $tasks = $query->orderBy('due_date', 'asc')->get();

        //stats ringkas
        $totalTask = task::count();
        $completedTask = task::where('is_completed', true)->count();
        $pendingTask = task::where('is_completed', false)->count();

        return view('tasks', compact('tasks', 'totalTasks', 'completedTask', 'pendingTasks'));

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
