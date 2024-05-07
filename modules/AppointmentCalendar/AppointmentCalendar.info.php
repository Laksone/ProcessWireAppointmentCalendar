<?php namespace ProcessWire;

/**
 * AppointmentCalendar.info.php
 * 
 * Return information about this module.
 *
 * If you prefer to keep everything in the main module file, you can move this
 * to a static getModuleInfo() method in the ProcessHello.module.php file, which
 * would return the same array as below.
 * 
 * Note that if you change any of these properties for an already installed 
 * module, you will need to do a Modules > Refresh before you see them. 
 *
 */

$info = array(

	// Your module's title
	'title' => 'Appointment Calendar',

	// A 1 sentence description of what your module does
	'summary' => 'Appointment Calendar for ProcessWire',

	// Module version number (integer)
	'version' => 1,

	// Name of person who created this module (change to your name)
	'author' => 'Matthäus Wende',

	// Icon to accompany this module (optional), uses font-awesome icon names, minus the "fa-" part
	'icon' => 'calendar',

	// Indicate any requirements as CSV string or array containing [RequiredModuleName][Operator][Version]
	'requires' => 'ProcessWire>=3.0.164',

	// URL to more info: change to your full modules.processwire.com URL (if available), or something else if you prefer
	'href' => 'https://www.matthaeus-wende.de',

	// name of permission required of users to execute this Process (optional)
	'permission' => 'appointment-calendar-edit',

	// permissions that you want automatically installed/uninstalled with this module (name => description)
	'permissions' => array(
		'appointment-calendar-edit' => 'Kalender bearbeiten'
	),

	// page that you want created to execute this module
	'page' => array(
		'name' => 'appointment-calendar-admin',
		// 'parent' => $this->wire()->config->urls->admin,
		'title' => 'Calendar'
	),

	// optional extra navigation that appears in admin drop down menus
	'nav' => array(
		array(
			'url' => 'bookings/',
			'label' => 'Appointments',
			'icon' => 'calendar',
		),
		array(
			'url' => 'employees/',
			'label' => 'Employees',
			'icon' => 'user',
		),
		array(
			'url' => 'services/',
			'label' => 'Services',
			'icon' => 'stethoscope',
		),
		array(
			'url' => 'locations/',
			'label' => 'Locations',
			'icon' => 'map-marker',
		),
	)

	// for more options that you may specify here, see the file: /wire/core/Process.php
	// and the file: /wire/core/Module.php

);
