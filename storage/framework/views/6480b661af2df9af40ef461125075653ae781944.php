<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="pl-5 pr-5">
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            <span class="sr-only"><?php echo trans('titles.toggleNav'); ?></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
            <ul class="navbar-nav mr-auto">
           
        <li>
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
            Bahisrator
        </a>

        </li>
                <?php if (Auth::check() && Auth::user()->hasRole('admin')): ?>
                <li class="nav-item dropdown">
                    
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo trans('titles.adminDropdownNav'); ?>

                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
             
                        <a class="dropdown-item <?php echo e((Request::is('server_setting')) ? 'active' : null); ?>" href="<?php echo e(route('server_setting')); ?>">
                           Server Status
                        </a>
                        <div class="dropdown-divider"></div>
                               <a class="dropdown-item <?php echo e((Request::is('roles') || Request::is('permissions')) ? 'active' : null); ?>" href="<?php echo e(route('laravelroles::roles.index')); ?>">
                            <?php echo trans('titles.laravelroles'); ?>

                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item <?php echo e(Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'active' : null); ?>" href="<?php echo e(url('/users')); ?>">
                            <?php echo trans('titles.adminUserList'); ?>

                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item <?php echo e(Request::is('users/create') ? 'active' : null); ?>" href="<?php echo e(url('/users/create')); ?>">
                            <?php echo trans('titles.adminNewUser'); ?>

                        </a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item <?php echo e(Request::is('logs') ? 'active' : null); ?>" href="<?php echo e(url('/logs')); ?>">
                            <?php echo trans('titles.adminLogs'); ?>

                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item <?php echo e(Request::is('activity') ? 'active' : null); ?>" href="<?php echo e(url('/activity')); ?>">
                            <?php echo trans('titles.adminActivity'); ?>

                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item <?php echo e(Request::is('logging') ? 'active' : null); ?>" href="<?php echo e(url('/logging')); ?>">
                            Server Logger
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item <?php echo e(Request::is('phpinfo') ? 'active' : null); ?>" href="<?php echo e(url('/phpinfo')); ?>">
                            <?php echo trans('titles.adminPHP'); ?>

                        </a>


                    </div>
                </li>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasRole('admin')): ?>
                <a class="nav-link " href="/codes" role="button" aria-haspopup="true" aria-expanded="false">
                    Codes
                </a>
                <?php endif; ?>
                <?php if(auth()->guard()->check()): ?>
                <a class="nav-link " href="/bet_companies" role="button" aria-haspopup="true" aria-expanded="false">
                    Bet Companies
                </a>
                <?php endif; ?>
                <?php if (Auth::check() && Auth::user()->hasRole('admin')): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDomainsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Domains
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDomainsDropdown">
                        <a class="dropdown-item <?php echo e((Request::is('un_used_domain_index') || Request::is('permissions')) ? 'active' : null); ?>" href="<?php echo e(route('un_used_domain_index')); ?>">
                            Unused Domains
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item <?php echo e((Request::is('movable_and_used_domain_index') || Request::is('permissions')) ? 'active' : null); ?>" href="<?php echo e(route('movable_and_used_domain_index')); ?>">
                            Running Domains
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item <?php echo e((Request::is('domains') || Request::is('permissions')) ? 'active' : null); ?>" href="<?php echo e(url('/domains')); ?>">
                            Unmovable Running Domains
                        </a>
                        <div class="dropdown-divider"></div>



                    </div>
                </li>
                <?php endif; ?>

                <?php if(auth()->guard()->check()): ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDomainsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Contents
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDomainsDropdown">
                        
                        <a class="nav-link " href="/contents" role="button" aria-haspopup="true" aria-expanded="false">
                        Content List
                </a>
                        <div class="dropdown-divider"></div>
                        <a class="nav-link " href="/websites" role="button" aria-haspopup="true" aria-expanded="false">
                    Website Picker
                </a>
                        <div class="dropdown-divider"></div>
                        


                    </div>
                </li>
                <?php endif; ?>
                



            </ul>
            
            <ul class="navbar-nav ml-auto">
                
                <?php if(auth()->guard()->guest()): ?>
                <li><a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(trans('titles.login')); ?></a></li>

                <?php else: ?>
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <?php if((Auth::User()->profile) && Auth::user()->profile->avatar_status == 1): ?>
                        <img src="<?php echo e(Auth::user()->profile->avatar); ?>" alt="<?php echo e(Auth::user()->name); ?>" class="user-avatar-nav">
                        <?php else: ?>
                        <div class="user-avatar-nav"></div>
                        <?php endif; ?>
                        <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item <?php echo e(Request::is('profile/'.Auth::user()->name, 'profile/'.Auth::user()->name . '/edit') ? 'active' : null); ?>" href="<?php echo e(url('/profile/'.Auth::user()->name)); ?>">
                            <?php echo trans('titles.profile'); ?>

                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                            <?php echo e(__('Logout')); ?>

                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav><?php /**PATH /home/nox/Sites/domain/resources/views/partials/nav.blade.php ENDPATH**/ ?>