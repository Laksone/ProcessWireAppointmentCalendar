<?php namespace ProcessWire;
	/** @var string $pageTitle */
	/** @var int $bookingsCount */
?>


<div class="appointment-calendar-row">

	<div class="appointment-calendar-col">
		<div class="appointment-calendar-card appointment-calendar-text-center appointment-calendar-position-relative">
			<div class="appointment-calendar-card-icon">
				<i class="fa fa-calendar"></i>
			</div>
			<div class="appointment-calendar-card-title">
				Bookings
			</div>

			<p class="appointment-calendar-card-text">
				You have <?= $bookingsCount; ?> upcoming bookings.
			</p>

			<a href="bookings/" class="appointment-calendar-btn appointment-calendar-btn-primary appointment-calendar-link-stretched">
				View bookings
			</a>
		</div>
	</div>

	<div class="appointment-calendar-col">
		<div class="appointment-calendar-card appointment-calendar-text-center appointment-calendar-position-relative">
			<div class="appointment-calendar-card-icon">
				<i class="fa fa-stethoscope"></i>
			</div>
			<div class="appointment-calendar-card-title">
				Services
			</div>

			<p class="appointment-calendar-card-text">
				Manage your services here.
			</p>

			<a href="services/" class="appointment-calendar-btn appointment-calendar-btn-primary appointment-calendar-link-stretched">
				View services
			</a>
		</div>
	</div>

	<div class="appointment-calendar-col">
		<div class="appointment-calendar-card appointment-calendar-text-center appointment-calendar-position-relative">
			<div class="appointment-calendar-card-icon">
				<i class="fa fa-users"></i>
			</div>
			<div class="appointment-calendar-card-title">
				Employees
			</div>

			<p class="appointment-calendar-card-text">
				Manage your employees here.
			</p>

			<a href="employees/" class="appointment-calendar-btn appointment-calendar-btn-primary appointment-calendar-link-stretched">
				View employees
			</a>
		</div>
	</div>

	<div class="appointment-calendar-col">
		<div class="appointment-calendar-card appointment-calendar-text-center appointment-calendar-position-relative">
			<div class="appointment-calendar-card-icon">
				<i class="fa fa-map-marker"></i>
			</div>
			<div class="appointment-calendar-card-title">
				Locations
			</div>

			<p class="appointment-calendar-card-text">
				Manage your locations here.
			</p>

			<a href="locations/" class="appointment-calendar-btn appointment-calendar-btn-primary appointment-calendar-link-stretched">
				View locations
			</a>
		</div>
	</div>


</div>