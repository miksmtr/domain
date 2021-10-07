<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Domain List </h2>
            </div>
           
        </div>
    </div>



    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Hosting</th>
            <th>Status</th>
            <th>Activation Date</th>
            <th width="280px">Action</th>
        </tr>
        <?php $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($domain->id); ?></td>
                <td><?php echo e($domain->name); ?></td>
                <td><?php echo e($domain->hosting); ?></td>
                <?php if($domain->status === -1): ?>
                <td style="background-color:orange;color:white;">Hata var!!!</td>
                <?php elseif($domain->status === 0): ?>
                <td style="background-color:green;color:white;">Aktif</td>
                <?php elseif($domain->status === 1): ?>
                <td style="background-color:red;color:white;">Deaktif</td>
                <?php elseif($domain->status === 2): ?>
                <td style="background-color:blue;color:white;">Taşınmış</td>
                <?php elseif($domain->status === 3): ?>
                <td style="background-color:red;color:white;">Deaktif - Mail gönderildi.</td>
                <?php endif; ?>
                <td><?php echo e($domain->created_at); ?></td>
                <td>
                    <form action="<?php echo e(route('domains.destroy', $domain->id)); ?>" method="POST">

<!--                         <a href="<?php echo e(route('domains.show', $domain->id)); ?>" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a> -->

                        <a href="<?php echo e(route('domains.edit', $domain->id)); ?>">
                            <i class="fa fa-pencil fa-fw "></i>

                        </a>

                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>

                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                            <i class="fa fa-trash-o fa-fw text-danger"></i>

                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/nox/Sites/domain/resources/views/domains/index.blade.php ENDPATH**/ ?>