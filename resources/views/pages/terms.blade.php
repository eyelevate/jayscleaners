@extends($layout)

@section('stylesheets')

@stop

@section('scripts')

@stop

@section('navigation')
<header id="header" class="reveal">
@if (Auth::check())
{!! View::make('partials.layouts.navigation_logged_in')
    ->render()
!!}
@else
{!! View::make('partials.layouts.navigation_logged_out')
    ->render()
!!}
@endif
</header>
@stop


@section('content')
<section class="wrapper style3 container special">
	<header>
		<h2>Terms of Service & Privacy Policy</h2>
	</header>
	<ul>
		<li>All personal information provided to us will be used solely to process your order with us. We will never sell, lease, rent or in any manner provide your personal information to any 3rd parties.  We will take all necessary precautions to safeguard your personal information at all times.</li>
		<li>We will exercise the utmost care in processing your articles entrusted to us and use such processes which, in our opinion, are best suited to the condition of each individual article or garment we process.  Nevertheless, we cannot assume responsibility for inherent weaknesses or defects in materials that are not readily apparent prior to processing.  This applies particularly, but not exclusively to suedes, leathers, silks, satins, double-faced fabrics, vinyls, polyurethanes etc.  Responsiblity is also disclamed for trimmings, buckles, beads, buttons, bells and sequins. </li>
		<li>In circumstances where the article we are processing requires laundering, we will follow the instructions given on the manufacturer's label. Furthermore, in laundering we cannot guarantee against color loss and shrinkage or against damage to weak and tender fabrics that were not readily apparent prior to processing.</li>
		<li>All clothing that is left as a bundle for pickup must be placed into a bag with the proper identification: First & Last Name, Phone and Email. Unless accompanied by a detailed list or count from the Customer, our count and itemized invoice must be accepted once the order is detailed by us. Furthermore, our Company does not assume responsibility for damages, theft and or tampering of articles left for pickup or after dropoff and a safe and weather-proof area must be designated by the Customer.  We will exercise our judgement when dropping off your articles and notify you immediately should your scheduled pick-up/drop-off area not be suitable for your delivery. Differences in count prior to our itemized invoice or after the articles are delivered must be reported within 48 hours of pickup or drop off.</li>
		<li>In circumstances where articles are left at our facility over 60 days, we will take all possible measures to notify you to claim your garments.  If we do not hear from you for or your articles are not claimed within 15 days (a total of 75 days left at our facility) we will make arrangements to have your articles donated to charity.</li>
		<li>All credit card transactions will be handled with the utmost care and we will take all measures to safeguard your information at all times.</li>
		<li>Although we will take all measures to avoid losing or damaging articles entrusted to us, Returns and Refunds will be handled in person over the phone or at our Facility. We must be notified regarding damaged or lost items within 48 hours of delivery or pickup at our Facility.  Our Company's liability with respect to any lost or damaged article shall be up to $500.00 or 10 times our charge for processing the article, whichever is less.  All returns, refunds or payments made for any lost or damaged items will be made by a check issued by our Company.  Any return, refund or payment for lost or damaged items must be accompanied by a detailed invoice containing the item and the detailed invoice must issued by our Company.   </li>
	</ul>

</section>


@stop