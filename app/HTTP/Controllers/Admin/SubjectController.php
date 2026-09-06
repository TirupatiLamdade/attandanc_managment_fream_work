<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectFolder;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Show subject main page.
     */
    public function show($id)
    {
        $subject = SubjectFolder::with('students.folder')
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        $folders = Folder::with('students')
            ->where('created_by', Auth::id())
            ->latest()
            ->get();

        return view(
            'admin.subjects.show',
            compact('subject', 'folders')
        );
    }


    /**
     * Show subject students management page.
     */
    public function students($id)
    {
        $subject = SubjectFolder::with('students.folder')
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        $folders = Folder::with('students')
            ->where('created_by', Auth::id())
            ->latest()
            ->get();

        return view(
            'admin.subjects.students',
            compact('subject', 'folders')
        );
    }


    /**
     * Create subject.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        SubjectFolder::create([
            'name' => $validated['name'],
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with(
                'success',
                'Subject added successfully.'
            );
    }


    /**
     * Add students to subject.
     */
    public function selectStudents(Request $request, $id)
    {
        $validated = $request->validate([
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $subject = SubjectFolder::where(
            'created_by',
            Auth::id()
        )->findOrFail($id);

        if (!empty($validated['student_ids'])) {

            $subject->students()
                ->syncWithoutDetaching(
                    $validated['student_ids']
                );
        }

        return redirect()
            ->route(
                'admin.subjects.students',
                $subject->id
            )
            ->with(
                'success',
                'Students added to subject successfully.'
            );
    }
}