
<?php $__env->startSection('title', 'Mark Attendance - ' . $folder->name); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('admin.folders.show', $folder->id)); ?>" class="text-accent hover:underline text-sm">← Back to Folder</a>
    <h1 class="text-3xl font-bold mt-2">Mark Attendance - <?php echo e($folder->name); ?></h1>
    <p class="text-secondary mt-1">Date: <?php echo e(\Carbon\Carbon::today()->format('d M, Y')); ?></p>
</div>

<?php if(session('success')): ?>
    <div class="bg-success/10 border border-success text-success px-4 py-2 rounded mb-4"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('admin.attendance.submit', $folder->id)); ?>" class="bg-surface border border-border rounded-xl p-6 glow-accent">
    <?php echo csrf_field(); ?>
    <table class="w-full mb-6">
        <thead class="bg-bg border-b border-border">
            <tr>
                <th class="text-left p-4 text-secondary font-medium">Serno</th>
                <th class="text-left p-4 text-secondary font-medium">Name</th>
                <th class="text-left p-4 text-secondary font-medium">Roll</th>
                <th class="text-center p-4 text-secondary font-medium">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $folder->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b border-border">
                <td class="p-4"><?php echo e($student->serno); ?></td>
                <td class="p-4"><?php echo e($student->name); ?></td>
                <td class="p-4"><?php echo e($student->roll_number); ?></td>
                <td class="p-4 text-center">
                    <label class="inline-flex items-center mr-6">
                        <input type="radio" name="student_<?php echo e($student->id); ?>" value="present" 
                               <?php echo e(isset($attendances[$student->id]) && $attendances[$student->id] === 'present' ? 'checked' : ''); ?>

                               class="text-success focus:ring-success mr-2">
                        <span class="text-success">Present</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="student_<?php echo e($student->id); ?>" value="absent" 
                               <?php echo e(isset($attendances[$student->id]) && $attendances[$student->id] === 'absent' ? 'checked' : ''); ?>

                               class="text-danger focus:ring-danger mr-2">
                        <span class="text-danger">Absent</span>
                    </label>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <button type="submit" class="bg-accent hover:bg-accentHover text-white px-8 py-3 rounded-lg glow-accent font-semibold">Submit Attendance</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/attendance.blade.php ENDPATH**/ ?>