

<?php $__env->startSection('title', $folder->name); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl shadow-darkCard relative">
            
            
            <div class="shrink-0 z-10">
                <a
                    href="<?php echo e(route('admin.dashboard')); ?>"
                    class="btn-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all"
                >
                    &larr; Back to Dashboard
                </a>
            </div>

            
            <div class="text-center absolute inset-x-0 mx-auto hidden md:block pointer-events-none">
                <h1 class="text-4xl font-extrabold text-textPrimary tracking-tight">
                    <?php echo e($folder->name); ?>

                </h1>
                <p class="text-textSecondary text-sm mt-1">
                    Attendance Management Folder
                </p>
            </div>

            
            <div class="text-left md:hidden mt-2">
                <h1 class="text-3xl font-extrabold text-textPrimary tracking-tight">
                    <?php echo e($folder->name); ?>

                </h1>
                <p class="text-textSecondary text-sm mt-1">
                    Attendance Management Folder
                </p>
            </div>

            <div></div> 
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-success/10 border border-success text-success px-4 py-3 rounded-xl mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-danger/10 border border-danger text-danger px-4 py-3 rounded-xl mb-6">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        
        <a
            href="<?php echo e(route('admin.students.index', $folder->id)); ?>"
            class="bg-surface border border-borderCol hover:border-blue-500 rounded-3xl p-8 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between"
        >
            <div>
                <div class="text-4xl mb-4">👨‍🎓</div>
                <h2 class="text-xl font-bold text-textPrimary">Students</h2>
                <p class="text-textSecondary text-sm mt-2">Add, edit, search and manage students.</p>
            </div>
            <div class="mt-6 text-blue-400 font-semibold text-sm">
                <?php echo e($folder->students_count ?? $folder->students->count() ?? 0); ?> Students →
            </div>
        </a>

        
        <a
            href="<?php echo e(route('admin.report.show', ['id' => $folder->id, 'type' => 'daily'])); ?>"
            class="bg-surface border border-borderCol hover:border-blue-500 rounded-3xl p-8 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between"
        >
            <div>
                <div class="text-4xl mb-4">📊</div>
                <h2 class="text-xl font-bold text-textPrimary">Report</h2>
                <p class="text-textSecondary text-sm mt-2">Daily, monthly and custom attendance reports.</p>
            </div>
            <div class="mt-6 text-blue-400 font-semibold text-sm">
                Open Report →
            </div>
        </a>

        
        <a
            href="<?php echo e(route('admin.report.total', $folder->id)); ?>"
            class="bg-surface border border-borderCol hover:border-blue-500 rounded-3xl p-8 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between"
        >
            <div>
                <div class="text-4xl mb-4">📈</div>
                <h2 class="text-xl font-bold text-textPrimary">Total</h2>
                <p class="text-textSecondary text-sm mt-2">Today's attendance summary and percentage.</p>
            </div>
            <div class="mt-6 text-blue-400 font-semibold text-sm">
                View Total →
            </div>
        </a>

        
        <a
            href="<?php echo e(route('admin.attendance.show', $folder->id)); ?>"
            class="bg-surface border border-borderCol hover:border-emerald-500 rounded-3xl p-8 transition-all duration-300 shadow-darkCard hover:-translate-y-1 flex flex-col justify-between"
        >
            <div>
                <div class="text-4xl mb-4">✅</div>
                <h2 class="text-xl font-bold text-textPrimary">Mark Attendance</h2>
                <p class="text-textSecondary text-sm mt-2">Mark Present or Absent for students.</p>
            </div>
            <div class="mt-6 text-emerald-400 font-semibold text-sm">
                Open Attendance →
            </div>
        </a>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/show.blade.php ENDPATH**/ ?>