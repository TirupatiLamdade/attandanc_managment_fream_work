

<?php $__env->startSection('title', $folder->name . ' - Students'); ?>

<?php $__env->startSection('content'); ?>





<div class="mb-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>

            <a
                href="<?php echo e(route('admin.folders.show', $folder->id)); ?>"
                class="text-accent hover:underline text-sm"
            >
                ← Back to Folder
            </a>

            <h1 class="text-3xl font-bold mt-2">
                <?php echo e($folder->name); ?> - Students
            </h1>

            <p class="text-secondary mt-1">
                Manage all students in this folder
            </p>

        </div>


        <button
            type="button"
            onclick="openAddModal()"
            class="bg-accent hover:bg-accentHover text-white px-6 py-3 rounded-lg font-semibold"
        >
            + Add Student
        </button>

    </div>

</div>






<div class="bg-surface border border-border rounded-xl p-3 mb-6">

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <a
            href="<?php echo e(route('admin.students.index', $folder->id)); ?>"
            class="px-4 py-3 rounded-lg text-center bg-accent text-white font-semibold"
        >
            👨‍🎓 Students
        </a>

        <a
            href="<?php echo e(route('admin.report.show', [
                'id' => $folder->id,
                'type' => 'daily'
            ])); ?>"
            class="px-4 py-3 rounded-lg text-center bg-bg hover:bg-accent hover:text-white transition font-semibold"
        >
            📊 Report
        </a>

        <a
            href="<?php echo e(route('admin.report.total', $folder->id)); ?>"
            class="px-4 py-3 rounded-lg text-center bg-bg hover:bg-accent hover:text-white transition font-semibold"
        >
            📈 Total
        </a>

        <a
            href="<?php echo e(route('admin.attendance.show', $folder->id)); ?>"
            class="px-4 py-3 rounded-lg text-center bg-bg hover:bg-accent hover:text-white transition font-semibold"
        >
            ✅ Mark Attendance
        </a>

    </div>

</div>






<?php if(session('success')): ?>

<div class="bg-success/10 border border-success text-success px-4 py-3 rounded-xl mb-5">
    <?php echo e(session('success')); ?>

</div>

<?php endif; ?>


<?php if(session('error')): ?>

<div class="bg-danger/10 border border-danger text-danger px-4 py-3 rounded-xl mb-5">
    <?php echo e(session('error')); ?>

</div>

<?php endif; ?>


<?php if($errors->any()): ?>

<div class="bg-danger/10 border border-danger text-danger px-4 py-3 rounded-xl mb-5">

    <ul class="list-disc pl-5">

        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <li><?php echo e($error); ?></li>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </ul>

</div>

<?php endif; ?>






<div class="bg-surface border border-border rounded-xl p-4 mb-5">

    <input
        type="text"
        id="studentSearch"
        placeholder="Search by name, roll number, branch or mobile number..."
        class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent"
    >

</div>






<div class="bg-surface border border-border rounded-xl overflow-hidden mb-6">

<div class="overflow-x-auto">

<table class="w-full" id="studentsTable">

<thead class="bg-bg border-b border-border">

<tr>

<th class="text-left p-4 text-secondary">
    S.No
</th>

<th class="text-left p-4 text-secondary">
    Name
</th>

<th class="text-left p-4 text-secondary">
    Roll Number
</th>

<th class="text-left p-4 text-secondary">
    Branch
</th>

<th class="text-left p-4 text-secondary">
    Mobile Number
</th>

<th class="text-center p-4 text-secondary">
    Actions
</th>

</tr>

</thead>


<tbody>

<?php $__empty_1 = true; $__currentLoopData = $folder->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

<tr
    class="border-b border-border hover:bg-bg/50 student-row"

    data-name="<?php echo e(strtolower($student->name)); ?>"

    data-roll="<?php echo e(strtolower($student->roll_number)); ?>"

    data-branch="<?php echo e(strtolower($student->branch)); ?>"

    data-phone="<?php echo e(strtolower($student->phone)); ?>"
>

<td class="p-4 font-semibold">
    <?php echo e($loop->iteration); ?>

</td>

<td class="p-4">
    <?php echo e($student->name); ?>

</td>

<td class="p-4 font-medium">
    <?php echo e($student->roll_number); ?>

</td>

<td class="p-4">
    <?php echo e($student->branch); ?>

</td>

<td class="p-4">
    <?php echo e($student->phone); ?>

</td>

<td class="p-4">

<div class="flex justify-center gap-2">

<button
    type="button"
    onclick="openEditModal(
        <?php echo \Illuminate\Support\Js::from($student->id)->toHtml() ?>,
        <?php echo \Illuminate\Support\Js::from($student->name)->toHtml() ?>,
        <?php echo \Illuminate\Support\Js::from($student->roll_number)->toHtml() ?>,
        <?php echo \Illuminate\Support\Js::from($student->branch)->toHtml() ?>,
        <?php echo \Illuminate\Support\Js::from($student->phone)->toHtml() ?>
    )"
    class="px-3 py-2 rounded-lg border border-border hover:bg-bg"
>
    ✏️ Edit
</button>


<button
    type="button"
    onclick="openDeleteModal(<?php echo \Illuminate\Support\Js::from($student->id)->toHtml() ?>)"
    class="px-3 py-2 rounded-lg bg-danger/10 text-danger hover:bg-danger/20"
>
    🗑️ Delete
</button>

</div>

</td>

</tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

<tr>

<td colspan="6" class="p-10 text-center text-secondary">

<div class="text-5xl mb-3">
    👨‍🎓
</div>

<h3 class="text-lg font-semibold">
    No Students Added
</h3>

<p class="mt-1">
    Click "+ Add Student" to add the first student.
</p>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>






<div
    id="addModal"
    class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50 p-4"
>

<div class="bg-surface rounded-xl p-6 w-full max-w-lg">

<div class="flex justify-between items-center mb-5">

<h2 class="text-xl font-bold">
    Add Student
</h2>

<button
    type="button"
    onclick="closeAddModal()"
    class="text-secondary text-xl"
>
    ✕
</button>

</div>


<form
    method="POST"
    action="<?php echo e(route('admin.students.store', $folder->id)); ?>"
>

<?php echo csrf_field(); ?>


<div class="mb-4">

<label class="block font-medium mb-2">
    Student Name *
</label>

<input
    type="text"
    name="name"
    required
    maxlength="255"
    pattern="[A-Za-z ]+"
    placeholder="Enter student name"
    value="<?php echo e(old('name')); ?>"
    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent"
>

</div>


<div class="mb-4">

<label class="block font-medium mb-2">
    Roll Number *
</label>

<input
    type="text"
    name="roll_number"
    required
    maxlength="50"
    pattern="[0-9]+"
    placeholder="Enter roll number"
    value="<?php echo e(old('roll_number')); ?>"
    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent"
>

</div>


<div class="mb-4">

<label class="block font-medium mb-2">
    Branch *
</label>

<input
    type="text"
    name="branch"
    required
    maxlength="255"
    pattern="[A-Za-z ]+"
    placeholder="Example: Computer Engineering"
    value="<?php echo e(old('branch')); ?>"
    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent"
>

</div>


<div class="mb-5">

<label class="block font-medium mb-2">
    Mobile Number *
</label>

<input
    type="tel"
    name="phone"
    required
    maxlength="10"
    minlength="10"
    pattern="[0-9]{10}"
    placeholder="Enter 10 digit mobile number"
    value="<?php echo e(old('phone')); ?>"
    class="w-full border border-border rounded-lg px-4 py-3 outline-none focus:ring-2 focus:ring-accent"
>

</div>


<div class="flex justify-end gap-3">

<button
    type="button"
    onclick="closeAddModal()"
    class="px-5 py-3 border border-border rounded-lg"
>
    Cancel
</button>

<button
    type="submit"
    class="bg-accent hover:bg-accentHover text-white px-6 py-3 rounded-lg font-semibold"
>
    Add Student
</button>

</div>

</form>

</div>

</div>






<div
    id="editModal"
    class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50 p-4"
>

<div class="bg-surface rounded-xl p-6 w-full max-w-lg">

<div class="flex justify-between items-center mb-5">

<h2 class="text-xl font-bold">
    Edit Student
</h2>

<button
    type="button"
    onclick="closeEditModal()"
    class="text-secondary text-xl"
>
    ✕
</button>

</div>


<form
    id="editForm"
    method="POST"
>

<?php echo csrf_field(); ?>

<?php echo method_field('PUT'); ?>


<div class="mb-4">

<label class="block font-medium mb-2">
    Student Name *
</label>

<input
    id="editName"
    type="text"
    name="name"
    required
    maxlength="255"
    pattern="[A-Za-z ]+"
    class="w-full border border-border rounded-lg px-4 py-3"
>

</div>


<div class="mb-4">

<label class="block font-medium mb-2">
    Roll Number *
</label>

<input
    id="editRoll"
    type="text"
    name="roll_number"
    required
    maxlength="50"
    pattern="[0-9]+"
    class="w-full border border-border rounded-lg px-4 py-3"
>

</div>


<div class="mb-4">

<label class="block font-medium mb-2">
    Branch *
</label>

<input
    id="editBranch"
    type="text"
    name="branch"
    required
    maxlength="255"
    pattern="[A-Za-z ]+"
    class="w-full border border-border rounded-lg px-4 py-3"
>

</div>


<div class="mb-5">

<label class="block font-medium mb-2">
    Mobile Number *
</label>

<input
    id="editPhone"
    type="tel"
    name="phone"
    required
    maxlength="10"
    minlength="10"
    pattern="[0-9]{10}"
    class="w-full border border-border rounded-lg px-4 py-3"
>

</div>


<div class="flex justify-end gap-3">

<button
    type="button"
    onclick="closeEditModal()"
    class="px-5 py-3 border border-border rounded-lg"
>
    Cancel
</button>

<button
    type="submit"
    class="bg-accent hover:bg-accentHover text-white px-6 py-3 rounded-lg font-semibold"
>
    Update Student
</button>

</div>

</form>

</div>

</div>






<div
    id="deleteModal"
    class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50 p-4"
>

<div class="bg-surface rounded-xl p-6 w-full max-w-md">

<h2 class="text-xl font-bold mb-2 text-danger">
    Delete Student
</h2>

<p class="text-secondary mb-5">
    Enter your admin password to permanently delete this student.
</p>


<form
    id="deleteForm"
    method="POST"
>

<?php echo csrf_field(); ?>

<?php echo method_field('DELETE'); ?>


<input
    type="password"
    name="password"
    required
    autocomplete="current-password"
    placeholder="Admin Password"
    class="w-full border border-border rounded-lg px-4 py-3 mb-4"
>


<div class="flex justify-end gap-3">

<button
    type="button"
    onclick="closeDeleteModal()"
    class="px-5 py-3 border border-border rounded-lg"
>
    Cancel
</button>

<button
    type="submit"
    class="bg-danger text-white px-6 py-3 rounded-lg font-semibold"
>
    Delete
</button>

</div>

</form>

</div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /* SEARCH */

    const search =
        document.getElementById('studentSearch');

    if (search) {

        search.addEventListener('input', function () {

            const value =
                this.value.toLowerCase().trim();

            document
                .querySelectorAll('.student-row')
                .forEach(function (row) {

                    const text =
                        (
                            row.dataset.name +
                            ' ' +
                            row.dataset.roll +
                            ' ' +
                            row.dataset.branch +
                            ' ' +
                            row.dataset.phone
                        ).toLowerCase();

                    row.style.display =
                        text.includes(value)
                            ? ''
                            : 'none';

                });

        });

    }

});


function openAddModal()
{
    const modal =
        document.getElementById('addModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function closeAddModal()
{
    const modal =
        document.getElementById('addModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


function openEditModal(
    id,
    name,
    roll,
    branch,
    phone
)
{
    document.getElementById('editForm').action =
        '/admin/students/' + id;

    document.getElementById('editName').value =
        name;

    document.getElementById('editRoll').value =
        roll;

    document.getElementById('editBranch').value =
        branch;

    document.getElementById('editPhone').value =
        phone;

    const modal =
        document.getElementById('editModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function closeEditModal()
{
    const modal =
        document.getElementById('editModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}


function openDeleteModal(id)
{
    document.getElementById('deleteForm').action =
        '/admin/students/' + id;

    const modal =
        document.getElementById('deleteModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}


function closeDeleteModal()
{
    const modal =
        document.getElementById('deleteModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    document.getElementById('deleteForm').reset();
}


document.addEventListener('keydown', function (event) {

    if (event.key === 'Escape') {

        closeAddModal();
        closeEditModal();
        closeDeleteModal();

    }

});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/folders/students.blade.php ENDPATH**/ ?>