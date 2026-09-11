

<?php $__env->startSection('title', 'My Attendance Report - ' . $folder->name); ?>

<?php $__env->startSection('content'); ?>

<?php
    $hasAttendance = $hasAttendance ?? false;
    $date = $date ?? now()->format('Y-m-d');
    $month = $month ?? now()->format('Y-m');
    $start = $start ?? now()->subDays(7)->format('Y-m-d');
    $end = $end ?? now()->format('Y-m-d');
    $type = $type ?? 'daily';
?>

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    
    
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl shadow-darkCard">
            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                <a
                    href="<?php echo e(route('student.folders')); ?>"
                    class="btn-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all shrink-0 w-fit"
                >
                    &larr; Back to Folders
                </a>

                <h1 class="text-3xl sm:text-4xl font-extrabold text-textPrimary tracking-tight break-words flex items-center gap-3">
                    <span class="text-2xl">📁</span>
                    <span><?php echo e($folder->name); ?> <span class="text-textMuted font-medium text-xl sm:text-2xl">/ My Attendance Report</span></span>
                </h1>
            </div>
        </div>
    </div>

    
    
    
    <div class="bg-surface border border-borderCol p-3 rounded-2xl mb-8 shadow-darkCard flex flex-wrap items-center gap-3">
        <a
            href="<?php echo e(route('student.report.show', ['id' => $folder->id, 'type' => 'daily', 'date' => $date])); ?>"
            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all <?php echo e($type === 'daily' ? 'btn-primary shadow-blueGlow' : 'text-textSecondary hover:text-textPrimary hover:bg-cardHover'); ?>"
        >
            Daily Report
        </a>
        <a
            href="<?php echo e(route('student.report.show', ['id' => $folder->id, 'type' => 'monthly', 'month' => $month])); ?>"
            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all <?php echo e($type === 'monthly' ? 'btn-primary shadow-blueGlow' : 'text-textSecondary hover:text-textPrimary hover:bg-cardHover'); ?>"
        >
            Monthly Report
        </a>
        <a
            href="<?php echo e(route('student.report.show', ['id' => $folder->id, 'type' => 'custom', 'start' => $start, 'end' => $end])); ?>"
            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all <?php echo e($type === 'custom' ? 'btn-primary shadow-blueGlow' : 'text-textSecondary hover:text-textPrimary hover:bg-cardHover'); ?>"
        >
            Custom Range Report
        </a>
        <a
            href="<?php echo e(route('student.report.show', ['id' => $folder->id, 'type' => 'all'])); ?>"
            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all <?php echo e($type === 'all' ? 'btn-primary shadow-blueGlow' : 'text-textSecondary hover:text-textPrimary hover:bg-cardHover'); ?>"
        >
            All Days Report
        </a>
    </div>

    
    
    
    <?php if($type === 'daily'): ?>
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl mb-8 shadow-darkCard">
        <form method="GET" action="<?php echo e(route('student.report.show', ['id' => $folder->id, 'type' => 'daily'])); ?>" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-5">
            <input type="hidden" name="type" value="daily">

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full lg:w-auto">
                <div>
                    <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Select Date</label>
                    <input
                        type="date"
                        name="date"
                        value="<?php echo e($date); ?>"
                        max="<?php echo e(now()->format('Y-m-d')); ?>"
                        class="input-dark rounded-xl px-5 py-3 text-sm font-medium [color-scheme:dark]"
                        required
                    >
                </div>
                <div class="pt-5">
                    <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold text-sm shadow-blueGlow w-full sm:w-auto">
                        View Report
                    </button>
                </div>
            </div>

            <?php if($hasAttendance): ?>
            <div>
                <a
                    href="<?php echo e(route('student.report.pdf', ['id' => $folder->id, 'type' => 'daily', 'date' => $date])); ?>"
                    target="_blank"
                    class="btn-danger px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2 shadow-md w-full sm:w-auto justify-center"
                >
                    <span>📄</span> Download PDF
                </a>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <?php if(!$hasAttendance): ?>
    <div class="bg-red-950/80 border border-red-500/50 text-red-200 px-6 py-5 rounded-2xl mb-8 shadow-lg text-base">
        <div class="text-lg font-bold mb-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-400"></span> ⚠️ No Attendance Found
        </div>
        <div>No attendance marked for any student on this date.</div>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    
    
    
    <?php if($type === 'monthly'): ?>
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl mb-8 shadow-darkCard">
        <form method="GET" action="<?php echo e(route('student.report.show', ['id' => $folder->id, 'type' => 'monthly'])); ?>" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-5">
            <input type="hidden" name="type" value="monthly">

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full lg:w-auto">
                <div>
                    <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">Select Month</label>
                    <input
                        type="month"
                        name="month"
                        value="<?php echo e($month); ?>"
                        max="<?php echo e(now()->format('Y-m')); ?>"
                        class="input-dark rounded-xl px-5 py-3 text-sm font-medium [color-scheme:dark]"
                        required
                    >
                </div>
                <div class="pt-5">
                    <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold text-sm shadow-blueGlow w-full sm:w-auto">
                        View Report
                    </button>
                </div>
            </div>

            <?php if($hasAttendance): ?>
            <div>
                <a
                    href="<?php echo e(route('student.report.pdf', ['id' => $folder->id, 'type' => 'monthly', 'month' => $month])); ?>"
                    target="_blank"
                    class="btn-danger px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2 shadow-md w-full sm:w-auto justify-center"
                >
                    <span>📄</span> Download PDF
                </a>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <?php if(!$hasAttendance): ?>
    <div class="bg-red-950/80 border border-red-500/50 text-red-200 px-6 py-5 rounded-2xl mb-8 shadow-lg text-base">
        <div class="text-lg font-bold mb-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-400"></span> ⚠️ No Attendance Found
        </div>
        <div>No attendance marked for any student in this month.</div>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    
    
    
    <?php if($type === 'custom'): ?>
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl mb-8 shadow-darkCard">
        <form method="GET" action="<?php echo e(route('student.report.show', ['id' => $folder->id, 'type' => 'custom'])); ?>" class="flex flex-col lg:flex-row items-end lg:items-center justify-between gap-5" id="customReportForm">
            <input type="hidden" name="type" value="custom">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full lg:w-auto">
                <div>
                    <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">From Date</label>
                    <input
                        type="date"
                        name="start"
                        id="startDate"
                        value="<?php echo e($start); ?>"
                        max="<?php echo e(now()->format('Y-m-d')); ?>"
                        class="input-dark rounded-xl px-5 py-3 text-sm font-medium [color-scheme:dark]"
                        required
                    >
                </div>
                <div>
                    <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">To Date</label>
                    <input
                        type="date"
                        name="end"
                        id="endDate"
                        value="<?php echo e($end); ?>"
                        max="<?php echo e(now()->format('Y-m-d')); ?>"
                        class="input-dark rounded-xl px-5 py-3 text-sm font-medium [color-scheme:dark]"
                        required
                    >
                </div>
            </div>

            <div class="flex items-center gap-3 w-full lg:w-auto justify-end pt-2">
                <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-semibold text-sm shadow-blueGlow">
                    View Report
                </button>

                <?php if($hasAttendance): ?>
                <a
                    href="<?php echo e(route('student.report.pdf', ['id' => $folder->id, 'type' => 'custom', 'start' => $start, 'end' => $end])); ?>"
                    target="_blank"
                    class="btn-danger px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2 shadow-md justify-center"
                >
                    <span>📄</span> PDF
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if(!$hasAttendance): ?>
    <div class="bg-red-950/80 border border-red-500/50 text-red-200 px-6 py-5 rounded-2xl mb-8 shadow-lg text-base">
        <div class="text-lg font-bold mb-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-400"></span> ⚠️ No Attendance Found
        </div>
        <div>No attendance marked between <strong><?php echo e($start); ?></strong> and <strong><?php echo e($end); ?></strong>.</div>
    </div>
    <?php endif; ?>

    <script>
    document.getElementById('customReportForm')?.addEventListener('submit', function(event) {
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;
        if (start && end && start > end) {
            event.preventDefault();
            alert('From Date cannot be after To Date.');
            return false;
        }
    });
    </script>
    <?php endif; ?>

    
    
    
    <?php if($type === 'all'): ?>
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl mb-8 shadow-darkCard flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-textPrimary">All Days Attendance Record</h3>
            <p class="text-textSecondary text-sm mt-1">Showing aggregated records from each student's joining date up to the current date.</p>
        </div>
        <?php if($hasAttendance): ?>
        <div>
            <a
                href="<?php echo e(route('student.report.pdf', ['id' => $folder->id, 'type' => 'all'])); ?>"
                target="_blank"
                class="btn-danger px-6 py-3 rounded-xl font-semibold text-sm inline-flex items-center gap-2 shadow-md justify-center"
            >
                <span>📄</span> Download PDF
            </a>
        </div>
        <?php endif; ?>
    </div>

    <?php if(!$hasAttendance): ?>
    <div class="bg-red-950/80 border border-red-500/50 text-red-200 px-6 py-5 rounded-2xl mb-8 shadow-lg text-base">
        <div class="text-lg font-bold mb-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-400"></span> ⚠️ No Attendance Found
        </div>
        <div>No attendance records exist for this folder.</div>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    
    
    
    <?php if($hasAttendance || $type === 'all'): ?>
    <div class="mb-8 flex justify-start">
        <div class="relative w-full max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-textMuted">
                🔍
            </span>
            <input
                type="text"
                id="reportSearch"
                placeholder="Search students..."
                class="input-dark rounded-2xl pl-11 pr-5 py-3 text-sm w-full shadow-inner"
                onkeyup="searchReport()"
            >
        </div>
    </div>

    
    
    
    <div class="bg-surface border border-borderCol rounded-3xl overflow-hidden shadow-darkCard mb-10">
        <div class="overflow-x-auto">
            <table class="table-dark text-left text-base" id="studentsTable">
                <thead>
                    <tr class="text-sm uppercase tracking-wider text-textSecondary">
                        <th class="w-20 py-5 px-6">S.No</th>
                        <th class="py-5 px-6">Name</th>
                        <th class="py-5 px-6">Roll Number</th>
                        <th class="py-5 px-6">Branch</th>
                        <th class="py-5 px-6">Mobile Number</th>

                        <?php if($type === 'daily'): ?>
                            <th class="text-center py-5 px-6">Status</th>
                        <?php else: ?>
                            <th class="text-center py-5 px-6">Total Days</th>
                            <th class="text-center py-5 px-6">Present</th>
                            <th class="text-center py-5 px-6">Absent</th>
                            <th class="text-center py-5 px-6">Percentage</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody id="reportTableBody">
                    <?php $__empty_1 = true; $__currentLoopData = $studentsData ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $studentObj = $data['student'] ?? null;
                        $name = $studentObj->name ?? $data['name'] ?? '';
                        $roll = $studentObj->roll_number ?? $data['roll'] ?? '';
                        $branch = $studentObj->branch ?? $data['branch'] ?? '';
                        $phone = $studentObj->phone ?? $data['phone'] ?? '';
                        $serno = $studentObj->serno ?? $data['serno'] ?? $loop->iteration;
                    ?>
                    <tr
                        class="report-row"
                        data-search="<?php echo e(strtolower($name . ' ' . $roll . ' ' . $branch . ' ' . $phone)); ?>"
                    >
                        <td class="font-semibold text-textSecondary py-5 px-6">
                            <?php echo e($serno); ?>

                        </td>
                        <td class="font-bold text-textPrimary py-5 px-6 text-lg">
                            <?php echo e($name); ?>

                        </td>
                        <td class="py-5 px-6">
                            <span class="bg-card px-3 py-1.5 rounded-xl border border-borderCol text-sm font-mono font-semibold">
                                <?php echo e($roll); ?>

                            </span>
                        </td>
                        <td class="text-textSecondary py-5 px-6">
                            <?php echo e($branch); ?>

                        </td>
                        <td class="text-textSecondary font-mono py-5 px-6">
                            <?php echo e($phone); ?>

                        </td>

                        <?php if($type === 'daily'): ?>
                            <td class="py-5 px-6 text-center">
                                <?php if(($data['daily_status'] ?? '') === 'Present'): ?>
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 px-3.5 py-1.5 rounded-xl font-semibold text-sm">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> ✅ Present
                                    </span>
                                <?php elseif(($data['daily_status'] ?? '') === 'Absent'): ?>
                                    <span class="inline-flex items-center gap-1.5 bg-red-500/10 border border-red-500/40 text-red-400 px-3.5 py-1.5 rounded-xl font-semibold text-sm">
                                        <span class="w-2 h-2 rounded-full bg-red-400"></span> ❌ Absent
                                    </span>
                                <?php else: ?>
                                    <span class="text-textMuted font-medium">-</span>
                                <?php endif; ?>
                            </td>
                        <?php else: ?>
                            <td class="py-5 px-6 text-center font-semibold text-textPrimary">
                                <?php echo e($data['total_days'] ?? 0); ?>

                            </td>
                            <td class="py-5 px-6 text-center text-emerald-400 font-bold">
                                <?php echo e($data['present'] ?? 0); ?>

                            </td>
                            <td class="py-5 px-6 text-center text-red-400 font-bold">
                                <?php echo e($data['absent'] ?? 0); ?>

                            </td>
                            <td class="py-5 px-6 text-center font-bold text-textPrimary">
                                <?php echo e($data['percentage'] ?? 0); ?>%
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr id="noSearchDataRow">
                        <td colspan="<?php echo e($type === 'daily' ? 6 : 9); ?>" class="p-16 text-center text-textMuted">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-5xl mb-3">🎓</span>
                                <p class="font-bold text-lg text-textSecondary">No Records Found</p>
                                <p class="text-sm text-textMuted mt-1">No attendance records found for this criteria.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</div>

<script>
function searchReport() {
    const input = document.getElementById('reportSearch')?.value.toLowerCase().trim();
    if (input === undefined) return;

    const rows = document.querySelectorAll('.report-row');
    let visibleCount = 0;

    rows.forEach(function(row) {
        const text = row.getAttribute('data-search') || '';
        if (text.includes(input)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    let noDataRow = document.getElementById('noSearchDataRow');
    const tbody = document.getElementById('reportTableBody');

    if (visibleCount === 0 && rows.length > 0) {
        if (!noDataRow) {
            noDataRow = document.createElement('tr');
            noDataRow.id = 'noSearchDataRow';
            noDataRow.innerHTML = `
                <td colspan="9" class="p-16 text-center text-textMuted">
                    <div class="flex flex-col items-center justify-center">
                        <span class="text-5xl mb-3">🔍</span>
                        <p class="font-bold text-lg text-textSecondary">No Matching Student Found</p>
                        <p class="text-sm text-textMuted mt-1">No student record matched your search query.</p>
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
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/student/report.blade.php ENDPATH**/ ?>