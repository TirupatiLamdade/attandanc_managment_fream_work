

<?php $__env->startSection('title', 'Report - ' . $folder->name); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <a href="<?php echo e(route('admin.folders.show', $folder->id)); ?>" class="text-accent hover:underline text-sm">← Back to Folder</a>
    <h1 class="text-3xl font-bold mt-2">Attendance Report - <?php echo e($folder->name); ?></h1>
</div>

<!-- Filter Tabs -->
<div class="bg-surface border border-border rounded-xl p-4 mb-6 flex gap-4 flex-wrap">
    <a href="<?php echo e(route('admin.report.show', ['id' => $folder->id, 'type' => 'daily', 'date' => \Carbon\Carbon::today()->toDateString()])); ?>" 
       class="px-4 py-2 rounded-lg <?php echo e($type === 'daily' ? 'bg-accent text-white' : 'bg-bg text-secondary hover:bg-bg/70'); ?>">Daily</a>
    <a href="<?php echo e(route('admin.report.show', ['id' => $folder->id, 'type' => 'monthly', 'month' => \Carbon\Carbon::today()->format('Y-m')])); ?>" 
       class="px-4 py-2 rounded-lg <?php echo e($type === 'monthly' ? 'bg-accent text-white' : 'bg-bg text-secondary hover:bg-bg/70'); ?>">Monthly</a>
    <a href="<?php echo e(route('admin.report.show', ['id' => $folder->id, 'type' => 'custom', 'start' => \Carbon\Carbon::today()->startOfMonth()->toDateString(), 'end' => \Carbon\Carbon::today()->endOfMonth()->toDateString()])); ?>" 
       class="px-4 py-2 rounded-lg <?php echo e($type === 'custom' ? 'bg-accent text-white' : 'bg-bg text-secondary hover:bg-bg/70'); ?>">Custom</a>
    
    <a href="<?php echo e(route('admin.report.pdf', ['id' => $folder->id, 'type' => $type, 'date' => $date, 'month' => $month, 'start' => $start, 'end' => $end])); ?>" 
       class="ml-auto px-4 py-2 bg-danger text-white rounded-lg hover:bg-danger/90">📄 Download PDF</a>
</div>

<!-- Report Table -->
<div class="bg-surface border border-border rounded-xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-bg border-b border-border">
            <tr>
                <th class="text-left p-4 text-secondary font-medium">Serno</th>
                <th class="text-left p-4 text-secondary font-medium">Name</th>
                <th class="text-left p-4 text-secondary font-medium">Roll</th>
                <th class="text-left p-4 text-secondary font-medium">Branch</th>
                <th class="text-center p-4 text-secondary font-medium">Present</th>
                <th class="text-center p-4 text-secondary font-medium">Absent</th>
                <th class="text-center p-4 text-secondary font-medium">Attendance %</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $studentsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b border-border">
                <td class="p-4"><?php echo e($data['serno']); ?></td>
                <td class="p-4"><?php echo e($data['name']); ?></td>
                <td class="p-4"><?php echo e($data['roll']); ?></td>
                <td class="p-4"><?php echo e($data['branch']); ?></td>
                <td class="p-4 text-center text-success"><?php echo e($data['present']); ?></td>
                <td class="p-4 text-center text-danger"><?php echo e($data['absent']); ?></td>
                <td class="p-4 text-center font-semibold"><?php echo e($data['percentage']); ?>%</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/report.blade.php ENDPATH**/ ?>