<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\SubjectFolder;
use App\Models\Attendance;
use App\Models\SubjectAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showLogin()
    {
        return view('staff.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->isStaff()) {
                return redirect()->route('staff.folders');
            }
            Auth::logout();
            return back()->withErrors(['email' => 'Not authorized as staff']);
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('landing');
    }

    public function index()
    {
        $today = Carbon::today()->toDateString();

        $folders = Folder::all();
        $subjects = SubjectFolder::all();

        // Check if attendance is truly marked today for Class Folders
        foreach ($folders as $folder) {
            $folder->marked_today = Attendance::where('folder_id', $folder->id)
                ->where('date', $today)
                ->whereIn('status', ['present', 'absent'])
                ->exists();
        }

        // Check if attendance is truly marked today for Subject Folders using SubjectAttendance table
        foreach ($subjects as $subject) {
            $subject->marked_today = SubjectAttendance::where('subject_folder_id', $subject->id)
                ->where('date', $today)
                ->whereIn('status', ['present', 'absent'])
                ->exists();
        }

        return view('staff.folders', compact('folders', 'subjects'));
    }

    public function show($id)
    {
        $folder = Folder::find($id);
        $isSubject = false;

        if (!$folder) {
            $folder = SubjectFolder::with('students')->findOrFail($id);
            $isSubject = true;
        } else {
            $folder = Folder::with('students')->findOrFail($id);
        }

        $today = Carbon::today()->toDateString();
        $studentIds = $folder->students->pluck('id');

        // Fetch attendances strictly for today and current folder students from respective tables
        if ($isSubject) {
            $attendances = SubjectAttendance::where('subject_folder_id', $id)
                ->where('date', $today)
                ->whereIn('student_id', $studentIds)
                ->get()
                ->keyBy('student_id');
        } else {
            $attendances = Attendance::where('folder_id', $id)
                ->where('date', $today)
                ->whereIn('student_id', $studentIds)
                ->get()
                ->keyBy('student_id');
        }

        // Map status cleanly so view can easily check it
        $selected_status = [];
        foreach ($folder->students as $student) {
            if (isset($attendances[$student->id])) {
                $selected_status[$student->id] = $attendances[$student->id]->status;
            } else {
                $selected_status[$student->id] = null; // Fresh / Not marked
            }
        }

        return view('staff.attendance', compact('folder', 'attendances', 'isSubject', 'selected_status'));
    }

    public function submit(Request $request, $id)
    {
        $isSubject = $request->get('is_subject', false);

        if ($isSubject) {
            $folder = SubjectFolder::with('students')->findOrFail($id);
        } else {
            $folder = Folder::with('students')->findOrFail($id);
        }

        $today = Carbon::today();

        foreach ($folder->students as $student) {
            $status = $request->input("student_{$student->id}");
            
            if ($status) {
                if ($isSubject) {
                    SubjectAttendance::updateOrCreate(
                        [
                            'subject_folder_id' => $id,
                            'student_id' => $student->id,
                            'date' => $today->toDateString(),
                        ],
                        [
                            'status' => $status,
                            'marked_by' => Auth::id(),
                            'marked_at' => now(),
                        ]
                    );
                } else {
                    Attendance::updateOrCreate(
                        [
                            'folder_id' => $id,
                            'student_id' => $student->id,
                            'date' => $today->toDateString(),
                        ],
                        [
                            'status' => $status,
                            'marked_by' => Auth::id(),
                            'marked_at' => now(),
                        ]
                    );
                }
            }
        }

        return back()->with('success', 'Attendance saved and updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $folder = Folder::find($id);

        if (!$folder) {
            $folder = SubjectFolder::findOrFail($id);
            $folder->delete();
        } else {
            $folder->delete();
        }

        return redirect()->route('staff.folders')->with('success', 'Folder deleted successfully.');
    }
}