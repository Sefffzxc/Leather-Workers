<?php $__env->startSection('title'); ?>
    Reviews
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('admin.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="col-md-9">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="mt-2">Reviews (<?php echo e($reviews->count()); ?>)</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Body</th>
                                    <th scope="col">Rating</th>
                                    <th scope="col">Approved</th>
                                    <th scope="col">By</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Created</th>
                                    <th scope="col" data-sortable="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td scope="col"><?php echo e($key += 1); ?></td>
                                        <td scope="col"><?php echo e($review->title); ?></td>
                                        <td scope="col"><?php echo e($review->body); ?></td>
                                        <td scope="col"><?php echo e($review->rating); ?></td>
                                        <td scope="col">
                                            <?php if($review->approved): ?>
                                                <span class="badge bg-success">
                                                    Yes
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    No
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td scope="col"><?php echo e($review->user->name); ?></td>
                                        <td scope="col">
                                            <img src="<?php echo e(asset($review->product->thumbnail)); ?>" class="rounded"
                                                width="60" height="60" alt="<?php echo e($review->product->name); ?>">
                                        </td>
                                        <td scope="col"><?php echo e($review->created_at); ?></td>
                                        <td>
                                            <?php if($review->approved): ?>
                                                <a href="<?php echo e(route('admin.reviews.update', ['review' => $review->id, 'status' => 0])); ?>"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fas fa-eye-slash"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('admin.reviews.update', ['review' => $review->id, 'status' => 1])); ?>"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-check-double"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="#" onclick="deleteItem(<?php echo e($review->id); ?>)"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="<?php echo e($review->id); ?>"
                                                action="<?php echo e(route('admin.reviews.delete', $review->id)); ?>" method="post">
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\macos\Desktop\laravel_react_ecommerce-main\backend\resources\views/admin/reviews/index.blade.php ENDPATH**/ ?>