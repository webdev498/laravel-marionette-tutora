<?php

namespace App\Http\Controllers\Admin\Relationships;

use App\Task;
use App\Relationship;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;

class TasksController extends AdminController
{
    public function store(Request $request, $relationship)
    {
        // Relationship
        $relationship = Relationship::findOrFail($relationship);
        // Task
        $task = new Task();
        // Attributes
        $task->body      = $request->body;
        $task->action_at = strtodate($request->action_at ?: '+ 2 days');
        // Save
        $relationship->tasks()->save($task);
        // Return
        return redirect()
            ->route('admin.relationships.details.show', [
                'id' => $relationship->id,
            ]);
    }

    public function update(Request $request, $relationship, $task)
    {
        // Relationship
        $relationship = Relationship::findOrFail($relationship);
        // Task
        $task = $relationship->tasks()->where('tasks.id', '=', $task)->first();
        // Attributes
        $task->body      = $request->body;
        $task->action_at = strtodate($request->action_at ?: '+ 2 days');
        // Save
        $task->save();
        // Return
        return redirect()
            ->route('admin.relationships.details.show', [
                'id' => $relationship->id,
            ]);
    }

    public function destroy($relationship, $task)
    {
        // Relationship
        $relationship = Relationship::findOrFail($relationship);
        // Task
        $task = $relationship->tasks()->where('tasks.id', '=', $task)->first();
        // Delete
        if ($task) {
            $task->delete();
        }
        // Return
        return redirect()
            ->route('admin.relationships.details.show', [
                'id' => $relationship->id,
            ]);
    }
}
