# WP Author Ranking

WP Author Ranking the wordpress plugin provides ranking of the popular blog authors. Results are depends on page viewing numbers.



## Usage
This plugin requires PHP5.3.



### Install
Copy "wp-author-ranking" directory into your wordpress plugins directory and activate it. Now your wordpress admin menu has "WP Author Ranking" menu in "setting" at sidebar. Make sure that you have it running.



### Get the ranking data.

The function "get_wpAuthorRanking()" return the ranking data stored in an array.
This example prints ranking data.

```
<?php
$rankingData = get_wpAuthorRanking();
for ($i=0; $i < count($rankingData); $i++) {
	echo '<li>';
	echo '<p>No'.$rankingData[$i]['rank'].'</p>';
	echo '<p><strong>'.$rankingData[$i]['display_name'].'</strong></p>';
	echo '<p>'.$rankingData[$i]['count'].'pv<p>';
	echo '</li>';
}
?>
```

#### Informations the ranking data has.

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


#### Request options

You have four options to require ranking data.

* y (year)
* m (month)
* d (day)
* cpt (custom post type)
* exclude (exclude users)
* sort

This example gets columnist (post type 'column') ranking in September 2014.

```
<?php 
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
?>
```

Gets totally worst ranking, exclude ID.3 and ID.4 user.

```
<?php 
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
?>
```


### Count pv manually

In default setting, page views are counted at single post page. You also have the function "count_wpAuthorRanking()" to count authors page views manually.
This example counts id-3 user on 'column' post type.

```
<?php 
count_wpAuthorRanking(array('user'=>3,'cpt'=>'column'));
?>
```


## contact

* minojiro (mail@minojiro.com)
