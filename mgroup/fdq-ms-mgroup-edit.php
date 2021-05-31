<?php
	global $wpdb;
	$ddebug = false;
	$gvar = get_vars(); //echo "<pre>";print_r($gvar);echo "</pre>";
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-mgroup.php"; //echo "pageUrl: ".$pageUrl."<br />";

	$error_msg = "";
	$success_msg = "";
	$err = array();

	if( isset($_REQUEST['btnEditMgroup']) ):
		$cod01 = ( isset($_REQUEST['cod01']) && trim($_REQUEST['cod01']) != "" ) ? $_REQUEST['cod01'] : "" ;
		$fv_title = ( isset($_REQUEST['fv_title']) && trim($_REQUEST['fv_title']) != "" ) ? $_REQUEST['fv_title'] : "" ;
		$fv_description = ( isset($_REQUEST['fv_description']) && trim($_REQUEST['fv_description']) != "" ) ? $_REQUEST['fv_description'] : "" ;
		$fv_absence_days = ( isset($_REQUEST['fv_absence_days']) && trim($_REQUEST['fv_absence_days']) != "" ) ? $_REQUEST['fv_absence_days'] : "" ;
		//$fv_user_type = ( isset($_REQUEST['fv_user_type']) && trim($_REQUEST['fv_user_type']) != "" ) ? $_REQUEST['fv_user_type'] : "" ;
		$fv_user_type = "1";
		$fv_subject = ( isset($_REQUEST['fv_subject']) && trim($_REQUEST['fv_subject']) != "" ) ? $_REQUEST['fv_subject'] : "" ;
		$fv_msg = ( isset($_REQUEST['fv_msg']) && trim($_REQUEST['fv_msg']) != "" ) ? $_REQUEST['fv_msg'] : "" ;
		$fv_active = ( isset($_REQUEST['fv_active']) && trim($_REQUEST['fv_active']) != "" ) ? $_REQUEST['fv_active'] : "" ;

		if( $cod01 != "" ):
			//--- send data for store process --------------------------------------------------------------------
			$err = fdq_ms_mgroup_edit_func($fv_title, $fv_description, $fv_absence_days, $fv_user_type, $fv_subject, $fv_msg, $fv_active, $cod01);
			if( count($err) > 0 ):
				$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
				$success_msg = "";
			else:
				$success_msg = "The email group was successfully updated";
				$error_msg = "";
			endif;
		endif;

	else:
		$cod01 = ( isset($_REQUEST['cod01']) && trim($_REQUEST['cod01']) != "" ) ? $_REQUEST['cod01'] : "" ;
		if( $cod01 != "" ):
			$qry = "SELECT * FROM `wpou_mscm_mgroup` WHERE `mgroup_id` = '".$cod01."'"; //echo "qry: ".$qry."<br />";
			$rs = $wpdb->get_results($qry, ARRAY_A); //echo "<pre>";print_r($rs);echo "</pre>";
			$fv_title=$rs[0]['title'];
			$fv_description=$rs[0]['description'];
			$fv_absence_days=$rs[0]['absence_days'];
			$fv_user_type="1";//$rs[0]['user_type'];
			$fv_subject=$rs[0]['subject'];
			$fv_msg=$rs[0]['msg'];
			$fv_active=$rs[0]['active'];
		else:
			$fv_title="";
			$fv_description="";
			$fv_absence_days=0;
			$fv_user_type="1";
			$fv_subject="";
			$fv_msg="";
			$fv_active="0";
		endif;
	endif;

	if($ddebug):
		echo "fv_title: ".$fv_title."<br />";
		echo "fv_description: ".$fv_description."<br />";
		echo "fv_absence_days: ".$fv_absence_days."<br />";
		echo "fv_user_type: ".$fv_user_type."<br />";
		echo "fv_subject: ".$fv_subject."<br />";
		echo "fv_msg: ".$fv_msg."<br />";
		echo "fv_active: ".$fv_active."<br />";
	endif;

	//$success_msg = 'this is a temp message, success message !!!';
?>
<div class="mscm_main_wrapper">
	<?php if( $cod01 != "" ): ?>
		<?php if( $success_msg == "" ): ?>
			<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
			<div class="mscm_page_title">Edit Email Group</div>
			<form name="recipe_add_form" id="recipe_add_form" action="<?php echo $pageUrl; ?>" method="post">
				<div class="mscm_row">
				<span class="mscm_field_title">Title (*)</span><br />
				<input type="text" name="fv_title" id="fv_title" value="<?php echo $fv_title; ?>">
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title">Description</span><br />
				<textarea name="fv_description" id="fv_description" style="height:70px;"><?php echo $fv_description; ?></textarea>
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title">Days of inactivity (*)</span><br />
				<input type="number" style="width:50px;" name="fv_absence_days" id="fv_absence_days" value="<?php echo $fv_absence_days; ?>">
			</div>
			<?php /* ?>
			<div class="mscm_row">
				<span class="mscm_field_title">Membership is (*)</span><br />
				<input type="radio" name="fv_user_type" id="fv_user_type1" value="1" <?php if($fv_user_type == '1'){ echo 'checked="checked"'; } ?> > <label for="fv_user_type1"><strong>Paid</strong></label>
				<input type="radio" name="fv_user_type" id="fv_user_type0" value="2" style="margin:0 0 0 10px" <?php if($fv_user_type == '2'){ echo 'checked="checked"'; } ?> > <label for="fv_user_type0"><strong>Free</strong></label>
			</div>
			<?php /**/ ?>
			<div class="mscm_row">
				<span class="mscm_field_title">Email Subject (*)</span><br />
				<input type="text" name="fv_subject" id="fv_subject" value="<?php echo $fv_subject; ?>">
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title">Email message (*)</span><br />
				<textarea name="fv_msg" id="fv_msg" style="max-width: 700px; height:200px;"><?php echo $fv_msg; ?></textarea>
			</div>
			<div class="mscm_row">
				<span class="mscm_field_title" style="display:inline-block;margin-bottom:8px;">Active:</span><br />
				<input type="radio" name="fv_active" id="fv_active1" value="1" <?php if($fv_active == '1'){ echo 'checked="checked"'; } ?> > <label for="fv_active1"><strong>Active</strong></label>
				<input type="radio" name="fv_active" id="fv_active0" value="0" style="margin:0 0 0 10px" <?php if($fv_active == '0'){ echo 'checked="checked"'; } ?> > <label for="fv_active0"><strong>Inactive</strong></label>
			</div>
				<div class="mscm_btn_row">
					<input type="hidden" name="cod01" value="<?php echo $cod01; ?>" />
					<input type="hidden" name="action" value="showMgroupEdit" />
					<input type="submit" name="btnEditMgroup" id="btnEditMgroup" value="Edit Email Group" class="mscm_btn" />
					<a class="mscm_btn_cancel" href="<?php echo $pageUrl; ?>&action=showMgroupList">Cancel</a>
				</div>
			</form>
		<?php else: ?>
			<div class="mscm_successMsgWrapper">
				<div class="mscm_successMsgInner">
					<div class="mscm_success_msg"><?php echo $success_msg; ?></div>
					<div class="mscm_successMegButtons">
						<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showMgroupList">Go back</a>
					</div>
				</div>
			</div>
		<?php endif; ?>
	<?php else: ?>
		<div class="mscm_successMsgWrapper">
			<div class="mscm_successMsgInner">
				<div class="mscm_success_msg">there was an error with the data, please go back and try again</div>
				<div class="mscm_successMegButtons">
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showMgroupList">Go back</a>
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