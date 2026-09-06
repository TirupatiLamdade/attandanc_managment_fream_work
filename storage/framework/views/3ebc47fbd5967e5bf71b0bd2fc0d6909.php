
<?php $__env->startSection('title', 'Total - ' . $folder->name); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('admin.folders.show', $folder->id)); ?>" class="text-accent hover:underline text-sm">← Back to Folder</a>
    <h1 class="text-3xl font-bold mt-2">Today's Summary - <?php echo e($folder->name); ?></h1>
    <p class="text-secondary mt-1"><?php echo e(\Carbon\Carbon::today()->format('d M, Y')); ?></p>
</div>

<div class="grid md:grid-cols-3 gap-6">
    <div class="bg-surface border border-border rounded-xl p-8 text-center glow-accent">
        <div class="text-4xl mb-2">👥</div>
        <div class="text-3xl font-bold text-primary"><?php echo e($total); ?></div>
        <div class="text-secondary mt-1">Total Students</div>
    </div>
    <div class="bg-surface border border-success rounded-xl p-8 text-center glow-accent">
        <div class="text-4xl mb-2">✅</div>
        <div class="text-3xl font-bold text-success"><?php echo e($present); ?></div>
        <div class="text-secondary mt-1">Present</div>
    </div>
    <div class="bg-surface border border-danger rounded-xl p-8 text-center glow-accent">
        <div class="text-4xl mb-2">❌</div>
        <div class="text-3xl font-bold text-danger"><?php echo e($absent); ?></div>
        <div class="text-secondary mt-1">Absent</div>
    </div>
</div>

<?php if($total > 0): ?>
<div class="mt-8 bg-surface border border-border rounded-xl p-6">
    <h3 class="font-semibold mb-4">Present Percentage</h3>
    <div class="w-full bg-bg rounded-full h-4">
        <div class="bg-success h-4 rounded-full" style="width: <?php echo e(($present / $total) * 100); ?>%"></div>
    </div>
    <p class="text-center mt-2 text-secondary"><?php echo e(round(($present / $total) * 100, 2)); ?>% Present</p>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/total.blade.php ENDPATH**/ ?>