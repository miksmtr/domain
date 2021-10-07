<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Bet Company List </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success margin-bottom-1 mb-1" href="<?php echo e(route('bet_companies.create')); ?>" title="Create a bet_company">Create</i>
                    </a>

            </div>
        </div>
    </div>



    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Status</th>
            <th width="280px">Action</th>
        </tr>
        <?php $__currentLoopData = $bet_companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bet_company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($bet_company->id); ?></td>
                <td><?php echo e($bet_company->name); ?></td>
                <td><?php echo e($bet_company->status); ?></td>
                <td>
                    <form action="<?php echo e(route('bet_companies.destroy', $bet_company->id)); ?>" method="POST">

<!--                         <a href="<?php echo e(route('bet_companies.show', $bet_company->id)); ?>" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a> -->

                        <a href="<?php echo e(route('bet_companies.edit', $bet_company->id)); ?>">
                            <i class="fa fa-pencil fa-fw "></i>

                        </a>

                        <?php echo csrf_field(); ?>
                       <!--  <?php echo method_field('DELETE'); ?>

                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                            <i class="fa fa-trash-o fa-fw text-danger"></i>

                        </button> -->
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/nox/Sites/domain/resources/views/bet_companies/index.blade.php ENDPATH**/ ?>