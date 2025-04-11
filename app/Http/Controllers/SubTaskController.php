<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'title' => 'required|string'
        ]);

        SubTask::create($request->only('task_id', 'title'));

        return redirect()->back()->with('success', 'Subtask added successfully.');
    }

    public function update(Request $request, SubTask $subTask, $id)
    {
        $request->validate([
            'title' => 'required|string'
        ]);

        $subTask = SubTask::findOrFail($id);
        $subTask->update($request->only('title'));

        return redirect()->back()->with('success', 'Subtask updated successfully.');
    }

    public function destroy(SubTask $subTask, $id)
    {
        $subTask = SubTask::findOrFail($id);
        $subTask->delete();

        return redirect()->back()->with('success', 'Subtask deleted successfully.');
    }

    public function complete(SubTask $subTask)
    {
        $subTask->update(['completed' => true]);
        return back();
    }

    public function markComplete(SubTask $subTask)
    {
        $subTask->update(['completed' => true]);
        return redirect()->route('tasks.index');
    }

    public function updateOrder(Request $request)
{
    foreach ($request->order as $index => $subTaskId) {
        \App\Models\SubTask::where('id', $subTaskId)->update(['position' => $index]);
    }

    return response()->json(['success' => true]);
}


}