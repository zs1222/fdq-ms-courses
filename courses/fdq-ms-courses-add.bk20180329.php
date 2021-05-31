<?php
	global $wpdb;
	$ddebug = false;
	$gvar = get_vars(); //echo "<pre>";print_r($gvar);echo "</pre>";
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-courses.php"; //echo "pageUrl: ".$pageUrl."<br />";

	$error_msg = "";
	$success_msg = "";
	$err = array();

	if( isset($_REQUEST['btnAddCourse']) ):
		
		$fv_ccode = ( isset($_REQUEST['fv_ccode']) && trim($_REQUEST['fv_ccode']) != "" ) ? $_REQUEST['fv_ccode'] : "" ;
		$fv_title = ( isset($_REQUEST['fv_title']) && trim($_REQUEST['fv_title']) != "" ) ? $_REQUEST['fv_title'] : "" ;
		$fv_paid = ( isset($_REQUEST['fv_paid']) && trim($_REQUEST['fv_paid']) != "" ) ? $_REQUEST['fv_paid'] : "" ;
		$fv_active = ( isset($_REQUEST['fv_active']) && trim($_REQUEST['fv_active']) != "" ) ? $_REQUEST['fv_active'] : "" ;

		//--- send data for store process --------------------------------------------------------------------
		$err = fdq_ms_course_add_func($fv_ccode, $fv_title, $fv_paid, $fv_active);
		if( count($err) > 0 ):
			$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
			$success_msg = "";
		else:
			$success_msg = "The new course was successfully created";
			$error_msg = "";
		endif;

	else:
		$fv_title="";
		$fv_paid="";
		$fv_active = "";
	endif;

	if($ddebug):
		echo "fv_title: ".$fv_title."<br />";
		echo "fv_paid: ".$fv_paid."<br />";
	endif;

	//$success_msg = 'this is a temp message, success message !!!';
?>
<div class="mscm_main_wrapper">
	<?php if( $success_msg == "" ): ?>
		<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
		<div class="mscm_page_title">Add new course</div>
		<form name="recipe_add_form" id="recipe_add_form" action="<?php echo $pageUrl; ?>" method="post">
			<div class="mscm_row">
				<span class="mscm_field_title">Course code</span><br />
				<input type="text" name="fv_ccode" id="fv_ccode" value="<?php echo $fv_ccode; ?>">
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title">Course Name</span><br />
				<input type="text" name="fv_title" id="fv_title" value="<?php echo $fv_title; ?>">
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title" style="display:inline-block;margin-bottom:8px;">The Course is</span><br />
				<input type="radio" name="fv_paid" id="fv_paid1" value="1" <?php if($fv_paid == '1'){ echo 'checked="checked"'; } ?> > <label for="fv_paid1"><strong>Paid</strong></label>
				<input type="radio" name="fv_paid" id="fv_paid0" value="0" style="margin:0 0 0 10px" <?php if($fv_paid == '0'){ echo 'checked="checked"'; } ?> > <label for="fv_paid0"><strong>Free</strong></label>
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title" style="display:inline-block;margin-bottom:8px;">Active:</span><br />
				<input type="radio" name="fv_active" id="fv_active1" value="1" <?php if($fv_active == '1'){ echo 'checked="checked"'; } ?> > <label for="fv_active1"><strong>Active</strong></label>
				<input type="radio" name="fv_active" id="fv_active0" value="0" style="margin:0 0 0 10px" <?php if($fv_active == '0'){ echo 'checked="checked"'; } ?> > <label for="fv_active0"><strong>Inactive</strong></label>
			</div>
			<div class="mscm_btn_row">
				<input type="hidden" name="action" value="showCourseAdd" />
				<input type="submit" name="btnAddCourse" id="btnAddCourse" value="Save Course" class="mscm_btn" />
				<a class="mscm_btn_cancel" href="<?php echo $pageUrl; ?>&action=showCourseList">Cancel</a>
			</div>
		</form>
	<?php else: ?>
		<div class="mscm_successMsgWrapper">
			<div class="mscm_successMsgInner">
				<div class="mscm_success_msg"><?php echo $success_msg; ?></div>
				<div class="mscm_successMegButtons">
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseAdd">Add Course</a>
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseList">Cancel</a>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
<script>
jQuery(document).ready(function(){
	//alert('hola');
});
</script>