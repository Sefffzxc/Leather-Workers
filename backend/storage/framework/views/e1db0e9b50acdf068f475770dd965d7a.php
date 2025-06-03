

<?php $__env->startSection('title', 'Our Artisans'); ?>

<?php $__env->startSection('content'); ?>
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-dark">Meet Our Skilled Artisans</h1>
                <p class="lead text-muted">Discover the talented craftspeople behind our beautiful products</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('artisans.index')); ?>">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="search" class="form-label">Search by name</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="<?php echo e(request('search')); ?>" placeholder="Artisan name...">
                            </div>
                            <div class="col-md-2">
                                <label for="skill" class="form-label">Skill</label>
                                <select class="form-control" id="skill" name="skill">
                                    <option value="">All Skills</option>
                                    <?php $__currentLoopData = $skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($skill); ?>" <?php echo e(request('skill') == $skill ? 'selected' : ''); ?>>
                                            <?php echo e($skill); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="skill_level" class="form-label">Level</label>
                                <select class="form-control" id="skill_level" name="skill_level">
                                    <option value="">All Levels</option>
                                    <?php $__currentLoopData = $skillLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($level); ?>" <?php echo e(request('skill_level') == $level ? 'selected' : ''); ?>>
                                            <?php echo e($level); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="">All Status</option>
                                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status); ?>" <?php echo e(request('status') == $status ? 'selected' : ''); ?>>
                                            <?php echo e($status); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="<?php echo e(route('artisans.index')); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-refresh"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results count -->
    <div class="row mb-3">
        <div class="col-12">
            <p class="text-muted">
                Showing <?php echo e($artisans->firstItem() ?? 0); ?> to <?php echo e($artisans->lastItem() ?? 0); ?> 
                of <?php echo e($artisans->total()); ?> artisans
            </p>
        </div>
    </div>

    <!-- Artisans Grid -->
    <div class="row">
        <?php $__empty_1 = true; $__currentLoopData = $artisans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artisan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body text-center">
                        <!-- Profile Image -->
                        <div class="mb-3">
                            <?php if($artisan->profile_image): ?>
                                <img src="<?php echo e(asset('storage/' . $artisan->profile_image)); ?>" 
                                     alt="<?php echo e($artisan->name); ?>" 
                                     class="rounded-circle"
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white mx-auto" 
                                     style="width: 100px; height: 100px; font-size: 2rem;">
                                    <?php echo e(strtoupper(substr($artisan->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Name and Status -->
                        <h5 class="card-title mb-2"><?php echo e($artisan->name); ?></h5>
                        <span class="badge <?php echo e($artisan->getAvailabilityBadgeClass()); ?> mb-3">
                            <?php echo e($artisan->availability_status); ?>

                        </span>

                        <!-- Skill Info -->
                        <div class="mb-3">
                            <h6 class="text-primary mb-1"><?php echo e($artisan->skill); ?></h6>
                            <span class="badge <?php echo e($artisan->getSkillLevelBadgeClass()); ?>">
                                <?php echo e($artisan->skill_level); ?>

                            </span>
                        </div>

                        <!-- Experience and Rate -->
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="border-end">
                                    <h6 class="mb-1"><?php echo e($artisan->years_experience); ?></h6>
                                    <small class="text-muted">Years Exp.</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1">
                                    <?php if($artisan->hourly_rate): ?>
                                        $<?php echo e(number_format($artisan->hourly_rate, 0)); ?>

                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </h6>
                                <small class="text-muted">Per Hour</small>
                            </div>
                        </div>

                        <!-- Bio snippet -->
                        <?php if($artisan->bio): ?>
                            <p class="card-text text-muted small mb-3">
                                <?php echo e(Str::limit($artisan->bio, 100)); ?>

                            </p>
                        <?php endif; ?>

                        <!-- Signature Products -->
                        <?php if($artisan->signature_products && count($artisan->signature_products) > 0): ?>
                            <div class="mb-3">
                                <h6 class="mb-2">Signature Products:</h6>
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    <?php $__currentLoopData = array_slice($artisan->signature_products, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-light text-dark"><?php echo e($product); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(count($artisan->signature_products) > 3): ?>
                                        <span class="badge bg-secondary">+<?php echo e(count($artisan->signature_products) - 3); ?> more</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-footer bg-white text-center">
                        <a href="<?php echo e(route('artisans.show', $artisan->id)); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> View Profile
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-3">No Artisans Found</h4>
                    <p class="text-muted">Try adjusting your search criteria or check back later.</p>
                    <a href="<?php echo e(route('artisans.index')); ?>" class="btn btn-primary">
                        <i class="fas fa-refresh"></i> Show All Artisans
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($artisans->hasPages()): ?>
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <?php echo e($artisans->withQueryString()->links()); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.hover-card {
    transition: transform 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\macos\Desktop\laravel_react_ecommerce-main\backend\resources\views/artisans/index.blade.php ENDPATH**/ ?>