

<?php $__env->startSection('title', 'My Attendance Report'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-6">
    <a href="<?php echo e(route('student.folders')); ?>"
       class="text-accent hover:underline text-sm">
        ← Back to Folders
    </a>

    <h1 class="text-3xl font-bold mt-2">
        <?php echo e($folder->name); ?> - My Attendance
    </h1>
</div>


<!-- Search -->
<div class="bg-surface border border-border rounded-xl p-4 mb-6">

    <form method="GET"
          action="<?php echo e(route('student.report.show', ['id' => $folder->id])); ?>"
          class="flex gap-4">

        <input type="hidden" name="type" value="<?php echo e($type); ?>">
        <input type="hidden" name="date" value="<?php echo e($date); ?>">
        <input type="hidden" name="month" value="<?php echo e($month); ?>">
        <input type="hidden" name="start" value="<?php echo e($start); ?>">
        <input type="hidden" name="end" value="<?php echo e($end); ?>">

        <input
            type="text"
            name="search"
            placeholder="Search your name, roll, branch..."
            value="<?php echo e(request('search')); ?>"
            class="flex-1 border border-border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent outline-none"
        >

        <button
            type="submit"
            class="bg-accent hover:bg-accentHover text-white px-6 py-2 rounded-lg">
            Search
        </button>

    </form>

</div>


<!-- Filter Tabs -->
<div class="bg-surface border border-border rounded-xl p-4 mb-6 flex gap-4 flex-wrap">

    <!-- Daily -->
    <a
        href="<?php echo e(route('student.report.show', [
            'id' => $folder->id,
            'type' => 'daily',
            'date' => \Carbon\Carbon::today()->toDateString()
        ])); ?>"
        class="px-4 py-2 rounded-lg
        <?php echo e($type === 'daily'
            ? 'bg-accent text-white'
            : 'bg-bg text-secondary hover:bg-bg/70'); ?>">
        Daily
    </a>


    <!-- Monthly -->
    <a
        href="<?php echo e(route('student.report.show', [
            'id' => $folder->id,
            'type' => 'monthly',
            'month' => \Carbon\Carbon::today()->format('Y-m')
        ])); ?>"
        class="px-4 py-2 rounded-lg
        <?php echo e($type === 'monthly'
            ? 'bg-accent text-white'
            : 'bg-bg text-secondary hover:bg-bg/70'); ?>">
        Monthly
    </a>


    <!-- Custom -->
    <a
        href="<?php echo e(route('student.report.show', [
            'id' => $folder->id,
            'type' => 'custom',
            'start' => \Carbon\Carbon::today()->startOfMonth()->toDateString(),
            'end' => \Carbon\Carbon::today()->endOfMonth()->toDateString()
        ])); ?>"
        class="px-4 py-2 rounded-lg
        <?php echo e($type === 'custom'
            ? 'bg-accent text-white'
            : 'bg-bg text-secondary hover:bg-bg/70'); ?>">
        Custom
    </a>


    <!-- PDF -->
    <a
        href="<?php echo e(route('student.report.pdf', [
            'id' => $folder->id,
            'type' => $type,
            'date' => $date,
            'month' => $month,
            'start' => $start,
            'end' => $end
        ])); ?>"
        class="ml-auto px-4 py-2 bg-danger text-white rounded-lg hover:bg-danger/90">
        📄 Download PDF
    </a>

</div>


<!-- No Records -->
<?php if(count($studentsData) === 0): ?>

    <div class="bg-surface border border-border rounded-xl p-10 text-center">

        <div class="text-5xl mb-4">
            📊
        </div>

        <h2 class="text-xl font-semibold mb-2">
            No Attendance Records
        </h2>

        <p class="text-secondary">
            No records found. Try a different search or date range.
        </p>

    </div>

<?php else: ?>


    <!-- Report Table -->
    <div class="bg-surface border border-border rounded-xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-bg border-b border-border">

                    <tr>

                        <th class="text-left p-4 text-secondary font-medium">
                            Name
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Roll
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Branch
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            Present
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            Absent
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            This Period %
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            Overall Avg %
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php $__currentLoopData = $studentsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <tr class="border-b border-border">

                            <td class="p-4">
                                <?php echo e($data['student']->name); ?>

                            </td>

                            <td class="p-4">
                                <?php echo e($data['student']->roll_number); ?>

                            </td>

                            <td class="p-4">
                                <?php echo e($data['student']->branch); ?>

                            </td>

                            <td class="p-4 text-center text-success">
                                <?php echo e($data['present']); ?>

                            </td>

                            <td class="p-4 text-center text-danger">
                                <?php echo e($data['absent']); ?>

                            </td>

                            <td class="p-4 text-center font-semibold">
                                <?php echo e($data['percentage']); ?>%
                            </td>

                            <td class="p-4 text-center font-semibold text-accent">
                                <?php echo e($data['avg_percentage']); ?>%
                            </td>

                        </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>

            </table>

        </div>

    </div>

<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/student/report.blade.php ENDPATH**/ ?>