<?php namespace ProcessWire;

/**
 * @param $year
 * @param $month
 *
 * @return array
 *
 * @var $input
 *
 * @var Pages $pages
 */

/* TODO: ONLY AVAILABLE DATES, I.E. NO SATURDAYS AND SUNDAYS  */
/* TODO: BLOCK DATES IF NO AVAILABLE TIMESLOTS FOR THE DAY */

/* GET ALL DATES OF A SPECIFIC MONTH */
function getMonthDates( $year, $month ): array {
	$firstDay = date( 'Y-m-d', strtotime( "$year-$month-01" ) );
	$lastDay  = date( 'Y-m-d', strtotime( "last day of $year-$month" ) );

	$allDates = array();

	$currentDate = $firstDay;

	while ( $currentDate <= $lastDay ) {
		$allDates[]  = $currentDate;
		$currentDate = date( 'Y-m-d', strtotime( "$currentDate + 1 day" ) );
	}

	return $allDates;
}


$selectedYear  = $input->get->int( 'selectedYear' );
$selectedMonth = $input->get->int( 'selectedMonth' );

/* TODO: REPLACE datesBlocked WITH BACKEND ARRAY */

$datesAll                   = getMonthDates( $selectedYear, $selectedMonth );
$datesBlocked               = [ '2024-01-02', '2024-01-03', '2024-01-07', '2024-01-28', '2024-02-01', '2025-01-01' ];
$datesBlockedPersonVacation = [ '2024-01-01', '2024-01-30', '2024-03-01' ];


/* ADD BLOCKED STATUS TO ARRAY */
function addBlockedStatus( $date, $blockedDates ) {
	if ( in_array( $date, $blockedDates ) ) {
		return [ 'date' => $date, 'status' => 'blocked' ];
	} else {
		return [ 'date' => $date, 'status' => 'free' ];
	}
}

$dates = array_map( function ( $date ) use ( $datesBlocked, $datesBlockedPersonVacation ) {
	return addBlockedStatus( $date, array_merge( $datesBlocked, $datesBlockedPersonVacation ) );
}, $datesAll );


$ret['data']    = $dates;
$ret['success'] = true;

echo json_encode( $ret );
