<?php
	global $wpdb;
	$ddebug = false;
	$gvar = get_vars(); //echo "<pre>";print_r($gvar);echo "</pre>";
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-courses.php"; //echo "pageUrl: ".$pageUrl."<br />";

	$error_msg = "";
	$success_msg = "";
	$err = array();

	if( isset($_REQUEST['btnEditCourse']) ):
		$cod01 = ( isset($_REQUEST['cod01']) && trim($_REQUEST['cod01']) != "" ) ? $_REQUEST['cod01'] : "" ;
		$fv_ccode = ( isset($_REQUEST['fv_ccode']) && trim($_REQUEST['fv_ccode']) != "" ) ? $_REQUEST['fv_ccode'] : "" ;
		$fv_title = ( isset($_REQUEST['fv_title']) && trim($_REQUEST['fv_title']) != "" ) ? $_REQUEST['fv_title'] : "" ;
		$fv_paid = ( isset($_REQUEST['fv_paid']) && trim($_REQUEST['fv_paid']) != "" ) ? $_REQUEST['fv_paid'] : "" ;
		$fv_active = ( isset($_REQUEST['fv_active']) && trim($_REQUEST['fv_active']) != "" ) ? $_REQUEST['fv_active'] : "" ;

		if( $cod01 != "" ):
			//--- send data for store process --------------------------------------------------------------------
			$err = fdq_ms_course_edit_func($fv_ccode, $fv_title, $fv_paid, $fv_active, $cod01);
			if( count($err) > 0 ):
				$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
				$success_msg = "";
			else:
				$success_msg = "The course was successfully updated";
				$error_msg = "";
			endif;
		endif;

	else:
		$cod01 = ( isset($_REQUEST['cod01']) && trim($_REQUEST['cod01']) != "" ) ? $_REQUEST['cod01'] : "" ;
		if( $cod01 != "" ):
			$qry = "SELECT * FROM wpou_mscm_course WHERE course_id = '".$cod01."'"; //echo "qry: ".$qry."<br />";
			$rs = $wpdb->get_results($qry, ARRAY_A); //echo "<pre>";print_r($rs);echo "</pre>";
			$fv_ccode = $rs[0]['ccode'];
			$fv_title = $rs[0]['title'];
			$fv_paid = $rs[0]['paid'];
			$fv_active = $rs[0]['active'];
		else:
			$fv_ccode = "";
			$fv_title = "";
			$fv_paid = "";
			$fv_active = "";
		endif;
	endif;

	if($ddebug):
		echo "cod01: ".$cod01."<br />";
		echo "fv_title: ".$fv_title."<br />";
		echo "fv_paid: ".$fv_paid."<br />";
		echo "fv_active: ".$fv_active."<br />";
	endif;

	//$success_msg = 'this is a temp message, success message !!!';
?>
<div class="mscm_main_wrapper">
	<?php if( $cod01 != "" ): ?>
		<?php if( $success_msg == "" ): ?>
			<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
			<div class="mscm_page_title">Edit Course</div>
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
					<input type="hidden" name="cod01" value="<?php echo $cod01; ?>" />
					<input type="hidden" name="action" value="showCourseEdit" />
					<input type="submit" name="btnEditCourse" id="btnEditCourse" value="Edit Course" class="mscm_btn" />
					<a class="mscm_btn_cancel" href="<?php echo $pageUrl; ?>&action=showCourseList">Cancel</a>
				</div>
			</form>
		<?php else: ?>
			<div class="mscm_successMsgWrapper">
				<div class="mscm_successMsgInner">
					<div class="mscm_success_msg"><?php echo $success_msg; ?></div>
					<div class="mscm_successMegButtons">
						<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseList">Go back</a>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<div class="mscm_successMsgWrapper">
			<div class="mscm_successMsgInner">
				<div class="mscm_success_msg">there was an error with the data, please go back and try again</div>
				<div class="mscm_successMegButtons">
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseList">Go back</a>
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