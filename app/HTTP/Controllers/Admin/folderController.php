<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Folder::create([
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Folder created successfully.');
    }

    public function show($id)
    {
        $folder = Folder::where('created_by', Auth::id())
            ->findOrFail($id);

        return view('admin.folders.show', compact('folder'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = Folder::where('created_by', Auth::id())
            ->findOrFail($id);

        $folder->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Folder updated successfully.');
    }

    public function destroy($id)
    {
        $folder = Folder::where('created_by', Auth::id())
            ->findOrFail($id);

        $folder->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Folder deleted successfully.');
    }
}