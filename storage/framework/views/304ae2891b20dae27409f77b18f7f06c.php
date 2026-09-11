<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Attendance Report - <?php echo e($folder->name); ?></title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 12px; }
        th { background-color: #f4f5f7; color: #2E3440; }
        h1 { color: #2E3440; font-size: 22px; margin-bottom: 5px; }
        p { font-size: 13px; color: #666; }
    </style>
</head>
<body>
    <h1>My Attendance Report - <?php echo e($folder->name); ?></h1>
    <p>Type: <?php echo e(ucfirst($type)); ?> | 
    <?php if($type === 'daily'): ?> Date: <?php echo e($date); ?>

    <?php elseif($type === 'monthly'): ?> Month: <?php echo e($month); ?>

    <?php else: ?> From: <?php echo e($start); ?> To: <?php echo e($end); ?>

    <?php endif; ?></p>
    
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Roll</th>
                <th>Branch</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Attendance %</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $studentsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($data['student']->name ?? $data['name'] ?? ''); ?></td>
                <td><?php echo e($data['student']->roll_number ?? $data['roll'] ?? ''); ?></td>
                <td><?php echo e($data['student']->branch ?? $data['branch'] ?? ''); ?></td>
                <td><?php echo e($data['present']); ?></td>
                <td><?php echo e($data['absent']); ?></td>
                <td><?php echo e($data['percentage']); ?>%</td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/student/pdf.blade.php ENDPATH**/ ?>