@extends('layouts.app')

@section('title', $subject->name . ' - Students')

@section('content')

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(session('success'))
        <div id="flashAlert" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-emerald-950/95 border border-emerald-500 text-emerald-200 px-6 sm:px-8 py-4 rounded-2xl shadow-2xl text-sm sm:text-base flex items-center gap-3 transition-all duration-500 max-w-[90vw] cursor-pointer">
            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div id="flashAlert" class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-red-950/95 border border-red-500 text-red-200 px-6 sm:px-8 py-4 rounded-2xl shadow-2xl text-sm sm:text-base flex items-center gap-3 transition-all duration-500 max-w-[90vw] cursor-pointer">
            <span class="w-3 h-3 rounded-full bg-red-400 animate-pulse shrink-0"></span>
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div id="errorAlert" class="mb-6 bg-red-950/90 border border-red-500 text-red-200 px-6 py-4 rounded-2xl shadow-xl cursor-pointer">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl shadow-darkCard">
            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                <a href="{{ route('admin.subjects.show', $subject->id) }}" class="btn-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shrink-0 w-fit">
                    &larr; Back
                </a>
                <div>
                    <p class="text-xs text-blue-400 uppercase tracking-wider font-bold mb-2">Subject Students</p>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-textPrimary tracking-tight break-words flex items-center gap-3">
                        <span class="text-2xl">📚</span>
                        <span>{{ $subject->name }}</span>
                    </h1>
                    <p class="text-textSecondary text-sm mt-2">Manage all students in this subject folder.</p>
                </div>
            </div>

            <button type="button" onclick="openAddModal()" class="btn-primary font-bold px-7 py-3.5 rounded-2xl text-sm flex items-center justify-center gap-2 shadow-blueGlow shrink-0 bg-blue-600 hover:bg-blue-700 text-white cursor-pointer">
                + Add Student
            </button>
        </div>
    </div>

    <div class="mb-8 flex justify-start">
        <div class="relative w-full max-w-xl">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-textMuted">🔍</span>
            <input type="text" id="studentSearch" placeholder="Search by name, roll number, branch or mobile number..." class="input-dark rounded-2xl pl-11 pr-5 py-3.5 text-sm w-full shadow-inner" autocomplete="off">
        </div>
    </div>

    <div class="bg-surface border border-borderCol rounded-3xl overflow-hidden shadow-darkCard mb-10">
        <div class="overflow-x-auto">
            <table class="table-dark text-left text-base min-w-[1000px]" id="studentsTable">
                <thead>
                    <tr>
                        <th class="w-20 py-5 px-6">S.No</th>
                        <th class="py-5 px-6">Name</th>
                        <th class="py-5 px-6">Roll Number</th>
                        <th class="py-5 px-6">Branch</th>
                        <th class="py-5 px-6">Mobile Number</th>
                        <th class="py-5 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    @forelse($students as $index => $student)
                        <tr class="student-row hover:bg-cardHover/50 transition-colors" data-name="{{ strtolower($student->name) }}" data-roll="{{ strtolower($student->roll_number) }}" data-branch="{{ strtolower($student->branch) }}" data-phone="{{ strtolower($student->phone) }}">
                            <td class="font-semibold text-textSecondary py-5 px-6">
                                <span class="bg-card px-3 py-1 rounded-lg border border-borderCol text-xs font-mono">{{ $student->serno ?? ($index + 1) }}</span>
                            </td>
                            <td class="font-bold text-textPrimary text-lg py-5 px-6">{{ $student->name }}</td>
                            <td class="py-5 px-6">
                                <span class="bg-card px-3 py-1.5 rounded-xl border border-borderCol text-sm font-mono font-semibold">{{ $student->roll_number }}</span>
                            </td>
                            <td class="text-textSecondary py-5 px-6">{{ $student->branch }}</td>
                            <td class="text-textSecondary font-mono py-5 px-6">{{ $student->phone }}</td>
                            <td class="py-5 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button type="button" title="View Student Added Date" onclick="openStudentDateModal(@js($student->name), @js($student->roll_number), @js($student->created_at ? $student->created_at->format('d/m/Y') : 'Date not available'), @js($student->created_at ? $student->created_at->format('h:i A') : ''))" class="p-2.5 rounded-xl text-xs font-bold bg-emerald-600/20 border border-emerald-500/40 text-emerald-400 hover:bg-emerald-600/30 transition-all inline-flex items-center justify-center cursor-pointer">📅</button>
                                    <button type="button" onclick="openEditModal(@js($student->id), @js($student->name), @js($student->roll_number), @js($student->branch), @js($student->phone))" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-blue-600/20 border border-blue-500/40 text-blue-400 hover:bg-blue-600/30 transition-all cursor-pointer">Edit</button>
                                    <button type="button" onclick="openDeleteModal(@js($student->id))" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-red-600/20 border border-red-500/40 text-red-400 hover:bg-red-600/30 transition-all cursor-pointer">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-20 text-center text-textMuted">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-6xl mb-4">🎓</span>
                                    <p class="font-bold text-xl text-textSecondary">No Students Added</p>
                                    <p class="text-base text-textMuted mt-2">Click “+ Add Student” above to add the first student to this subject.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="addModal" class="hidden fixed inset-0 bg-black/85 backdrop-blur-md items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-surface border border-borderCol rounded-3xl p-8 w-full max-w-lg shadow-darkCard">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-textPrimary">Add Student</h2>
            <button type="button" onclick="closeAddModal()" class="text-textSecondary hover:text-textPrimary text-xl font-bold cursor-pointer">✕</button>
        </div>

        <form id="addStudentForm" method="POST" action="{{ route('admin.subjects.students.store', $subject->id) }}" class="space-y-5" onsubmit="return validateStudentForm(event, this)">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Student Name *</label>
                <input type="text" name="name" id="addName" required maxlength="255" placeholder="Enter student name" value="{{ old('name') }}" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full transition-colors" oninput="validateFieldLive(this, 'name')">
                <p id="nameError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter letters only (no numbers)</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Roll Number *</label>
                <input type="text" name="roll_number" id="addRoll" required maxlength="50" placeholder="Enter roll number" value="{{ old('roll_number') }}" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full transition-colors" oninput="validateFieldLive(this, 'roll')">
                <p id="rollError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter numbers only</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Branch *</label>
                <input type="text" name="branch" id="addBranch" required maxlength="255" placeholder="Example: Computer Engineering" value="{{ old('branch') }}" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full transition-colors" oninput="validateFieldLive(this, 'branch')">
                <p id="branchError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter letters only (no numbers)</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Mobile Number *</label>
                <input type="tel" name="phone" id="addPhone" required maxlength="10" minlength="10" placeholder="Enter 10 digit mobile number" value="{{ old('phone') }}" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full transition-colors" oninput="validateFieldLive(this, 'phone')">
                <p id="phoneError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter valid 10 digit number</p>
            </div>

            <div class="flex items-center justify-between pt-3">
                <div>
                    <button type="button" onclick="clearAddForm()" class="px-4 py-3 rounded-2xl text-red-400 hover:text-red-300 text-xs font-bold bg-red-500/10 border border-red-500/30 transition-colors cursor-pointer">Clear Form</button>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-5 py-3 rounded-2xl text-textSecondary hover:text-textPrimary text-sm font-semibold transition-colors cursor-pointer">Cancel</button>
                    <button type="submit" id="addSubmitBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-sm shadow-md cursor-pointer">Add Student</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="studentDateModal" class="hidden fixed inset-0 bg-black/85 backdrop-blur-md items-center justify-center z-50 p-4">
    <div class="bg-surface border border-borderCol rounded-3xl p-8 w-full max-w-md shadow-darkCard">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-textPrimary flex items-center gap-2"><span>📅</span> Student Added Date</h2>
            <button type="button" onclick="closeStudentDateModal()" class="text-textSecondary hover:text-textPrimary text-xl font-bold cursor-pointer">✕</button>
        </div>
        <div class="space-y-4">
            <div class="bg-card border border-borderCol rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wider font-semibold text-textMuted">Student Name</p>
                <p id="addedStudentName" class="text-xl font-bold text-textPrimary mt-2"></p>
            </div>
            <div class="bg-card border border-borderCol rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wider font-semibold text-textMuted">Roll Number</p>
                <p id="addedStudentRoll" class="text-lg font-mono font-semibold text-textPrimary mt-2"></p>
            </div>
            <div class="border border-blue-500/50 bg-blue-500/5 rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wider font-semibold text-blue-400">Added in System On</p>
                <p id="addedStudentDate" class="text-2xl font-extrabold text-textPrimary mt-2"></p>
                <p id="addedStudentTime" class="text-sm text-textSecondary mt-1"></p>
            </div>
        </div>
        <div class="mt-7 flex justify-end">
            <button type="button" onclick="closeStudentDateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm cursor-pointer shadow-md">Close</button>
        </div>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 bg-black/85 backdrop-blur-md items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-surface border border-borderCol rounded-3xl p-8 w-full max-w-lg shadow-darkCard">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-textPrimary">Edit Student</h2>
            <button type="button" onclick="closeEditModal()" class="text-textSecondary hover:text-textPrimary text-xl font-bold cursor-pointer">✕</button>
        </div>
        <form id="editForm" method="POST" class="space-y-5" onsubmit="return validateStudentForm(event, this)">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Student Name *</label>
                <input id="editName" type="text" name="name" required maxlength="255" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full" oninput="validateFieldLive(this, 'name')">
                <p id="editNameError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter letters only</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Roll Number *</label>
                <input id="editRoll" type="text" name="roll_number" required maxlength="50" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full" oninput="validateFieldLive(this, 'roll')">
                <p id="editRollError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter numbers only</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Branch *</label>
                <input id="editBranch" type="text" name="branch" required maxlength="255" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full" oninput="validateFieldLive(this, 'branch')">
                <p id="editBranchError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter letters only</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Mobile Number *</label>
                <input id="editPhone" type="tel" name="phone" required maxlength="10" minlength="10" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full" oninput="validateFieldLive(this, 'phone')">
                <p id="editPhoneError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter valid 10 digit number</p>
            </div>
            <div class="flex justify-end gap-3 pt-3">
                <button type="button" onclick="closeEditModal()" class="px-6 py-3 rounded-2xl text-textSecondary hover:text-textPrimary text-base font-semibold cursor-pointer">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-7 py-3 rounded-2xl text-base shadow-md cursor-pointer">Update Student</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="hidden fixed inset-0 bg-black/85 backdrop-blur-md items-center justify-center z-50 p-4">
    <div class="bg-surface border border-borderCol rounded-3xl p-8 w-full max-w-md shadow-darkCard">
        <h2 class="text-2xl font-bold mb-2 text-red-500">Delete Student</h2>
        <p class="text-textSecondary text-base mb-6">Enter your admin password to securely delete this student record from the subject.</p>
        <form id="deleteForm" method="POST" class="space-y-5">
            @csrf
            @method('DELETE')
            <div>
                <input type="password" name="password" required placeholder="Admin Password" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 rounded-2xl text-textSecondary hover:text-textPrimary text-base font-semibold cursor-pointer">Cancel</button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-3 rounded-2xl text-base shadow-md cursor-pointer">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.existingRolls = [];
        window.existingPhones = [];

        @foreach($subject->students as $st)
            window.existingRolls.push("{{ strtolower(trim($st->roll_number)) }}");
            window.existingPhones.push("{{ strtolower(trim($st->phone)) }}");
        @endforeach

        const flashAlert = document.getElementById('flashAlert');
        const errorAlert = document.getElementById('errorAlert');

        function dismissAlerts() {
            if (flashAlert) {
                flashAlert.style.opacity = '0';
                setTimeout(() => flashAlert.remove(), 300);
            }
            if (errorAlert) {
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 300);
            }
        }

        if (flashAlert || errorAlert) {
            setTimeout(dismissAlerts, 3000);
            document.addEventListener('click', dismissAlerts, { once: true });
        }

        const search = document.getElementById('studentSearch');
        if (search) {
            search.addEventListener('input', function () {
                const value = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.student-row');
                const tableBody = document.getElementById('studentTableBody');
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const text = (
                        (row.dataset.name || '') + ' ' +
                        (row.dataset.roll || '') + ' ' +
                        (row.dataset.branch || '') + ' ' +
                        (row.dataset.phone || '')
                    ).toLowerCase();

                    const isVisible = text.includes(value);
                    row.style.display = isVisible ? '' : 'none';
                    if (isVisible) visibleCount++;
                });

                let noDataRow = document.getElementById('noSearchDataRow');
                if (visibleCount === 0 && rows.length > 0 && value !== '') {
                    if (!noDataRow) {
                        noDataRow = document.createElement('tr');
                        noDataRow.id = 'noSearchDataRow';
                        noDataRow.innerHTML = `
                            <td colspan="6" class="p-16 text-center text-textMuted">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-5xl mb-3">🔍</span>
                                    <p class="font-bold text-lg text-textSecondary">No Matching Student Found</p>
                                    <p class="text-sm text-textMuted mt-1">No student record matched your search query.</p>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(noDataRow);
                    }
                } else if (noDataRow) {
                    noDataRow.remove();
                }
            });
        }
    });

    function validateFieldLive(input, type) {
        const val = input.value.trim().toLowerCase();
        const prefix = input.id.startsWith('edit') ? 'edit' : '';
        const errElement = document.getElementById(prefix + type + 'Error');

        const alphabetRegex = /^[A-Za-z]+(\s+[A-Za-z]+)*$/;
        const numericRegex = /^[0-9]+$/;

        let hasError = false;
        let errorMessage = '';

        if (type === 'name' || type === 'branch') {
            if (val !== '' && (!alphabetRegex.test(val) || /\d/.test(val))) {
                hasError = true;
                errorMessage = 'please enter letters only (no numbers)';
            }
        } else if (type === 'roll') {
            if (val !== '' && !numericRegex.test(val)) {
                hasError = true;
                errorMessage = 'please enter numbers only';
            } else if (val !== '' && window.existingRolls && window.existingRolls.includes(val)) {
                hasError = true;
                errorMessage = 'already user in this roll number';
            }
        } else if (type === 'phone') {
            if (val !== '' && (!numericRegex.test(val) || val.length !== 10)) {
                hasError = true;
                errorMessage = 'please enter valid 10 digit number';
            } else if (val !== '' && window.existingPhones && window.existingPhones.includes(val)) {
                hasError = true;
                errorMessage = 'already user in this mobile number';
            }
        }

        if (hasError) {
            input.classList.add('border-red-500', 'ring-1', 'ring-red-500');
            if (errElement) {
                errElement.innerText = errorMessage;
                errElement.classList.remove('hidden');
            }
        } else {
            input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
            if (errElement) {
                errElement.classList.add('hidden');
            }
        }
    }

    function validateStudentForm(event, form) {
        const nameInput = form.querySelector('input[name="name"]');
        const rollInput = form.querySelector('input[name="roll_number"]');
        const branchInput = form.querySelector('input[name="branch"]');
        const phoneInput = form.querySelector('input[name="phone"]');

        let isValid = true;
        const alphabetRegex = /^[A-Za-z]+(\s+[A-Za-z]+)*$/;
        const numericRegex = /^[0-9]+$/;

        if (nameInput) {
            const val = nameInput.value.trim();
            if (!alphabetRegex.test(val) || /\d/.test(val)) {
                nameInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                const err = document.getElementById((form.id === 'editForm' ? 'edit' : '') + 'nameError');
                if (err) { err.innerText = 'please enter letters only'; err.classList.remove('hidden'); }
                isValid = false;
            }
        }

        if (rollInput) {
            const val = rollInput.value.trim().toLowerCase();
            if (!numericRegex.test(val) || (window.existingRolls && window.existingRolls.includes(val))) {
                rollInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                const err = document.getElementById((form.id === 'editForm' ? 'edit' : '') + 'rollError');
                if (err) { 
                    err.innerText = window.existingRolls && window.existingRolls.includes(val) ? 'already user in this roll number' : 'please enter numbers only'; 
                    err.classList.remove('hidden'); 
                }
                isValid = false;
            }
        }

        if (branchInput) {
            const val = branchInput.value.trim();
            if (!alphabetRegex.test(val) || /\d/.test(val)) {
                branchInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                const err = document.getElementById((form.id === 'editForm' ? 'edit' : '') + 'branchError');
                if (err) { err.innerText = 'please enter letters only'; err.classList.remove('hidden'); }
                isValid = false;
            }
        }

        if (phoneInput) {
            const val = phoneInput.value.trim().toLowerCase();
            if (!numericRegex.test(val) || val.length !== 10 || (window.existingPhones && window.existingPhones.includes(val))) {
                phoneInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                const err = document.getElementById((form.id === 'editForm' ? 'edit' : '') + 'phoneError');
                if (err) { 
                    err.innerText = window.existingPhones && window.existingPhones.includes(val) ? 'already user in this mobile number' : 'please enter valid 10 digit number'; 
                    err.classList.remove('hidden'); 
                }
                isValid = false;
            }
        }

        if (!isValid) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    function openAddModal() { document.getElementById('addModal').classList.remove('hidden'); document.getElementById('addModal').classList.add('flex'); }
    function closeAddModal() { document.getElementById('addModal').classList.add('hidden'); document.getElementById('addModal').classList.remove('flex'); clearAddForm(); }
    function clearAddForm() { document.getElementById('addStudentForm').reset(); }

    function openEditModal(id, name, roll, branch, phone) {
        document.getElementById('editForm').action = '/admin/subject-students/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editRoll').value = roll;
        document.getElementById('editBranch').value = branch;
        document.getElementById('editPhone').value = phone;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); document.getElementById('editModal').classList.remove('flex'); }

    function openDeleteModal(id) {
        document.getElementById('deleteForm').action = '/admin/subject-students/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }
    function closeDeleteModal() { document.getElementById('deleteModal').classList.add('hidden'); document.getElementById('deleteModal').classList.remove('flex'); document.getElementById('deleteForm').reset(); }

    function openStudentDateModal(name, roll, date, time) {
        document.getElementById('addedStudentName').innerText = name;
        document.getElementById('addedStudentRoll').innerText = roll;
        document.getElementById('addedStudentDate').innerText = date;
        document.getElementById('addedStudentTime').innerText = time ? 'Time: ' + time : '';
        document.getElementById('studentDateModal').classList.remove('hidden');
        document.getElementById('studentDateModal').classList.add('flex');
    }
    function closeStudentDateModal() { document.getElementById('studentDateModal').classList.add('hidden'); document.getElementById('studentDateModal').classList.remove('flex'); }
</script>

@endsection