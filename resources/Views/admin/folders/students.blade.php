@extends('layouts.app')

@section('title', $folder->name . ' - Students')

@section('content')

<div class="mb-6 flex justify-between items-center">
    <div>
        {{-- Correct route: admin.folders.show --}}
        <a href="{{ route('admin.folders.show', $folder->id) }}"
           class="text-accent hover:underline text-sm">
            ← Back to Folder
        </a>

        <h1 class="text-3xl font-bold mt-2">
            {{ $folder->name }} - Students
        </h1>

        <p class="text-secondary mt-1">
            Manage all students in this folder
        </p>
    </div>

    <button
        type="button"
        onclick="openAddModal()"
        class="bg-accent hover:bg-accentHover text-white px-5 py-3 rounded-lg font-semibold">
        + Add Student
    </button>
</div>


{{-- ================= SUCCESS MESSAGE ================= --}}

@if(session('success'))
    <div class="bg-success/10 border border-success text-success px-4 py-3 rounded-lg mb-5">
        {{ session('success') }}
    </div>
@endif


{{-- ================= ERROR MESSAGE ================= --}}

@if(session('error'))
    <div class="bg-danger/10 border border-danger text-danger px-4 py-3 rounded-lg mb-5">
        {{ session('error') }}
    </div>
@endif


{{-- ================= VALIDATION ERRORS ================= --}}

@if($errors->any())
    <div class="bg-danger/10 border border-danger text-danger px-4 py-3 rounded-lg mb-5">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- ================= SEARCH ================= --}}

<div class="bg-surface border border-border rounded-xl p-4 mb-5">

    <input
        type="text"
        id="studentSearch"
        placeholder="Search student by name, roll number, branch or mobile..."
        class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent">

</div>


{{-- ================= STUDENTS TABLE ================= --}}

<div class="bg-surface border border-border rounded-xl overflow-hidden mb-6">

    <div class="overflow-x-auto">

        <table class="w-full" id="studentsTable">

            <thead class="bg-bg border-b border-border">

                <tr>

                    <th class="text-left p-4 text-secondary font-medium">
                        S.No
                    </th>

                    <th class="text-left p-4 text-secondary font-medium">
                        Name
                    </th>

                    <th class="text-left p-4 text-secondary font-medium">
                        Roll Number
                    </th>

                    <th class="text-left p-4 text-secondary font-medium">
                        Branch
                    </th>

                    <th class="text-left p-4 text-secondary font-medium">
                        Mobile Number
                    </th>

                    <th class="text-center p-4 text-secondary font-medium">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody id="studentsTableBody">

                @forelse($folder->students as $student)

                    <tr
                        class="border-b border-border hover:bg-bg/50 student-row"

                        data-name="{{ strtolower($student->name) }}"

                        data-roll="{{ strtolower($student->roll_number) }}"

                        data-branch="{{ strtolower($student->branch) }}"

                        data-phone="{{ strtolower($student->phone) }}"
                    >

                        {{-- 
                            IMPORTANT:
                            $loop->iteration gives display S.No according
                            to the current sorted student collection.
                        --}}
                        <td class="p-4 font-semibold">
                            {{ $loop->iteration }}
                        </td>


                        {{-- Student Name --}}
                        <td class="p-4 student-name">
                            {{ $student->name }}
                        </td>


                        {{-- Roll Number --}}
                        <td class="p-4 student-roll font-medium">
                            {{ $student->roll_number }}
                        </td>


                        {{-- Branch --}}
                        <td class="p-4 student-branch">
                            {{ $student->branch }}
                        </td>


                        {{-- Phone --}}
                        <td class="p-4 student-phone">
                            {{ $student->phone }}
                        </td>


                        {{-- Actions --}}
                        <td class="p-4">

                            <div class="flex justify-center gap-2">

                                {{-- ================= EDIT ================= --}}

                                <button
                                    type="button"

                                    onclick="openEditModal(
                                        @js($student->id),
                                        @js($student->name),
                                        @js($student->roll_number),
                                        @js($student->branch),
                                        @js($student->phone)
                                    )"

                                    class="px-3 py-2 rounded-lg border border-border hover:bg-bg transition">

                                    ✏️ Edit

                                </button>


                                {{-- ================= DELETE ================= --}}

                                <button
                                    type="button"

                                    onclick="openDeleteModal(
                                        @js($student->id)
                                    )"

                                    class="px-3 py-2 rounded-lg bg-danger/10 text-danger hover:bg-danger/20 transition">

                                    🗑️ Delete

                                </button>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="p-10 text-center text-secondary">

                            <div class="text-5xl mb-3">
                                👨‍🎓
                            </div>

                            <h3 class="text-lg font-semibold">
                                No Students Added
                            </h3>

                            <p class="mt-1">
                                Click "+ Add Student" to add the first student.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MENU TABS --}}
{{-- ========================================================= --}}

<div class="grid md:grid-cols-3 gap-4 mb-6">


    {{-- REPORT --}}

    <a
        href="{{ route('admin.report.show', [
            'id' => $folder->id,
            'type' => 'daily'
        ]) }}"

        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent">

        <div class="text-2xl mb-2">
            📊
        </div>

        <h3 class="font-semibold">
            Report
        </h3>

        <p class="text-secondary text-sm mt-1">
            Daily / Monthly / Custom
        </p>

    </a>


    {{-- TOTAL --}}

    <a
        href="{{ route('admin.report.total', $folder->id) }}"

        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent">

        <div class="text-2xl mb-2">
            📈
        </div>

        <h3 class="font-semibold">
            Total
        </h3>

        <p class="text-secondary text-sm mt-1">
            Current day stats
        </p>

    </a>


    {{-- ATTENDANCE --}}

    <a
        href="{{ route('admin.attendance.show', $folder->id) }}"

        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent">

        <div class="text-2xl mb-2">
            ✅
        </div>

        <h3 class="font-semibold">
            Mark Attendance
        </h3>

        <p class="text-secondary text-sm mt-1">
            Mark today's attendance
        </p>

    </a>

</div>


{{-- ========================================================= --}}
{{-- ADD STUDENT MODAL --}}
{{-- ========================================================= --}}

<div
    id="addModal"

    class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50 p-4">

    <div class="bg-surface rounded-xl p-6 w-full max-w-lg">

        {{-- Header --}}

        <div class="flex justify-between items-center mb-5">

            <h2 class="text-xl font-bold">
                Add Student
            </h2>

            <button
                type="button"
                onclick="closeAddModal()"
                class="text-secondary text-xl">

                ✕

            </button>

        </div>


        {{-- Form --}}

        <form
            method="POST"
            action="{{ route('admin.students.store', $folder->id) }}">

            @csrf


            {{-- NAME --}}

            <div class="mb-4">

                <label class="block font-medium mb-2">

                    Student Name

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    type="text"

                    name="name"

                    required

                    maxlength="255"

                    placeholder="Enter student name"

                    value="{{ old('name') }}"

                    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent">

            </div>


            {{-- ROLL NUMBER --}}

            <div class="mb-4">

                <label class="block font-medium mb-2">

                    Roll Number

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    type="text"

                    name="roll_number"

                    id="addRoll"

                    required

                    maxlength="50"

                    placeholder="Enter roll number"

                    value="{{ old('roll_number') }}"

                    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent">

            </div>


            {{-- BRANCH --}}

            <div class="mb-4">

                <label class="block font-medium mb-2">

                    Branch

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    type="text"

                    name="branch"

                    required

                    maxlength="255"

                    placeholder="Example: Computer Engineering"

                    value="{{ old('branch') }}"

                    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent">

            </div>


            {{-- PHONE --}}

            <div class="mb-5">

                <label class="block font-medium mb-2">

                    Mobile Number

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    type="tel"

                    name="phone"

                    required

                    maxlength="10"

                    minlength="10"

                    pattern="[0-9]{10}"

                    placeholder="Enter 10 digit mobile number"

                    value="{{ old('phone') }}"

                    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent">

            </div>


            {{-- BUTTONS --}}

            <div class="flex justify-end gap-3">

                <button
                    type="button"

                    onclick="closeAddModal()"

                    class="px-5 py-3 border border-border rounded-lg">

                    Cancel

                </button>


                <button
                    type="submit"

                    class="bg-accent hover:bg-accentHover text-white px-6 py-3 rounded-lg font-semibold">

                    Add Student

                </button>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- EDIT STUDENT MODAL --}}
{{-- ========================================================= --}}

<div
    id="editModal"

    class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50 p-4">

    <div class="bg-surface rounded-xl p-6 w-full max-w-lg">

        {{-- Header --}}

        <div class="flex justify-between items-center mb-5">

            <h2 class="text-xl font-bold">
                Edit Student
            </h2>

            <button
                type="button"
                onclick="closeEditModal()"
                class="text-secondary text-xl">

                ✕

            </button>

        </div>


        {{-- Form --}}

        <form
            id="editForm"
            method="POST">

            @csrf

            @method('PUT')


            {{-- NAME --}}

            <div class="mb-4">

                <label class="block font-medium mb-2">

                    Student Name

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    id="editName"

                    type="text"

                    name="name"

                    required

                    maxlength="255"

                    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent">

            </div>


            {{-- ROLL --}}

            <div class="mb-4">

                <label class="block font-medium mb-2">

                    Roll Number

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    id="editRoll"

                    type="text"

                    name="roll_number"

                    required

                    maxlength="50"

                    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent">

            </div>


            {{-- BRANCH --}}

            <div class="mb-4">

                <label class="block font-medium mb-2">

                    Branch

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    id="editBranch"

                    type="text"

                    name="branch"

                    required

                    maxlength="255"

                    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent">

            </div>


            {{-- PHONE --}}

            <div class="mb-5">

                <label class="block font-medium mb-2">

                    Mobile Number

                    <span class="text-danger">
                        *
                    </span>

                </label>

                <input
                    id="editPhone"

                    type="tel"

                    name="phone"

                    required

                    maxlength="10"

                    minlength="10"

                    pattern="[0-9]{10}"

                    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent">

            </div>


            {{-- BUTTONS --}}

            <div class="flex justify-end gap-3">

                <button
                    type="button"

                    onclick="closeEditModal()"

                    class="px-5 py-3 border border-border rounded-lg">

                    Cancel

                </button>


                <button
                    type="submit"

                    class="bg-accent hover:bg-accentHover text-white px-6 py-3 rounded-lg font-semibold">

                    Update Student

                </button>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- DELETE STUDENT MODAL --}}
{{-- ========================================================= --}}

<div
    id="deleteModal"

    class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50 p-4">

    <div class="bg-surface rounded-xl p-6 w-full max-w-md">

        <h2 class="text-xl font-bold mb-2 text-danger">
            Delete Student
        </h2>


        <p class="text-secondary mb-5">

            Enter your admin password to permanently delete this student.

        </p>


        <form
            id="deleteForm"
            method="POST">

            @csrf

            @method('DELETE')


            <input
                type="password"

                name="password"

                required

                autocomplete="current-password"

                placeholder="Admin Password"

                class="w-full border border-border rounded-lg px-4 py-3 mb-4 outline-none focus:ring-2 focus:ring-accent">


            <div class="flex justify-end gap-3">

                <button
                    type="button"

                    onclick="closeDeleteModal()"

                    class="px-5 py-3 border border-border rounded-lg">

                    Cancel

                </button>


                <button
                    type="submit"

                    class="bg-danger hover:opacity-90 text-white px-6 py-3 rounded-lg font-semibold">

                    Delete

                </button>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =======================================================
       SEARCH
    ======================================================= */

    const searchInput =
        document.getElementById('studentSearch');


    if (searchInput) {

        searchInput.addEventListener('input', function () {

            const search =
                this.value.toLowerCase().trim();


            document
                .querySelectorAll('.student-row')
                .forEach(function (row) {

                    const name =
                        row.dataset.name || '';

                    const roll =
                        row.dataset.roll || '';

                    const branch =
                        row.dataset.branch || '';

                    const phone =
                        row.dataset.phone || '';


                    const matched =
                        name.includes(search) ||
                        roll.includes(search) ||
                        branch.includes(search) ||
                        phone.includes(search);


                    row.style.display =
                        matched ? '' : 'none';

                });

        });

    }


    /* =======================================================
       CLOSE MODALS WHEN CLICK OUTSIDE
    ======================================================= */

    const addModal =
        document.getElementById('addModal');

    const editModal =
        document.getElementById('editModal');

    const deleteModal =
        document.getElementById('deleteModal');


    if (addModal) {

        addModal.addEventListener('click', function (event) {

            if (event.target === this) {

                closeAddModal();

            }

        });

    }


    if (editModal) {

        editModal.addEventListener('click', function (event) {

            if (event.target === this) {

                closeEditModal();

            }

        });

    }


    if (deleteModal) {

        deleteModal.addEventListener('click', function (event) {

            if (event.target === this) {

                closeDeleteModal();

            }

        });

    }


    /* =======================================================
       ESC KEY
    ======================================================= */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeAddModal();

            closeEditModal();

            closeDeleteModal();

        }

    });

});


/* ===========================================================
   ADD MODAL
=========================================================== */

function openAddModal()
{

    const modal =
        document.getElementById('addModal');


    modal.classList.remove('hidden');

    modal.classList.add('flex');

}


function closeAddModal()
{

    const modal =
        document.getElementById('addModal');


    modal.classList.add('hidden');

    modal.classList.remove('flex');

}


/* ===========================================================
   EDIT MODAL
=========================================================== */

function openEditModal(
    id,
    name,
    roll,
    branch,
    phone
)
{

    const form =
        document.getElementById('editForm');


    const nameInput =
        document.getElementById('editName');

    const rollInput =
        document.getElementById('editRoll');

    const branchInput =
        document.getElementById('editBranch');

    const phoneInput =
        document.getElementById('editPhone');


    /*
     * Actual route:
     * PUT /admin/students/{id}
     */

    form.action =
        '/admin/students/' + id;


    nameInput.value =
        name;

    rollInput.value =
        roll;

    branchInput.value =
        branch;

    phoneInput.value =
        phone;


    const modal =
        document.getElementById('editModal');


    modal.classList.remove('hidden');

    modal.classList.add('flex');


    setTimeout(function () {

        nameInput.focus();

    }, 100);

}


function closeEditModal()
{

    const modal =
        document.getElementById('editModal');


    modal.classList.add('hidden');

    modal.classList.remove('flex');

}


/* ===========================================================
   DELETE MODAL
=========================================================== */

function openDeleteModal(id)
{

    const form =
        document.getElementById('deleteForm');


    /*
     * Actual route:
     * DELETE /admin/students/{id}
     */

    form.action =
        '/admin/students/' + id;


    const modal =
        document.getElementById('deleteModal');


    modal.classList.remove('hidden');

    modal.classList.add('flex');


    const passwordInput =
        form.querySelector('input[name="password"]');


    if (passwordInput) {

        setTimeout(function () {

            passwordInput.focus();

        }, 100);

    }

}


function closeDeleteModal()
{

    const modal =
        document.getElementById('deleteModal');


    modal.classList.add('hidden');

    modal.classList.remove('flex');


    const form =
        document.getElementById('deleteForm');


    if (form) {

        form.reset();

    }

}

</script>


@endsection