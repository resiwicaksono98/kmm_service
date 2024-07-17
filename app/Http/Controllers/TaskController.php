<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->paginate(10);
        return Inertia::render('admin/task/Index', [
            'title' => 'Tasks',
            'tasks' => TaskResource::collection($tasks)
        ]);
    }

    public function createPage()
    {
        return Inertia::render('admin/task/Create', [
            'title' => 'Create Task',
            'back' => '/admin/tasks'
        ]);
    }

    public function create(TaskRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('icon')) {
            $path = Storage::disk('public')->put('task-icon', $request->file('icon'));
            $data['icon'] = $path;
        }

        Task::create([
            'name' => $data['name'],
            'icon' => $data['icon'],
            'duration_of_work' => $data['durationOfWork'],
        ]);
        return redirect()->intended('/admin/tasks')->with('toast', "Task Berhasil Ditambah");
    }

    public function editPage(Task $task)
    {
        return Inertia::render('admin/task/Edit', [
            'title' => 'Update Task',
            'back' => '/admin/tasks',
            'task' => new TaskResource($task)
        ]);
    }

    public function edit(Task $task, TaskRequest $request,)
    {
        $data = $request->validated();


        if ($request->hasFile('icon')) {
            if ($task->icon) {
                Storage::disk('public')->delete($task->icon);
            }
            $path = Storage::disk('public')->put('task-icon', $request->file('icon'));
            $data['icon'] = $path;
        }


        $task->update([
            'name' => $data['name'],
            'icon' => isset($data['icon']) ? str_replace('http://localhost:8000/storage', '', $data['icon']) : $task['icon'],
            'duration_of_work' => $data['durationOfWork'],
        ]);
        return redirect()->intended('/admin/tasks')->with('toast', "Task Berhasil Diubah");
    }

    public function delete(Task $task)
    {
        if ($task->icon) {
            Storage::disk('public')->delete($task->icon);
        }
        $task->delete();
        return redirect()->intended('/admin/tasks')->with('toast', "Task Berhasil Dihapus");
    }
}
