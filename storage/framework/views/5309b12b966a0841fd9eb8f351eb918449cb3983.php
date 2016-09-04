<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/packages/zebra_datepicker/public/css/bootstrap.css" type="text/css">
<link rel="stylesheet" href="/css/deliveries/delivery.css" type="text/css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/js/deliveries/confirmation.js"></script>
<script type="text/javascript">

</script>
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
        <div id="bc1" class="btn-group btn-breadcrumb col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <a href="<?php echo e(route('delivery_pickup')); ?>" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px; overflow:hidden">
            	<h2><span class="badge">1</span> Pickup</h2>
        		<table class="table table-condensed ">
        			<tbody>
        				<tr>
        					<td><p style="margin:0;"><?php echo e($breadcrumb_data['pickup_address']); ?></p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0"><?php echo e($breadcrumb_data['pickup_date']); ?></p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0"><?php echo e($breadcrumb_data['pickup_time']); ?></p></td>
        				</tr>
        			</tbody>
        		</table>
            </a>
            <a href="<?php echo e(route('delivery_dropoff')); ?>" class="btn btn-default col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px; overflow:hidden">
            	<h2><span class="badge">2</span> Dropoff</h2>
            	<table class="table table-condensed ">
        			<tbody>
        				<tr>
        					<td><p style="margin:0;"><?php echo e($breadcrumb_data['dropoff_address']); ?></p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0"><?php echo e($breadcrumb_data['dropoff_date']); ?></p></td>
        				</tr>
        				<tr>
        					<td><p style="margin:0"><?php echo e($breadcrumb_data['dropoff_time']); ?></p></td>
        				</tr>
        			</tbody>
        		</table>
            </a>
            <a href="<?php echo e(route('delivery_confirmation')); ?>" class="btn btn-default active col-lg-4 col-md-4 col-sm-4 col-xs-12" style="height:160px">
            	<h2><span class="badge">3</span> Confirm</h2>
            	<p>Not yet confirmed.</p>
            </a>

        </div>
	</div>
	<div class="row"><p></p></div>
	<?php if(count($cards) > 0): ?>
    <div class="wrapper style2 special-alt no-background-image">
    	<div class="row 50%">
            <div class="8u">
                <header>
                    <h2>Outstanding! <strong></strong> is covered by our delivery routes!</h2>
                </header>
                <p>Click Here To learn more about our delivery system and how we can provide our quality and price guarantee. </p>
                <footer>
                    <ul class="buttons">
                        <li><a href="<?php echo e(route('pages_registration')); ?>" class="button">Get Started</a></li>
                    </ul>
                </footer>
            </div>
            <div class="4u">
                <ul class="featured-icons">
                    <li><span class="icon fa-clock-o"><span class="label">Feature 1</span></span></li>
                    <li><span class="icon fa-volume-up"><span class="label">Feature 2</span></span></li>
                    <li><span class="icon fa-laptop"><span class="label">Feature 3</span></span></li>
                    <li><span class="icon fa-inbox"><span class="label">Feature 4</span></span></li>
                    <li><span class="icon fa-lock"><span class="label">Feature 5</span></span></li>
                    <li><span class="icon fa-cog"><span class="label">Feature 6</span></span></li>
                </ul>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="wrapper style3 special-alt no-background-image">
        <div class="row 50%">
            <div class="8u">
                <header>
                    <h2>No credit card on file!</h2>
                </header>
                <p>In order for us to confirm your delivery schedule we must have at least one qualified credit card on file. Please use the link below to setup your stored credit card information.</p>
                <footer>
                    <ul class="buttons">
                        <li><a href="<?php echo e(route('cards_index')); ?>" class="button">Manage Credit Cards</a></li>
                    </ul>
                </footer>
            </div>
            <div class="4u">
                <ul class="featured-icons">
                    <li><span class="icon fa-clock-o"><span class="label">Feature 1</span></span></li>
                    <li><span class="icon fa-volume-up"><span class="label">Feature 2</span></span></li>
                    <li><span class="icon fa-laptop"><span class="label">Feature 3</span></span></li>
                    <li><span class="icon fa-inbox"><span class="label">Feature 4</span></span></li>
                    <li><span class="icon fa-lock"><span class="label">Feature 5</span></span></li>
                    <li><span class="icon fa-cog"><span class="label">Feature 6</span></span></li>
                </ul>
            </div>
        </div>
    </div>
	<?php endif; ?>		


<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>