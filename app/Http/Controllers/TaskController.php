<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['subTasks' => function ($q) {
            $q->orderBy('position'); // urutkan subtask berdasarkan posisi
        }])->orderBy('position'); // kalau task juga punya posisi
    
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
    
        $tasks = $query->get();
    
        return view('tasks.index', compact('tasks'));
    }
    


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high' // Validasi tambahan
        ]);
    
        Task::create([
            'title' => $request->title,
            'deadline' => $request->deadline,
            'priority' => $request->priority // Simpan prioritas
        ]);
    
        return redirect()->back()->with('success', 'Task added successfully.');
    }    

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string',
            'deadline' => 'nullable|date'
        ]);
    
        $task->update($request->all());
        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully.');
    }

    public function complete(Task $task)
{
    // Pastikan semua subtask sudah selesai dulu
    if ($task->subTasks()->where('completed', false)->exists()) {
        return back()->with('error', 'Selesaikan semua SubTask dulu!');
    }

    $task->update(['completed' => true]);
    return back();
}

public function markComplete(Task $task)
{
    $task->update(['completed' => true]);
    return redirect()->route('tasks.index');
}

public function prioritas()
{
    $tasks = Task::where('priority', true)->get(); // Ambil hanya task yang diprioritaskan
    return view('tasks.prioritas', compact('tasks'));
}

public function updateOrder(Request $request)
{
    $tasks = $request->tasks; // Data array dari frontend

    foreach ($tasks as $index => $taskId) {
        Task::where('id', $taskId)->update(['position' => $index]);
    }

    return response()->json(['success' => true]);
}

public function kalender()
{
    return view('tasks.kalender');
}

public function kalenderEvents()
{
    $tasks = Task::whereNotNull('deadline')->get();

    $events = $tasks->map(function ($task) {
        return [
            'title' => $task->title,
            'start' => $task->deadline,
            'color' => $task->completed ? '#198754' : '#dc3545'
        ];
    });

    return response()->json($events);
}

}

