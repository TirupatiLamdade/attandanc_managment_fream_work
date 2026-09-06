

<?php $__env->startSection('title', 'Mark Attendance - ' . $subject->name); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-6">

    <a
        href="<?php echo e(route('admin.subjects.show', $subject->id)); ?>"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Subject
    </a>

    <h1 class="text-3xl font-bold mt-2">
        <?php echo e($subject->name); ?>

    </h1>

    <p class="text-secondary mt-1">
        Mark Subject-wise Attendance
    </p>

</div>


<?php if(session('success')): ?>

<div class="bg-success/10 border border-success text-success px-4 py-3 rounded mb-4">
    <?php echo e(session('success')); ?>

</div>

<?php endif; ?>


<?php if($errors->any()): ?>

<div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-4">

    <ul class="list-disc ml-5">

        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <li><?php echo e($error); ?></li>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </ul>

</div>

<?php endif; ?>


<div class="bg-surface border border-border rounded-xl overflow-hidden glow-accent">

    <div class="p-5 border-b border-border flex justify-between items-center">

        <div>

            <h2 class="text-xl font-bold">
                <?php echo e($subject->name); ?>

            </h2>

            <p class="text-secondary text-sm mt-1">
                Date: <?php echo e(\Carbon\Carbon::parse($today)->format('d M Y')); ?>

            </p>

        </div>

        <div class="text-sm text-secondary">

            Total Students:
            <span class="font-bold text-white">
                <?php echo e($subject->students->count()); ?>

            </span>

        </div>

    </div>


    <?php if($subject->students->count() > 0): ?>

    <form
        method="POST"
        action="<?php echo e(route('admin.subjects.attendance.submit', $subject->id)); ?>"
    >

        <?php echo csrf_field(); ?>


        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-bg border-b border-border">

                    <tr>

                        <th class="text-left p-4 text-secondary font-medium">
                            Serno
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Student Name
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Roll Number
                        </th>

                        <th class="text-left p-4 text-secondary font-medium">
                            Branch
                        </th>

                        <th class="text-center p-4 text-secondary font-medium">
                            Attendance
                        </th>

                    </tr>

                </thead>


                <tbody>

                <?php $__currentLoopData = $subject->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        $currentStatus = $attendances[$student->id] ?? null;
                    ?>

                    <tr class="border-b border-border hover:bg-bg/50">

                        <td class="p-4">
                            <?php echo e($student->serno); ?>

                        </td>

                        <td class="p-4 font-medium">
                            <?php echo e($student->name); ?>

                        </td>

                        <td class="p-4">
                            <?php echo e($student->roll_number); ?>

                        </td>

                        <td class="p-4 text-secondary">
                            <?php echo e($student->branch); ?>

                        </td>

                        <td class="p-4">

                            <div class="flex justify-center gap-4">

                                <label class="flex items-center gap-2 cursor-pointer">

                                    <input
                                        type="radio"
                                        name="student_<?php echo e($student->id); ?>"
                                        value="present"
                                        <?php echo e($currentStatus === 'present' ? 'checked' : ''); ?>

                                    >

                                    <span class="text-green-500 font-medium">
                                        Present
                                    </span>

                                </label>


                                <label class="flex items-center gap-2 cursor-pointer">

                                    <input
                                        type="radio"
                                        name="student_<?php echo e($student->id); ?>"
                                        value="absent"
                                        <?php echo e($currentStatus === 'absent' ? 'checked' : ''); ?>

                                    >

                                    <span class="text-red-500 font-medium">
                                        Absent
                                    </span>

                                </label>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </tbody>

            </table>

        </div>


        <div class="p-5 border-t border-border flex justify-end">

            <button
                type="submit"
                class="bg-accent hover:bg-accentHover text-white px-6 py-3 rounded-lg font-semibold"
            >
                Save Attendance
            </button>

        </div>

    </form>

    <?php else: ?>

    <div class="p-10 text-center">

        <div class="text-5xl mb-4">
            👨‍🎓
        </div>

        <h3 class="text-xl font-bold">
            No Students Added
        </h3>

        <p class="text-secondary mt-2">
            First select students from folders.
        </p>

        <a
            href="<?php echo e(route('admin.subjects.show', $subject->id)); ?>"
            class="inline-block mt-5 bg-accent text-white px-5 py-3 rounded-lg"
        >
            Select Students
        </a>

    </div>

    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/subjects/attendance.blade.php ENDPATH**/ ?>