<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable'
        ]);

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);

        return response()->json($task);
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Handle task completion
        if (isset($request->completed)) {
            $task->update([
                'completed' => $request->completed
            ]);

            return response()->json([
                'task' => $task->fresh()
            ]);
        }

        // Handle other updates (title, description, etc.)
        $task->update($request->only(['title', 'description']));

        return response()->json($task);
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->update([
            'is_deleted' => true,
            'deleted_at' => now()
        ]);

        return response()->json(['message' => 'Task moved to trash']);
    }

    public function toggleFavorite(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->update([
            'is_favorite' => !$task->is_favorite
        ]);

        return response()->json($task);
    }

    public function trash()
    {
        $trashedTasks = auth()->user()->tasks()
            ->where('is_deleted', true)
            ->latest()
            ->get();
        
        return view('tasks.trash', compact('trashedTasks'));
    }

    public function restore(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->update([
            'is_deleted' => false,
            'deleted_at' => null
        ]);

        return response()->json(['message' => 'Task restored']);
    }

    public function forceDelete(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'Task permanently deleted']);
    }
} 