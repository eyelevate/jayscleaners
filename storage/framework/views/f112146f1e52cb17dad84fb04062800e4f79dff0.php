<?php $__env->startSection('stylesheets'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/mask/mask.min.js"></script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>

    <header id="header" class="reveal">
        <h1 id="logo"><a href="<?php echo e(route('pages_index')); ?>">Jays Cleaners</a></h1>
        <nav id="nav">
            <ul>
                <li class="submenu">
                    <a href="#"><small>Hello </small><strong><?php echo e(Auth::user()->username); ?></strong></a>
                    <ul>
                        <li><a href="no-sidebar.html">Your Deliveries</a></li>
                        <li><a href="left-sidebar.html">Services</a></li>
                        <li><a href="right-sidebar.html">Business Hours</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                        <li class="submenu">
                            <a href="#"><?php echo e(Auth::user()->username); ?> menu</a>
                            <ul>
                                <li><a href="#">Dolore Sed</a></li>
                                <li><a href="#">Consequat</a></li>
                                <li><a href="#">Lorem Magna</a></li>
                                <li><a href="#">Sed Magna</a></li>
                                <li><a href="#">Ipsum Nisl</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a id="logout_button" href="#" class="button special">Logout</a>
                    <?php echo Form::open(['action' => 'PagesController@postLogout', 'id'=>'logout_form', 'class'=>'form-horizontal','role'=>"form"]); ?>

                    <?php echo Form::close(); ?>

                </li>
            </ul>
        </nav>
    </header>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">Credit Card Form</div>
                <div class="panel-body">
                    <?php echo Form::open(['action' => 'CardsController@postAdd', 'class'=>'form-horizontal','role'=>"form"]); ?>

                        <?php echo csrf_field(); ?>

                        <div class="form-group<?php echo e($errors->has('first_name') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Billing First Name <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="first_name" value="<?php echo e(old('first_name')); ?>" placeholder="e.g. John">

                                <?php if($errors->has('first_name')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('first_name')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('last_name') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Billing Last Name <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="last_name" value="<?php echo e(old('last_name')); ?>" placeholder="e.g. Doe">

                                <?php if($errors->has('last_name')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('last_name')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('street') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Billing Street <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="street" value="<?php echo e(old('street')); ?>" placeholder="e.g. 12345 1st Ave. N">

                                <?php if($errors->has('street')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('street')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('suite') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Billing Suite</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control"  name="suite" value="<?php echo e(old('suite')); ?>" placeholder="e.g. 201A">

                                <?php if($errors->has('suite')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('suite')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('city') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Billing City <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control"  name="city" value="<?php echo e(old('city')); ?>" placeholder="e.g. Seattle">

                                <?php if($errors->has('city')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('city')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('state') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Billing State <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                
                                <?php echo e(Form::select('state',$states,old('state'),['class'=>'form-control'])); ?>

                                <?php if($errors->has('state')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('state')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('zipcode') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Billing Zipcode <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="zipcode" value="<?php echo e(old('zipcode')); ?>" placeholder="e.g. 98115">

                                <?php if($errors->has('zipcode')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('zipcode')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('card') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Credit Card Number <span style="color:#ff0000">*</span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="card" value="<?php echo e(old('card')); ?>" placeholder="format. XXXX XXXX XXXX XXXX">

                                <?php if($errors->has('card')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('card')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group<?php echo e($errors->has('year') ? ' has-error' : ''); ?>">
                            <label class="col-md-4 control-label padding-top-none">Expiration <span style="color:#ff0000">*</span></label>

                            <div class="col-md-4">
                                <input type="text" class="form-control" name="year" value="<?php echo e(old('year')); ?>" placeholder="format. YYYY">

                                <?php if($errors->has('year')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('year')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="month" value="<?php echo e(old('month')); ?>" placeholder="format. MM">

                                <?php if($errors->has('month')): ?>
                                    <span class="help-block">
                                        <strong><?php echo e($errors->first('month')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4 clearfix">
                            	<a href="<?php echo e(route('cards_index')); ?>" class="btn btn-danger btn-lg">Cancel</a>
                                <button type="submit" data-toggle="modal" data-target="#loading" class="btn btn-lg btn-primary pull-right">Add</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('modals'); ?>
    <?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>