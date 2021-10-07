<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php if (! empty(trim($__env->yieldContent('template_title')))): ?><?php echo $__env->yieldContent('template_title'); ?> | <?php endif; ?> <?php echo e(config('app.name', Lang::get('titles.app'))); ?></title>
        <meta name="description" content="">
        <meta name="author" content="Jeremy Kenedy">
        <link rel="shortcut icon" href="/favicon.ico">
<script type="text/javascript" src="<?php echo e(asset('js/jquery.js')); ?>"></script>

<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>



        
        <?php echo $__env->yieldContent('template_linked_fonts'); ?>
<style>
    #cke_1_contents, #cke_2_contents {
    min-height: 800px !important;
}
</style>



        
        <link href="<?php echo e(mix('/css/app.css')); ?>" rel="stylesheet">
    
        <?php echo $__env->yieldContent('template_linked_css'); ?>

        <style type="text/css">
            <?php echo $__env->yieldContent('template_fastload_css'); ?>

            <?php if(Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0)): ?>
                .user-avatar-nav {
                    background: url(<?php echo e(Gravatar::get(Auth::user()->email)); ?>) 50% 50% no-repeat;
                    background-size: auto 100%;
                }
            <?php endif; ?>

        </style>

        
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>;
        </script>

        <?php if(Auth::User() && (Auth::User()->profile) && $theme->link != null && $theme->link != 'null'): ?>
            <link rel="stylesheet" type="text/css" href="<?php echo e($theme->link); ?>">
        <?php endif; ?>

        <?php echo $__env->yieldContent('head'); ?>
        <?php echo $__env->make('scripts.ga-analytics', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </head>
    <body>
        <div id="app">

            <?php echo $__env->make('partials.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <main class="pl-5 pr-5">

                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <?php echo $__env->make('partials.form-status', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    </div>
                </div>

                <?php echo $__env->yieldContent('content'); ?>

            </main>

        </div>

        
        <script src="<?php echo e(mix('/js/app.js')); ?>"></script>

        <?php if(config('settings.googleMapsAPIStatus')): ?>
            <?php echo HTML::script('//maps.googleapis.com/maps/api/js?key='.config("settings.googleMapsAPIKey").'&libraries=places&dummy=.js', array('type' => 'text/javascript')); ?>

        <?php endif; ?>

        <?php echo $__env->yieldContent('footer_scripts'); ?>

    </body>
</html>
<?php /**PATH /home/nox/Sites/domain/resources/views/layouts/app.blade.php ENDPATH**/ ?>