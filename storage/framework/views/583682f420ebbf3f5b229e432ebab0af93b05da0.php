<?php if(session('message')): ?>
  <div class="alert alert-<?php echo e(Session::get('status')); ?> status-box alert-dismissable fade show" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;<span class="sr-only">Close</span></a>
    <?php echo e(session('message')); ?>

  </div>
<?php endif; ?>

<?php if(session('success')): ?>
  <div class="alert alert-success alert-dismissable fade show" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4><i class="icon fa fa-check fa-fw" aria-hidden="true"></i> Success</h4>
    <?php echo e(session('success')); ?>

  </div>
<?php endif; ?>

<?php if(session()->has('status')): ?>
    <?php if(session()->get('status') == 'wrong'): ?>
        <div class="alert alert-danger status-box alert-dismissable fade show" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;<span class="sr-only">Close</span></a>
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if(session('error')): ?>
  <div class="alert alert-danger alert-dismissable fade show" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4>
      <i class="icon fa fa-warning fa-fw" aria-hidden="true"></i>
      Error
    </h4>
    <?php echo e(session('error')); ?>

  </div>
<?php endif; ?>

<?php if(session('errors') && count($errors) > 0): ?>
  <div class="alert alert-danger alert-dismissable fade show" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4>
      <i class="icon fa fa-warning fa-fw" aria-hidden="true"></i>
      <strong><?php echo e(Lang::get('auth.whoops')); ?></strong> <?php echo e(Lang::get('auth.someProblems')); ?>

    </h4>
    <ul>
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>
<?php /**PATH /home/nox/Sites/domain/resources/views/partials/form-status.blade.php ENDPATH**/ ?>