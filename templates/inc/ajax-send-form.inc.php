<?php namespace ProcessWire;

function areFieldsValid( $fields ) {
	foreach ( $fields as $field ) {
		if ( empty( $field ) ) {
			return false;
		}
	}

	return true;
}

/**********************************************/
/* INPUT DATA */
/**********************************************/

$selectedLocation = $input->get->int( 'selectedLocation' );
$selectedService  = $input->get->int( 'selectedService' );
$selectedPerson   = $input->get->int( 'selectedPerson' );
$selectedDate     = $input->get->date( 'selectedDate' );
$selectedTime     = $input->get->date( 'selectedTime' );
$inputNameFirst   = $input->get->text( 'inputNameFirst' );
$inputNameLast    = $input->get->text( 'inputNameLast' );
$inputPhone       = $input->get->text( 'inputPhone' );
$inputEmail       = $input->get->text( 'inputEmail' );
$inputHoneypot    = $input->get->text( 'inputHoneypot' );

$fieldsToCheck = [
	$selectedLocation,
	$selectedService,
	$selectedPerson,
	$selectedDate,
	$selectedTime,
	$inputNameFirst,
	$inputNameLast,
	$inputEmail
];

/* TODO: CHECK IF SLOT IS FREE */

if ( empty( $inputHoneypot ) && areFieldsValid( $fieldsToCheck ) ) {

	/*************************************/
	/* SAVE ENTRY AS PAGE */
	/*************************************/

	$bookingPage = $pages->get( "template=appointment-calendar-bookings" );

	$p           = new Page();
	$p->template = 'appointment-calendar-booking';
	$p->parent   = $bookingPage;

	$p->title                                   = date( "d.m.Y", $selectedDate ) . " | " . $inputNameFirst . " " . $inputNameLast;
	$p->appointment_calendar_booking_location   = $selectedLocation;
	$p->appointment_calendar_booking_date       = $selectedDate;
	$p->appointment_calendar_booking_time       = date( "H:i", $selectedTime );
	$p->appointment_calendar_booking_first_name = $inputNameFirst;
	$p->appointment_calendar_booking_last_name  = $inputNameLast;
	$p->appointment_calendar_booking_phone      = $inputPhone;
	$p->appointment_calendar_booking_email      = $inputEmail;
	$p->appointment_calendar_booking_employee   = $selectedPerson;
	$p->appointment_calendar_booking_service    = $selectedService;

	// $p->addStatus( Page::statusUnpublished );
	// $p->addStatus( Page::statusHidden );
	$p->save();

	$ret['success'] = true;

} else {
	$ret['success'] = false;
}

echo json_encode( $ret );