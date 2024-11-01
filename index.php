<?php

/**
 * @package WP Author Ranking
 * @version 1.0.4
 */
/*
Plugin Name: WP Author Ranking
Description: Ranking the popular blog authors. Results are depends on page viewing numbers! Who is today's mega-star!?
Author: Natsuyasumi Seisakushitsu
Version: 1.0.4
*/

class wpAuthorRanking {
	private $already;
	private $tableName;

	public function __construct(){
		global $wpdb;
		$this->tableName = $wpdb->prefix.'author_ranking';
		$this->already=false;
		add_action('init', array( $this, 'pageHead'));
		add_action('wp_head', array( $this, 'autoSingleCount'));
		add_filter('activated_plugin', array( $this, 'init'));
		add_action('admin_menu', array( $this, 'addAdminMenu'));
	}

	public function count( $opt = Array() ) {
		if ( !( $this->already && get_option('wpAuthorRanking_countUU') ) ) {
			global $wpdb, $post;
			$countDate = preg_split('/[- ]/', date_i18n('Y-n-j'));
			$optDef = Array(
				'user' => have_posts()? $post->post_author : 0,
				'cpt'  => have_posts()? $post->post_type : 'post',
				'y'    => $countDate[0],
				'm'    => $countDate[1],
				'd'    => $countDate[2]);
			foreach ( $optDef as $key => $value ){
				$opt[$key] = isset($opt[$key])? $opt[$key] : $value;
			}
			if( $opt['user'] != 0 ){
				if( $wpdb->get_row( $wpdb->prepare("SELECT count FROM {$this->tableName} WHERE user = %d AND cpt = %s AND y = %d AND m = %d AND d = %d;", $opt['user'], $opt['cpt'], $opt['y'], $opt['m'], $opt['d']) ) ) {
					$dbQuery = $wpdb->prepare("UPDATE {$this->tableName} SET count = count + 1
						WHERE user = %d AND cpt = %s AND y = %d AND m = %d AND d = %d;",
						$opt['user'], $opt['cpt'], $opt['y'], $opt['m'], $opt['d']);
					$wpdb->query($dbQuery);
				} else {
					$opt['count'] = 1;
					$wpdb->insert( $this->tableName, $opt );
				}
			}
		}
	}

	public function getRank( $opt = array() ) {
		global $wpdb;
		$optDef = Array( 'cpt' => '', 'y'  => '', 'm' => '', 'd' => '', 'exclude' => false, 'sort' => 'DESC' );
		$where   = '';
		$exclude = '';
		$sort    = '';
		foreach ( $optDef as $key => $value ){
			$value = ( isset($opt[$key]) ? $opt[$key] : $value );
			if( $key == 'sort' ){
				$sort = 'ORDER BY count '.( $value=='DESC' ? 'DESC ' : 'ASC ' );
			} elseif( $key == 'exclude' ) {
				if(is_array($value)) {
					foreach ($value as $userId) {
						$exclude .= ( $exclude=='' ? 'AND user NOT IN (' : ',').$wpdb->prepare( '%d ' ,$userId);
					}
					$exclude .= ($exclude==''? '':')');
				}
			} else {
				if($value!=''){
					$where .= ($where==''? '' : 'AND ');
					$where .= $wpdb->prepare( $key.'='.($key=='cpt'?'%s ':'%d ') ,(isset($opt[$key])? $opt[$key] : $value));
				}
			}
		}

		$dbQuery = "SELECT ID, user_login, user_nicename, user_email, user_url, user_registered, user_activation_key, display_name, SUM(count) AS count FROM {$this->tableName} JOIN {$wpdb->users} ON {$this->tableName}.user = {$wpdb->users}.ID ". ($where=='' && $exclude=='' ? ' ':'WHERE ') . $where . $exclude .'GROUP BY user '. $sort .';';
		$sqlResult = $wpdb->get_results($dbQuery);
		$sqlResult_len = count($sqlResult);

		$result = Array();
		$rankerRank = 1;
		$curRank = -1;
		for( $i = 0; $i < $sqlResult_len; $i++ ) {
			$rankerData = (array)$sqlResult[$i];
			if( $rankerData['count'] != $curRank ){
				$rankerRank = $i+1;
				$curRank = $rankerData['count'];
			}
			$rankerData += array('rank' => $rankerRank);
			array_push( $result , $rankerData );
		}
		return ( count($result) > 0 ? $result : false);
	}

	public function init() {
		global $wpdb;
		if( $wpdb->get_var('SHOW TABLES LIKE "'.$this->tableName.'";') != $this->tableName ){
			$dbQuery = "CREATE TABLE {$this->tableName} ( user int, cpt VARCHAR(20), y int, m int, d int, count int);";
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($dbQuery);
		}
		update_option( 'wpAuthorRanking_countSingle' , true );
		update_option( 'wpAuthorRanking_countUU' , true );
	}

	public function addAdminMenu() {
		add_options_page('WP Author Ranking', 'WP Author Ranking', 'manage_options', __FILE__, array( $this, 'theAdminMenu'));
	}

	public function theAdminMenu() {
		include 'setting.php';
	}

	public function pageHead() {
		if( !is_admin() ) {
			$reqUri = $_SERVER['REQUEST_URI'];
			$cookieData = [];
			$this->already = false;
			if( isset($_COOKIE['wpAuthorRanking_already']) ) {
				$cookieData =  $_COOKIE['wpAuthorRanking_already'];
				if( array_search($reqUri, $cookieData) !== false ){
					$this->already = true;
				} else {
					$pushCookie = 'wpAuthorRanking_already['.(count($cookieData)).']';
					setcookie ($pushCookie, $reqUri);
				}
			} else {
				$pushCookie = 'wpAuthorRanking_already[0]';
				setcookie ($pushCookie, $reqUri);
			}
		}
	}

	public function autoSingleCount() {
		if( is_single() && get_option('wpAuthorRanking_countSingle') ) {
			$this->count();
		}
	}
}

$wpAuthorRanking = new wpAuthorRanking();

function get_wpAuthorRanking( $opt = Array() ) {
	global $wpAuthorRanking;
	return $wpAuthorRanking->getRank($opt);
}

function count_wpAuthorRanking( $opt = Array() ) {
	global $wpAuthorRanking;
	$wpAuthorRanking->count($opt);
}

?>