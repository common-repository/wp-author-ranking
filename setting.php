<?php
	$currentDate = preg_split('/[- ]/', date_i18n('Y-n-j'));
	$tiaraColor = ['#e8c289','#bec6cc','#d8b2ae'];
	load_plugin_textdomain('wpAuthorRanking',false,'wp-author-ranking/lang/');
	if ( isset($_POST['wpuserranking_opt']['set'])) {
		update_option('wpAuthorRanking_countSingle', (isset($_POST['wpuserranking_opt']['countSinglePosts'])? true:false) );
		update_option('wpAuthorRanking_countUU', (isset($_POST['wpuserranking_opt']['countUU'])? true:false) );
	}
	if ( isset($_POST['resetdata'])) {
		global $wpdb;
		$tableName = $wpdb->prefix.'user_ranking';
		$wpdb->query('TRUNCATE TABLE '.$this->tableName.';');
	}
?>
<br><br><h2>WP Author Ranking</h2><br><hr><br>
	<form action='' method='post' style='display:inline;'>
		<input type="hidden" name='wpuserranking_opt[set]' value='set'>
		<p><input type='checkbox' name='wpuserranking_opt[countSinglePosts]' value='checked' <?php echo get_option('wpAuthorRanking_countSingle')? 'checked':'' ?> /><?php _e('count single post page visitor automatically.', 'wpAuthorRanking'); ?></p>
		<p><input type='checkbox' name='wpuserranking_opt[countUU]' value='checked' <?php echo get_option('wpAuthorRanking_countUU')? 'checked':'' ?> /><?php _e('count first visit (unique-user) only.', 'wpAuthorRanking'); ?></p>
		<p class='submit'><input type='submit' name='submit' id='submit' class='button button-primary' value='<?php _e('save', 'wpAuthorRanking'); ?>'></p>
	</form>
<hr>

<?php
	for ($i=0; $i < 4; $i++) {
		$rankingGraphTitle = array('Daily','Monthly','Yearly','Total');
?>
	<h3 style='margin: 25px 10px 8px;'><?php _e($rankingGraphTitle[$i], 'wpAuthorRanking'); ?></h3>

<?php
		$theQuery=array();
		if ($i<=2) {	$theQuery['y'] = $currentDate[0];	}
		if ($i<=1) {	$theQuery['m'] = $currentDate[1];	}
		if ($i==0) {	$theQuery['d'] = $currentDate[2];	}
		$rankingData = get_wpAuthorRanking($theQuery);
		$theMaxCount = 0;
		if($rankingData){
		foreach($rankingData as $key) {
			$theMaxCount = $theMaxCount<$key['count']? $key['count']:$theMaxCount;
		}
?>
<table cellspacing='10' style='width:100%; max-width:600px;'>
<?php for ($j=0; $j < count($rankingData); $j++) { ?>
	<tr>
		<td width='25'>
<?php if($rankingData[$j]['rank']<4){ ?>
			<svg width="30px" height="20px" viewBox="0 0 144 117">
				<path fill="<?php echo $tiaraColor[$rankingData[$j]['rank']-1]; ?>" d="M144,36c0,4.971-4.029,9-9,9c-1.751,0-3.38-0.508-4.762-1.373C117.005,66.335,117,81,117,81H72H27 c0,0-0.005-14.665-13.238-37.373C12.38,44.492,10.751,45,9,45c-4.971,0-9-4.029-9-9s4.029-9,9-9s9,4.029,9,9 c0,1.23-0.248,2.401-0.695,3.469C24.233,42.004,34.613,45,45,45c12.288,0,20.379-16.773,24.278-27.421C65.639,16.425,63,13.021,63,9 c0-4.971,4.029-9,9-9s9,4.029,9,9c0,4.021-2.639,7.425-6.278,8.579C78.621,28.227,86.712,45,99,45 c10.387,0,20.767-2.996,27.695-5.531C126.248,38.401,126,37.23,126,36c0-4.971,4.029-9,9-9S144,31.029,144,36z M117,90H27v18h90V90z"/>
			</svg>
			<?php } else { ?>&nbsp;<?php } ?></td>
		<td width='20%'><b><?php echo $rankingData[$j]['display_name']; ?></b><br><?php echo $rankingData[$j]['count']; ?>pv</td>
		<td width='80%'><table  cellspacing='0'  bgcolor='#2ea2cc' style='background-color:#2ea2cc!important;' height='24' width='<?php echo $rankingData[$j]['count']/$theMaxCount*100; ?>%'><tr><td></td></tr></table></td>
	</tr>
<?php } ?>
</table>
<?php }else{ ?>
<p><?php _e('no data', 'wpAuthorRanking'); ?></p>
<?php } ?>
<hr>
<?php } ?>
<br><br>
<form action='' method='POST' name='resetdata' id='resetdata'>
	<input type='hidden' name='resetdata' value='1' />
	<a href="javascript:void(0)" onclick="wpUserRanking_resetData()"><?php _e('Reset all count data', 'wpAuthorRanking'); ?></a>
</form>
<script>
<!--
function wpUserRanking_resetData(){ if(window.confirm('<?php _e('Are you shure?', 'wpAuthorRanking'); ?>')){ document.resetdata.submit(); } return false; }
-->
</script>
<br><br>
<p><i>produced by <a href="http://natsu-yasumi.jp/" target="blank">Natsuyasumi Seisakushitsu</a></i></p>