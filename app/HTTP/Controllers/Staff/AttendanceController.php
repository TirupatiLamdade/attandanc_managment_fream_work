<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\SubjectFolder;
use App\Models\Attendance;
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
        $folders = Folder::all();
        $subjects = SubjectFolder::all();

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

        $attendances = Attendance::where('folder_id', $id)
            ->where('date', $today)
            ->pluck('status', 'student_id');

        return view('staff.attendance', compact('folder', 'attendances', 'isSubject'));
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

        return redirect()->route('staff.folders')->with('success', 'Attendance submitted');
    }
}