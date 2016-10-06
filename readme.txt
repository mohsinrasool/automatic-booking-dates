=== Automatic Booking Dates ===
Contributors: mohsinrasool
Requires at least: 4.6.0
Tested up to: 4.6.0
Stable tag: 1.0.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Plugin allows to upload several (10 or more) lists of dates (in the format DD-MM-YY). Plugin then offers a series of shortcodes that can be inserted into posts and pages, in the format [booking_date list="booking-list"] and [event_date list="booking-list"].

== Description ==

1. Admin will add a list titled "Booking 1" with dates "30 September, 6 October, 20 October, 5 November etc"
2. Admin will then be able to add two kind of shortcodes
	a. [booking_date list="booking-1"] : It will display the upcoming date from the list i.e. 6 October
	b. [deadline_date list="booking-1"] : It will display the date of (a) + 7 days i.e. 13 October 2016
3. Admin should be able to add multiple Listings like "Booking 1"

== Usage ==

Plugin provides two shortcodes

1. *Booking Date*
It will display the upcoming date from provided list slug i.e. 6 October, 2016

[booking_date list="booking-1"]

Attributes:
a. list: Slug of the data list
b. format: Date format to be output

1. *Event Date*
It will display the upcoming date from provided list slug i.e. 13 October, 2016

[event_date list="booking-1"]

Attributes:
a. list: Slug of the data list. (required)
b. format: Date format to be output, format should be specified as per "PHP documentation"[http://php.net/manual/en/function.date.php]. Default "d F, Y"
c. days: Days to add to booking date. Default to 7

== Installation ==

Installing "Automatic Booking Dates" can be done either by searching for "Automatic Booking Dates" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org.
1. Upload the ZIP file through the "Plugins > Add New > Upload" screen in your WordPress dashboard.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit the settings screen and configure, as desired.


== Upgrade Notice ==

= 1.0.0 =
* 2016-10-04
* Initial release. Woo!

== Changelog ==

= 1.0.0 =
* 2016-10-04
* Initial release. Woo!
