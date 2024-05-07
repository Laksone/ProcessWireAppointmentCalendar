<?php namespace ProcessWire;
	/** @var array $persons */
?>

<?php foreach ( $persons as $person ): ?>
	<div class="appointment-calendar-card appointment-calendar-position-relative">
		<div class="appointment-calendar-d-flex">
			<div>
				<img src="<?= $person->appointment_calendar_employee_image->first->size(100, 100)->url ?>" alt="">
			</div>
			<div style="padding: 0 1.5rem; width: 100%;">
				<ul class="fa-ul" style="margin-bottom: 0;">
					<li>
						<i class="fa-li fa fa-id-card"></i>
						<strong><?= $person->appointment_calendar_employee_first_name ?> <?= $person->appointment_calendar_employee_last_name ?></strong>
					</li>
					<li>
						<i class="fa-li fa fa-info-circle"></i>
						<?= $person->appointment_calendar_employee_info ?>
					</li>
					<li>
						<i class="fa-li fa fa-stethoscope"></i>

						<?php foreach ( $person->appointment_calendar_employee_services as $service ): ?>
							<?= $service->title ?>,
						<?php endforeach; ?>
					</li>
				</ul>

			</div>
			<div>
				<a class="appointment-calendar-btn appointment-calendar-btn-sm appointment-calendar-btn-primary appointment-calendar-link-stretched" href='<?= $person->editUrl ?>'>
					<i class="fa fa-fw fa-pencil"></i>
				</a>
			</div>
		</div>
	</div>
<?php endforeach; ?>
