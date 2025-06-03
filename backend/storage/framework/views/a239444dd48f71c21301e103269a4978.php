<?php $__env->startSection('title'); ?>
    Orders
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('admin.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="col-md-9">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="mt-2">Orders (<?php echo e($orders->count()); ?>)</h3>
                    </div>
                    <hr>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Product Price</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Coupon</th>
                                    <th scope="col">By</th>
                                    <th scope="col">Done</th>
                                    <th scope="col">Delivered</th>
                                    <th scope="col" data-sortable="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td scope="col"><?php echo e($key += 1); ?></td>
                                        <td scope="col">
                                            <div class="d-flex flex-column">
                                                <?php $__currentLoopData = $order->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php echo e($product->name); ?>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </td>
                                        <td scope="col">
                                            <div class="d-flex flex-column">
                                                <?php $__currentLoopData = $order->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php echo e($product->price); ?>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </td>
                                        <td scope="col">
                                            <div class="d-flex flex-column">
                                                <?php echo e($order->qty); ?>

                                            </div>
                                        </td>
                                        <td scope="col">
                                            <?php echo e($order->total); ?>

                                        </td>
                                        <td>
                                            <?php if($order->coupon()->exists()): ?>
                                                <span class="badge bg-success">
                                                    <?php echo e($order->coupon->name); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">
                                                    N/A
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td scope="col">
                                            <?php echo e($order->user->name); ?>

                                        </td>
                                        <td scope="col">
                                            <?php echo e($order->created_at); ?>

                                        </td>
                                        <td scope="col">
                                            <?php if($order->delivered_at): ?>
                                                <span class="badge bg-success">
                                                    <?php echo e(\Carbon\Carbon::parse($order->delivered_at)->diffForHumans()); ?>

                                                </span>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('admin.orders.update', $order->id)); ?>">
                                                    <i class="fas fa-pencil mx-2"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="#" onclick="deleteItem(<?php echo e($order->id); ?>)"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="<?php echo e($order->id); ?>"
                                                action="<?php echo e(route('admin.orders.delete', $order->id)); ?>" method="post">
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\macos\Desktop\laravel_react_ecommerce-main\backend\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>