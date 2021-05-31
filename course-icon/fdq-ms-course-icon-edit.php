<?php
	global $wpdb;
	$ddebug = false;
	$gvar = get_vars(); //echo "<pre>";print_r($gvar);echo "</pre>";
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-course-icon.php"; //echo "pageUrl: ".$pageUrl."<br />";
	$imgDir = $gvar['img_dir'];

	$error_msg = "";
	$success_msg = "";
	$err = array();

	$cod01 = ( isset($_REQUEST['cod01']) && trim($_REQUEST['cod01']) != "" ) ? $_REQUEST['cod01'] : "" ;
	if( $cod01 != "" ):
		$qry = "SELECT * FROM `wpou_mscm_icon` WHERE icon_id = '".$cod01."'"; //echo "qry: ".$qry."<br />";
		$rs = $wpdb->get_results($qry, ARRAY_A); //echo "<pre>";print_r($rs);echo "</pre>";
		$_icon01 = $rs[0]['icon_complete'];
		$_icon02 = $rs[0]['icon_current'];
		$_icon03 = $rs[0]['icon_blocked'];
		$fv_icon_day_number = $rs[0]['icon_day_number'];
		$fv_active = $rs[0]['active'];
	endif;


	if( isset($_REQUEST['btnEditCourseIcon']) ):
		//--- send data for store process --------------------------------------------------------------------
		//$cod01 = ( isset($_REQUEST['cod01']) && trim($_REQUEST['cod01']) != "" ) ? $_REQUEST['cod01'] : "" ;
		$iconComplete = ( isset($_FILES['iconCompleteUpload']) ) ? $_FILES['iconCompleteUpload'] : "" ;
		$iconCurrent  = ( isset($_FILES['iconCurrentUpload'])  ) ? $_FILES['iconCurrentUpload']  : "" ;
		$iconBlocked  = ( isset($_FILES['iconBlockedUpload'])  ) ? $_FILES['iconBlockedUpload']  : "" ;
		
		$fv_icon_day_number = ( isset($_REQUEST['fv_icon_day_number']) ) ? $_REQUEST['fv_icon_day_number'] : "1" ;  //echo "fv_icon_day_number: ".$fv_icon_day_number."<br />";
		$fv_active = ( isset($_REQUEST['fv_active']) ) ? $_REQUEST['fv_active'] : "1" ;  //echo "fv_active: ".$fv_active."<br />";
		

		$err = fdq_ms_course_icon_edit_func($iconComplete, $iconCurrent, $iconBlocked, $fv_active, $fv_icon_day_number, $cod01);
		
		//$err[]='temp error';
		
		if( count($err) > 0 ):
			$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
			$success_msg = "";
		else:
			$success_msg = "The new icon was successfully edited";
			$error_msg = "";
		endif;
	else:
		
		/*if( $cod01 != "" ):
			$qry = "SELECT * FROM `wpou_mscm_icon` WHERE icon_id = '".$cod01."'"; //echo "qry: ".$qry."<br />";
			$rs = $wpdb->get_results($qry, ARRAY_A); //echo "<pre>";print_r($rs);echo "</pre>";
			$_icon01 = $rs[0]['icon_complete'];
			$_icon02 = $rs[0]['icon_current'];
			$_icon03 = $rs[0]['icon_blocked'];
			$fv_active = $rs[0]['active'];
		else:
			$fv_active = "1";
		endif;
		*/

	endif;

	if($ddebug):
		echo "fv_title: ".$fv_title."<br />";
		echo "fv_paid: ".$fv_paid."<br />";
	endif;

	//$success_msg = 'this is a temp message, success message !!!';
?>
<div class="mscm_main_wrapper">
	<?php if( $cod01 != "" ): ?>
		<?php if( $success_msg == "" ): ?>
			<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
			<div class="mscm_page_title">edit Icons</div>
			<div class="mscm_iconWrapper">
				<?php
					$_iconimg01 = $_iconimg02 = $_iconimg03 = "";
					if( trim($_icon01) != ""):
						$_iconimg01 = '<img src="'.$imgDir.$_icon01.'" alt="'.$_icon01.'" title="'.$_icon01.'" />';
					endif;
					if( trim($_icon02) != ""):
						$_iconimg02 = '<img src="'.$imgDir.$_icon02.'" alt="'.$_icon02.'" title="'.$_icon02.'" />';
					endif;
					if( trim($_icon03) != ""):
						$_iconimg03 = '<img src="'.$imgDir.$_icon03.'" alt="'.$_icon03.'" title="'.$_icon03.'" />';
					endif;
				?>
				<div class="mscm_icon"><?php echo $_iconimg01; ?><p>Icon Complete</p></div>
				<div class="mscm_icon"><?php echo $_iconimg02; ?><p>Icon Current</p></div>
				<div class="mscm_icon"><?php echo $_iconimg03; ?><p>Icon Blocked</p></div>
				<div style="clear:both;"></div>
			</div>
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
					<input type="hidden" name="action" value="showCourseIconEdit" />
					<input type="hidden" name="cod01" value="<?php echo $cod01; ?>" />
					<input type="submit" name="btnEditCourseIcon" id="btnEditCourseIcon" value="Save Icon" class="mscm_btn" />
					<a class="mscm_btn_cancel" href="<?php echo $pageUrl; ?>&action=showCourseIconList">Cancel</a>
				</div>
			</form>
		<?php else: ?>
			<div class="mscm_successMsgWrapper">
				<div class="mscm_successMsgInner">
					<div class="mscm_success_msg"><?php echo $success_msg; ?></div>
					<div class="mscm_successMegButtons">
						<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseIconList">Go Back</a>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<div class="mscm_successMsgWrapper">
			<div class="mscm_successMsgInner">
				<div class="mscm_success_msg">there was an error with the data, please go back and try again</div>
				<div class="mscm_successMegButtons">
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseIconList">Go back</a>
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