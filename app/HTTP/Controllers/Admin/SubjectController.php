<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectFolder;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    private function ownedSubject($id)
    {
        return SubjectFolder::where('created_by', Auth::id())
            ->findOrFail($id);
    }

    public function show($id)
    {
        $subject = $this->ownedSubject($id);

        return view('admin.subjects.show', compact('subject'));
    }

    public function students(Request $request, $id)
    {
        $subject = SubjectFolder::where('created_by', Auth::id())
            ->with('students')
            ->findOrFail($id);

        $search = trim($request->get('search', ''));

        $students = $subject->students()
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
            'admin.subjects.students',
            compact(
                'subject',
                'students',
                'search'
            )
        );
    }

    public function store(Request $request)
    {
        $adminId = Auth::id();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subject_folders', 'name')->where(function ($query) use ($adminId) {
                    return $query->where('created_by', $adminId);
                }),
            ],
        ], [
            'name.unique' => 'A subject folder with this name already exists.',
        ]);

        SubjectFolder::create([
            'name' => trim($validated['name']),
            'created_by' => $adminId,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Subject created successfully.');
    }

    public function update(Request $request, $id)
    {
        $subject = $this->ownedSubject($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subject_folders', 'name')->where(function ($query) {
                    return $query->where('created_by', Auth::id());
                })->ignore($subject->id),
            ],
        ], [
            'name.unique' => 'A subject folder with this name already exists.',
        ]);

        $subject->update([
            'name' => trim($validated['name']),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Wrong admin password.');
        }

        $subject = $this->ownedSubject($id);
        $subject->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Subject deleted successfully.');
    }

    public function storeStudent(Request $request, $id)
    {
        $subject = $this->ownedSubject($id);

        $validated = $request->validate([
            'name' => 'required|string|regex:/^[A-Za-z\s]+$/|max:255',
            'roll_number' => 'required|string|regex:/^[0-9]+$/|max:50',
            'branch' => 'required|string|regex:/^[A-Za-z\s]+$/|max:255',
            'phone' => 'required|digits:10',
        ]);

        // Check if roll number already exists in this specific subject
        $rollExists = $subject->students()->where('roll_number', $validated['roll_number'])->exists();
        if ($rollExists) {
            return back()->withInput()->with('error', 'already user in this roll number.');
        }

        // Check if phone number already exists in this specific subject
        $phoneExists = $subject->students()->where('phone', $validated['phone'])->exists();
        if ($phoneExists) {
            return back()->withInput()->with('error', 'already user in this mobile number.');
        }

        $formattedName = ucwords(strtolower(preg_replace('/\s+/', ' ', trim($validated['name']))));
        $formattedBranch = ucwords(strtolower(preg_replace('/\s+/', ' ', trim($validated['branch']))));

        // Check if student exists globally or create new record
        $student = Student::where('phone', $validated['phone'])
            ->orWhere('roll_number', $validated['roll_number'])
            ->first();

        if (!$student) {
            $maxSerno = $subject->students()->max('serno') ?? 0;
            $nextSerno = $maxSerno + 1;

            $student = Student::create([
                'folder_id' => 0,
                'name' => $formattedName,
                'roll_number' => $validated['roll_number'],
                'branch' => $formattedBranch,
                'phone' => $validated['phone'],
                'serno' => $nextSerno,
            ]);
        }

        if (!$subject->students()->where('student_id', $student->id)->exists()) {
            $subject->students()->attach($student->id);
        } else {
            return back()->withInput()->with('error', 'This student is already added to this subject.');
        }

        return redirect()
            ->route('admin.subjects.students', $subject->id)
            ->with('success', 'Student added to subject successfully.');
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|regex:/^[A-Za-z\s]+$/|max:255',
            'roll_number' => 'required|string|regex:/^[0-9]+$/|max:50',
            'branch' => 'required|string|regex:/^[A-Za-z\s]+$/|max:255',
            'phone' => 'required|digits:10',
        ]);

        $student->update([
            'name' => ucwords(strtolower(preg_replace('/\s+/', ' ', trim($validated['name'])))),
            'roll_number' => $validated['roll_number'],
            'branch' => ucwords(strtolower(preg_replace('/\s+/', ' ', trim($validated['branch'])))),
            'phone' => $validated['phone'],
        ]);

        return back()->with('success', 'Student updated successfully.');
    }

    public function destroyStudent(Request $request, $id)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::user();
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Wrong admin password.');
        }

        $student = Student::findOrFail($id);
        $student->attendances()->delete();
        $student->delete();

        return back()->with('success', 'Student deleted successfully.');
    }
}