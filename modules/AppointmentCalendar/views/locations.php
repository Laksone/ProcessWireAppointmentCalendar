<?php namespace ProcessWire;
	/** @var array $locations */
?>

<?php foreach ( $locations as $location ): ?>
	<div class="appointment-calendar-card appointment-calendar-position-relative">
		<div class="appointment-calendar-d-flex">
			<div style="padding: 0 1.5rem 0 0; width: 100%;">
				<ul class="fa-ul" style="margin-bottom: 0;">
					<li>
						<i class="fa-li fa fa-map-marker"></i>
						<strong><?= $location->title ?></strong>
					</li>
					<li>
						<i class="fa-li fa fa-user"></i>
						<?php foreach ( $location->appointment_calendar_location_employees as $employee ): ?>
							<?= $employee->appointment_calendar_employee_first_name ?> <?= $employee->appointment_calendar_employee_last_name ?><br>
						<?php endforeach; ?>
					</li>
				</ul>
			</div>
			<div>
				<a
				 class="appointment-calendar-btn appointment-calendar-btn-sm appointment-calendar-btn-primary appointment-calendar-link-stretched"
				 href='<?= $location->editUrl ?>'>
					<i class="fa fa-fw fa-pencil"></i>
				</a>
			</div>
		</div>
	</div>
<?php endforeach; ?>
