
<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold">Admin Dashboard</h1>
    <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
        <?php echo csrf_field(); ?>
        <button class="bg-danger hover:bg-danger/90 text-white px-4 py-2 rounded-lg">Logout</button>
    </form>
</div>
<?php if(session('success')): ?>
    <div class="bg-success/10 border border-success text-success px-4 py-2 rounded mb-4"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="bg-danger/10 border border-danger text-danger px-4 py-2 rounded mb-4"><?php echo e(session('error')); ?></div>
<?php endif; ?>
<div class="bg-surface border border-border rounded-xl p-6 mb-8 glow-accent">
    <h2 class="text-xl font-semibold mb-4">Create New Folder</h2>
    <form method="POST" action="<?php echo e(route('admin.folders.store')); ?>" class="flex gap-4">
        <?php echo csrf_field(); ?>
        <input type="text" name="name" placeholder="Folder Name" class="flex-1 border border-border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent outline-none" required>
        <button type="submit" class="bg-accent hover:bg-accentHover text-white px-6 py-2 rounded-lg glow-accent">+ Create Folder</button>
    </form>
</div>
<div class="mb-8">
    <h2 class="text-2xl font-semibold mb-4">Your Folders</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-surface border border-border rounded-xl p-4 card-hover transition-all">
            <h3 class="font-semibold text-lg mb-2"><?php echo e($folder->name); ?></h3>
            <div class="flex gap-2 mb-3">
                <a href="<?php echo e(route('admin.folders.show', $folder->id)); ?>" class="text-accent hover:underline text-sm">Open →</a>
            </div>
            <div class="flex gap-2">
                <button onclick="editFolder(<?php echo e($folder->id); ?>, '<?php echo e($folder->name); ?>')" class="text-secondary hover:text-accent text-sm">Edit Name</button>
                <button onclick="deleteFolder(<?php echo e($folder->id); ?>)" class="text-danger hover:text-danger text-sm">Delete</button>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<div>
    <h2 class="text-2xl font-semibold mb-4">Subject Wise Attendance</h2>
    <div class="bg-surface border border-border rounded-xl p-6 mb-4">
        <form method="POST" action="<?php echo e(route('admin.subjects.store')); ?>" class="flex gap-4">
            <?php echo csrf_field(); ?>
            <input type="text" name="name" placeholder="Subject Name" class="flex-1 border border-border rounded-lg px-4 py-2 focus:ring-2 focus:ring-accent outline-none" required>
            <button type="submit" class="bg-accent hover:bg-accentHover text-white px-6 py-2 rounded-lg glow-accent">+ Add Subject</button>
        </form>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('admin.subjects.show', $subject->id)); ?>" class="bg-surface border border-border rounded-xl p-4 card-hover transition-all block">
            <h3 class="font-semibold text-lg"><?php echo e($subject->name); ?></h3>
            <p class="text-secondary text-sm mt-1">Click to manage →</p>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<div id="editModal" class="hidden fixed inset-0 bg-black/50 items-center justify-center">
    <div class="bg-surface rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Edit Folder Name</h3>
        <form id="editForm" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <input type="text" id="editName" name="name" class="w-full border border-border rounded-lg px-4 py-2 mb-4 focus:ring-2 focus:ring-accent outline-none" required>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeEdit()" class="px-4 py-2 text-secondary">Cancel</button>
                <button type="submit" class="bg-accent text-white px-4 py-2 rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 items-center justify-center">
    <div class="bg-surface rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4 text-danger">Confirm Delete</h3>
        <p class="mb-4">Enter admin password to delete this folder:</p>
        <form id="deleteForm" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <input type="password" name="password" placeholder="Admin Password" class="w-full border border-border rounded-lg px-4 py-2 mb-4 focus:ring-2 focus:ring-accent outline-none" required>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeDelete()" class="px-4 py-2 text-secondary">Cancel</button>
                <button type="submit" class="bg-danger text-white px-4 py-2 rounded-lg">Delete</button>
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