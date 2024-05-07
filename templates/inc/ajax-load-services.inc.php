<?php namespace ProcessWire;

/**
 * @return array
 * @var Pages $pages
 *
 * @var $input
 */

$data             = [];
$selectedLocation = $input->get->int( 'selectedLocation' );
$employees        = $pages->get( $selectedLocation )->appointment_calendar_location_employees;

$uniqueServices = [];

foreach ( $employees as $employee ) {
	foreach ( $employee->appointment_calendar_employee_services as $service ) {

		if ( ! isset( $uniqueServices[ $service->id ] ) ) {

			$uniqueServices[ $service->id ] = true;

			$data[] = [
				'name' => $service->title,
				'id'   => $service->id,
			];
		}
	}
}

$ret['data']    = $data;
$ret['success'] = true;

echo json_encode( $ret );