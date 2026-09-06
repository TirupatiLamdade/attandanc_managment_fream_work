

<?php $__env->startSection('title', $folder->name); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-6">

    <a
        href="<?php echo e(route('admin.dashboard')); ?>"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Dashboard
    </a>

    <h1 class="text-3xl font-bold mt-2">
        <?php echo e($folder->name); ?>

    </h1>

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


<!-- ============================================== -->
<!-- FOLDER ACTION CARDS -->
<!-- ============================================== -->

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">


    <!-- STUDENTS -->

    <a
        href="<?php echo e(route('admin.students.index', $folder->id)); ?>"
        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent min-h-[170px] flex flex-col items-center justify-center"
    >

        <div class="text-4xl mb-4">
            👨‍🎓
        </div>

        <h3 class="font-semibold text-lg">
            Students
        </h3>

        <p class="text-secondary text-sm mt-2">
            View & Manage Students
        </p>

    </a>


    <!-- REPORT -->

    <a
        href="<?php echo e(route('admin.report.show', [
            'id' => $folder->id,
            'type' => 'daily'
        ])); ?>"
        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent min-h-[170px] flex flex-col items-center justify-center"
    >

        <div class="text-4xl mb-4">
            📊
        </div>

        <h3 class="font-semibold text-lg">
            Report
        </h3>

        <p class="text-secondary text-sm mt-2">
            Daily / Monthly / Custom
        </p>

    </a>


    <!-- TOTAL -->

    <a
        href="<?php echo e(route('admin.report.total', $folder->id)); ?>"
        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent min-h-[170px] flex flex-col items-center justify-center"
    >

        <div class="text-4xl mb-4">
            📈
        </div>

        <h3 class="font-semibold text-lg">
            Total
        </h3>

        <p class="text-secondary text-sm mt-2">
            Current day stats
        </p>

    </a>


    <!-- MARK ATTENDANCE -->

    <a
        href="<?php echo e(route('admin.attendance.show', $folder->id)); ?>"
        class="bg-surface border border-border rounded-xl p-6 text-center card-hover transition-all glow-accent min-h-[170px] flex flex-col items-center justify-center"
    >

        <div class="text-4xl mb-4">
            ✅
        </div>

        <h3 class="font-semibold text-lg">
            Mark Attendance
        </h3>

        <p class="text-secondary text-sm mt-2">
            Mark today's attendance
        </p>

    </a>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/show.blade.php ENDPATH**/ ?>