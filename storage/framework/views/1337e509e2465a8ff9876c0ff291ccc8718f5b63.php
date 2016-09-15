<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>
<script type="text/javascript" src="/js/pages/register.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>

<header id="header" class="reveal">
    <h1 id="logo"><a href="<?php echo e(route('pages_index')); ?>" class="nav-colors">Jays Cleaners</a></h1>
    <nav id="nav">
        <ul>
            <li class="submenu nav-colors">
                <a href="#">About Us</a>
                <ul>
                    <li><a href="<?php echo e(route('delivery_pickup')); ?>" >Schedule Delivery</a></li>
                    <li><a href="left-sidebar.html">Services</a></li>
                    <li><a href="right-sidebar.html">Business Hours</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Registration Form</div>
                <div class="panel-body">
                    <?php echo Form::open(['action' => 'PagesController@postRegistration', 'class'=>'form-horizontal','role'=>"form"]); ?>

                        <?php echo csrf_field(); ?>


                        <div class="form-group<?php echo e($errors->has('first_name') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">First Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="<?php echo e(old('first_name')); ?>" placeholder="e.g. Jane">

                                <?php if($errors->has('first_name')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('first_name')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('last_name') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Last Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="last_name" value="<?php echo e(old('last_name')); ?>" placeholder="e.g. Doe">

                                <?php if($errors->has('last_name')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('last_name')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Phone Number</label>

                            <div class="col-md-6">
                                <input type="text" class="phone form-control"  data-mask="(000) 000-0000" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="format (XXX) XXX-XXXX">

                                <?php if($errors->has('phone')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('phone')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('username') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Username</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="username" value="<?php echo e(old('username')); ?>" placeholder="minimum 5 alpha-numeric characters">

                                <?php if($errors->has('username')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('username')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Email</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" placeholder="e.g. jane.doe@jayscleaners.com">

                                <?php if($errors->has('email')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('email')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" placeholder="minimum 6 characters">

                                <?php if($errors->has('password')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('password')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('password_confirmation') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Password Confirmation</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password_confirmation">

                                <?php if($errors->has('password_confirmation')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('password_confirmation')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-lg btn-primary">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>