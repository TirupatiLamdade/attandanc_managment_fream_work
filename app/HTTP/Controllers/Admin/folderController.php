<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Folder list.
     */
    public function index()
    {
        $folders = Folder::where(
                'created_by',
                Auth::id()
            )
            ->withCount('students')
            ->latest()
            ->get();

        return view(
            'admin.folders.index',
            compact('folders')
        );
    }

    /**
     * Create folder.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        Folder::create([
            'name' => trim($validated['name']),
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.folders.index')
            ->with(
                'success',
                'Folder created successfully.'
            );
    }

    /**
     * Show folder.
     */
    public function show($id)
    {
        $folder = Folder::where(
                'created_by',
                Auth::id()
            )
            ->withCount('students')
            ->findOrFail($id);

        return view(
            'admin.folders.show',
            compact('folder')
        );
    }

    /**
     * Update folder.
     */
    public function update(Request $request, $id)
    {
        $folder = Folder::where(
                'created_by',
                Auth::id()
            )
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $folder->update([
            'name' => trim($validated['name']),
        ]);

        return redirect()
            ->route(
                'admin.folders.show',
                $folder->id
            )
            ->with(
                'success',
                'Folder updated successfully.'
            );
    }

    /**
     * Delete folder.
     */
    public function destroy($id)
    {
        $folder = Folder::where(
                'created_by',
                Auth::id()
            )
            ->findOrFail($id);

        /*
         * Delete attendance first.
         */
        $folder->attendances()->delete();

        /*
         * Delete students.
         */
        $folder->students()->delete();

        /*
         * Finally delete folder.
         */
        $folder->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with(
                'success',
                'Folder deleted successfully.'
            );
    }
}