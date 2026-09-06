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
     * Show students of a particular folder.
     *
     * Students are sorted by Roll Number ASC.
     * Serno is displayed according to this sorted order.
     */
    public function index($id)
    {
        $folder = Folder::with([
            'students' => function ($query) {
                $query->orderByRaw(
                    'CAST(roll_number AS UNSIGNED) ASC'
                );
            }
        ])
        ->where('created_by', Auth::id())
        ->findOrFail($id);

        return view(
            'admin.folders.students',
            compact('folder')
        );
    }

    /**
     * Add student to folder.
     */
    public function store(Request $request, $folderId)
    {
        // Only admin's folder
        $folder = Folder::where('created_by', Auth::id())
            ->findOrFail($folderId);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            // Student Name
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z ]+$/',
            ],

            // Roll Number
            // Same folder मध्ये duplicate allowed नाही
            'roll_number' => [
                'required',
                'regex:/^[0-9]+$/',
                'max:50',
                'unique:students,roll_number,NULL,id,folder_id,' . $folder->id,
            ],

            // Branch
            'branch' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z ]+$/',
            ],

            // Mobile Number
            'phone' => [
                'required',
                'digits:10',
            ],
        ], [

            /*
            |--------------------------------------------------------------------------
            | Custom Error Messages
            |--------------------------------------------------------------------------
            */

            'name.required' =>
                'Student name is required.',

            'name.regex' =>
                'Student name can contain only letters and spaces.',

            'name.max' =>
                'Student name is too long.',


            'roll_number.required' =>
                'Roll number is required.',

            'roll_number.regex' =>
                'Roll number must contain numbers only.',

            'roll_number.max' =>
                'Roll number is too large.',

            'roll_number.unique' =>
                'This roll number already exists in this folder.',


            'branch.required' =>
                'Branch is required.',

            'branch.regex' =>
                'Branch can contain only letters and spaces.',

            'branch.max' =>
                'Branch name is too long.',


            'phone.required' =>
                'Mobile number is required.',

            'phone.digits' =>
                'Mobile number must be exactly 10 digits.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Automatic Serial Number
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | Serno is NOT used for sorting anymore.
        |
        | Roll Number decides the order.
        |
        | Example:
        |
        | Roll: 25
        | Roll: 10
        | Roll: 5
        | Roll: 1
        |
        | Display:
        |
        | Serno 1 -> Roll 1
        | Serno 2 -> Roll 5
        | Serno 3 -> Roll 10
        | Serno 4 -> Roll 25
        |
        |--------------------------------------------------------------------------
        */

        Student::create([
            'folder_id' => $folder->id,

            'name' =>
                trim($validated['name']),

            'branch' =>
                trim($validated['branch']),

            'roll_number' =>
                $validated['roll_number'],

            'phone' =>
                $validated['phone'],

            /*
            |--------------------------------------------------------------------------
            | Database Serno
            |--------------------------------------------------------------------------
            |
            | Keep this value for compatibility with existing database.
            | Actual displayed Serno comes from Blade $loop->iteration.
            |
            |--------------------------------------------------------------------------
            */

            'serno' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.students.index',
                $folder->id
            )
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


        // Security:
        // Student must belong to logged-in admin's folder
        if (
            !$student->folder ||
            $student->folder->created_by !== Auth::id()
        ) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z ]+$/',
            ],

            'roll_number' => [
                'required',
                'regex:/^[0-9]+$/',
                'max:50',

                // Current student's roll number सोडून
                // बाकी students मध्ये unique
                'unique:students,roll_number,'
                    . $student->id
                    . ',id,folder_id,'
                    . $student->folder_id,
            ],

            'branch' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z ]+$/',
            ],

            'phone' => [
                'required',
                'digits:10',
            ],
        ], [

            'name.required' =>
                'Student name is required.',

            'name.regex' =>
                'Student name can contain only letters and spaces.',

            'roll_number.required' =>
                'Roll number is required.',

            'roll_number.regex' =>
                'Roll number must contain numbers only.',

            'roll_number.unique' =>
                'This roll number already exists in this folder.',

            'branch.required' =>
                'Branch is required.',

            'branch.regex' =>
                'Branch can contain only letters and spaces.',

            'phone.required' =>
                'Mobile number is required.',

            'phone.digits' =>
                'Mobile number must be exactly 10 digits.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Student
        |--------------------------------------------------------------------------
        */

        $student->update([
            'name' =>
                trim($validated['name']),

            'branch' =>
                trim($validated['branch']),

            'roll_number' =>
                $validated['roll_number'],

            'phone' =>
                $validated['phone'],
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
        /*
        |--------------------------------------------------------------------------
        | Password Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'password' => [
                'required',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Check Admin Password
        |--------------------------------------------------------------------------
        */

        if (
            !Hash::check(
                $request->password,
                Auth::user()->password
            )
        ) {
            return back()
                ->with(
                    'error',
                    'Wrong admin password'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Find Student
        |--------------------------------------------------------------------------
        */

        $student = Student::with('folder')
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (
            !$student->folder ||
            $student->folder->created_by !== Auth::id()
        ) {
            abort(403);
        }


        $folderId = $student->folder_id;


        /*
        |--------------------------------------------------------------------------
        | Delete Student
        |--------------------------------------------------------------------------
        */

        $student->delete();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

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