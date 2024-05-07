<?php namespace ProcessWire;

/** @var Config $config */

if ( $config->ajax ) {

	$action = $_REQUEST['action'] ?? null;
	$ret    = [ 'success' => false ];

	switch ( $action ) {
		case 'load_services':
			include "inc/ajax-load-services.inc.php";
			exit;
		case 'load_persons':
			include "inc/ajax-load-persons.inc.php";
			exit;
		case 'load_dates':
			include "inc/ajax-load-dates.inc.php";
			exit;
		case 'load_timeslots':
			include "inc/ajax-load-timeslots.inc.php";
			exit;
		case 'send_form':
			include "inc/ajax-send-form.inc.php";
			exit;
	}

	die();
}

