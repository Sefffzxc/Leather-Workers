<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'My App'); ?></title>

    <!-- Bootstrap CSS (Optional CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (For Icons) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?> 
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo e(route('artisans.index')); ?>">CraftConnect</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('artisans.index')); ?>">Artisans</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('artisans.index')); ?>">Artisans</a></li>
                    <!-- Add more nav links if needed -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-5">
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; <?php echo e(date('Y')); ?> CraftConnect. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS (Optional CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?> 
</body>
</html>
<?php /**PATH C:\Users\macos\Desktop\laravel_react_ecommerce-main\backend\resources\views/layouts/app.blade.php ENDPATH**/ ?>