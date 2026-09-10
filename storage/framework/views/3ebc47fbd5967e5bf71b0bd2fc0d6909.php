

<?php $__env->startSection('title', 'Total - ' . $folder->name); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-[92rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    
    
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 bg-surface border border-borderCol p-8 sm:p-10 rounded-3xl shadow-darkCard">

            <div class="space-y-3">
                <!-- Back to Folder Overview Button -->
                <div>
                    <a
                        href="<?php echo e(route('admin.folders.show', $folder->id)); ?>"
                        class="btn-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md transition-all"
                    >
                        &larr; Back
                    </a>
                </div>

                <!-- Title -->
                <h1 class="text-4xl sm:text-5xl font-extrabold text-textPrimary tracking-tight break-words flex items-baseline gap-3 pt-1">
                    <span class="w-4 h-4 rounded-full bg-orangeAccent inline-block shrink-0"></span>
                    <span><?php echo e($folder->name); ?> <span class="text-textMuted font-medium text-2xl">/ Attendance Summary</span></span>
                </h1>

                <p class="text-textSecondary text-base sm:text-lg">
                    <?php echo e(now()->format('d M Y')); ?> • Real-time overview of student attendance statistics and progress.
                </p>
            </div>

        </div>
    </div>

    
    
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">

        <div class="bg-surface border border-borderCol p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl mb-2">
                👥
            </div>
            <div class="text-4xl font-extrabold text-textPrimary tracking-tight mt-1">
                <?php echo e($total); ?>

            </div>
            <div class="text-textSecondary text-sm font-semibold uppercase tracking-wider mt-2">
                Total Students
            </div>
        </div>

        <div class="bg-surface border border-emerald-500/40 p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl mb-2">
                ✅
            </div>
            <div class="text-4xl font-extrabold text-emerald-400 tracking-tight mt-1">
                <?php echo e($present); ?>

            </div>
            <div class="text-emerald-400 text-sm font-semibold uppercase tracking-wider mt-2">
                Present
            </div>
        </div>

        <div class="bg-surface border border-red-500/40 p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl mb-2">
                ❌
            </div>
            <div class="text-4xl font-extrabold text-red-400 tracking-tight mt-1">
                <?php echo e($absent); ?>

            </div>
            <div class="text-red-400 text-sm font-semibold uppercase tracking-wider mt-2">
                Absent
            </div>
        </div>

        <div class="bg-surface border border-borderCol p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl mb-2">
                📝
            </div>
            <div class="text-4xl font-extrabold text-textPrimary tracking-tight mt-1">
                <?php echo e($notMarked); ?>

            </div>
            <div class="text-textSecondary text-sm font-semibold uppercase tracking-wider mt-2">
                Not Marked
            </div>
        </div>

        <div class="bg-surface border border-blue-500/40 p-6 rounded-3xl shadow-darkCard flex flex-col justify-between text-center">
            <div class="text-4xl mb-2">
                📊
            </div>
            <div class="text-4xl font-extrabold text-blue-400 tracking-tight mt-1">
                <?php echo e($percentage); ?>%
            </div>
            <div class="text-blue-400 text-sm font-semibold uppercase tracking-wider mt-2">
                Present %
            </div>
        </div>

    </div>

    
    
    
    <?php if($total > 0): ?>
        <div class="bg-surface border border-borderCol p-8 rounded-3xl shadow-darkCard mb-10">
            <h2 class="text-xl font-bold text-textPrimary mb-4">
                Today's Attendance Progress
            </h2>

            <div class="w-full h-5 bg-[#172033] rounded-full overflow-hidden p-0.5 border border-borderCol shadow-inner">
                <div
                    class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full transition-all duration-500"
                    style="width: <?php echo e(min(100, $percentage)); ?>%"
                ></div>
            </div>

            <div class="text-center text-textSecondary font-semibold mt-3 text-base">
                <?php echo e($present); ?> Present out of <?php echo e($total); ?> students
            </div>
        </div>
    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/total.blade.php ENDPATH**/ ?>