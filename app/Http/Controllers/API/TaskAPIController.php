<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class TaskAPIController extends Controller
{
    public function index()
    {
        return Task::all();
    }
 
    public function show($id)
    {
        return Task::find($id);
    }

    public function store(Request $request)
    {
        $validator = $this->validateTask($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $task = Task::create($request->all());

        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validateTask($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $task = Task::findOrFail($id);
        $task->update($request->all());

        return response()->json($task, 200);
    }

    public function delete(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.'], 204);
    }

    protected function validateTask(Request $request, $isRequired = true)
    {
        $rules = [
            'title' => $isRequired ? 'required|string' : 'string',
            'description' => $isRequired ? 'required|string' : 'string',
            'status' => $isRequired ? 'required|in:completed,uncompleted' : 'in:completed,uncompleted',
            'due_date' => $isRequired ? 'required|date' : 'date',
        ];

        return Validator::make($request->all(), $rules);
    }
}
