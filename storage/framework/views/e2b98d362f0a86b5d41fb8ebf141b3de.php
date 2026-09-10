

<?php $__env->startSection('title', $folder->name . ' - Students'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    
    
    <?php if(session('success')): ?>
        <div
            id="flashAlert"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-emerald-950/95 border border-emerald-500 text-emerald-200 px-6 sm:px-8 py-4 rounded-2xl shadow-2xl text-sm sm:text-base flex items-center gap-3 transition-all duration-500 max-w-[90vw] cursor-pointer"
        >
            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
            <span class="font-semibold"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div
            id="flashAlert"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-50 bg-red-950/95 border border-red-500 text-red-200 px-6 sm:px-8 py-4 rounded-2xl shadow-2xl text-sm sm:text-base flex items-center gap-3 transition-all duration-500 max-w-[90vw] cursor-pointer"
        >
            <span class="w-3 h-3 rounded-full bg-red-400 animate-pulse shrink-0"></span>
            <span class="font-semibold"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div id="errorAlert" class="mb-6 bg-red-950/90 border border-red-500 text-red-200 px-6 py-4 rounded-2xl shadow-xl cursor-pointer">
            <ul class="list-disc list-inside text-sm space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    
    
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl shadow-darkCard">

            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                <a
                    href="<?php echo e(route('admin.folders.show', $folder->id)); ?>"
                    class="btn-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shrink-0 w-fit"
                >
                    &larr; Back
                </a>

                <div>
                    <p class="text-xs text-orangeLight uppercase tracking-wider font-bold mb-2">
                        Folder Students
                    </p>

                    <h1 class="text-3xl sm:text-4xl font-extrabold text-textPrimary tracking-tight break-words flex items-center gap-3">
                        <span class="text-2xl">📁</span>
                        <span><?php echo e($folder->name); ?></span>
                    </h1>

                    <p class="text-textSecondary text-sm mt-2">
                        Manage all students in this folder.
                    </p>
                </div>
            </div>

            <button
                type="button"
                onclick="openAddModal()"
                class="btn-primary font-bold px-7 py-3.5 rounded-2xl text-sm flex items-center justify-center gap-2 shadow-blueGlow shrink-0"
            >
                + Add Student
            </button>
        </div>
    </div>

    
    
    
    <div class="mb-8 flex justify-start">
        <div class="relative w-full max-w-xl">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-textMuted">
                🔍
            </span>

            <input
                type="text"
                id="studentSearch"
                placeholder="Search by name, roll number, branch or mobile number..."
                class="input-dark rounded-2xl pl-11 pr-5 py-3.5 text-sm w-full shadow-inner"
                autocomplete="off"
            >
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
                    <?php $__empty_1 = true; $__currentLoopData = $folder->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr
                            class="student-row hover:bg-cardHover/50 transition-colors"
                            data-name="<?php echo e(strtolower($student->name)); ?>"
                            data-roll="<?php echo e(strtolower($student->roll_number)); ?>"
                            data-branch="<?php echo e(strtolower($student->branch)); ?>"
                            data-phone="<?php echo e(strtolower($student->phone)); ?>"
                        >
                            <td class="font-semibold text-textSecondary py-5 px-6">
                                <span class="bg-card px-3 py-1 rounded-lg border border-borderCol text-xs font-mono">
                                    <?php echo e($student->serno ?? ($index + 1)); ?>

                                </span>
                            </td>

                            <td class="font-bold text-textPrimary text-lg py-5 px-6">
                                <?php echo e($student->name); ?>

                            </td>

                            <td class="py-5 px-6">
                                <span class="bg-card px-3 py-1.5 rounded-xl border border-borderCol text-sm font-mono font-semibold">
                                    <?php echo e($student->roll_number); ?>

                                </span>
                            </td>

                            <td class="text-textSecondary py-5 px-6">
                                <?php echo e($student->branch); ?>

                            </td>

                            <td class="text-textSecondary font-mono py-5 px-6">
                                <?php echo e($student->phone); ?>

                            </td>

                            <td class="py-5 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    
                                    <button
                                        type="button"
                                        title="View Student Added Date"
                                        onclick="openStudentDateModal(
                                            <?php echo \Illuminate\Support\Js::from($student->name)->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($student->roll_number)->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($student->created_at ? $student->created_at->format('d/m/Y') : 'Date not available')->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($student->created_at ? $student->created_at->format('h:i A') : '')->toHtml() ?>
                                        )"
                                        class="p-2.5 rounded-xl text-xs font-bold bg-emerald-600/20 border border-emerald-500/40 text-emerald-400 hover:bg-emerald-600/30 transition-all inline-flex items-center justify-center"
                                    >
                                        📅
                                    </button>

                                    <button
                                        type="button"
                                        onclick="openEditModal(
                                            <?php echo \Illuminate\Support\Js::from($student->id)->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($student->name)->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($student->roll_number)->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($student->branch)->toHtml() ?>,
                                            <?php echo \Illuminate\Support\Js::from($student->phone)->toHtml() ?>
                                        )"
                                        class="px-4 py-2.5 rounded-xl text-xs font-bold bg-blue-600/20 border border-blue-500/40 text-blue-400 hover:bg-blue-600/30 transition-all"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        onclick="openDeleteModal(<?php echo \Illuminate\Support\Js::from($student->id)->toHtml() ?>)"
                                        class="px-4 py-2.5 rounded-xl text-xs font-bold bg-red-600/20 border border-red-500/40 text-red-400 hover:bg-red-600/30 transition-all"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="p-20 text-center text-textMuted">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-6xl mb-4">🎓</span>

                                    <p class="font-bold text-xl text-textSecondary">
                                        No Students Added
                                    </p>

                                    <p class="text-base text-textMuted mt-2">
                                        Click “+ Add Student” above to add the first student to this folder.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<div id="studentDateModal" class="hidden fixed inset-0 bg-black/85 backdrop-blur-md items-center justify-center z-50 p-4">
    <div class="bg-surface border border-borderCol rounded-3xl p-8 w-full max-w-md shadow-darkCard">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-textPrimary flex items-center gap-2">
                <span>📅</span>
                Student Added Date
            </h2>

            <button
                type="button"
                onclick="closeStudentDateModal()"
                class="text-textSecondary hover:text-textPrimary text-xl font-bold"
            >
                ✕
            </button>
        </div>

        <div class="space-y-4">
            <div class="bg-card border border-borderCol rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wider font-semibold text-textMuted">
                    Student Name
                </p>

                <p id="addedStudentName" class="text-xl font-bold text-textPrimary mt-2"></p>
            </div>

            <div class="bg-card border border-borderCol rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wider font-semibold text-textMuted">
                    Roll Number
                </p>

                <p id="addedStudentRoll" class="text-lg font-mono font-semibold text-textPrimary mt-2"></p>
            </div>

            <div class="border border-orangeAccent/50 bg-orangeAccent/5 rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wider font-semibold text-orangeLight">
                    Added in System On
                </p>

                <p id="addedStudentDate" class="text-2xl font-extrabold text-textPrimary mt-2"></p>

                <p id="addedStudentTime" class="text-sm text-textSecondary mt-1"></p>
            </div>
        </div>

        <div class="mt-7 flex justify-end">
            <button
                type="button"
                onclick="closeStudentDateModal()"
                class="btn-primary px-6 py-3 rounded-xl font-bold text-sm"
            >
                Close
            </button>
        </div>
    </div>
</div>




<div id="addModal" class="hidden fixed inset-0 bg-black/85 backdrop-blur-md items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-surface border border-borderCol rounded-3xl p-8 w-full max-w-lg shadow-darkCard">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-textPrimary">
                Add Student
            </h2>

            <button
                type="button"
                onclick="closeAddModal()"
                class="text-textSecondary hover:text-textPrimary text-xl font-bold"
            >
                ✕
            </button>
        </div>

        <form id="addStudentForm" method="POST" action="<?php echo e(route('admin.students.store', $folder->id)); ?>" class="space-y-5" onsubmit="return validateStudentForm(event, this)">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    Student Name *
                </label>

                <input
                    type="text"
                    name="name"
                    id="addName"
                    required
                    maxlength="255"
                    placeholder="Enter student name"
                    value="<?php echo e(old('name')); ?>"
                    class="input-dark rounded-2xl px-5 py-3.5 text-base w-full transition-colors"
                    oninput="validateFieldLive(this, 'name')"
                >
                <p id="nameError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter letters only (no numbers)</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    Roll Number *
                </label>

                <input
                    type="text"
                    name="roll_number"
                    id="addRoll"
                    required
                    maxlength="50"
                    placeholder="Enter roll number"
                    value="<?php echo e(old('roll_number')); ?>"
                    class="input-dark rounded-2xl px-5 py-3.5 text-base w-full transition-colors"
                    oninput="validateFieldLive(this, 'roll')"
                >
                <p id="rollError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter numbers only</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    Branch *
                </label>

                <input
                    type="text"
                    name="branch"
                    id="addBranch"
                    required
                    maxlength="255"
                    placeholder="Example: Computer Engineering"
                    value="<?php echo e(old('branch')); ?>"
                    class="input-dark rounded-2xl px-5 py-3.5 text-base w-full transition-colors"
                    oninput="validateFieldLive(this, 'branch')"
                >
                <p id="branchError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter letters only (no numbers)</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    Mobile Number *
                </label>

                <input
                    type="tel"
                    name="phone"
                    id="addPhone"
                    required
                    maxlength="10"
                    minlength="10"
                    placeholder="Enter 10 digit mobile number"
                    value="<?php echo e(old('phone')); ?>"
                    class="input-dark rounded-2xl px-5 py-3.5 text-base w-full transition-colors"
                    oninput="validateFieldLive(this, 'phone')"
                >
                <p id="phoneError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter valid 10 digit number</p>
            </div>

            <div class="flex items-center justify-between pt-3">
                <button
                    type="button"
                    onclick="clearAddForm()"
                    class="px-5 py-3 rounded-2xl text-red-400 hover:text-red-300 text-sm font-bold bg-red-500/10 border border-red-500/30 transition-colors cursor-pointer"
                >
                    Clear Form
                </button>

                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        onclick="closeAddModal()"
                        class="px-6 py-3 rounded-2xl text-textSecondary hover:text-textPrimary text-base font-semibold transition-colors"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        id="addSubmitBtn"
                        class="btn-primary font-bold px-7 py-3 rounded-2xl text-base shadow-blueGlow cursor-pointer"
                    >
                        Add Student
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>




<div id="editModal" class="hidden fixed inset-0 bg-black/85 backdrop-blur-md items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-surface border border-borderCol rounded-3xl p-8 w-full max-w-lg shadow-darkCard">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-textPrimary">
                Edit Student
            </h2>

            <button
                type="button"
                onclick="closeEditModal()"
                class="text-textSecondary hover:text-textPrimary text-xl font-bold"
            >
                ✕
            </button>
        </div>

        <form id="editForm" method="POST" class="space-y-5" onsubmit="return validateStudentForm(event, this)">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    Student Name *
                </label>

                <input id="editName" type="text" name="name" required maxlength="255" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full" oninput="validateFieldLive(this, 'name')">
                <p id="editNameError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter letters only</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    Roll Number *
                </label>

                <input id="editRoll" type="text" name="roll_number" required maxlength="50" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full" oninput="validateFieldLive(this, 'roll')">
                <p id="editRollError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter numbers only</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    Branch *
                </label>

                <input id="editBranch" type="text" name="branch" required maxlength="255" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full" oninput="validateFieldLive(this, 'branch')">
                <p id="editBranchError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter letters only</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    Mobile Number *
                </label>

                <input id="editPhone" type="tel" name="phone" required maxlength="10" minlength="10" class="input-dark rounded-2xl px-5 py-3.5 text-base w-full" oninput="validateFieldLive(this, 'phone')">
                <p id="editPhoneError" class="text-xs text-red-400 mt-1.5 font-medium hidden">please enter valid 10 digit number</p>
            </div>

            <div class="flex justify-end gap-3 pt-3">
                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="px-6 py-3 rounded-2xl text-textSecondary hover:text-textPrimary text-base font-semibold transition-colors"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn-primary font-bold px-7 py-3 rounded-2xl text-base shadow-blueGlow"
                >
                    Update Student
                </button>
            </div>
        </form>
    </div>
</div>




<div id="deleteModal" class="hidden fixed inset-0 bg-black/85 backdrop-blur-md items-center justify-center z-50 p-4">
    <div class="bg-surface border border-borderCol rounded-3xl p-8 w-full max-w-md shadow-darkCard">

        <h2 class="text-2xl font-bold mb-2 text-danger">
            Delete Student
        </h2>

        <p class="text-textSecondary text-base mb-6">
            Enter your admin password to securely delete this student record and associated attendance logs.
        </p>

        <form id="deleteForm" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>

            <div>
                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Admin Password"
                    class="input-dark rounded-2xl px-5 py-3.5 text-base w-full"
                >
            </div>

            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="px-6 py-3 rounded-2xl text-textSecondary hover:text-textPrimary text-base font-semibold transition-colors"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn-danger font-bold px-7 py-3 rounded-2xl text-base"
                >
                    Confirm Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* Collect existing roll numbers and mobile numbers from database records for live duplicate checking */
        window.existingRolls = [];
        window.existingPhones = [];

        <?php $__currentLoopData = $folder->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            window.existingRolls.push("<?php echo e(strtolower(trim($st->roll_number))); ?>");
            window.existingPhones.push("<?php echo e(strtolower(trim($st->phone))); ?>");
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        /* Auto close green/red flash message after 3 seconds or on any screen click */
        const flashAlert = document.getElementById('flashAlert');
        const errorAlert = document.getElementById('errorAlert');

        function dismissAlerts() {
            if (flashAlert) {
                flashAlert.style.opacity = '0';
                flashAlert.style.transform = 'translate(-50%, -20px)';
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

        /* Search only inside this folder */
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

                    if (isVisible) {
                        visibleCount++;
                    }
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

        /* Automatic Title Case Formatting on Blur for Inputs */
        const textInputs = document.querySelectorAll('input[name="name"], input[name="branch"], #editName, #editBranch');
        textInputs.forEach(input => {
            input.addEventListener('blur', function() {
                let cleanedValue = this.value.trim().replace(/\s+/g, ' ');
                let words = cleanedValue.toLowerCase().split(' ');
                this.value = words.map(word => word ? word.charAt(0).toUpperCase() + word.slice(1) : '').join(' ');
            });
        });
    });

    /* Live Validation as user types with small letter instructions */
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
                errorMessage = 'already number is exist';
            }
        } else if (type === 'phone') {
            if (val !== '' && (!numericRegex.test(val) || val.length !== 10)) {
                hasError = true;
                errorMessage = 'please enter valid 10 digit number';
            } else if (val !== '' && window.existingPhones && window.existingPhones.includes(val)) {
                hasError = true;
                errorMessage = 'already number is exist';
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

    /* Strict Form Submit Validation */
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
                if (err) {
                    err.innerText = 'please enter letters only';
                    err.classList.remove('hidden');
                }
                isValid = false;
            }
        }

        if (rollInput) {
            const val = rollInput.value.trim().toLowerCase();
            if (!numericRegex.test(val) || (window.existingRolls && window.existingRolls.includes(val))) {
                rollInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                const err = document.getElementById((form.id === 'editForm' ? 'edit' : '') + 'rollError');
                if (err) {
                    err.innerText = window.existingRolls && window.existingRolls.includes(val) 
                        ? 'already number is exist' 
                        : 'please enter numbers only';
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
                if (err) {
                    err.innerText = 'please enter letters only';
                    err.classList.remove('hidden');
                }
                isValid = false;
            }
        }

        if (phoneInput) {
            const val = phoneInput.value.trim().toLowerCase();
            if (!numericRegex.test(val) || val.length !== 10 || (window.existingPhones && window.existingPhones.includes(val))) {
                phoneInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                const err = document.getElementById((form.id === 'editForm' ? 'edit' : '') + 'phoneError');
                if (err) {
                    err.innerText = window.existingPhones && window.existingPhones.includes(val) 
                        ? 'already number is exist' 
                        : 'please enter valid 10 digit number';
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

    function clearAddForm() {
        document.getElementById('addStudentForm').reset();
        const inputs = document.querySelectorAll('#addStudentForm input');
        inputs.forEach(input => {
            input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
        });
        ['nameError', 'rollError', 'branchError', 'phoneError'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        });
    }

    /* Student system added-date calendar modal */
    function openStudentDateModal(name, roll, date, time) {
        document.getElementById('addedStudentName').innerText = name;
        document.getElementById('addedStudentRoll').innerText = roll;
        document.getElementById('addedStudentDate').innerText = date;
        document.getElementById('addedStudentTime').innerText = time
            ? 'Time: ' + time
            : '';

        const modal = document.getElementById('studentDateModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeStudentDateModal() {
        const modal = document.getElementById('studentDateModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    /* Add modal */
    function openAddModal() {
        const modal = document.getElementById('addModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAddModal() {
        const modal = document.getElementById('addModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        clearAddForm();
    }

    /* Edit modal */
    function openEditModal(id, name, roll, branch, phone) {
        document.getElementById('editForm').action = '/admin/students/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editRoll').value = roll;
        document.getElementById('editBranch').value = branch;
        document.getElementById('editPhone').value = phone;

        const modal = document.getElementById('editModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    /* Delete modal */
    function openDeleteModal(id) {
        document.getElementById('deleteForm').action = '/admin/students/' + id;

        const modal = document.getElementById('deleteModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.getElementById('deleteForm').reset();
    }

    /* Escape key closes all modals */
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeStudentDateModal();
            closeAddModal();
            closeEditModal();
            closeDeleteModal();
        }
    });

    /* Click outside any modal to close it */
    ['studentDateModal', 'addModal', 'editModal', 'deleteModal'].forEach(function (modalId) {
        const modal = document.getElementById(modalId);

        if (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        }
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/students.blade.php ENDPATH**/ ?>