<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Http\Resources\PackageResource;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/package/Index', [
            'title' => 'Package',
            'packages' => PackageResource::collection(Package::latest()->paginate(10)),
        ]);
    }

    public function createPage()
    {
        return Inertia::render('admin/package/Create', [
            'title' => 'Create Package',
            'back' => '/admin/packages',
            'tasks' => TaskResource::collection(Task::all())
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'tasks' => 'required|array',
            'tasks.*' => 'required|exists:tasks,id',
        ]);


        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->put('package-image', $request->file('image'));
            $data['image'] = $path;
        }

        $tasks = Task::whereIn('id', $data['tasks'])->get();
        $totalDuration = $tasks->sum('duration_of_work');


        $package = Package::create([
            'name' => $data['name'],
            'estimated_time' => $totalDuration,
            'image' => $data['image'],
            'description' => $data['description'],
        ]);

        foreach ($data['tasks'] as $task) {
            $package->tasks()->attach($task);
        }
        return redirect()->intended('/admin/packages')->with('toast', "Package Berhasil Ditambah");
    }

    public function editPage(Package $package)
    {
        return Inertia::render('admin/package/Edit', [
            'title' => 'Edit Package',
            'back' => '/admin/packages',
            'package' => new PackageResource($package),
            'tasks' => TaskResource::collection(Task::all())
        ]);
    }

    public function edit(Package $package, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable',
            'description' => 'nullable|string',
            'tasks' => 'required|array',
            'tasks.*' => 'required',
        ]);
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($package->image);
            $path = Storage::disk('public')->put('package-image', $request->file('image'));
            $data['image'] = $path;
        }

        $tasks = Task::whereIn('id', $data['tasks'])->get();
        $totalDuration = $tasks->sum('duration_of_work');

        $package->update([
            'name' => $data['name'],
            'estimated_time' => $totalDuration,
            'description' => $data['description'],
            'image' =>  $data['image'] ?? $package->image,
        ]);

        $package->tasks()->sync($data['tasks']);

        return redirect()->intended('/admin/packages')->with('toast', "Package Berhasil Diubah");
    }

    public function delete(Package $package)
    {
        $package->delete();

        return redirect()->intended('/admin/packages')->with('toast', "Package Berhasil Dihapus");
    }
}
