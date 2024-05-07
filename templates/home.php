<?php namespace ProcessWire;

/** @var Pages $pages */


$variables = [
	//'timeSlots' => $freeTimeSlots,
	'locations' => $pages->get( "template=appointment-calendar-locations" )->children(),
	'services'  => $pages->get( "template=appointment-calendar-services" )->children(),
	'bookings'  => $pages->get( "template=appointment-calendar-bookings" )->children(),
];