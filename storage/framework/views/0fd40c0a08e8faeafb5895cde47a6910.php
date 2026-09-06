
<?php $__env->startSection('title', 'Student - Folders'); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('landing')); ?>" class="text-accent hover:underline text-sm">← Back to Home</a>
    <h1 class="text-3xl font-bold mt-2">Select Your Folder</h1>
</div>

<div class="mb-8">
    <h2 class="text-2xl font-semibold mb-4">Class Folders</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('student.report.show', ['id' => $folder->id, 'type' => 'daily'])); ?>" class="bg-surface border border-border rounded-xl p-6 card-hover transition-all block">
            <h3 class="font-semibold text-lg"><?php echo e($folder->name); ?></h3>
            <p class="text-secondary text-sm mt-2">Click to view your attendance →</p>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div>
    <h2 class="text-2xl font-semibold mb-4">Subject Folders</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('student.report.show', ['id' => $subject->id, 'type' => 'daily'])); ?>" class="bg-surface border border-border rounded-xl p-6 card-hover transition-all block">
            <h3 class="font-semibold text-lg"><?php echo e($subject->name); ?></h3>
            <p class="text-secondary text-sm mt-2">Click to view your attendance →</p>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/student/folders.blade.php ENDPATH**/ ?>