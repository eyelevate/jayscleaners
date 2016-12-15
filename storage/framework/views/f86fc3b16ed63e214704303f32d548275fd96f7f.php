<?php $__env->startSection('stylesheets'); ?>
<link rel="stylesheet" href="/css/pages/frontend.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="/packages/Readmore/readmore.min.js"></script>
<script type="text/javascript" src="/js/pages/index.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('navigation'); ?>
	<header id="header" class="alt">
	<?php if($auth): ?>
		<?php echo View::make('partials.layouts.navigation_logged_in')
			->render(); ?>

	<?php else: ?>
		<?php echo View::make('partials.layouts.navigation_logged_out')
			->render(); ?>

	<?php endif; ?>
	</header>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('banner'); ?>
	<?php if($auth): ?>

	<header>
		<h2>Jays Cleaners</h2>
	</header>
	<p>Welcome back <strong><?php echo e(Auth::user()->username); ?></strong>
	<br />
	Start your delivery today!
	<br /><br/>							
	<ul class="buttons vertical">
		<li><a href="<?php echo e(route('delivery_start')); ?>" class="button fit">Schedule Delivery</a></li>
		<?php if(count($schedules) > 0): ?>
		<li>
			<p><strong>OR</strong></p>
		</li>
		<li>
			<?php echo Form::open(['action' => 'PagesController@postOneTouch', 'class'=>'form-horizontal','role'=>"form"]); ?>

  			<?php echo csrf_field(); ?>

			<ul class="buttons vertical">
				<li><input data-toggle="modal" data-target="#loading" type="submit" class="button fit" value="Repeat Last Delivery"/></li>
			</ul>  			
			<?php echo Form::close(); ?>

		</li>
		<?php endif; ?>
	</ul>

	<?php else: ?>

	<?php echo Form::open(['action' => 'PagesController@postZipcodes', 'class'=>'form-horizontal','role'=>"form"]); ?>

	<?php echo csrf_field(); ?>

	<header>
		<h2>Jays Cleaners</h2>
	</header>
	<p><strong>Free</strong> delivery and pickup!
	<br />
	Start your delivery today!
	<br /><br/>
	<header>
        <?php echo Form::text('zipcode', old('zipcode'), ['placeholder'=>'Enter your zipcode', 'style'=>'background-color:#ffffff; color:#000000;']); ?>

        <?php if($errors->has('zipcode')): ?>
            <span class="help-block">
                <strong style="color:#ffffff"><?php echo e($errors->first('zipcode')); ?></strong>
            </span>
        <?php endif; ?>
	</header>							
	<ul class="buttons vertical">
		<li><input type="submit" class="button fit" text="Start"/></li>
	</ul>
	<?php echo Form::close(); ?>


	<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<header class="special container">
	<span class="icon fa-home fa-fw" style="font-size:25px;"></span>
	<h2>Welcome to Jays Cleaners. With over <strong>70 years</strong> of experience, <strong>let us work for you</strong>.</h2>
</header>
<section class="parallax-window" data-parallax="scroll" data-image-src="/imgs/website/display-4.png" style="min-height:300px;"></section>
<!-- Two -->
<br/>
<section class="wrapper style1 container special clearfix">
	<div class="row">
		<div class="4u 12u(narrower)">

			<section class="read_articles" style="background-color:#F3F6FA; padding: 5px;">
				
				<header>
					<span class="icon featured fa-history"></span>
					<h3>About Us</h3>
				</header>
				<p>
					Jays Cleaners was established in the Greenlake neighborhood of Seattle over 70 years ago. 
					Family run and operated, we at Jays Cleaners have always held the belief that the Customer expects and deserves the best and it is our duty to deliver the best.
				</p>
				<p>
					We at Jays Cleaners are relentlessly setting and maintaining high standards for quality, continuously implementing industry best practices and always paying careful attention to the many details of what makes a quality finished product (ie. stain removal, replacing cracked or chipped buttons, sewing loose hems, scrubbing collars, etc). We are always striving to deliver the best quality services, on-time, every time. 
					Our goal is simple: 100% Customer Satisfaction!
				</p>
				<a href="https://www.yelp.com/biz/jays-dry-cleaners-roosevelt-seattle" class="btn btn-lg btn-info">Read Our Reviews</a>
			</section>

		</div>
		<div class="4u 12u(narrower)">

			<section class="read_articles" style="background-color:#F3F6FA; padding: 5px;">
				
				<header>
					<span class="icon featured fa-check-square-o"></span>
					<h3>THE HEALTHY CLEANING ALTERNATIVE</h3>
				</header>
				<p>Jays Cleaners utilizes the SystemK4 cleaning system which is a Toxin-Free, Environmentally Safe and Healthy cleaning system. Unlike the cleaning methods of the past which relied heavily on Perchloroethylene (Perc), SystemK4 utilizes a perc-free/halogen-free, organic solvent and has been tested to be dermatologically safe, biodegradable and provides an excellent, odorless finish to every garment. </p>
				<a href="http://www.systemk4.com/en/" target="__blank" class="btn btn-lg btn-info">Learn More</a>
			</section>

		</div>
		<div class="4u 12u(narrower)">

			<section class="read_articles" style="background-color:#F3F6FA; padding:5px;">
				
				<header>
					<span class="icon featured fa-truck"></span>
					<h3>Delivery Specialists</h3>
				</header>
				<p>Create an Account using our <a href="<?php echo e(route('pages_registration')); ?>">Sign Up</a> page and set up a delivery schedule today. Returning Members can simply <a href="<?php echo e(route('pages_login')); ?>">Login</a> to schedule a delivery.</p>  
				<p>Special Instructions for any article or garment and delivery location (concierge, front porch, etc) can be included on each delivery schedule. Once your finished, we will send you an email confirmation.</p>
				<a href="<?php echo e(route('delivery_pickup')); ?>" class="btn btn-lg btn-info">Schedule A Delivery</a>
			</section>

		</div>

	</div>
</section>

<div class="row-fluid">
	<article class="parallax-window" data-parallax="scroll" data-image-src="/imgs/website/display-5.png" style="min-height:300px; position:relative;"></article>

	<!-- One -->
	<section class="wrapper style3 container special">
		
		<div class="row">
			<header class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
				<span class="icon featured fa-map-o"></span>
				<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Where to find us</h3>
			</header>
			<section class="clearfix">
			<p>
				We proudly serve the Seattle region at our conveniently located Montlake and Roosevelt locations.  Additionally, we offer free pickup/delivery to local Seattle neighborhood
				homes, offices, business and more.
			</p>
			<?php if(count($companies) > 0): ?>
				<?php foreach($companies as $company): ?>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="padding-bottom:30px;">
					<div style="margin-bottom:10px;">
						<address>
							<strong class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><?php echo e($company->name); ?></strong>
							<span class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><?php echo e($company->street); ?></span>
							<span class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><?php echo e($company->city); ?>, <?php echo e($company->state); ?> <?php echo e($company->zipcode); ?></span>
							<span class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><?php echo e($company->phone); ?></span>

						</address>
						<a href="<?php echo e($company->map); ?>" class="btn btn-warning btn-lg"><i class="fa fa-map-marker"></i>&nbsp;Directions</a>
					</div>

					
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
			</section>
		</div>
		<div id="store_hours" class="row">
			<header class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">
				<span class="icon featured fa-clock-o"></span>
				<h3 class="wrapper style2 special-alt col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:5px; padding-bottom:5px; margin-bottom:10px;">Store Hours</h3>
			</header>
			<section class="clearfix col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<div class="table-responsive">
					<table class="table table-condensed">	
						<thead>
							<tr>
								<th style="text-align:right;"><strong>Day</strong></th>
								<th style="text-align:center;"><strong>Hours</strong></th>
								<th style="text-align:left;"><strong>Currently</strong></th>
							</tr>
						</thead>
						<tbody>
						<?php if(count($companies) > 0): ?>
							<?php foreach($companies as $company): ?>
								<?php if(count($company->store_hours) > 0 && $company->id == 1): ?>
									<?php foreach($company->store_hours as $key => $value): ?>
										<?php if(date('l') == $key): ?>
										<tr class="warning" style="color:#5e5e5e; font-weight:bold;">
											<th style="text-align:right;"><strong><?php echo e($key); ?></strong></th>
											<td style="text-align:center;"><strong><?php echo e($value); ?></strong></td>
											<td style="text-align:left;"><strong style="color:<?php echo e($company['open_status'] ? 'green' : 'red'); ?>;"><?php echo e($company['open_status'] ? 'Open' : 'Closed'); ?></strong></td>
										</tr>
										<?php else: ?>
										<tr>
											<th style="text-align:right;"><?php echo e($key); ?></th>
											<td style="text-align:center;"><?php echo e($value); ?></td>
											<td style="text-align:left;"></td>
										</tr>
										<?php endif; ?>
									
									<?php endforeach; ?>
								<?php endif; ?>
			
							<?php endforeach; ?>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</section>


		</div>


	</section>
</div>
<section class="parallax-window" data-parallax="scroll" data-image-src="/imgs/website/display-2.png" style="min-height:300px;" ></section>
<?php echo View::make('partials.pages.services')->render(); ?>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
	<?php echo View::make('partials.frontend.modals')->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make($layout, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>