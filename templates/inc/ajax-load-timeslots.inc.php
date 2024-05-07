<?php

	namespace ProcessWire;

	function convertTimeToMinutes( $time ): int {
		$time = strtotime( $time );

		return date( 'H', $time ) * 60 + date( 'i', $time );
	}

	function convertMinutesToTime( $minutes ) {
		$hours   = floor( $minutes / 60 );
		$minutes %= 60;

		return sprintf( '%02d:%02d', $hours, $minutes );
	}

	function createSlots( $slotLength ): array {
		$slots = array();

		$totalMinutesInDay = 24 * 60;

		for ( $i = 0; $i < $totalMinutesInDay; $i += $slotLength ) {
			$slots[] = array( 'start' => $i, 'end' => $i + $slotLength );
		}

		return $slots;
	}

	function getSlots( $slots, $times ): array {
		$result = array();

		foreach ( $slots as $slot ) {
			foreach ( $times as $time ) {
				if ( $slot['start'] < $time['end'] && $slot['end'] > $time['start'] ) {
					$result[] = $slot;
					break;
				}
			}
		}

		return $result;
	}

	function removeSlots( $slots, $times ): array {
		$result = array();

		foreach ( $slots as $slot ) {
			$isBooked = false;

			foreach ( $times as $time ) {
				if ( $slot['start'] < $time['end'] && $slot['end'] > $time['start'] ) {
					$isBooked = true;
					break;
				}
			}

			if ( ! $isBooked ) {
				$result[] = $slot;
			}
		}

		return $result;
	}


	/**
	 * @param int $slotLength
	 * @param int $serviceDuration
	 * @param array $timeslotsBlocked
	 *
	 * @return array
	 *
	 * @var $input
	 *
	 * @var Pages $pages
	 */

	/**********************************************/
	/* INPUT DATA */
	/**********************************************/

	$selectedPerson = $input->get->int( 'selectedPerson' );

	if ( ! empty( $input->get->date( 'selectedDate' ) ) ) {
		$selectedDate = date( 'Y-m-d', $input->get->date( 'selectedDate' ) );
	} else {
		exit();
	}

	$calendarConfig = $pages->get( "template=appointment-calendar" );
	$slotLength     = ! empty( $calendarConfig->appointment_calendar_settings_timeslots ) ? $calendarConfig->appointment_calendar_settings_timeslots : 30;

	/**********************************************/
	/* ALL BOOKINGS ON SELECTED DATE */
	/**********************************************/

	$bookings = $pages->find( "template=appointment-calendar-booking, appointment_calendar_booking_date=$selectedDate, appointment_calendar_booking_employee=$selectedPerson" );


	/**********************************************/
	/* BLOCKED BOOKING TIMES IN MINUTES */
	/**********************************************/

	$timesBookingsInMinutes = [];

	foreach ( $bookings as $booking ) {
		$service  = $pages->get( $booking->appointment_calendar_booking_service->id );
		$duration = $service->appointment_calendar_service_duration;

		/* SET TO 0 TO AVOID PHP ERROR */
		$durationBefore = ! empty( $service->appointment_calendar_service_preparation_before ) ? $service->appointment_calendar_service_preparation_before : 0;
		$durationAfter  = ! empty( $service->appointment_calendar_service_preparation_after ) ? $service->appointment_calendar_service_preparation_after : 0;

		$bookingStartTime = convertTimeToMinutes( $booking->appointment_calendar_booking_time ) - $durationBefore;
		$bookingEndTime   = $bookingStartTime + $durationBefore + $duration + $durationAfter;

		$timesBookingsInMinutes[] = [
			'start' => $bookingStartTime,
			'end'   => $bookingEndTime
		];
	}

	/**********************************************/
	/* WORKING TIMES OF SELECTED STAFF MEMBER */
	/**********************************************/

	/* TODO: REPLACE WITH DYNAMIC BACKEND VALUES */

	$timesWork = array(
		array( 'start' => '09:00', 'end' => '12:00' ),
		array( 'start' => '15:00', 'end' => '19:00' )
	);

	/**********************************************/
	/* WORKING TIMES IN MINUTES */
	/**********************************************/

	$timesWorkInMinutes = [];

	foreach ( $timesWork as $time ) {
		$timesWorkInMinutes[] = array(
			'start' => convertTimeToMinutes( $time['start'] ),
			'end'   => convertTimeToMinutes( $time['end'] )
		);
	}

	/**********************************************/
	/* FIND FREE SLOTS IN MINUTES */
	/**********************************************/

	$slotsAll          = createSlots( $slotLength );
	$slotsWorkingTimes = getSlots( $slotsAll, $timesWorkInMinutes );
	$slotsFree         = removeSlots( $slotsWorkingTimes, $timesBookingsInMinutes );

	$slotsTimesFree = [];

	foreach ( $slotsFree as $time ) {
		$slotsTimesFree[] = array(
			'start' => convertMinutesToTime( $time['start'] ),
			'end'   => convertMinutesToTime( $time['end'] )
		);
	}

	$ret['data']    = $slotsTimesFree;
	$ret['success'] = true;

	echo json_encode( $ret );