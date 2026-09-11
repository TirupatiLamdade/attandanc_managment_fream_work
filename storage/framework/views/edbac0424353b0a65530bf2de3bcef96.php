

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

    <!-- Compact Dashboard Header with Profile & Logout -->
    <div class="mb-8 flex flex-row justify-between items-center bg-card border border-borderCol px-6 py-5 rounded-3xl shadow-xl">
        <div>
            <p class="text-orangeLight text-xs font-semibold tracking-[0.18em] uppercase mb-1">
                Attendance Management System
            </p>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-textPrimary tracking-tight">
                Admin Dashboard
            </h1>
        </div>

        <!-- Right Side: Profile Circle & Logout -->
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-3 bg-surface border border-borderCol px-4 py-2 rounded-full shadow-inner">
                <div class="w-9 h-9 rounded-full bg-orangeAccent text-white font-bold flex items-center justify-center text-sm shadow-md">
                    <?php echo e(strtoupper(substr(Auth::user()->name ?? 'A', 0, 1))); ?>

                </div>
                <span class="text-sm font-semibold text-textPrimary hidden sm:inline">
                    <?php echo e(Auth::user()->name ?? 'Admin'); ?>

                </span>
            </div>

            <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn-danger font-semibold px-5 py-2.5 rounded-xl text-xs sm:text-sm active:scale-95 shadow-md">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- ================= STUDENT FOLDERS SECTION ================= -->
    <div class="mb-12">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-textPrimary flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-orangeAccent"></span>
                Student Attendance Folders
            </h2>
            <button
                type="button"
                onclick="toggleCreateFolder()"
                class="btn-primary font-semibold px-6 py-3 rounded-2xl text-sm flex items-center gap-2 shadow-lg"
            >
                <span>📁</span> + Create New Folder
            </button>
        </div>

        <!-- Create Folder Expandable Form -->
        <div id="createFolderBox" class="hidden bg-card border border-orangeAccent/50 rounded-3xl p-6 mb-6 shadow-xl relative overflow-hidden transition-all">
            <h3 class="text-base font-bold mb-3 text-textPrimary flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orangeAccent"></span>
                Enter New Folder Name
            </h3>

            <form method="POST" action="<?php echo e(route('admin.folders.store')); ?>" class="flex flex-col sm:flex-row gap-3">
                <?php echo csrf_field(); ?>
                <input
                    type="text"
                    name="name"
                    placeholder="Enter Folder Name (e.g., FY BSc CS)"
                    class="input-dark flex-1 rounded-xl px-5 py-3.5 text-sm"
                    required
                >
                <div class="flex gap-2">
                    <button type="button" onclick="toggleCreateFolder()" class="btn-outline px-5 py-3.5 rounded-xl text-sm">Cancel</button>
                    <button type="submit" class="btn-primary font-semibold px-6 py-3.5 rounded-xl text-sm">Save Folder</button>
                </div>
            </form>
        </div>

        <!-- Folder Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-card border border-borderCol rounded-3xl p-6 transition-all duration-300 hover:border-orangeAccent hover:shadow-2xl hover:-translate-y-1 flex flex-col justify-between group shadow-xl">
                    <div>
                        <div class="flex justify-between items-start mb-4 gap-3">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-12 h-12 rounded-2xl border border-orangeAccent/40 bg-orangeAccent/10 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                                    📁
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <?php if(isset($folder->today_attendance_marked) && $folder->today_attendance_marked): ?>
                                            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-md flex-shrink-0" title="Marked"></span>
                                        <?php else: ?>
                                            <span class="w-3 h-3 rounded-full bg-red-500 shadow-md flex-shrink-0" title="Pending"></span>
                                        <?php endif; ?>
                                        <h3 class="font-bold text-lg text-textPrimary group-hover:text-orangeLight transition-colors truncate">
                                            <?php echo e($folder->name); ?>

                                        </h3>
                                    </div>
                                    <p class="text-xs text-textMuted mt-1">Student Attendance Folder</p>
                                </div>
                            </div>

                            <span class="bg-slate-900 text-textSecondary text-xs px-3 py-1 rounded-xl border border-slate-700 whitespace-nowrap">
                                <?php echo e($folder->students_count ?? $folder->students->count()); ?> Students
                            </span>
                        </div>

                        <a
                            href="<?php echo e(route('admin.folders.show', $folder->id)); ?>"
                            class="inline-flex items-center gap-1.5 text-orangeLight hover:text-textPrimary font-semibold text-sm transition-colors mb-4"
                        >
                            Open Folder →
                        </a>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-borderCol text-xs">
                        <button type="button" onclick="editFolder(<?php echo e($folder->id); ?>, <?php echo \Illuminate\Support\Js::from($folder->name)->toHtml() ?>)" class="text-textSecondary hover:text-textPrimary font-medium">
                            Edit Name
                        </button>
                        <button type="button" onclick="deleteFolder(<?php echo e($folder->id); ?>)" class="text-red-400 hover:text-red-300 font-medium">
                            Delete
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full bg-card border border-dashed border-orangeAccent/40 rounded-3xl p-10 text-center">
                    <p class="text-sm text-textMuted">No student folders created yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ================= SUBJECT FOLDERS SECTION ================= -->
    <div class="mb-8">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-textPrimary flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-orangeAccent"></span>
                Subject Attendance Folders
            </h2>
            <button
                type="button"
                onclick="toggleCreateSubject()"
                class="btn-primary font-semibold px-6 py-3 rounded-2xl text-sm flex items-center gap-2 shadow-lg"
            >
                <span>📚</span> + Add Subject
            </button>
        </div>

        <!-- Add Subject Expandable Form -->
        <div id="createSubjectBox" class="hidden bg-card border border-orangeAccent/50 rounded-3xl p-6 mb-6 shadow-xl relative overflow-hidden transition-all">
            <h3 class="text-base font-bold mb-3 text-textPrimary flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orangeAccent"></span>
                Enter Subject Name
            </h3>

            <form method="POST" action="<?php echo e(route('admin.subjects.store')); ?>" class="flex flex-col sm:flex-row gap-3">
                <?php echo csrf_field(); ?>
                <input
                    type="text"
                    name="name"
                    placeholder="Subject Name (e.g., Data Structures)"
                    class="input-dark flex-1 rounded-xl px-5 py-3.5 text-sm"
                    required
                >
                <div class="flex gap-2">
                    <button type="button" onclick="toggleCreateSubject()" class="btn-outline px-5 py-3.5 rounded-xl text-sm">Cancel</button>
                    <button type="submit" class="btn-primary font-semibold px-6 py-3.5 rounded-xl text-sm">Save Subject</button>
                </div>
            </form>
        </div>

        <!-- Subject Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-card border border-borderCol rounded-3xl p-6 transition-all duration-300 hover:border-orangeAccent hover:shadow-2xl hover:-translate-y-1 flex flex-col justify-between group shadow-xl">
                    <div>
                        <div class="flex items-center gap-3.5 mb-4">
                            <div class="w-12 h-12 rounded-2xl border border-orangeAccent/40 bg-orangeAccent/10 flex items-center justify-center text-2xl flex-shrink-0 shadow-inner">
                                📚
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-lg text-textPrimary group-hover:text-orangeLight transition-colors truncate">
                                    <?php echo e($subject->name); ?>

                                </h3>
                                <p class="text-xs text-textMuted mt-0.5">Subject Attendance Folder</p>
                            </div>
                        </div>

                        <a
                            href="<?php echo e(route('admin.subjects.show', $subject->id)); ?>"
                            class="inline-flex items-center gap-1.5 text-orangeLight hover:text-textPrimary font-semibold text-sm transition-colors mb-4"
                        >
                            Open Subject →
                        </a>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-borderCol text-xs">
                        <button type="button" onclick="editSubject(<?php echo e($subject->id); ?>, <?php echo \Illuminate\Support\Js::from($subject->name)->toHtml() ?>)" class="text-textSecondary hover:text-textPrimary font-medium">
                            Edit Name
                        </button>
                        <button type="button" onclick="deleteSubject(<?php echo e($subject->id); ?>)" class="text-red-400 hover:text-red-300 font-medium">
                            Delete
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full bg-card border border-dashed border-orangeAccent/40 rounded-3xl p-10 text-center">
                    <p class="text-sm text-textMuted">No subject folders added yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md items-center justify-center z-50 p-4">
    <div class="bg-card border border-orangeAccent/40 rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
        <h3 id="editModalTitle" class="text-xl font-bold mb-4 text-textPrimary">Edit Name</h3>
        <form id="editForm" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <input type="text" id="editName" name="name" class="input-dark rounded-xl px-4 py-3.5 mb-6 text-sm w-full" required>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeEdit()" class="btn-outline px-5 py-2.5 rounded-xl text-sm">Cancel</button>
                <button type="submit" class="btn-primary font-semibold px-6 py-2.5 rounded-xl text-sm">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-md items-center justify-center z-50 p-4">
    <div class="bg-card border border-danger/50 rounded-3xl p-6 sm:p-8 w-full max-w-md shadow-2xl">
        <h3 id="deleteModalTitle" class="text-xl font-bold mb-2 text-red-400">Confirm Delete</h3>
        <p class="text-textSecondary text-sm mb-6">Enter admin password to securely delete.</p>
        <form id="deleteForm" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <input type="password" name="password" placeholder="Admin Password" class="input-dark rounded-xl px-4 py-3.5 mb-6 text-sm w-full" required>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeDelete()" class="btn-outline px-5 py-2.5 rounded-xl text-sm">Cancel</button>
                <button type="submit" class="btn-danger font-semibold px-6 py-2.5 rounded-xl text-sm">Confirm Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleCreateFolder() {
        const box = document.getElementById('createFolderBox');
        box.classList.toggle('hidden');
    }

    function toggleCreateSubject() {
        const box = document.getElementById('createSubjectBox');
        box.classList.toggle('hidden');
    }

    function editFolder(id, name) {
        document.getElementById('editModalTitle').innerText = 'Edit Folder Name';
        document.getElementById('editForm').action = '/admin/folders/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function deleteFolder(id) {
        document.getElementById('deleteModalTitle').innerText = 'Confirm Folder Delete';
        document.getElementById('deleteForm').action = '/admin/folders/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function editSubject(id, name) {
        document.getElementById('editModalTitle').innerText = 'Edit Subject Name';
        document.getElementById('editForm').action = '/admin/subjects/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function deleteSubject(id) {
        document.getElementById('deleteModalTitle').innerText = 'Confirm Subject Delete';
        document.getElementById('deleteForm').action = '/admin/subjects/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeEdit() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    function closeDelete() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/dashboard.blade.php ENDPATH**/ ?>