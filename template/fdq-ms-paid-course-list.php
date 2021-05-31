<?php
	global $wpdb;
	//--- GET The list of available courses ---------------------------------
	$qry = "SELECT * FROM `wpou_mscm_course` WHERE active='1' AND paid='1' ORDER BY `course_id`";
	$rs = $wpdb->get_results($qry, ARRAY_A);
	//echo "<pre>"; print_r($rs); echo "</pre>";

	//echo $_SERVER['SERVER_NAME']."<br />";
	//echo $_SERVER['REQUEST_URI']."<br />";
	$_pg = "//".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	//echo "pg: ".$_pg."<br />";
?>

<?php if( count($rs) > 0 ): ?>
	<div class="fdq-ms-paid-courses-wrapper">
		<?php foreach( $rs as $val): ?>
			<div class="fdq-ms-course-wrapper">
				<div class="fdq-ms-course_icon"><i class="fa fa-play-circle-o" aria-hidden="true"></i></div>
				<div class="fdq-ms-course_name">
					<h4><?php echo $val['title']; ?></h4>
					<a href="<?php echo $_pg."?cid=".$val['course_id'] ?>">Ver el Curso</a>
				</div>
			</div>

		<?php endforeach; ?>
		<div style="clear:both;"></div>
	</div>
<?php else: ?>
	echo "There are no available courses, please check again later!";
<?php endif; ?>