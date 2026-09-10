
<?php $__env->startSection('title', 'Staff Login'); ?>
<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="bg-surface border border-border rounded-3xl p-8 sm:p-10 w-full max-w-md shadow-darkCard relative overflow-hidden">
        
        
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="text-center mb-8 relative z-10">
            <div class="w-16 h-16 bg-accent/10 border border-accent/30 rounded-2xl mx-auto flex items-center justify-center text-3xl mb-4 shadow-sm">
                👨‍🏫
            </div>
            <h2 class="text-3xl font-extrabold text-textPrimary tracking-tight">Staff Login</h2>
            <p class="text-secondary text-sm mt-2"></p>
        </div>

        <?php if($errors->any()): ?>
            <div class="bg-red-950/90 border border-red-500 text-red-200 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center gap-3 shadow-lg relative z-10">
                <span class="w-2.5 h-2.5 rounded-full bg-red-400 animate-pulse shrink-0"></span>
                <span><?php echo e($errors->first()); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('staff.login.post')); ?>" class="space-y-6 relative z-10">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-textMuted pointer-events-none">✉️</span>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?php echo e(old('email')); ?>" 
                        placeholder="staff@attendance.com"
                        class="w-full bg-card border border-border rounded-2xl pl-11 pr-4 py-3.5 text-sm text-textPrimary focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all shadow-inner" 
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-secondary uppercase tracking-wider mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-textMuted pointer-events-none">🔑</span>
                    <input 
                        type="password" 
                        name="password" 
                        id="passwordField"
                        placeholder="••••••••"
                        class="w-full bg-card border border-border rounded-2xl pl-11 pr-12 py-3.5 text-sm text-textPrimary focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all shadow-inner" 
                        required
                        autocomplete="current-password"
                    >
                    
                    <button
                        type="button"
                        onclick="togglePasswordVisibility()"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-secondary hover:text-textPrimary transition-colors cursor-pointer"
                        title="Show/Hide Password"
                    >
                        <span id="eyeIcon" class="text-base">👁️‍🗨️</span>
                    </button>
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full bg-accent hover:bg-accentHover text-white font-semibold py-4 rounded-2xl glow-accent transition-all shadow-md cursor-pointer flex items-center justify-center gap-2 text-base"
            >
                <span>Login</span>
                <span>&rarr;</span>
            </button>
        </form>

        <div class="mt-8 text-center relative z-10 pt-6 border-t border-border">
            <a href="<?php echo e(route('landing')); ?>" class="text-secondary text-xs font-bold hover:text-accent transition-colors inline-flex items-center gap-1.5">
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Attandance_Fremwork_2026\resources\Views/staff/login.blade.php ENDPATH**/ ?>