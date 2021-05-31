<?php
	global $wpdb;
	$ddebug = false;
	$gvar = get_vars(); //echo "<pre>";print_r($gvar);echo "</pre>";
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-course-icon.php"; //echo "pageUrl: ".$pageUrl."<br />";

	$error_msg = "";
	$success_msg = "";
	$err = array();

	if( isset($_REQUEST['btnAddCourseIcon']) ):
		//--- send data for store process --------------------------------------------------------------------
		$iconComplete = ( isset($_FILES['iconCompleteUpload']) ) ? $_FILES['iconCompleteUpload'] : "" ;
		$iconCurrent  = ( isset($_FILES['iconCurrentUpload'])  ) ? $_FILES['iconCurrentUpload']  : "" ;
		$iconBlocked  = ( isset($_FILES['iconBlockedUpload'])  ) ? $_FILES['iconBlockedUpload']  : "" ;
		
		$fv_icon_day_number = ( isset($_REQUEST['fv_icon_day_number']) ) ? $_REQUEST['fv_icon_day_number'] : "1" ;  //echo "fv_icon_day_number: ".$fv_icon_day_number."<br />";
		$fv_active = ( isset($_REQUEST['fv_active']) ) ? $_REQUEST['fv_active'] : "1" ;  //echo "fv_active: ".$fv_active."<br />";
		

		//echo "<pre>"; print_r($_FILES); echo "</pre>";
		if( basename($iconComplete["name"]) == "" && basename($iconCurrent["name"]) == "" && basename($iconBlocked["name"]) =="" ):
			$err[] = "No icon was selected to upload.";
		else:
			$err = fdq_ms_course_icon_add_func($iconComplete, $iconCurrent, $iconBlocked, $fv_active, $fv_icon_day_number);
		endif;
		//$err[]='temp error';
		
		if( count($err) > 0 ):
			$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
			$success_msg = "";
		else:
			$success_msg = "The new course was successfully created";
			$error_msg = "";
		endif;
	else:
		$fv_icon_day_number = '';
		$fv_active = "1";
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
		<div class="mscm_page_title">Add new icon</div>
		<form name="course_icon_add_form" id="course_icon_add_form" action="<?php echo $pageUrl; ?>" method="post" enctype="multipart/form-data">
			<div class="mscm_row">
				<span class="mscm_field_title">Icon Name</span><br />
				<input type="text" name="fv_icon_day_number" id="fv_icon_day_number" value="<?php echo $fv_icon_day_number; ?>">				
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title">Select Icon Complete </span><br />
				<input type="file" name="iconCompleteUpload" id="iconCompleteUpload">				
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title">Select Icon Current </span><br />
				<input type="file" name="iconCurrentUpload" id="iconCurrentUpload">				
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title">Select Icon Blocked </span><br />
				<input type="file" name="iconBlockedUpload" id="iconBlockedUpload">				
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title" style="display:inline-block;margin-bottom:8px;">Active:</span><br />
				<input type="radio" name="fv_active" id="fv_active1" value="1" <?php if($fv_active == '1'){ echo 'checked="checked"'; } ?> > <label for="fv_active1"><strong>Active</strong></label>
				<input type="radio" name="fv_active" id="fv_active0" value="0" style="margin:0 0 0 10px" <?php if($fv_active == '0'){ echo 'checked="checked"'; } ?> > <label for="fv_active0"><strong>Inactive</strong></label>
			</div>
			<div class="mscm_btn_row">
				<input type="hidden" name="action" value="showCourseIconAdd" />
				<input type="submit" name="btnAddCourseIcon" id="btnAddCourseIcon" value="Save Icon" class="mscm_btn" />
				<a class="mscm_btn_cancel" href="<?php echo $pageUrl; ?>&action=showCourseIconList">Cancel</a>
			</div>
		</form>
	<?php else: ?>
		<div class="mscm_successMsgWrapper">
			<div class="mscm_successMsgInner">
				<div class="mscm_success_msg"><?php echo $success_msg; ?></div>
				<div class="mscm_successMegButtons">
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseIconAdd">Add Icon</a>
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseIconList">Cancel</a>
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