<?php $__env->startSection('title'); ?>
    Products
<?php $__env->stopSection(); ?>
 
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('admin.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="col-md-9">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="mt-2">Products (<?php echo e($products->count()); ?>)</h3>
                        <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-sm btn-primary">
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
                                    <th scope="col">Colors</th>
                                    <th scope="col">Sizes</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Price</th>
                                    <th scope="col" data-sortable="false">Images</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" data-sortable="false"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"><?php echo e($key += 1); ?></th>
                                        <td><?php echo e($product->name); ?></td>
                                        <td>
                                            <?php $__currentLoopData = $product->colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-light text-dark">
                                                    <?php echo e($color->name); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td>
                                            <?php $__currentLoopData = $product->sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-light text-dark">
                                                    <?php echo e($size->name); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td><?php echo e($product->qty); ?></td>
                                        <td><?php echo e($product->price); ?></td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <img src="<?php echo e(asset($product->thumbnail)); ?>" alt="<?php echo e($product->name); ?>"
                                                    class="img-fluid rounded mb-1" width="30" height="30">
                                                <?php if($product->first_image): ?>
                                                    <img src="<?php echo e(asset($product->first_image)); ?>"
                                                        alt="<?php echo e($product->name); ?>" class="img-fluid rounded mb-1"
                                                        width="30" height="30">
                                                <?php endif; ?>
                                                <?php if($product->second_image): ?>
                                                    <img src="<?php echo e(asset($product->second_image)); ?>"
                                                        alt="<?php echo e($product->name); ?>" class="img-fluid rounded mb-1"
                                                        width="30" height="30">
                                                <?php endif; ?>
                                                <?php if($product->third_image): ?>
                                                    <img src="<?php echo e(asset($product->third_image)); ?>"
                                                        alt="<?php echo e($product->name); ?>" class="img-fluid rounded" width="30"
                                                        height="30">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($product->status): ?>
                                                <span class="badge bg-success p-2">
                                                    In Stock
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger p-2">
                                                    Out of Stock
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-flex">
                                            <a href="<?php echo e(route('admin.products.edit', $product->slug)); ?>"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" onclick="deleteItem(<?php echo e($product->id); ?>)"
                                                class="btn btn-sm btn-danger mx-1">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="<?php echo e($product->id); ?>"
                                                action="<?php echo e(route('admin.products.destroy', $product->slug)); ?>"
                                                method="post">
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\macos\Desktop\laravel_react_ecommerce-main\backend\resources\views/admin/products/index.blade.php ENDPATH**/ ?>