<?php namespace ProcessWire;
	/** @var array $services */
?>

<?php foreach ( $services as $service ): ?>

	<div class="appointment-calendar-card appointment-calendar-position-relative">
		<div class="appointment-calendar-d-flex">
			<div style="padding: 0 1.5rem 0 0; width: 100%;">
				<ul class="fa-ul" style="margin-bottom: 0;">
					<li>
						<i class="fa-li fa fa-stethoscope"></i>
						<strong><?= $service->title ?></strong>
					</li>
					<li>
						<i class="fa-li fa fa-money"></i>
						<?= $service->appointment_calendar_service_price ?>
					</li>
					<li>
						<i class="fa-li fa fa-clock-o"></i>
						<?= $service->appointment_calendar_service_duration ?> Minutes

						<?php if ($service->appointment_calendar_service_preparation_before) {
							echo '(+ before: ' . $service->appointment_calendar_service_preparation_before . ')';
						} ?>
						<?php if ($service->appointment_calendar_service_preparation_after) {
							echo '(+ after: ' . $service->appointment_calendar_service_preparation_after . ')';
						} ?>
					</li>
				</ul>
			</div>
			<div>
				<a
				 class="appointment-calendar-btn appointment-calendar-btn-sm appointment-calendar-btn-primary appointment-calendar-link-stretched"
				 href='<?= $service->editUrl ?>'>
					<i class="fa fa-fw fa-pencil"></i>
				</a>
			</div>
		</div>
	</div>

<?php endforeach; ?>
