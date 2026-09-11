

<?php $__env->startSection('title', 'Student - Folders'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl shadow-darkCard relative">
            
            <div class="shrink-0 z-10">
                <a href="<?php echo e(route('landing')); ?>" class="btn-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all">
                    &larr; Back to Home
                </a>
            </div>

            <div class="text-center absolute inset-x-0 mx-auto hidden md:block pointer-events-none">
                <h1 class="text-4xl font-extrabold text-textPrimary tracking-tight">
                    Select Your Folder
                </h1>
                <p class="text-textSecondary text-sm mt-1">
                    Choose a class or subject folder to view your attendance reports.
                </p>
            </div>

            <div class="text-left md:hidden mt-2">
                <h1 class="text-3xl font-extrabold text-textPrimary tracking-tight">
                    Select Your Folder
                </h1>
                <p class="text-textSecondary text-sm mt-1">
                    Choose a class or subject folder to view your attendance reports.
                </p>
            </div>

            <div></div>
        </div>
    </div>

    
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-textPrimary flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-orangeAccent"></span>
                Class Folders
            </h2>
            <span class="text-xs bg-slate-900 border border-orangeAccent/40 text-orangeLight px-3 py-1.5 rounded-xl">
                <?php echo e($folders->count()); ?> Folders
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('student.report.show', ['id' => $folder->id, 'type' => 'daily'])); ?>" class="bg-surface border border-borderCol hover:border-orangeAccent rounded-3xl p-6 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between group block">
                    <div>
                        <div class="flex justify-between items-start mb-4 gap-3">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-12 h-12 rounded-2xl border border-orangeAccent/40 bg-orangeAccent/10 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                                    📁
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <?php if(isset($folder->today_attendance_marked) && $folder->today_attendance_marked): ?>
                                            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-md flex-shrink-0" title="Today's attendance marked"></span>
                                        <?php else: ?>
                                            <span class="w-3 h-3 rounded-full bg-red-500 shadow-md flex-shrink-0" title="Today's attendance pending"></span>
                                        <?php endif; ?>
                                        <h3 class="font-bold text-lg text-textPrimary group-hover:text-orangeLight transition-colors truncate">
                                            <?php echo e($folder->name); ?>

                                        </h3>
                                    </div>
                                    <p class="text-xs mt-1">
                                        <?php if(isset($folder->today_attendance_marked) && $folder->today_attendance_marked): ?>
                                            <span class="text-emerald-400 font-medium">Today's Attendance Marked</span>
                                        <?php else: ?>
                                            <span class="text-red-400 font-medium">Attendance Not Marked</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <span class="bg-slate-900 text-textSecondary text-xs px-3 py-1 rounded-xl border border-slate-700 whitespace-nowrap">
                                <?php echo e($folder->students_count ?? $folder->students->count()); ?> Students
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-borderCol text-orangeLight font-semibold text-sm flex items-center justify-between">
                        <span>Click to view attendance</span>
                        <span>→</span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full bg-surface border border-dashed border-orangeAccent/40 rounded-3xl p-10 text-center">
                    <p class="text-sm text-textMuted">No class folders available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-textPrimary flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-orangeAccent"></span>
                Subject Folders
            </h2>
            <span class="text-xs bg-slate-900 border border-orangeAccent/40 text-orangeLight px-3 py-1.5 rounded-xl">
                <?php echo e($subjects->count()); ?> Subjects
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('student.report.show', ['id' => $subject->id, 'type' => 'daily'])); ?>" class="bg-surface border border-borderCol hover:border-orangeAccent rounded-3xl p-6 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between group block">
                    <div>
                        <div class="flex justify-between items-start mb-4 gap-3">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-12 h-12 rounded-2xl border border-orangeAccent/40 bg-orangeAccent/10 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                                    📚
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <?php if(isset($subject->today_attendance_marked) && $subject->today_attendance_marked): ?>
                                            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-md flex-shrink-0" title="Today's attendance marked"></span>
                                        <?php else: ?>
                                            <span class="w-3 h-3 rounded-full bg-red-500 shadow-md flex-shrink-0" title="Today's attendance pending"></span>
                                        <?php endif; ?>
                                        <h3 class="font-bold text-lg text-textPrimary group-hover:text-orangeLight transition-colors truncate">
                                            <?php echo e($subject->name); ?>

                                        </h3>
                                    </div>
                                    <p class="text-xs mt-1">
                                        <?php if(isset($subject->today_attendance_marked) && $subject->today_attendance_marked): ?>
                                            <span class="text-emerald-400 font-medium">Today's Attendance Marked</span>
                                        <?php else: ?>
                                            <span class="text-red-400 font-medium">Attendance Not Marked</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <span class="bg-slate-900 text-textSecondary text-xs px-3 py-1 rounded-xl border border-slate-700 whitespace-nowrap">
                                <?php echo e($subject->students_count ?? $subject->students->count()); ?> Students
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-borderCol text-orangeLight font-semibold text-sm flex items-center justify-between">
                        <span>Click to view attendance</span>
                        <span>→</span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full bg-surface border border-dashed border-orangeAccent/40 rounded-3xl p-10 text-center">
                    <p class="text-sm text-textMuted">No subject folders available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/student/folders.blade.php ENDPATH**/ ?>