

<?php $__env->startSection('title', 'Mark Attendance - ' . $subject->name); ?>

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

    
    
    
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl shadow-darkCard">
            <div class="space-y-3">
                <div>
                    <a
                        href="<?php echo e(route('admin.subjects.show', $subject->id)); ?>"
                        class="btn-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all"
                    >
                        &larr; Back
                    </a>
                </div>

                <h1 class="text-4xl sm:text-5xl font-extrabold text-textPrimary tracking-tight break-words flex items-baseline gap-3 pt-1">
                    <span class="w-4 h-4 rounded-full bg-blue-500 inline-block shrink-0"></span>
                    <span><?php echo e($subject->name); ?> <span class="text-textMuted font-medium text-2xl">/ Mark Attendance</span></span>
                </h1>

                <p class="text-textSecondary text-base sm:text-lg">
                    Select attendance status for each student and save daily records securely for this subject.
                </p>
            </div>
        </div>
    </div>

    
    
    
    <div class="bg-surface border border-borderCol p-8 rounded-3xl mb-8 shadow-darkCard">
        <form
            method="GET"
            action="<?php echo e(route('admin.subjects.attendance', $subject->id)); ?>"
            id="dateForm"
            class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end"
        >
            <div>
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    📅 Attendance Date
                </label>
                <input
                    type="date"
                    name="date"
                    id="attendanceDate"
                    class="input-dark rounded-2xl px-5 py-3.5 text-base"
                    style="color-scheme: dark;"
                    value="<?php echo e($selectedDate ?? \Carbon\Carbon::today()->toDateString()); ?>"
                    max="<?php echo e(\Carbon\Carbon::today()->toDateString()); ?>"
                    onchange="changeAttendanceDate()"
                >
                <div class="text-xs text-textMuted mt-2">
                    Today: <strong class="text-textPrimary"><?php echo e(\Carbon\Carbon::today()->format('d M Y')); ?></strong>
                </div>
            </div>

            <div class="relative">
                <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">
                    🔎 Search Students
                </label>
                <input
                    type="text"
                    id="studentSearch"
                    class="input-dark rounded-2xl px-5 py-3.5 text-base"
                    placeholder="Search by name, roll number, branch or mobile..."
                    onkeyup="searchStudents()"
                    autocomplete="off"
                >
            </div>
        </form>
    </div>

    
    
    
    <?php
        $totalStudents = 0;
        $presentCount = 0;
        $absentCount = 0;
        $markedCount = 0;

        foreach ($subject->students as $student) {
            $totalStudents++;
            $status = $attendances[$student->id] ?? null;
            if ($status === 'present') {
                $presentCount++;
                $markedCount++;
            }
            if ($status === 'absent') {
                $absentCount++;
                $markedCount++;
            }
        }
        $notMarkedCount = $totalStudents - $markedCount;
    ?>

    
    
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-surface border border-borderCol p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl font-extrabold text-textPrimary tracking-tight mt-1"><?php echo e($totalStudents); ?></div>
            <div class="text-textSecondary text-sm font-semibold uppercase tracking-wider mt-2">Total Students</div>
        </div>
        <div class="bg-surface border border-emerald-500/40 p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl font-extrabold text-emerald-400 tracking-tight mt-1" id="presentCountDisplay"><?php echo e($presentCount); ?></div>
            <div class="text-emerald-400 text-sm font-semibold uppercase tracking-wider mt-2">Present</div>
        </div>
        <div class="bg-surface border border-red-500/40 p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl font-extrabold text-red-400 tracking-tight mt-1" id="absentCountDisplay"><?php echo e($absentCount); ?></div>
            <div class="text-red-400 text-sm font-semibold uppercase tracking-wider mt-2">Absent</div>
        </div>
        <div class="bg-surface border border-borderCol p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl font-extrabold text-textPrimary tracking-tight mt-1"><?php echo e($notMarkedCount); ?></div>
            <div class="text-textSecondary text-sm font-semibold uppercase tracking-wider mt-2">Not Marked</div>
        </div>
    </div>

    
    
    
    <div class="bg-surface border border-borderCol rounded-3xl overflow-hidden shadow-darkCard mb-10">
        <form
            method="POST"
            action="<?php echo e(route('admin.subjects.attendance.submit', $subject->id)); ?>"
            id="attendanceForm"
            onsubmit="return validateAttendanceSubmit(event)"
        >
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($selectedDate ?? \Carbon\Carbon::today()->toDateString()); ?>">

            <div class="p-8 border-b border-borderCol flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-textPrimary">Subject Attendance</h2>
                    <p class="text-textSecondary text-sm mt-1">Select attendance status for each student and save records.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-dark text-left text-base min-w-[1000px]">
                    <thead>
                        <tr class="text-sm uppercase tracking-wider text-textSecondary">
                            <th class="w-20 py-5 px-6">S.No</th>
                            <th class="py-5 px-6">Student</th>
                            <th class="py-5 px-6">Roll Number</th>
                            <th class="py-5 px-6">Branch</th>
                            <th class="py-5 px-6">Mobile Number</th>
                            <th class="py-5 px-6 text-center">Attendance</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <?php $__empty_1 = true; $__currentLoopData = $subject->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $currentStatus = $attendances[$student->id] ?? null;
                            ?>

                            <tr
                                class="student-row"
                                data-search="<?php echo e(strtolower($student->name . ' ' . $student->roll_number . ' ' . $student->branch . ' ' . $student->phone)); ?>"
                            >
                                <td class="font-semibold text-textSecondary py-5 px-6">
                                    <span class="bg-card px-3 py-1 rounded-lg border border-borderCol text-xs font-mono"><?php echo e($student->serno ?? ($index + 1)); ?></span>
                                </td>

                                <td class="py-5 px-6">
                                    <div class="font-bold text-textPrimary text-lg">
                                        <?php echo e($student->name); ?>

                                    </div>
                                    <?php if($student->created_at): ?>
                                        <span class="text-textMuted text-xs mt-1 block">
                                            Added: <?php echo e($student->created_at->format('d M Y')); ?>

                                        </span>
                                    <?php endif; ?>
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
                                    
                                    <div class="flex items-center justify-center gap-3 <?php echo e(is_null($currentStatus) ? 'hidden' : ''); ?>" id="display-box-<?php echo e($student->id); ?>">
                                        <span class="px-4 py-2 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 <?php echo e($currentStatus === 'present' ? 'bg-emerald-500/10 border border-emerald-500/40 text-emerald-400' : 'bg-red-500/10 border border-red-500/40 text-red-400'); ?>">
                                            <?php if($currentStatus === 'present'): ?> ✓ Present <?php else: ?> ✕ Absent <?php endif; ?>
                                        </span>
                                        <button
                                            type="button"
                                            onclick="enableEdit('<?php echo e($student->id); ?>')"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-600/20 border border-blue-500/40 text-blue-400 hover:bg-blue-600/30 transition-all cursor-pointer shadow-sm"
                                        >
                                            Edit
                                        </button>
                                    </div>

                                    
                                    <div class="flex items-center justify-center gap-2 <?php echo e(!is_null($currentStatus) ? 'hidden' : ''); ?>" id="edit-box-<?php echo e($student->id); ?>">
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-borderCol transition-all status-btn present <?php echo e($currentStatus === 'present' ? 'active-present' : 'bg-card text-textSecondary hover:border-emerald-500 hover:text-emerald-400'); ?>"
                                            data-student="<?php echo e($student->id); ?>"
                                            data-status="present"
                                            onclick="setAttendanceDirect(this)"
                                        >
                                            ✓ Present
                                        </button>
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-borderCol transition-all status-btn absent <?php echo e($currentStatus === 'absent' ? 'active-absent' : 'bg-card text-textSecondary hover:border-red-500 hover:text-red-400'); ?>"
                                            data-student="<?php echo e($student->id); ?>"
                                            data-status="absent"
                                            onclick="setAttendanceDirect(this)"
                                        >
                                            ✕ Absent
                                        </button>
                                    </div>

                                    
                                    <div class="flex items-center justify-center gap-2 hidden" id="edit-save-box-<?php echo e($student->id); ?>">
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-borderCol transition-all status-btn present <?php echo e($currentStatus === 'present' ? 'active-present' : 'bg-card text-textSecondary hover:border-emerald-500 hover:text-emerald-400'); ?>"
                                            data-student="<?php echo e($student->id); ?>"
                                            data-status="present"
                                            onclick="setAttendanceDirect(this)"
                                        >
                                            ✓ Present
                                        </button>
                                        <button
                                            type="button"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold border border-borderCol transition-all status-btn absent <?php echo e($currentStatus === 'absent' ? 'active-absent' : 'bg-card text-textSecondary hover:border-red-500 hover:text-red-400'); ?>"
                                            data-student="<?php echo e($student->id); ?>"
                                            data-status="absent"
                                            onclick="setAttendanceDirect(this)"
                                        >
                                            ✕ Absent
                                        </button>
                                        <button
                                            type="button"
                                            onclick="saveSingleStudent('<?php echo e($student->id); ?>')"
                                            class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 transition-all cursor-pointer shadow-md"
                                        >
                                            Save
                                        </button>
                                    </div>

                                    <input type="hidden" name="student_<?php echo e($student->id); ?>" id="status-<?php echo e($student->id); ?>" value="<?php echo e($currentStatus); ?>" class="attendance-input" data-name="<?php echo e($student->name); ?>" data-roll="<?php echo e($student->roll_number); ?>">
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="p-20 text-center text-textMuted">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="text-6xl mb-4">👨‍🎓</span>
                                        <p class="font-bold text-xl text-textSecondary">No Students Found</p>
                                        <p class="text-base text-textMuted mt-2">There are no students in this subject folder.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($subject->students->count() > 0): ?>
                <div class="p-8 border-t border-borderCol bg-surface flex justify-end">
                    <button
                        type="submit"
                        class="btn-primary font-bold px-8 py-4 rounded-2xl text-base shadow-blueGlow submit-btn cursor-pointer"
                    >
                        ✓ Save Attendance
                    </button>
                </div>
            <?php endif; ?>
        </form>
    </div>

</div>

<style>
.active-present {
    background: #10B981 !important;
    border-color: #10B981 !important;
    color: #FFFFFF !important;
    box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);
}
.active-absent {
    background: #EF4444 !important;
    border-color: #EF4444 !important;
    color: #FFFFFF !important;
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const flashAlert = document.getElementById('flashAlert');

        function dismissAlerts() {
            if (flashAlert) {
                flashAlert.style.opacity = '0';
                flashAlert.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => flashAlert.remove(), 300);
            }
        }

        if (flashAlert) {
            setTimeout(dismissAlerts, 3000);
            document.addEventListener('click', dismissAlerts, { once: true });
        }
    });

    function changeAttendanceDate() {
        document.getElementById('dateForm').submit();
    }

    function searchStudents() {
        const search = document.getElementById('studentSearch').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.student-row');
        let visibleCount = 0;

        rows.forEach(function(row) {
            const data = row.getAttribute('data-search') || '';
            if (data.includes(search)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        let noDataRow = document.getElementById('noSearchDataRow');
        const tbody = document.getElementById('studentTableBody');

        if (visibleCount === 0) {
            if (!noDataRow) {
                noDataRow = document.createElement('tr');
                noDataRow.id = 'noSearchDataRow';
                noDataRow.innerHTML = `
                    <td colspan="6" class="p-16 text-center text-textMuted">
                        <div class="flex flex-col items-center justify-center">
                            <span class="text-5xl mb-3">🔍</span>
                            <p class="font-bold text-lg text-textSecondary">No Matching Student Found</p>
                            <p class="text-sm text-textMuted mt-1">No student data found matching your search query.</p>
                        </div>
                    </td>
                `;
                tbody.appendChild(noDataRow);
            }
        } else {
            if (noDataRow) {
                noDataRow.remove();
            }
        }
    }

    function enableEdit(studentId) {
        const displayBox = document.getElementById('display-box-' + studentId);
        const editSaveBox = document.getElementById('edit-save-box-' + studentId);
        if (displayBox) displayBox.classList.add('hidden');
        if (editSaveBox) editSaveBox.classList.remove('hidden');
    }

    function setAttendanceDirect(button) {
        const studentId = button.getAttribute('data-student');
        const status = button.getAttribute('data-status');
        const parentTd = button.closest('td');

        const input = document.getElementById('status-' + studentId);
        if (!input) return;
        input.value = status;

        parentTd.querySelectorAll('.status-btn').forEach(btn => {
            btn.classList.remove('active-present', 'active-absent');
            btn.classList.add('bg-card', 'text-textSecondary');
        });

        parentTd.querySelectorAll(`.status-btn[data-student="${studentId}"][data-status="${status}"]`).forEach(btn => {
            btn.classList.remove('bg-card', 'text-textSecondary');
            if (status === 'present') {
                btn.classList.add('active-present');
            } else {
                btn.classList.add('active-absent');
            }
        });

        updateLiveCounts();
    }

    function updateLiveCounts() {
        let present = 0;
        let absent = 0;
        document.querySelectorAll('.attendance-input').forEach(input => {
            if (input.value === 'present') present++;
            else if (input.value === 'absent') absent++;
        });
        const presentDisplay = document.getElementById('presentCountDisplay');
        const absentDisplay = document.getElementById('absentCountDisplay');
        if (presentDisplay) presentDisplay.textContent = present;
        if (absentDisplay) absentDisplay.textContent = absent;
    }

    function validateAttendanceSubmit(event) {
        let missingStudents = [];
        document.querySelectorAll('.attendance-input').forEach(input => {
            if (!input.value || (input.value !== 'present' && input.value !== 'absent')) {
                const name = input.getAttribute('data-name');
                const roll = input.getAttribute('data-roll');
                missingStudents.push(`• Roll: ${roll} (${name})`);
            }
        });

        if (missingStudents.length > 0) {
            const message = "⚠️ ATTENDANCE SUBMISSION BLOCKED\n\n" +
                            "Please ensure attendance status (Present or Absent) is marked for all students before saving.\n\n" +
                            "Pending Students:\n" + missingStudents.join('\n');
            alert(message);
            event.preventDefault();
            return false;
        }
        return true;
    }

    function saveSingleStudent(studentId) {
        const statusInput = document.getElementById('status-' + studentId);
        const dateInput = document.getElementById('attendanceDate').value;
        
        if (!statusInput || !statusInput.value) {
            alert('Please select Present or Absent.');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "<?php echo e(route('admin.subjects.attendance.submit', $subject->id)); ?>";

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = "<?php echo e(csrf_token()); ?>";
        form.appendChild(csrf);

        const dateField = document.createElement('input');
        dateField.type = 'hidden';
        dateField.name = 'date';
        dateField.value = dateInput;
        form.appendChild(dateField);

        const statusField = document.createElement('input');
        statusField.type = 'hidden';
        statusField.name = 'student_' + studentId;
        statusField.value = statusInput.value;
        form.appendChild(statusField);

        document.body.appendChild(form);
        form.submit();
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/subjects/attendance.blade.php ENDPATH**/ ?>