

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<div class="max-w-7xl mx-auto px-4 py-8">

    <!-- Success message -->
    <?php if(session('success')): ?>
        <div class="mb-6 bg-emerald-950/80 border border-emerald-500 text-emerald-200 px-5 py-4 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="font-medium text-sm"><?php echo e(session('success')); ?></span>
            </div>

            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200 text-xs font-bold uppercase tracking-wider">
                Dismiss
            </button>
        </div>
    <?php endif; ?>

    <!-- Error message -->
    <?php if(session('error')): ?>
        <div class="mb-6 bg-red-950/80 border border-red-500 text-red-200 px-5 py-4 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-red-400 animate-pulse"></span>
                <span class="font-medium text-sm"><?php echo e(session('error')); ?></span>
            </div>

            <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-200 text-xs font-bold uppercase tracking-wider">
                Dismiss
            </button>
        </div>
    <?php endif; ?>

    <!-- Validation errors -->
    <?php if($errors->any()): ?>
        <div class="mb-6 bg-red-950/80 border border-red-500 text-red-200 px-5 py-4 rounded-2xl shadow-lg backdrop-blur-md">
            <ul class="list-disc list-inside text-sm space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Dashboard Header -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-card border border-borderCol p-6 sm:p-8 rounded-3xl shadow-xl">

        <div>
            <p class="text-orangeLight text-xs font-semibold tracking-[0.18em] uppercase mb-2">
                Attendance Management System
            </p>

            <h1 class="text-3xl font-extrabold text-textPrimary tracking-tight">
                Admin Dashboard
            </h1>

            <p class="text-textSecondary text-sm mt-2">
                Manage attendance folders, student directories, and performance logs securely.
            </p>
        </div>

        <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="w-full sm:w-auto">
            <?php echo csrf_field(); ?>

            <button type="submit" class="btn-danger w-full sm:w-auto font-semibold px-6 py-3 rounded-xl active:scale-95">
                Logout
            </button>
        </form>
    </div>

    <!-- Create Folder Section -->
    <div class="bg-card border border-borderCol rounded-3xl p-6 sm:p-8 mb-10 shadow-xl relative overflow-hidden">

        <!-- Orange only as decorative border / background -->
        <div class="absolute top-0 right-0 w-48 h-48 border-l border-b border-orangeAccent/30 bg-orangeAccent/5 rounded-bl-full pointer-events-none"></div>

        <h2 class="text-xl font-bold mb-4 text-textPrimary flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-orangeAccent"></span>
            Create New Folder
        </h2>

        <form method="POST" action="<?php echo e(route('admin.folders.store')); ?>" class="flex flex-col sm:flex-row gap-4 relative">
            <?php echo csrf_field(); ?>

            <input
                type="text"
                name="name"
                placeholder="Enter Folder Name (e.g., FY BSc CS)"
                class="input-dark flex-1 rounded-xl px-5 py-3.5 text-sm"
                required
            >

            <button type="submit" class="btn-primary font-semibold px-8 py-3.5 rounded-xl active:scale-95 text-sm">
                + Create Folder
            </button>
        </form>
    </div>

    <!-- Folders List Section -->
    <div class="mb-12">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h2 class="text-2xl font-bold text-textPrimary flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-orangeAccent"></span>
                Your Folders
            </h2>

            <span class="text-xs bg-slate-900 border border-orangeAccent/40 text-orangeLight px-3 py-1.5 rounded-xl">
                <?php echo e($folders->count()); ?> Total Folders
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <?php $__empty_1 = true; $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-card border border-borderCol rounded-3xl p-6 transition-all duration-300 hover:border-orangeAccent hover:shadow-2xl hover:-translate-y-1 flex flex-col justify-between group relative">

                    <div>
                        <div class="flex justify-between items-start mb-4 gap-3">

                            <div class="flex items-center gap-3 min-w-0">
                                <!-- Attendance status indicator -->
                                <?php if(isset($folder->today_attendance_marked) && $folder->today_attendance_marked): ?>
                                    <span
                                        class="w-3.5 h-3.5 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/50 flex-shrink-0"
                                        title="Today's attendance marked"
                                    ></span>
                                <?php else: ?>
                                    <span
                                        class="w-3.5 h-3.5 rounded-full bg-red-500 shadow-lg shadow-red-500/50 flex-shrink-0"
                                        title="Today's attendance pending"
                                    ></span>
                                <?php endif; ?>

                                <h3 class="font-bold text-xl text-textPrimary group-hover:text-orangeLight transition-colors truncate">
                                    <?php echo e($folder->name); ?>

                                </h3>
                            </div>

                            <span class="bg-slate-900 text-textSecondary text-xs px-3 py-1 rounded-xl border border-slate-600 whitespace-nowrap">
                                <?php echo e($folder->students_count ?? $folder->students->count()); ?> Students
                            </span>
                        </div>

                        <p class="text-sm text-textMuted mb-6">
                            Add students, mark attendance, check reports and download PDFs.
                        </p>

                        <a
                            href="<?php echo e(route('admin.folders.show', $folder->id)); ?>"
                            class="inline-flex items-center gap-1.5 text-orangeLight hover:text-textPrimary font-semibold text-sm transition-colors"
                        >
                            Open Folder →
                        </a>
                    </div>

                    <div class="flex items-center justify-between pt-4 mt-5 border-t border-borderCol">
                        <button
                            type="button"
                            onclick="editFolder(<?php echo e($folder->id); ?>, <?php echo \Illuminate\Support\Js::from($folder->name)->toHtml() ?>)"
                            class="text-textSecondary hover:text-textPrimary text-sm font-medium transition-colors"
                        >
                            Edit Name
                        </button>

                        <button
                            type="button"
                            onclick="deleteFolder(<?php echo e($folder->id); ?>)"
                            class="text-red-400 hover:text-red-300 text-sm font-medium transition-colors"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full bg-card border border-dashed border-orangeAccent/50 rounded-3xl p-10 text-center">
                    <div class="text-4xl mb-3">📁</div>

                    <h3 class="font-bold text-textPrimary">
                        No Folder Created Yet
                    </h3>

                    <p class="text-sm text-textMuted mt-2">
                        Create your first class, batch, or department folder using the form above.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Subject Wise Attendance -->
    <div class="mb-8">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <h2 class="text-2xl font-bold text-textPrimary flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-orangeAccent"></span>
                Subject Wise Attendance
            </h2>

            <span class="text-xs bg-slate-900 border border-orangeAccent/40 text-orangeLight px-3 py-1.5 rounded-xl">
                <?php echo e($subjects->count()); ?> Subjects
            </span>
        </div>

        <!-- Add Subject -->
        <div class="bg-card border border-borderCol rounded-3xl p-6 sm:p-8 mb-6 shadow-xl">
            <form method="POST" action="<?php echo e(route('admin.subjects.store')); ?>" class="flex flex-col sm:flex-row gap-4">
                <?php echo csrf_field(); ?>

                <input
                    type="text"
                    name="name"
                    placeholder="Subject Name (e.g., Data Structures)"
                    class="input-dark flex-1 rounded-xl px-5 py-3.5 text-sm"
                    required
                >

                <button type="submit" class="btn-primary font-semibold px-8 py-3.5 rounded-xl active:scale-95 text-sm">
                    + Add Subject
                </button>
            </form>
        </div>

        <!-- Subject cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a
                    href="<?php echo e(route('admin.subjects.show', $subject->id)); ?>"
                    class="bg-card border border-borderCol rounded-3xl p-6 transition-all duration-300 hover:border-orangeAccent hover:shadow-2xl hover:-translate-y-1 block group"
                >
                    <div class="w-11 h-11 rounded-xl border border-orangeAccent/50 bg-orangeAccent/5 flex items-center justify-center text-xl mb-4">
                        📚
                    </div>

                    <h3 class="font-bold text-lg text-textPrimary group-hover:text-orangeLight transition-colors mb-2">
                        <?php echo e($subject->name); ?>

                    </h3>

                    <p class="text-textSecondary text-sm">
                        Click to manage subject attendance →
                    </p>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full bg-card border border-dashed border-orangeAccent/50 rounded-3xl p-8 text-center">
                    <p class="text-textMuted">
                        No subjects have been added yet.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Folder Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md items-center justify-center z-50 p-4">
    <div class="bg-card border border-orangeAccent/40 rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">

        <h3 class="text-xl font-bold mb-4 text-textPrimary">
            Edit Folder Name
        </h3>

        <form id="editForm" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <input
                type="text"
                id="editName"
                name="name"
                class="input-dark rounded-xl px-4 py-3.5 mb-6 text-sm"
                required
            >

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeEdit()" class="btn-outline px-5 py-2.5 rounded-xl text-sm">
                    Cancel
                </button>

                <button type="submit" class="btn-primary font-semibold px-6 py-2.5 rounded-xl text-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Folder Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md items-center justify-center z-50 p-4">
    <div class="bg-card border border-danger/50 rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">

        <h3 class="text-xl font-bold mb-2 text-red-400">
            Confirm Delete
        </h3>

        <p class="text-textSecondary text-sm mb-6">
            Enter your admin password to securely delete this folder and all its student and attendance records.
        </p>

        <form id="deleteForm" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>

            <input
                type="password"
                name="password"
                placeholder="Admin Password"
                class="input-dark rounded-xl px-4 py-3.5 mb-6 text-sm focus:border-red-500 focus:ring-red-500/20"
                required
            >

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeDelete()" class="btn-outline px-5 py-2.5 rounded-xl text-sm">
                    Cancel
                </button>

                <button type="submit" class="btn-danger font-semibold px-6 py-2.5 rounded-xl text-sm">
                    Confirm Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function editFolder(id, name) {
        document.getElementById('editForm').action = '/admin/folders/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEdit() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    function deleteFolder(id) {
        document.getElementById('deleteForm').action = '/admin/folders/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDelete() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/dashboard.blade.php ENDPATH**/ ?>