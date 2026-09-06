

<?php $__env->startSection('title', 'Report - ' . $folder->name); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-6">

    <a
        href="<?php echo e(route('admin.folders.show', $folder->id)); ?>"
        class="text-accent hover:underline text-sm"
    >
        ← Back to Folder
    </a>

    <h1 class="text-3xl font-bold mt-3">
        Attendance Report
    </h1>

    <p class="text-secondary">
        <?php echo e($folder->name); ?>

    </p>

</div>


<?php if(session('error')): ?>

<div class="bg-danger/10 border border-danger text-danger px-4 py-3 rounded-xl mb-5">
    <?php echo e(session('error')); ?>

</div>

<?php endif; ?>




<div class="bg-surface border border-border rounded-xl p-5 mb-6">

    <div class="flex gap-3 flex-wrap">

        <a
            href="<?php echo e(route('admin.report.show', [
                'id' => $folder->id,
                'type' => 'daily',
                'date' => now()->format('Y-m-d')
            ])); ?>"
            class="px-5 py-3 rounded-lg font-semibold
            <?php echo e($type === 'daily'
                ? 'bg-accent text-white'
                : 'bg-bg text-secondary'); ?>"
        >
            Daily
        </a>


        <a
            href="<?php echo e(route('admin.report.show', [
                'id' => $folder->id,
                'type' => 'monthly',
                'month' => now()->format('Y-m')
            ])); ?>"
            class="px-5 py-3 rounded-lg font-semibold
            <?php echo e($type === 'monthly'
                ? 'bg-accent text-white'
                : 'bg-bg text-secondary'); ?>"
        >
            Monthly
        </a>


        <a
            href="<?php echo e(route('admin.report.show', [
                'id' => $folder->id,
                'type' => 'custom'
            ])); ?>"
            class="px-5 py-3 rounded-lg font-semibold
            <?php echo e($type === 'custom'
                ? 'bg-accent text-white'
                : 'bg-bg text-secondary'); ?>"
        >
            Custom
        </a>

    </div>

</div>




<?php if($type === 'daily'): ?>

<form
    method="GET"
    class="bg-surface border border-border rounded-xl p-5 mb-6"
>

    <input
        type="hidden"
        name="type"
        value="daily"
    >

    <div class="flex flex-col md:flex-row gap-4 items-end">

        <div>

            <label class="block text-sm font-semibold mb-2">
                Select Date
            </label>

            <input
                type="date"
                name="date"
                value="<?php echo e($date); ?>"
                max="<?php echo e(now()->format('Y-m-d')); ?>"
                class="border border-border rounded-lg px-4 py-3 bg-bg"
            >

        </div>


        <button
            class="bg-accent text-white px-6 py-3 rounded-lg font-semibold"
        >
            View Report
        </button>


        <a
            href="<?php echo e(route('admin.report.pdf', [
                'id' => $folder->id,
                'type' => 'daily',
                'date' => $date
            ])); ?>"
            class="bg-danger text-white px-6 py-3 rounded-lg font-semibold"
        >
            📄 PDF
        </a>

    </div>

</form>

<?php endif; ?>




<?php if($type === 'monthly'): ?>

<form
    method="GET"
    class="bg-surface border border-border rounded-xl p-5 mb-6"
>

    <input
        type="hidden"
        name="type"
        value="monthly"
    >

    <div class="flex flex-col md:flex-row gap-4 items-end">

        <div>

            <label class="block text-sm font-semibold mb-2">
                Select Month
            </label>

            <input
                type="month"
                name="month"
                value="<?php echo e($month); ?>"
                class="border border-border rounded-lg px-4 py-3 bg-bg"
            >

        </div>


        <button
            class="bg-accent text-white px-6 py-3 rounded-lg font-semibold"
        >
            View Report
        </button>


        <a
            href="<?php echo e(route('admin.report.pdf', [
                'id' => $folder->id,
                'type' => 'monthly',
                'month' => $month
            ])); ?>"
            class="bg-danger text-white px-6 py-3 rounded-lg font-semibold"
        >
            📄 PDF
        </a>

    </div>

</form>

<?php endif; ?>




<?php if($type === 'custom'): ?>

<form
    method="GET"
    class="bg-surface border border-border rounded-xl p-5 mb-6"
>

    <input
        type="hidden"
        name="type"
        value="custom"
    >

    <div class="grid md:grid-cols-3 gap-4 items-end">

        <div>

            <label class="block text-sm font-semibold mb-2">
                From Date
            </label>

            <input
                type="date"
                name="start"
                id="startDate"
                value="<?php echo e($start); ?>"
                max="<?php echo e(now()->format('Y-m-d')); ?>"
                class="w-full border border-border rounded-lg px-4 py-3 bg-bg"
                required
            >

        </div>


        <div>

            <label class="block text-sm font-semibold mb-2">
                To Date
            </label>

            <input
                type="date"
                name="end"
                id="endDate"
                value="<?php echo e($end); ?>"
                max="<?php echo e(now()->format('Y-m-d')); ?>"
                class="w-full border border-border rounded-lg px-4 py-3 bg-bg"
                required
            >

        </div>


        <div class="flex gap-2">

            <button
                class="bg-accent text-white px-6 py-3 rounded-lg font-semibold"
            >
                View Report
            </button>

            <a
                href="<?php echo e(route('admin.report.pdf', [
                    'id' => $folder->id,
                    'type' => 'custom',
                    'start' => $start,
                    'end' => $end
                ])); ?>"
                class="bg-danger text-white px-6 py-3 rounded-lg font-semibold"
            >
                PDF
            </a>

        </div>

    </div>

</form>

<script>

document
    .querySelector('form')
    ?.addEventListener(
        'submit',
        function(event) {

            const start =
                document.getElementById(
                    'startDate'
                )?.value;

            const end =
                document.getElementById(
                    'endDate'
                )?.value;

            if (
                start &&
                end &&
                start > end
            ) {

                event.preventDefault();

                alert(
                    'From Date cannot be after To Date.'
                );

            }

        }
    );

</script>

<?php endif; ?>




<div class="bg-surface border border-border rounded-xl p-5 mb-5">

    <label class="block text-sm font-semibold mb-2">
        Search Student
    </label>

    <input
        type="text"
        id="reportSearch"
        placeholder="Search name, roll, branch, mobile..."
        class="w-full md:w-1/2 border border-border rounded-lg px-4 py-3 bg-bg"
        onkeyup="searchReport()"
    >

</div>




<div class="bg-surface border border-border rounded-xl overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-bg border-b border-border">

                <tr>

                    <th class="p-4 text-left">
                        S.No
                    </th>

                    <th class="p-4 text-left">
                        Name
                    </th>

                    <th class="p-4 text-left">
                        Roll
                    </th>

                    <th class="p-4 text-left">
                        Branch
                    </th>

                    <th class="p-4 text-left">
                        Mobile
                    </th>

                    <?php if($type === 'daily'): ?>

                        <th class="p-4 text-center">
                            Status
                        </th>

                    <?php else: ?>

                        <th class="p-4 text-center">
                            Total Days
                        </th>

                        <th class="p-4 text-center">
                            Present
                        </th>

                        <th class="p-4 text-center">
                            Absent
                        </th>

                        <th class="p-4 text-center">
                            Not Marked
                        </th>

                        <th class="p-4 text-center">
                            Percentage
                        </th>

                    <?php endif; ?>

                </tr>

            </thead>


            <tbody id="reportTableBody">

            <?php $__currentLoopData = $studentsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tr
                    class="report-row border-b border-border"
                    data-search="<?php echo e(strtolower(
                        $data['name'] . ' ' .
                        $data['roll'] . ' ' .
                        $data['branch'] . ' ' .
                        $data['phone']
                    )); ?>"
                >

                    <td class="p-4">
                        <?php echo e($data['serno']); ?>

                    </td>

                    <td class="p-4 font-semibold">
                        <?php echo e($data['name']); ?>

                    </td>

                    <td class="p-4">
                        <?php echo e($data['roll']); ?>

                    </td>

                    <td class="p-4">
                        <?php echo e($data['branch']); ?>

                    </td>

                    <td class="p-4">
                        <?php echo e($data['phone']); ?>

                    </td>


                    <?php if($type === 'daily'): ?>

                        <td class="p-4 text-center">

                            <?php if($data['daily_status'] === 'Present'): ?>

                                <span class="text-success font-bold">
                                    ✅ Present
                                </span>

                            <?php elseif($data['daily_status'] === 'Absent'): ?>

                                <span class="text-danger font-bold">
                                    ❌ Absent
                                </span>

                            <?php elseif($data['daily_status'] === 'Not Applicable'): ?>

                                <span class="text-secondary font-semibold">
                                    Not Applicable
                                </span>

                            <?php else: ?>

                                <span class="text-secondary font-semibold">
                                    Not Attendance Marked
                                </span>

                            <?php endif; ?>

                        </td>

                    <?php else: ?>

                        <td class="p-4 text-center">
                            <?php echo e($data['total_days']); ?>

                        </td>

                        <td class="p-4 text-center text-success font-bold">
                            <?php echo e($data['present']); ?>

                        </td>

                        <td class="p-4 text-center text-danger font-bold">
                            <?php echo e($data['absent']); ?>

                        </td>

                        <td class="p-4 text-center text-secondary">
                            <?php echo e($data['not_marked']); ?>

                        </td>

                        <td class="p-4 text-center font-bold">
                            <?php echo e($data['percentage']); ?>%
                        </td>

                    <?php endif; ?>

                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            <?php if(count($studentsData) === 0): ?>

                <tr>

                    <td
                        colspan="10"
                        class="p-10 text-center text-secondary"
                    >
                        No students found.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


<?php if($type === 'daily' && count($studentsData) > 0): ?>

<?php

    $hasAttendance =
        collect($studentsData)
            ->contains(function ($item) {

                return in_array(
                    $item['daily_status'],
                    ['Present', 'Absent'],
                    true
                );

            });

?>


<?php if(!$hasAttendance): ?>

<div class="mt-5 bg-danger/10 border border-danger text-danger px-5 py-4 rounded-xl">

    ⚠️ No attendance marked for any student on this date.

</div>

<?php endif; ?>

<?php endif; ?>


<script>

function searchReport() {

    const input =
        document
            .getElementById('reportSearch')
            .value
            .toLowerCase()
            .trim();

    document
        .querySelectorAll('.report-row')
        .forEach(row => {

            const text =
                row
                    .getAttribute('data-search')
                    .toLowerCase();

            row.style.display =
                text.includes(input)
                    ? ''
                    : 'none';

        });

}

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/report.blade.php ENDPATH**/ ?>