<?php namespace ProcessWire;

/* TODO: COPY TEMPLATE FILES TO TEMPLATE FOLDER */
/* TODO: CREATE AJAX TEMPLATE AND COPY FILE */

class AppointmentCalendar extends Process implements Module {

	static public function getDefaultFields() {
		return array(
			'appointment_calendar_settings_timeslots',
			'appointment_calendar_booking_date',
			'appointment_calendar_booking_time',
			'appointment_calendar_booking_location',
			'appointment_calendar_booking_first_name',
			'appointment_calendar_booking_last_name',
			'appointment_calendar_booking_phone',
			'appointment_calendar_booking_email',
			'appointment_calendar_booking_employee',
			'appointment_calendar_booking_service',
			'appointment_calendar_location_employees',
			'appointment_calendar_employee_services',
			'appointment_calendar_employee_first_name',
			'appointment_calendar_employee_last_name',
			'appointment_calendar_employee_info',
			'appointment_calendar_employee_image',
			'appointment_calendar_service_price',
			'appointment_calendar_service_duration',
			'appointment_calendar_service_preparation_before',
			'appointment_calendar_service_preparation_after',
		);
	}

	static public function getDefaultTemplates() {
		return array(
			'appointment-calendar',
			'appointment-calendar-bookings',
			'appointment-calendar-booking',
			'appointment-calendar-employees',
			'appointment-calendar-employee',
			'appointment-calendar-services',
			'appointment-calendar-service',
			'appointment-calendar-locations',
			'appointment-calendar-location',
		);
	}

	/**
	 *
	 * ADMIN VIEWS
	 *
	 * @return string|array
	 */

	/***********************************/
	/* DASHBOARD PAGE */
	/***********************************/

	public function ___execute() {
		$pages = $this->wire()->pages;

		return [
			'bookingsCount' => $pages->find( "template=appointment-calendar-booking, appointment_calendar_booking_date>=today" )->count(),
			'pageTitle' => 'Dashboard',
		];
	}

	/***********************************/
	/* EMPLOYEES PAGE */
	/***********************************/

	public function ___executeEmployees() {

		$pages = $this->wire()->pages;

		return [
			'persons' => $pages->find( "template=appointment-calendar-employee, sort=-created" ),
		];
	}

	/***********************************/
	/* BOOKINGS PAGE */
	/***********************************/

	public function ___executeBookings() {

		$pages   = $this->wire()->pages;
		$input   = $this->wire()->input;
		$modules = $this->wire()->modules;

		/**********************************************
		 * FILTER FORM
		 **********************************************/

		$form = $modules->get( 'InputfieldForm' );

		/**
		 * EMPLOYEE INPUT
		 **/

		$employees            = $pages->find( "template=appointment-calendar-employee, include=hidden" );
		$employeesArr         = array();
		$employeesArr[ null ] = __( 'Please select' );
		foreach ( $employees as $employee ) {
			$employeesArr[ $employee->id ] = __( $employee->title );
		}

		$selectEmployee              = $modules->get( "InputfieldSelect" );
		$selectEmployee->icon        = 'user';
		$selectEmployee->columnWidth = 25;
		$selectEmployee->label       = 'Employee';
		$selectEmployee->attr( 'name', 'selectedEmployee' );
		$selectEmployee->addOptions( $employeesArr );
		$form->add( $selectEmployee );

		/**
		 * SERVICE INPUT
		 **/

		$services            = $pages->find( "template=appointment-calendar-service, include=hidden" );
		$servicesArr         = array();
		$servicesArr[ null ] = __( 'Please select' );
		foreach ( $services as $service ) {
			$servicesArr[ $service->id ] = __( $service->title );
		}

		$selectService              = $modules->get( "InputfieldSelect" );
		$selectService->icon        = 'stethoscope';
		$selectService->columnWidth = 25;
		$selectService->label       = 'Service';
		$selectService->attr( 'name', 'selectedService' );
		$selectService->addOptions( $servicesArr );
		$form->add( $selectService );

		/**
		 * LOCATION INPUT
		 **/

		$locations            = $pages->find( "template=appointment-calendar-location, include=hidden" );
		$locationsArr         = array();
		$locationsArr[ null ] = __( 'Please select' );
		foreach ( $locations as $location ) {
			$locationsArr[ $location->id ] = __( $location->title );
		}

		$selectLocation              = $modules->get( "InputfieldSelect" );
		$selectLocation->icon        = 'map-marker';
		$selectLocation->columnWidth = 25;
		$selectLocation->label       = 'Locations';
		$selectLocation->attr( 'name', 'selectedLocation' );
		$selectLocation->addOptions( $locationsArr );
		$form->add( $selectLocation );

		/**
		 * DATE INPUT
		 **/

		$selectDate              = $modules->get( "InputfieldDatetime" );
		$selectDate->icon        = 'calendar';
		$selectDate->columnWidth = 25;
		$selectDate->label       = 'Date';
		$selectDate->attr( 'name', 'selectedDate' );
		$selectDate->set( "inputType", "html" );
		$selectDate->set( "dateOutputFormat", "Y-m-d" );
		$selectDate->set( "htmlType", "date" );
		$form->add( $selectDate );

		/**
		 * SUBMIT BUTTON
		 **/

		$submit        = $modules->get( 'InputfieldButton' );
		$submit->id    = 'btn-submit';
		$submit->value = 'filter';
		$submit->name  = 'filterBookings';
		$submit->icon  = 'filter';
		$submit->type  = 'submit';
		$form->add( $submit );

		// Process the form submission
		if ( $input->post( $submit->name ) ) {
			$selectedEmployee = $input->post( 'selectedEmployee' );
			$selectedService  = $input->post( 'selectedService' );
			$selectedLocation  = $input->post( 'selectedLocation' );
			$selectedDate  = $input->post( 'selectedDate' );
		}

		// Build the selector based on form input
		$selector = "template=appointment-calendar-booking, appointment_calendar_booking_date>=today, sort=appointment_calendar_booking_date, sort=appointment_calendar_booking_time";

		if ( $selectedService ) {
			$selector .= ", appointment_calendar_booking_service=$selectedService";
		}
		if ( $selectedEmployee ) {
			$selector .= ", appointment_calendar_booking_employee=$selectedEmployee";
		}
		if ( $selectedLocation ) {
			$selector .= ", appointment_calendar_booking_location=$selectedLocation";
		}
		if ( $selectedDate ) {
			$selector .= ", appointment_calendar_booking_date=$selectedDate";
		}

		$bookings = $pages->find( $selector );

		return [
			'bookings' => $bookings,
			'form'     => $form->render(),
		];
	}


	/***********************************/
	/* LOCATIONS PAGE */
	/***********************************/

	public function ___executeLocations() {
		$pages = $this->wire()->pages;

		return [
			'locations' => $pages->find( "template=appointment-calendar-location, sort=-created" ),
		];
	}

	/***********************************/
	/* SERVICES PAGE */
	/***********************************/

	public function ___executeServices() {
		$pages = $this->wire()->pages;

		return [
			'services' => $pages->find( "template=appointment-calendar-service, sort=-created" ),
		];
	}

	/**
	 * Called only when your module is installed
	 * If you don't need anything here, you can simply remove this method.
	 *
	 * @throws WireException
	 */

	public function ___install() {

		$fields    = wire( 'fields' );
		$pages     = wire( 'pages' );
		$templates = wire( 'templates' );

		/**
		 *
		 * CREATE FIELDS
		 *
		 */

		/*******************************************************/
		/* CALENDAR SETTINGS */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_settings_timeslots' ) ) {
			$field              = new Field();
			$field->type        = 'FieldtypeInteger';
			$field->name        = 'appointment_calendar_settings_timeslots';
			$field->label       = $this->_( 'Timeslots' );
			$field->description = $this->_( 'Duration of the timeslots that can be booked in minutes.' );
			$field->icon        = 'bars';
			$this->fields->save( $field );

			$field->set( "inputType", "number" );
			$field->set( "defaultValue", 30 );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 100;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_settings_timeslots' . " already existing." );
		}

		/*******************************************************/
		/* BOOKING LOCATION */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_booking_location' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypePage';
			$field->name  = 'appointment_calendar_booking_location';
			$field->label = $this->_( 'Location' );
			$field->icon  = 'map-marker';
			$this->fields->save( $field );

			$field->set( "inputfield", "InputfieldSelect" );
			$field->set( "labelFieldName", "title" );
			$field->set( "derefAsPage", "1" );
			$field->required    = 1;
			$field->columnWidth = 33;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_booking_location' . " already existing." );
		}

		/*******************************************************/
		/* BOOKING DATE */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_booking_date' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeDatetime';
			$field->name  = 'appointment_calendar_booking_date';
			$field->label = $this->_( 'Booking date' );
			$field->icon  = 'calendar';
			$this->fields->save( $field );

			$field->set( "inputType", "html" );
			$field->set( "dateOutputFormat", "Y-m-d" );
			$field->set( "htmlType", "date" );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 33;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_booking_date' . " already existing." );
		}

		/*******************************************************/
		/* BOOKING TIME */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_booking_time' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeDatetime';
			$field->name  = 'appointment_calendar_booking_time';
			$field->label = $this->_( 'Booking time' );
			$field->icon  = 'clock-o';
			$this->fields->save( $field );

			$field->set( "inputType", "html" );
			$field->set( "dateOutputFormat", "H:i" );
			$field->set( "htmlType", "time" );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 33;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_booking_time' . " already existing." );
		}

		/*******************************************************/
		/* BOOKING FIRST NAME */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_booking_first_name' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeText';
			$field->name  = 'appointment_calendar_booking_first_name';
			$field->label = $this->_( 'Booking first name' );
			$field->icon  = 'user-circle';
			$this->fields->save( $field );

			$field->set( "textformatters", array( "TextformatterEntities" ) );
			$field->set( "stripTags", 1 );
			$field->set( "maxlength", 255 );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 50;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_booking_first_name' . " already existing." );
		}

		/*******************************************************/
		/* BOOKING LAST NAME */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_booking_last_name' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeText';
			$field->name  = 'appointment_calendar_booking_last_name';
			$field->label = $this->_( 'Booking last name' );
			$field->icon  = 'user-circle-o';
			$this->fields->save( $field );

			$field->set( "textformatters", array( "TextformatterEntities" ) );
			$field->set( "stripTags", 1 );
			$field->set( "maxlength", 255 );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 50;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_booking_last_name' . " already existing." );
		}

		/*******************************************************/
		/* BOOKING PHONE */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_booking_phone' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeText';
			$field->name  = 'appointment_calendar_booking_phone';
			$field->label = $this->_( 'Phone' );
			$field->icon  = 'phone';
			$this->fields->save( $field );

			$field->set( "textformatters", array( "TextformatterEntities" ) );
			$field->set( "stripTags", 1 );
			$field->set( "maxlength", 255 );
			$field->columnWidth = 50;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_booking_phone' . " already existing." );
		}

		/*******************************************************/
		/* BOOKING EMAIL */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_booking_email' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeEmail';
			$field->name  = 'appointment_calendar_booking_email';
			$field->label = $this->_( 'Email' );
			$field->icon  = 'envelope';
			$this->fields->save( $field );

			$field->set( "maxlength", 255 );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 50;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_booking_email' . " already existing." );
		}

		/*******************************************************/
		/* BOOKING EMPLOYEE */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_booking_employee' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypePage';
			$field->name  = 'appointment_calendar_booking_employee';
			$field->label = $this->_( 'Employee' );
			$field->icon  = 'user-md';
			$this->fields->save( $field );

			$field->set( "inputfield", "InputfieldSelect" );
			$field->set( "labelFieldName", "title" );
			$field->set( "derefAsPage", "1" );
			$field->required    = 1;
			$field->columnWidth = 50;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_booking_employee' . " already existing." );
		}

		/*******************************************************/
		/* BOOKING SERVICE */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_booking_service' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypePage';
			$field->name  = 'appointment_calendar_booking_service';
			$field->label = $this->_( 'Service' );
			$field->icon  = 'stethoscope';
			$this->fields->save( $field );

			$field->set( "inputfield", "InputfieldSelect" );
			$field->set( "labelFieldName", "title" );
			$field->set( "derefAsPage", "1" );
			$field->required    = 1;
			$field->columnWidth = 50;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_booking_service' . " already existing." );
		}

		/*******************************************************/
		/* LOCATION EMPLOYEES */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_location_employees' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypePage';
			$field->name  = 'appointment_calendar_location_employees';
			$field->label = $this->_( 'Employees' );
			$field->icon  = 'user-md';
			$this->fields->save( $field );

			$field->set( "inputfield", "InputfieldTextTags" );
			$field->set( "labelFieldName", "title" );
			$field->required    = 1;
			$field->columnWidth = 100;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_location_employees' . " already existing." );
		}

		/*******************************************************/
		/* EMPLOYEE SERVICES */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_employee_services' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypePage';
			$field->name  = 'appointment_calendar_employee_services';
			$field->label = $this->_( 'Services' );
			$field->icon  = 'stethoscope';
			$this->fields->save( $field );

			$field->set( "inputfield", "InputfieldTextTags" );
			$field->set( "labelFieldName", "title" );
			$field->required    = 1;
			$field->columnWidth = 100;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_employee_services' . " already existing." );
		}

		/*******************************************************/
		/* EMPLOYEE FIRST NAME */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_employee_first_name' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeText';
			$field->name  = 'appointment_calendar_employee_first_name';
			$field->label = $this->_( 'Employee first name' );
			$field->icon  = 'vcard';
			$this->fields->save( $field );

			$field->set( "textformatters", array( "TextformatterEntities" ) );
			$field->set( "stripTags", 1 );
			$field->set( "maxlength", 255 );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 50;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_employee_first_name' . " already existing." );
		}

		/*******************************************************/
		/* EMPLOYEE LAST NAME */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_employee_last_name' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeText';
			$field->name  = 'appointment_calendar_employee_last_name';
			$field->label = $this->_( 'Employee last name' );
			$field->icon  = 'vcard-o';
			$this->fields->save( $field );

			$field->set( "textformatters", array( "TextformatterEntities" ) );
			$field->set( "stripTags", 1 );
			$field->set( "maxlength", 255 );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 50;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_employee_last_name' . " already existing." );
		}

		/*******************************************************/
		/* EMPLOYEE INFO */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_employee_info' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeTextarea';
			$field->name  = 'appointment_calendar_employee_info';
			$field->label = $this->_( 'Employee info' );
			$field->icon  = 'info-circle';
			$this->fields->save( $field );

			$field->set( "textformatters", array( "TextformatterEntities" ) );
			$field->set( "stripTags", 1 );
			$field->columnWidth = 100;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_employee_info' . " already existing." );
		}

		/*******************************************************/
		/* EMPLOYEE IMAGE */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_employee_image' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeImage';
			$field->name  = 'appointment_calendar_employee_image';
			$field->label = $this->_( 'Employee image' );
			$field->icon  = 'camera';
			$this->fields->save( $field );

			$field->columnWidth = 100;
			$field->set( "textformatters", array( "TextformatterEntities" ) );
			$field->set( "maxFiles", 1 );
			$field->set( "extensions", "jpg jpeg png" );
			$field->set( "maxWidth", 1024 );
			$field->set( "maxHeight", 1024 );
			$field->tags = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_employee_image' . " already existing." );
		}

		/*******************************************************/
		/* SERVICE PRICE */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_service_price' ) ) {
			$field        = new Field();
			$field->type  = 'FieldtypeFloat';
			$field->name  = 'appointment_calendar_service_price';
			$field->label = $this->_( 'Service price' );
			$field->icon  = 'money';
			$this->fields->save( $field );

			$field->set( "inputType", "number" );
			$field->set( "precision", 2 );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 100;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_service_price' . " already existing." );
		}

		/*******************************************************/
		/* SERVICE DURATION */
		/*******************************************************/

		if ( ! $fields->get( 'appointment_calendar_service_duration' ) ) {
			$field              = new Field();
			$field->type        = 'FieldtypeInteger';
			$field->name        = 'appointment_calendar_service_duration';
			$field->label       = $this->_( 'Service duration' );
			$field->description = $this->_( 'Duration of the service in minutes.' );
			$field->icon        = 'clock-o';
			$this->fields->save( $field );

			$field->set( "inputType", "number" );
			$field->required = 1;
			$field->set( "requiredAttr", 1 );
			$field->columnWidth = 33;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_service_duration' . " already existing." );
		}

		if ( ! $fields->get( 'appointment_calendar_service_preparation_before' ) ) {
			$field              = new Field();
			$field->type        = 'FieldtypeInteger';
			$field->name        = 'appointment_calendar_service_preparation_before';
			$field->label       = $this->_( 'Preparation time before' );
			$field->description = $this->_( 'Time in minutes that is needed before the service.' );
			$field->icon        = 'clock-o';
			$this->fields->save( $field );

			$field->set( "inputType", "number" );
			$field->columnWidth = 33;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_service_preparation_before' . " already existing." );
		}

		if ( ! $fields->get( 'appointment_calendar_service_preparation_after' ) ) {
			$field              = new Field();
			$field->type        = 'FieldtypeInteger';
			$field->name        = 'appointment_calendar_service_preparation_after';
			$field->label       = $this->_( 'Preparation time after' );
			$field->description = $this->_( 'Time in minutes that is needed after the service.' );
			$field->icon        = 'clock-o';
			$this->fields->save( $field );

			$field->set( "inputType", "number" );
			$field->columnWidth = 33;
			$field->tags        = 'appointment-calendar';
			$this->fields->save( $field );
		} else {
			throw new WireException( 'appointment_calendar_service_preparation_after' . " already existing." );
		}

		/**
		 *
		 * CREATE TEMPLATES & PAGES
		 *
		 */

		$templateAppointmentCalendar          = 'appointment-calendar';
		$templateAppointmentCalendarBookings  = 'appointment-calendar-bookings';
		$templateAppointmentCalendarBooking   = 'appointment-calendar-booking';
		$templateAppointmentCalendarLocations = 'appointment-calendar-locations';
		$templateAppointmentCalendarLocation  = 'appointment-calendar-location';
		$templateAppointmentCalendarEmployees = 'appointment-calendar-employees';
		$templateAppointmentCalendarEmployee  = 'appointment-calendar-employee';
		$templateAppointmentCalendarServices  = 'appointment-calendar-services';
		$templateAppointmentCalendarService   = 'appointment-calendar-service';


		/*******************************************************/
		/* APPOINTMENT CALENDAR TEMPLATE & PAGE */
		/*******************************************************/

		if ( ! $templates->get( $templateAppointmentCalendar ) ) {
			$fieldgroup       = new Fieldgroup();
			$fieldgroup->name = $templateAppointmentCalendar;
			$fieldgroup->add( "title" );
			$fieldgroup->add( "appointment_calendar_settings_timeslots" );
			$fieldgroup->save();

			$template                 = new Template();
			$template->name           = $templateAppointmentCalendar;
			$template->label          = $this->_( 'Appointment Calendar' );
			$template->tags           = 'appointment-calendar';
			$template->pageLabelField = "fa-calendar title";
			$template->noParents      = "-1";
			$template->fieldgroup     = $fieldgroup;
			$template->save();
		} else {
			throw new WireException( "Template " . $templateAppointmentCalendar . " already exists." );
		}

		/* APPOINTMENT CALENDAR PAGE */
		if ( ! $pages->get( $templateAppointmentCalendar )->id ) {
			$page           = new Page();
			$page->template = $templateAppointmentCalendar;
			$page->parent   = wire( 'pages' )->get( 1 );
			$page->name     = $templateAppointmentCalendar;
			$page->title    = 'Appointment calendar';
			$page->addStatus( Page::statusHidden );
			$page->save();
		} else {
			throw new WireException( "Page " . $templateAppointmentCalendar . " already exists." );
		}

		/*******************************************************/
		/* BOOKINGS TEMPLATES & PAGE */
		/*******************************************************/

		if ( ! $templates->get( $templateAppointmentCalendarBookings ) ) {
			$fieldgroup       = new Fieldgroup();
			$fieldgroup->name = $templateAppointmentCalendarBookings;
			$fieldgroup->add( "title" );
			$fieldgroup->save();

			$template                 = new Template();
			$template->name           = $templateAppointmentCalendarBookings;
			$template->label          = $this->_( 'Bookings' );
			$template->tags           = 'appointment-calendar';
			$template->pageLabelField = "fa-calendar-check-o title";
			$template->noParents      = "-1";
			$template->fieldgroup     = $fieldgroup;
			$template->save();
		} else {
			throw new WireException( "Template " . $templateAppointmentCalendarBookings . " already exists." );
		}

		if ( ! $templates->get( $templateAppointmentCalendarBooking ) ) {
			$fieldgroup       = new Fieldgroup();
			$fieldgroup->name = $templateAppointmentCalendarBooking;
			$fieldgroup->add( "title" );
			$fieldgroup->add( "appointment_calendar_booking_location" );
			$fieldgroup->add( "appointment_calendar_booking_date" );
			$fieldgroup->add( "appointment_calendar_booking_time" );
			$fieldgroup->add( "appointment_calendar_booking_first_name" );
			$fieldgroup->add( "appointment_calendar_booking_last_name" );
			$fieldgroup->add( "appointment_calendar_booking_phone" );
			$fieldgroup->add( "appointment_calendar_booking_email" );
			$fieldgroup->add( "appointment_calendar_booking_employee" );
			$fieldgroup->add( "appointment_calendar_booking_service" );
			$fieldgroup->save();

			$template             = new Template();
			$template->name       = $templateAppointmentCalendarBooking;
			$template->label      = $this->_( 'Booking' );
			$template->tags       = 'appointment-calendar';
			$template->noChildren = "1";
			$template->fieldgroup = $fieldgroup;
			$template->save();
		} else {
			throw new WireException( "Template " . $templateAppointmentCalendarBooking . " already exists." );
		}

		/* BOOKINGS PAGE */

		if ( ! $pages->get( $templateAppointmentCalendarBookings )->id ) {
			$page           = new Page();
			$page->template = $templateAppointmentCalendarBookings;
			$page->parent   = wire( 'pages' )->get( $templateAppointmentCalendar );
			$page->name     = $templateAppointmentCalendarBookings;
			$page->title    = 'Bookings';
			$page->addStatus( Page::statusHidden );
			$page->save();
		} else {
			throw new WireException( "Page " . $templateAppointmentCalendarBookings . " already exists." );
		}

		/*******************************************************/
		/* LOCATIONS TEMPLATES & PAGE */
		/*******************************************************/

		if ( ! $templates->get( $templateAppointmentCalendarLocations ) ) {
			$fieldgroup       = new Fieldgroup();
			$fieldgroup->name = $templateAppointmentCalendarLocations;
			$fieldgroup->add( "title" );
			$fieldgroup->save();

			$template                 = new Template();
			$template->name           = $templateAppointmentCalendarLocations;
			$template->label          = $this->_( 'Locations' );
			$template->tags           = 'appointment-calendar';
			$template->pageLabelField = "fa-map-marker title";
			$template->noParents      = "-1";
			$template->fieldgroup     = $fieldgroup;
			$template->save();
		} else {
			throw new WireException( "Template " . $templateAppointmentCalendarLocations . " already exists." );
		}

		if ( ! $templates->get( $templateAppointmentCalendarLocation ) ) {
			$fieldgroup       = new Fieldgroup();
			$fieldgroup->name = $templateAppointmentCalendarLocation;
			$fieldgroup->add( "title" );
			$fieldgroup->add( "appointment_calendar_location_employees" );
			$fieldgroup->save();

			$template             = new Template();
			$template->name       = $templateAppointmentCalendarLocation;
			$template->label      = $this->_( 'Location' );
			$template->tags       = 'appointment-calendar';
			$template->noChildren = "1";
			$template->fieldgroup = $fieldgroup;
			$template->save();
		} else {
			throw new WireException( "Template " . $templateAppointmentCalendarLocation . " already exists." );
		}


		/* LOCATIONS PAGE */
		if ( ! $pages->get( $templateAppointmentCalendarLocations )->id ) {
			$page           = new Page();
			$page->template = $templateAppointmentCalendarLocations;
			$page->parent   = wire( 'pages' )->get( $templateAppointmentCalendar );
			$page->name     = $templateAppointmentCalendarLocations;
			$page->title    = 'Locations';
			$page->addStatus( Page::statusHidden );
			$page->save();
		} else {
			throw new WireException( "Page " . $templateAppointmentCalendarLocations . " already exists." );
		}


		/*******************************************************/
		/* SERVICES TEMPLATES & PAGE */
		/*******************************************************/

		if ( ! $templates->get( $templateAppointmentCalendarServices ) ) {
			$fieldgroup       = new Fieldgroup();
			$fieldgroup->name = $templateAppointmentCalendarServices;
			$fieldgroup->add( "title" );
			$fieldgroup->save();

			$template                 = new Template();
			$template->name           = $templateAppointmentCalendarServices;
			$template->label          = $this->_( 'Services' );
			$template->tags           = 'appointment-calendar';
			$template->pageLabelField = "fa-stethoscope title";
			$template->noParents      = "-1";
			$template->fieldgroup     = $fieldgroup;
			$template->save();
		} else {
			throw new WireException( "Template " . $templateAppointmentCalendarServices . " already exists." );
		}

		if ( ! $templates->get( $templateAppointmentCalendarService ) ) {
			$fieldgroup       = new Fieldgroup();
			$fieldgroup->name = $templateAppointmentCalendarService;
			$fieldgroup->add( "title" );
			$fieldgroup->add( "appointment_calendar_service_price" );
			$fieldgroup->add( "appointment_calendar_service_duration" );
			$fieldgroup->add( "appointment_calendar_service_preparation_before" );
			$fieldgroup->add( "appointment_calendar_service_preparation_after" );
			$fieldgroup->save();

			$template             = new Template();
			$template->name       = $templateAppointmentCalendarService;
			$template->label      = $this->_( 'Service' );
			$template->tags       = 'appointment-calendar';
			$template->noChildren = "1";
			$template->fieldgroup = $fieldgroup;
			$template->save();
		} else {
			throw new WireException( "Template " . $templateAppointmentCalendarService . " already exists." );
		}

		/* SERVICES PAGE */
		if ( ! $pages->get( $templateAppointmentCalendarServices )->id ) {
			$page           = new Page();
			$page->template = $templateAppointmentCalendarServices;
			$page->parent   = wire( 'pages' )->get( $templateAppointmentCalendar );
			$page->name     = $templateAppointmentCalendarServices;
			$page->title    = 'Services';
			$page->addStatus( Page::statusHidden );
			$page->save();
		} else {
			throw new WireException( "Page " . $templateAppointmentCalendarServices . " already exists." );
		}

		/*******************************************************/
		/* EMPLOYEES TEMPLATES & PAGE */
		/*******************************************************/

		if ( ! $templates->get( $templateAppointmentCalendarEmployees ) ) {
			$fieldgroup       = new Fieldgroup();
			$fieldgroup->name = $templateAppointmentCalendarEmployees;
			$fieldgroup->add( "title" );
			$fieldgroup->save();

			$template                 = new Template();
			$template->name           = $templateAppointmentCalendarEmployees;
			$template->label          = $this->_( 'Employees' );
			$template->tags           = 'appointment-calendar';
			$template->pageLabelField = "fa-user-md title";
			$template->noParents      = "-1";
			$template->fieldgroup     = $fieldgroup;
			$template->save();
		} else {
			throw new WireException( "Template " . $templateAppointmentCalendarEmployees . " already exists." );
		}

		if ( ! $templates->get( $templateAppointmentCalendarEmployee ) ) {
			$fieldgroup       = new Fieldgroup();
			$fieldgroup->name = $templateAppointmentCalendarEmployee;
			$fieldgroup->add( "title" );
			$fieldgroup->add( "appointment_calendar_employee_first_name" );
			$fieldgroup->add( "appointment_calendar_employee_last_name" );
			$fieldgroup->add( "appointment_calendar_employee_info" );
			$fieldgroup->add( "appointment_calendar_employee_image" );
			$fieldgroup->add( "appointment_calendar_employee_services" );
			$fieldgroup->save();

			$template             = new Template();
			$template->name       = $templateAppointmentCalendarEmployee;
			$template->label      = $this->_( 'Employee' );
			$template->tags       = 'appointment-calendar';
			$template->noChildren = "1";
			$template->fieldgroup = $fieldgroup;
			$template->save();
		} else {
			throw new WireException( "Template " . $templateAppointmentCalendarEmployee . " already exists." );
		}

		/* EMPLOYEES PAGE */
		if ( ! $pages->get( $templateAppointmentCalendarEmployees )->id ) {
			$page           = new Page();
			$page->template = $templateAppointmentCalendarEmployees;
			$page->parent   = wire( 'pages' )->get( $templateAppointmentCalendar );
			$page->name     = $templateAppointmentCalendarEmployees;
			$page->title    = 'Employees';
			$page->addStatus( Page::statusHidden );
			$page->save();
		} else {
			throw new WireException( "Page " . $templateAppointmentCalendarEmployees . " already exists." );
		}

		/**
		 *
		 * SET PARENT AND CHILD TEMPLATE OPTIONS AFTER TEMPLATES CREATED
		 *
		 */

		/* CALENDAR */
		$template                 = $templates->get( $templateAppointmentCalendar );
		$template->childTemplates = array(
			$templateAppointmentCalendarBookings,
			$templateAppointmentCalendarEmployees,
			$templateAppointmentCalendarServices,
			$templateAppointmentCalendarLocations
		);
		$template->save();

		/* BOOKINGS */
		$template                  = $templates->get( $templateAppointmentCalendarBookings );
		$template->parentTemplates = array( $templateAppointmentCalendar );
		$template->childTemplates  = array( $templateAppointmentCalendarBooking );
		$template->save();

		/* BOOKING */
		$template                  = $templates->get( $templateAppointmentCalendarBooking );
		$template->parentTemplates = array( $templateAppointmentCalendarBookings );
		$template->save();

		/* LOCATIONS */
		$template                  = $templates->get( $templateAppointmentCalendarLocations );
		$template->parentTemplates = array( $templateAppointmentCalendar );
		$template->childTemplates  = array( $templateAppointmentCalendarLocation );
		$template->save();

		/* LOCATION */
		$template                  = $templates->get( $templateAppointmentCalendarLocation );
		$template->parentTemplates = array( $templateAppointmentCalendarLocations );
		$template->save();

		/* SERVICES */
		$template                  = $templates->get( $templateAppointmentCalendarServices );
		$template->parentTemplates = array( $templateAppointmentCalendar );
		$template->childTemplates  = array( $templateAppointmentCalendarService );
		$template->save();

		/* SERVICE */
		$template                  = $templates->get( $templateAppointmentCalendarService );
		$template->parentTemplates = array( $templateAppointmentCalendarServices );
		$template->save();

		/* EMPLOYEES */
		$template                  = $templates->get( $templateAppointmentCalendarEmployees );
		$template->parentTemplates = array( $templateAppointmentCalendar );
		$template->childTemplates  = array( $templateAppointmentCalendarEmployee );
		$template->save();

		/* EMPLOYEE */
		$template                  = $templates->get( $templateAppointmentCalendarEmployee );
		$template->parentTemplates = array( $templateAppointmentCalendarEmployees );
		$template->save();

		/**
		 *
		 * SET SELECTABLE TEMPLATES FOR FIELDS AFTER TEMPLATES CREATED
		 *
		 */

		/* BOOKING LOCATION */
		$field = $fields->get( 'appointment_calendar_booking_location' );
		$field->set( "template_id", $templates->get( $templateAppointmentCalendarLocation )->id );
		$fields->save( $field );

		/* BOOKING SERVICE */
		$field = $fields->get( 'appointment_calendar_booking_service' );
		$field->set( "template_id", $templates->get( $templateAppointmentCalendarService )->id );
		$fields->save( $field );

		/* BOOKING EMPLOYEE */
		$field = $fields->get( 'appointment_calendar_booking_employee' );
		$field->set( "template_id", $templates->get( $templateAppointmentCalendarEmployee )->id );
		$fields->save( $field );

		/* EMPLOYEE SERVICES */
		$field = $fields->get( 'appointment_calendar_employee_services' );
		$field->set( "template_id", $templates->get( $templateAppointmentCalendarService )->id );
		$fields->save( $field );

		/* LOCATION EMPLOYEES */
		$field = $fields->get( 'appointment_calendar_location_employees' );
		$field->set( "template_id", $templates->get( $templateAppointmentCalendarEmployee )->id );
		$fields->save( $field );

	}

	/**
	 * Called only when your module is uninstalled
	 *
	 * This should return the site to the same state it was in before the module was installed.
	 *
	 * If you don't need anything here, you can simply remove this method.
	 *
	 * @throws WireException
	 */


	public function ___uninstall() {

		/**
		 *
		 * DELETE PAGES, FIELDS, TEMPLATES
		 *
		 */

		$pages     = wire( 'pages' );
		$fields    = wire( 'fields' );
		$templates = wire( 'templates' );

		/* DELETE PAGES */
		$pagesToDelete = $pages->get( "template=appointment-calendar" );
		if ( $pagesToDelete->id ) {
			$pages->delete( $pagesToDelete, true );
		}

		/* DELETE TEMPLATES */
		foreach ( self::getDefaultTemplates() as $template ) {
			$templateToDelete = $templates->get( $template );
			if ( ! $templateToDelete ) {
				continue;
			}
			$templates->delete( $templates->get( $templateToDelete ) );
		}

		/* DELETE FIELDS */
		foreach ( self::getDefaultFields() as $field ) {
			$fieldToDelete = $fields->get( $field );
			if ( ! $fieldToDelete ) {
				continue;
			}
			$fields->delete( $fieldToDelete );
		}

	}
}

