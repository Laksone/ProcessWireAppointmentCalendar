<?php namespace ProcessWire;
	/** @var array $bookings */
	/** @var $form */
?>

<?= $form; ?>

<?php foreach ( $bookings as $booking ): ?>
	<div class="appointment-calendar-card appointment-calendar-position-relative">

		<div class="appointment-calendar-d-flex">

			<div>
				<div class="appointment-calendar-datebox">
					<div class="appointment-calendar-datebox-day">
						<?= date( 'd', $booking->appointment_calendar_booking_date ) ?>
					</div>
					<div class="appointment-calendar-datebox-month">
						<?= date( 'M', $booking->appointment_calendar_booking_date ) ?>
					</div>
					<div class="appointment-calendar-datebox-year">
						<?= date( 'Y', $booking->appointment_calendar_booking_date ) ?>
					</div>
				</div>
			</div>

			<div style="width: 100%;">
				<ul class="fa-ul" style="margin-bottom: 0;">
					<li>
						<i class="fa-li fa fa-clock-o"></i>
						<?= date( 'H:i', $booking->appointment_calendar_booking_time ) ?> h
					</li>
					<li>
						<i class="fa-li fa fa-user-md"></i>
						<?= $booking->appointment_calendar_booking_employee->title ?>
					</li>
					<li>
						<i class="fa-li fa fa-user"></i>
						<?= $booking->appointment_calendar_booking_first_name ?> <?= $booking->appointment_calendar_booking_last_name ?>
					</li>
					<li>
						<i class="fa-li fa fa-stethoscope"></i>
						<?= $booking->appointment_calendar_booking_service->title ?>
					</li>
					<li>
						<i class="fa-li fa fa-map-marker"></i>
						<?= $booking->appointment_calendar_booking_location->title ?>
					</li>
				</ul>
			</div>
			<div>
				<a class="appointment-calendar-btn appointment-calendar-btn-sm appointment-calendar-btn-primary appointment-calendar-link-stretched" href='<?= $booking->editUrl ?>'>
					<i class="fa fa-fw fa-pencil"></i>
				</a>
			</div>
		</div>
	</div>
<?php endforeach; ?>
