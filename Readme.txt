=== WP Author Ranking ===
Contributors: Natsuyasumi Seisakushitsu
Tags: author, user, popular, ranking, pageview, unique user
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
== Description ==
 
WP Author Ranking the wordpress plugin provides ranking of the popular blog authors. Results are depends on page viewing numbers. Getting data, it has options (year, month, day, post type) to filtering.

== Installation ==
 
1. Upload the `wp-author-ranking` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Open the setting menu, it is possible to set the execution condition.

= Get the ranking data. =

The function "get_wpAuthorRanking()" return the ranking data stored in an array.
This example prints ranking data.

`<?php
$rankingData = get_wpAuthorRanking();
for ($i=0; $i < count($rankingData); $i++) {
	echo '<li>';
	echo '<p>No'.$rankingData[$i]['rank'].'</p>';
	echo '<p><strong>'.$rankingData[$i]['display_name'].'</strong></p>';
	echo '<p>'.$rankingData[$i]['count'].'pv<p>';
	echo '</li>';
}
?>`

= Informations the ranking data has. =

* ID
* user_login
* user_nicename
* user_email
* user_url
* user_registered
* user_activation_key
* display_name
* count (page view count)
* rank (rank)


Request options

You have four options to require ranking data.

* y (year)
* m (month)
* d (day)
* cpt (custom post type)
* exclude (exclude users)
* sort

This example gets columnist (post type 'column') ranking in September 2014.

`<?php 
$rankingData = get_wpAuthorRanking(array(
	'y'=>2014,
	'm'=>9,
	'cpt'=>'column'));

for ($i=0; $i < count($rankingData); $i++) {
	echo '<li>';
	echo '<p>No'.$rankingData[$i]['rank'].'</p>';
	echo '<p><strong>'.$rankingData[$i]['display_name'].'</strong></p>';
	echo '<p>'.$rankingData[$i]['count'].'pv<p>';
	echo '</li>';
}
?>`

Gets totally worst ranking, exclude ID.3 and ID.4 user.

`<?php 
$rankingData = get_wpAuthorRanking(array(
	'sort'=>'ASC',
	'exclude'=>array(3,4)));
for ($i=0; $i < count($rankingData); $i++) {
	echo '<li>';
	echo '<p>No'.$rankingData[$i]['rank'].'</p>';
	echo '<p><strong>'.$rankingData[$i]['display_name'].'</strong></p>';
	echo '<p>'.$rankingData[$i]['count'].'pv<p>';
	echo '</li>';
}
?>`


= Count pv manually =

In default setting, page views are counted at single post page. You also have the function "count_wpAuthorRanking()" to count authors page views manually.
This example counts id-3 user on 'column' post type.

`<?php 
count_wpAuthorRanking(array('user'=>3,'cpt'=>'column'));
?>`


== Screenshots ==
 
1. The setting menu.
 
== Changelog ==

= 1.0.4 =
* bug fix

= 1.0.3 =
* Language: add French language translate. (Thanks Nicolas)

= 1.0.2 =
* Fix: showing setting page properly in wordpress version 3.0

= 1.0.1 =
* Add filtering option that excludes users.
* Add request option to request worst ranking.

= 1.0.0 =
* 2014-09-01  First release