<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Automatic_Booking_Dates_Shortcode Class
 *
 * @class Automatic_Booking_Dates_Shortcode
 * @version	1.0.0
 * @since 1.0.0
 * @package	Automatic_Booking_Dates
 * @author Jeffikus
 */
final class Automatic_Booking_Dates_Shortcode {
	/**
	 * Automatic_Booking_Dates_Shortcode The single instance of Automatic_Booking_Dates_Shortcode.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */

	private static $_instance = null;


	public function __construct () {

		// Register the settings with WordPress.
		add_shortcode( 'booking_date', array( $this, 'booking_date' ) );

		// Register the settings screen within WordPress.
		add_shortcode( 'event_date', array( $this, 'event_date' ) );

	} // End __construct()



	/**
	 * A function for the [booking_date] shortcode
	 *
	 * @return string
	 * @author Mohsin Rasool
	 **/

	function booking_date($atts, $content)
	{

		// extract the attributes into variables
	    extract(shortcode_atts(array(
	        'list' => null,
	        'format' => 'd F, Y',
	    ), $atts));

	    if(empty($list))
	    	return '';

		if ( $post = get_page_by_path( $list, OBJECT, 'date_list' ) ) {

			$dateList = get_post_meta( $post->ID, '_list', true );

			$dateList = preg_split("/[\s,]+/", $dateList);

			if(count($dateList) > 0) {
				$bookingDate = $this->get_upcoming_date($dateList);

				if($bookingDate != null)
					return date($format, $bookingDate);
				else
					return __('No upcoming date found in provided date list','automatic-booking-dates');
			}
			else
				return __('Date List do not have any dates.','automatic-booking-dates');
		}
		else
		    return __('Date List not found','automatic-booking-dates');


	}

	/**
	 * A function for the [evemt_date] shortcode
	 *
	 * @return string
	 * @author Mohsin Rasool
	 **/

	function event_date($atts, $content)
	{

		// extract the attributes into variables
	    extract(shortcode_atts(array(
	        'list' => null,
	        'format' => 'd F, Y',
	        'days' => 7
	    ), $atts));

	    if(empty($list))
	    	return '';

		if ( $post = get_page_by_path( $list, OBJECT, 'date_list' ) ) {

			$dateList = get_post_meta( $post->ID, '_list', true );

			$dateList = preg_split("/[\s,]+/", $dateList);

			if(count($dateList) > 0) {
				$bookingDate = $this->get_upcoming_date($dateList);

				if($bookingDate != null)
					return date($format, strtotime('+'.$days.' day',$bookingDate));
				else
					return __('No upcoming date found in provided date list','automatic-booking-dates');
			}
			else
				return __('Date List do not have any dates.','automatic-booking-dates');
		}
		else
		    return __('Date List not found','automatic-booking-dates');


	}

	/**
	 * It will return the next upcoming date from the $dateList. If $dateList is empty or all dates are older, it will return null
	 *
	 * @return string
	 * @author Mohsin Rasool
	 **/

	private function get_upcoming_date($dateList)
	{
		$upcomingDate = null;
		$currentDate = strtotime('now');

		for ($i=0; $i < count($dateList); $i++) {

			if(strtotime($dateList[$i]) < $currentDate)
				continue;
			else {
				$upcomingDate = strtotime($dateList[$i]);
				break;
			}
		}

		return $upcomingDate;
	}

	/**
	 * Main Automatic_Booking_Dates_Shortcode Instance
	 *
	 * Ensures only one instance of Automatic_Booking_Dates_Shortcode is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Main Automatic_Booking_Dates_Shortcode instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()



} // End Class
