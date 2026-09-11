

<?php $__env->startSection('title', 'Admin Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="bg-surface border border-borderCol rounded-3xl p-8 sm:p-10 w-full max-w-md shadow-darkCard relative overflow-hidden">
        
        
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-orangeAccent/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="text-center mb-8 relative z-10">
            <div class="w-16 h-16 bg-orangeAccent/10 border border-orangeAccent/30 rounded-2xl mx-auto flex items-center justify-center text-3xl mb-4 shadow-sm">
                🔐
            </div>
            <h2 class="text-3xl font-extrabold text-textPrimary tracking-tight">Admin Login</h2>
            <p class="text-textSecondary text-sm mt-2">Sign in to manage attendance securely</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="bg-red-950/90 border border-red-500 text-red-200 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-3 shadow-lg relative z-10">
                <span class="w-2.5 h-2.5 rounded-full bg-red-400 animate-pulse shrink-0"></span>
                <span><?php echo e($errors->first()); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.login.post')); ?>" class="space-y-6 relative z-10">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-bold text-textSecondary uppercase tracking-wider mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-textMuted pointer-events-none">✉️</span>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?php echo e(old('email')); ?>" 
                        placeholder="admin@attendance.com"
                        class="input-dark w-full rounded-2xl pl-11 pr-4 py-3.5 text-sm" 
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-textSecondary uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-textMuted pointer-events-none">🔑</span>
                    <input 
                        type="password" 
                        name="password" 
                        id="passwordField"
                        placeholder="••••••••"
                        class="input-dark w-full rounded-2xl pl-11 pr-12 py-3.5 text-sm" 
                        required
                        autocomplete="current-password"
                    >
                    
                    <button
                        type="button"
                        onclick="togglePasswordVisibility()"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-textSecondary hover:text-textPrimary transition-colors cursor-pointer"
                        title="Show/Hide Password"
                    >
                        <span id="eyeIcon" class="text-base">👁️‍🗨️</span>
                    </button>
                </div>
            </div>

            <button 
                type="submit" 
                class="btn-primary w-full font-semibold py-4 rounded-2xl transition-all shadow-md cursor-pointer flex items-center justify-center gap-2 text-base shadow-blueGlow"
            >
                <span>Login</span>
                <span>&rarr;</span>
            </button>
        </form>

        <div class="mt-8 text-center relative z-10 pt-6 border-t border-borderCol">
            <a href="<?php echo e(route('landing')); ?>" class="text-textSecondary text-xs font-bold hover:text-orangeLight transition-colors inline-flex items-center gap-1.5">
                &larr; Back to Home
            </a>
        </div>
    </div>
</div>

<script>
    // Password Show / Hide Toggle Script
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('passwordField');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.textContent = '👁️';
        } else {
            passwordField.type = 'password';
            eyeIcon.textContent = '👁️‍🗨️';
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/admin/login.blade.php ENDPATH**/ ?>