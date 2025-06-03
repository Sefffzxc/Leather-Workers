<?php $__env->startSection('title'); ?>
    Coupons
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('admin.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="col-md-9">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="mt-2">Coupons (<?php echo e($coupons->count()); ?>)</h3>
                        <a href="<?php echo e(route('admin.coupons.create')); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                    <hr>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Discount</th>
                                    <th scope="col">Validity</th>
                                    <th scope="col" data-sortable="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"><?php echo e($key += 1); ?></th>
                                        <td><?php echo e($coupon->name); ?></td>
                                        <td><?php echo e($coupon->discount); ?></td>
                                        <td>
                                            <?php if($coupon->checkIfValid()): ?>
                                                <span class="bg-success border border-dark p-1 text-white">
                                                    Valid until
                                                    <?php echo e(\Carbon\Carbon::parse($coupon->valid_until)->diffForHumans()); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="bg-danger border border-dark p-1 text-white">
                                                    Expired
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.coupons.edit', $coupon->id)); ?>"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" onclick="deleteItem(<?php echo e($coupon->id); ?>)"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="<?php echo e($coupon->id); ?>"
                                                action="<?php echo e(route('admin.coupons.destroy', $coupon->id)); ?>" method="post">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\macos\Desktop\laravel_react_ecommerce-main\backend\resources\views/admin/coupons/index.blade.php ENDPATH**/ ?>