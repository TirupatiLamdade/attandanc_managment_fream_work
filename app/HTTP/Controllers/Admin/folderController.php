<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FolderController extends Controller
{
    /**
     * Folder list.
     */
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $folders = Folder::where(
                'created_by',
                Auth::id()
            )
            ->withCount('students')
            ->with(['attendances' => function ($query) use ($today) {
                $query->where('date', $today)->whereIn('status', ['present', 'absent']);
            }])
            ->latest()
            ->get();

        foreach ($folders as $folder) {
            $applicableStudentsCount = $folder->students->filter(function ($student) use ($today) {
                if (!$student->created_at) {
                    return true;
                }
                return Carbon::parse($student->created_at)->toDateString() <= $today;
            })->count();

            $markedCount = $folder->attendances->unique('student_id')->count();

            if ($applicableStudentsCount > 0 && $markedCount >= $applicableStudentsCount) {
                $folder->today_attendance_marked = true;
            } else {
                $folder->today_attendance_marked = false;
            }
        }

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
                Rule::unique('folders', 'name')->where(function ($query) {
                    return $query->where('created_by', Auth::id());
                }),
            ],
        ], [
            'name.unique' => 'A folder with this name already exists.',
        ]);

        Folder::create([
            'name' => trim($validated['name']),
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.dashboard')
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
                Rule::unique('folders', 'name')->where(function ($query) {
                    return $query->where('created_by', Auth::id());
                })->ignore($folder->id),
            ],
        ], [
            'name.unique' => 'A folder with this name already exists.',
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