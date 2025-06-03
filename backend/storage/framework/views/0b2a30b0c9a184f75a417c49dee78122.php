

<?php $__env->startSection('title'); ?>
    Update Your Info
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('admin.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="col-md-9">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="mt-2">Update Your Information</h3>
                    </div>
                    <hr>
                    
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <form action="<?php echo e(route('admin.info.update')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            
                            <!-- Profile Image Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Profile Image</label>
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="position-relative">
                                            <img id="imagePreview" 
                                                src="<?php echo e($adminInfo->image ? asset('storage/' . $adminInfo->image) : asset('images/default-avatar.png')); ?>" 
                                                alt="Profile Image" 
                                                class="rounded-circle border" 
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                            
                                            <?php if($adminInfo->hasImage()): ?>
                                                <button type="button" id="removeImageBtn" 
                                                        class="btn btn-sm btn-danger position-absolute" 
                                                        style="top: -5px; right: -5px; border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="flex-grow-1">
                                            <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                                   id="image" name="image" accept="image/*">
                                            <div class="form-text">
                                                Accepted formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB
                                            </div>
                                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="name" name="name" value="<?php echo e(old('name', $adminInfo->name)); ?>" required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
                                        <option value="available" <?php echo e(old('status', $adminInfo->status) == 'available' ? 'selected' : ''); ?>>Available</option>
                                        <option value="busy" <?php echo e(old('status', $adminInfo->status) == 'busy' ? 'selected' : ''); ?>>Busy</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Skills</label>
                                    <div class="row">
                                        <?php
                                            $availableSkills = ['Hand Stitching', 'Cutting and Pattern Making', 'Edge Finishing', 'Tooling and Carving', 'Dyeing and Finishing', 'Skiving', 'Hardware Installation', 'Molding and Wet Forming'];
                                            $currentSkills = old('skills', $adminInfo->skills ?? []);
                                        ?>
                                        
                                        <?php $__currentLoopData = $availableSkills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="skills[]" 
                                                           value="<?php echo e($skill); ?>" id="skill_<?php echo e($loop->index); ?>"
                                                           <?php echo e(in_array($skill, $currentSkills) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="skill_<?php echo e($loop->index); ?>">
                                                        <?php echo e($skill); ?>

                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="skill_level" class="form-label">Skill Level</label>
                                    <select class="form-select <?php $__errorArgs = ['skill_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="skill_level" name="skill_level" required>
                                        <option value="beginner" <?php echo e(old('skill_level', $adminInfo->skill_level) == 'beginner' ? 'selected' : ''); ?>>Beginner</option>
                                        <option value="intermediate" <?php echo e(old('skill_level', $adminInfo->skill_level) == 'intermediate' ? 'selected' : ''); ?>>Intermediate</option>
                                        <option value="advanced" <?php echo e(old('skill_level', $adminInfo->skill_level) == 'advanced' ? 'selected' : ''); ?>>Advanced</option>
                                        <option value="expert" <?php echo e(old('skill_level', $adminInfo->skill_level) == 'expert' ? 'selected' : ''); ?>>Expert</option>
                                    </select>
                                    <?php $__errorArgs = ['skill_level'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="signature_products" class="form-label">Signature Products</label>
                                    <textarea class="form-control <?php $__errorArgs = ['signature_products'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="signature_products" name="signature_products" rows="4" 
                                              placeholder="Describe your signature products or specialties..."><?php echo e(old('signature_products', $adminInfo->signature_products)); ?></textarea>
                                    <?php $__errorArgs = ['signature_products'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Update Information
                                    </button>
                                    <a href="<?php echo e(route('admin.index')); ?>" class="btn btn-secondary ms-2">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImageBtn');

    // Preview image when file is selected
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                // Show remove button if it was hidden
                if (removeImageBtn) {
                    removeImageBtn.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this image?')) {
                fetch('<?php echo e(route("admin.info.remove-image")); ?>', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        imagePreview.src = '<?php echo e(asset("images/default-avatar.png")); ?>';
                        removeImageBtn.style.display = 'none';
                        imageInput.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to remove image. Please try again.');
                });
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\macos\Desktop\laravel_react_ecommerce-main\backend\resources\views/admin/info/index.blade.php ENDPATH**/ ?>