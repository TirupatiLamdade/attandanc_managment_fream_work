<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function show($id)
    {
        $folder = Folder::with('students')->findOrFail($id);
        $today = Carbon::today()->toDateString();

        $attendances = Attendance::where('folder_id', $id)
            ->where('date', $today)
            ->pluck('status', 'student_id');

        return view('admin.folders.attendance', compact('folder', 'attendances'));
    }

    public function submit(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
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

        return redirect()->route('admin.folders.show', $id)->with('success', 'Attendance marked');
    }
}