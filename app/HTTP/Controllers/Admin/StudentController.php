<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Get only current admin's folder.
     */
    private function ownedFolder($id)
    {
        return Folder::where('created_by', Auth::id())
            ->findOrFail($id);
    }

    /**
     * Student list.
     */
    public function index(Request $request, $id)
    {
        $folder = Folder::where('created_by', Auth::id())
            ->findOrFail($id);

        $search = trim($request->get('search', ''));

        $students = Student::where('folder_id', $folder->id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('roll_number', 'like', "%{$search}%")
                        ->orWhere('branch', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('CAST(roll_number AS UNSIGNED) ASC')
            ->orderBy('roll_number', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        return view(
            'admin.folders.students',
            compact(
                'folder',
                'students',
                'search'
            )
        );
    }

    /**
     * Add student.
     */
    public function store(Request $request, $folderId)
    {
        $folder = $this->ownedFolder($folderId);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z]+(\s+[A-Za-z]+)*$/',
            ],

            'roll_number' => [
                'required',
                'regex:/^[0-9]+$/',
                'max:50',
                'unique:students,roll_number,NULL,id,folder_id,' . $folder->id,
            ],

            'branch' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z]+(\s+[A-Za-z]+)*$/',
            ],

            'phone' => [
                'required',
                'digits:10',
                'unique:students,phone,NULL,id,folder_id,' . $folder->id,
            ],
        ], [
            'name.required' => 'Student name is required.',
            'name.regex' => 'Student name can contain only letters and proper single spaces between words.',

            'roll_number.required' => 'Roll number is required.',
            'roll_number.regex' => 'Roll number must contain numbers only.',
            'roll_number.unique' => 'This roll number already exists in this folder.',

            'branch.required' => 'Branch is required.',
            'branch.regex' => 'Branch can contain only letters and proper single spaces between words.',

            'phone.required' => 'Mobile number is required.',
            'phone.digits' => 'Mobile number must be exactly 10 digits.',
            'phone.unique' => 'This mobile number is already registered for another student in this folder.',
        ]);

        $formattedName = ucwords(strtolower(preg_replace('/\s+/', ' ', trim($validated['name']))));
        $formattedBranch = ucwords(strtolower(preg_replace('/\s+/', ' ', trim($validated['branch']))));

        // Auto increment serno sequence within the specific folder
        $maxSerno = Student::where('folder_id', $folder->id)->max('serno') ?? 0;
        $nextSerno = $maxSerno + 1;

        Student::create([
            'folder_id' => $folder->id,
            'name' => $formattedName,
            'roll_number' => $validated['roll_number'],
            'branch' => $formattedBranch,
            'phone' => $validated['phone'],
            'serno' => $nextSerno,
        ]);

        return redirect()
            ->route('admin.students.index', $folder->id)
            ->with(
                'success',
                'Student added successfully.'
            );
    }

    /**
     * Update student.
     */
    public function update(Request $request, $id)
    {
        $student = Student::with('folder')
            ->findOrFail($id);

        if (
            !$student->folder ||
            $student->folder->created_by !== Auth::id()
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z]+(\s+[A-Za-z]+)*$/',
            ],

            'roll_number' => [
                'required',
                'regex:/^[0-9]+$/',
                'max:50',
                'unique:students,roll_number,' .
                $student->id .
                ',id,folder_id,' .
                $student->folder_id,
            ],

            'branch' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z]+(\s+[A-Za-z]+)*$/',
            ],

            'phone' => [
                'required',
                'digits:10',
                'unique:students,phone,' .
                $student->id .
                ',id,folder_id,' .
                $student->folder_id,
            ],
        ], [
            'name.required' => 'Student name is required.',
            'name.regex' => 'Student name can contain only letters and proper single spaces between words.',

            'roll_number.required' => 'Roll number is required.',
            'roll_number.regex' => 'Roll number must contain numbers only.',
            'roll_number.unique' => 'This roll number already exists in this folder.',

            'branch.required' => 'Branch is required.',
            'branch.regex' => 'Branch can contain only letters and proper single spaces between words.',

            'phone.required' => 'Mobile number is required.',
            'phone.digits' => 'Mobile number must be exactly 10 digits.',
            'phone.unique' => 'This mobile number is already registered for another student in this folder.',
        ]);

        $formattedName = ucwords(strtolower(preg_replace('/\s+/', ' ', trim($validated['name']))));
        $formattedBranch = ucwords(strtolower(preg_replace('/\s+/', ' ', trim($validated['branch']))));

        $student->update([
            'name' => $formattedName,
            'roll_number' => $validated['roll_number'],
            'branch' => $formattedBranch,
            'phone' => $validated['phone'],
        ]);

        return redirect()
            ->route(
                'admin.students.index',
                $student->folder_id
            )
            ->with(
                'success',
                'Student updated successfully.'
            );
    }

    /**
     * Delete student.
     */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'password' => [
                'required',
                'string',
            ],
        ]);

        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if (
            !Hash::check(
                $request->password,
                $user->password
            )
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Wrong admin password.'
                );
        }

        $student = Student::with('folder')
            ->findOrFail($id);

        if (
            !$student->folder ||
            $student->folder->created_by !== Auth::id()
        ) {
            abort(403);
        }

        $folderId = $student->folder_id;

        /*
         * Delete student's attendance records together with student.
         * This is intentionally controlled by admin delete action.
         */
        $student->attendances()->delete();

        $student->delete();

        return redirect()
            ->route(
                'admin.students.index',
                $folderId
            )
            ->with(
                'success',
                'Student deleted successfully.'
            );
    }
}