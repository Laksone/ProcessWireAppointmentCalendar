<?php namespace ProcessWire;

/**
 * @return array
 * @var Pages $pages
 *
 * @var $input
 */

$selectedLocation  = $input->get->int( 'selectedLocation' );
$selectedService   = $input->get->int( 'selectedService' );
$locationEmployees = $pages->get( $selectedLocation )->appointment_calendar_location_employees;

$employees = $pages->find("id=$locationEmployees, appointment_calendar_employee_services=$selectedService");

$data = [];

foreach ( $employees as $employee ) {
	$data[] = [
		'name' => $employee->title,
		'id'   => $employee->id,
		'image'   => $employee->appointment_calendar_employee_image->url,
		'info'   => $employee->appointment_calendar_employee_info,
	];
}

$ret['data']    = $data;
$ret['success'] = true;

echo json_encode( $ret );
