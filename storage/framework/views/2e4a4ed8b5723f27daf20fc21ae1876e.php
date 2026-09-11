

<?php $__env->startSection('title', 'Attendance Summary - ' . $subject->name); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    
    
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl shadow-darkCard">
            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                <a
                    href="<?php echo e(route('admin.subjects.show', $subject->id)); ?>"
                    class="btn-danger inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all shrink-0 w-fit"
                >
                    &larr; Back
                </a>

                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-textPrimary tracking-tight break-words flex items-center gap-3">
                        <span class="w-3.5 h-3.5 rounded-full bg-orange-500 inline-block"></span>
                        <span><?php echo e($subject->name); ?> <span class="text-textMuted font-medium text-xl sm:text-2xl">/ Attendance Summary</span></span>
                    </h1>
                    <p class="text-textSecondary text-sm mt-2">
                        Real-time overview of student attendance statistics and progress for the selected date.
                    </p>
                </div>
            </div>
        </div>
    </div>

    
    
    
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl mb-8 shadow-darkCard">
        <form method="GET" action="<?php echo e(route('admin.subjects.total', $subject->id)); ?>" id="dateForm" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div>
                    <label class="block text-xs font-semibold text-textSecondary uppercase tracking-wider mb-2">📅 Select Date</label>
                    <input
                        type="date"
                        name="date"
                        value="<?php echo e($selectedDate); ?>"
                        max="<?php echo e($today); ?>"
                        class="input-dark rounded-xl px-5 py-3 text-sm font-medium [color-scheme:dark]"
                        onchange="this.form.submit()"
                    >
                </div>
            </div>
            <div class="text-sm text-textSecondary">
                Viewing date: <strong class="text-textPrimary"><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d M Y')); ?></strong>
            </div>
        </form>
    </div>

    
    
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
        <div class="bg-surface border border-borderCol p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl font-extrabold text-textPrimary tracking-tight mt-1"><?php echo e($totalStudents); ?></div>
            <div class="text-textSecondary text-xs font-semibold uppercase tracking-wider mt-2">Total Students</div>
        </div>

        <div class="bg-surface border border-emerald-500/40 p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl font-extrabold text-emerald-400 tracking-tight mt-1"><?php echo e($present); ?></div>
            <div class="text-emerald-400 text-xs font-semibold uppercase tracking-wider mt-2">Present</div>
        </div>

        <div class="bg-surface border border-red-500/40 p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl font-extrabold text-red-400 tracking-tight mt-1"><?php echo e($absent); ?></div>
            <div class="text-red-400 text-xs font-semibold uppercase tracking-wider mt-2">Absent</div>
        </div>

        <div class="bg-surface border border-borderCol p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl font-extrabold text-textPrimary tracking-tight mt-1"><?php echo e($notMarked); ?></div>
            <div class="text-textSecondary text-xs font-semibold uppercase tracking-wider mt-2">Not Marked</div>
        </div>

        <div class="bg-surface border border-blue-500/40 p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl font-extrabold text-blue-400 tracking-tight mt-1"><?php echo e($percentage); ?>%</div>
            <div class="text-blue-400 text-xs font-semibold uppercase tracking-wider mt-2">Present %</div>
        </div>
    </div>

    
    
    
    <div class="bg-surface border border-borderCol p-6 sm:p-8 rounded-3xl shadow-darkCard mb-10">
        <h3 class="text-lg font-bold text-textPrimary mb-3">Attendance Progress</h3>
        <div class="w-full bg-card rounded-full h-4 overflow-hidden border border-borderCol">
            <div class="bg-emerald-500 h-full transition-all duration-500" style="width: <?php echo e($percentage); ?>%"></div>
        </div>
        <p class="text-textSecondary text-sm mt-3 text-center">
            <?php echo e($present); ?> Present out of <?php echo e($totalStudents); ?> students on this date.
        </p>
    </div>

    
    
    
    <div class="bg-surface border border-borderCol rounded-3xl overflow-hidden shadow-darkCard mb-10">
        <div class="p-8 border-b border-borderCol flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-textPrimary">
                    Student Status on <?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d M Y')); ?>

                </h2>
                <p class="text-textSecondary text-sm mt-1">Individual attendance record for the chosen date.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table-dark text-left text-base">
                <thead>
                    <tr class="text-sm uppercase tracking-wider text-textSecondary">
                        <th class="w-20 py-5 px-6">S.No</th>
                        <th class="py-5 px-6">Name</th>
                        <th class="py-5 px-6">Roll Number</th>
                        <th class="py-5 px-6">Branch</th>
                        <th class="py-5 px-6">Mobile Number</th>
                        <th class="py-5 px-6 text-center">Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $studentsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-b border-borderCol hover:bg-cardHover/50 transition-colors">
                        <td class="font-semibold text-textSecondary py-5 px-6">
                            <?php echo e($data['serno']); ?>

                        </td>

                        <td class="font-bold text-textPrimary py-5 px-6 text-lg">
                            <?php echo e($data['name']); ?>

                        </td>

                        <td class="py-5 px-6">
                            <span class="bg-card px-3 py-1.5 rounded-xl border border-borderCol text-sm font-mono font-semibold">
                                <?php echo e($data['roll']); ?>

                            </span>
                        </td>

                        <td class="text-textSecondary py-5 px-6">
                            <?php echo e($data['branch']); ?>

                        </td>

                        <td class="text-textSecondary font-mono py-5 px-6">
                            <?php echo e($data['phone']); ?>

                        </td>

                        <td class="py-5 px-6 text-center">
                            <?php if($data['daily_status'] === 'Present'): ?>
                                <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 px-3.5 py-1.5 rounded-xl font-semibold text-sm">
                                    ✅ Present
                                </span>
                            <?php elseif($data['daily_status'] === 'Absent'): ?>
                                <span class="inline-flex items-center gap-1.5 bg-red-500/10 border border-red-500/40 text-red-400 px-3.5 py-1.5 rounded-xl font-semibold text-sm">
                                    ❌ Absent
                                </span>
                            <?php else: ?>
                                <span class="text-textMuted font-medium">- Not Marked -</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="p-20 text-center text-textMuted">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-6xl mb-4">🎓</span>
                                <p class="font-bold text-xl text-textSecondary">No Students Found</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/subjects/total.blade.php ENDPATH**/ ?>