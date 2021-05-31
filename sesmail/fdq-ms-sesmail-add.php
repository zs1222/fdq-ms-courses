<?php
	global $wpdb;
	$ddebug = false;
	$gvar = get_vars(); //echo "<pre>";print_r($gvar);echo "</pre>";
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-sesmail.php"; //echo "pageUrl: ".$pageUrl."<br />";

	$error_msg = "";
	$success_msg = "";
	$err = array();

	if( isset($_REQUEST['btnAddsesmail']) ):
		
		$fv_title = ( isset($_REQUEST['fv_title']) && trim($_REQUEST['fv_title']) != "" ) ? $_REQUEST['fv_title'] : "" ;
		$fv_description = ( isset($_REQUEST['fv_description']) && trim($_REQUEST['fv_description']) != "" ) ? $_REQUEST['fv_description'] : "" ;
		$fv_session_day = ( isset($_REQUEST['fv_session_day']) && trim($_REQUEST['fv_session_day']) != "" ) ? $_REQUEST['fv_session_day'] : "" ;
		//$fv_user_type = ( isset($_REQUEST['fv_user_type']) && trim($_REQUEST['fv_user_type']) != "" ) ? $_REQUEST['fv_user_type'] : "" ;
		$fv_user_type="1";
		$fv_subject = ( isset($_REQUEST['fv_subject']) && trim($_REQUEST['fv_subject']) != "" ) ? $_REQUEST['fv_subject'] : "" ;
		$fv_msg = ( isset($_REQUEST['fv_msg']) && trim($_REQUEST['fv_msg']) != "" ) ? $_REQUEST['fv_msg'] : "" ;
		$fv_active = ( isset($_REQUEST['fv_active']) && trim($_REQUEST['fv_active']) != "" ) ? $_REQUEST['fv_active'] : "" ;

		if( 1 == 2 ):
			echo "fv_title: ".$fv_title."<br />";
			echo "fv_description: ".$fv_description."<br />";
			echo "fv_session_day: ".$fv_session_day."<br />";
			echo "fv_user_type: ".$fv_user_type."<br />";
			echo "fv_subject: ".$fv_subject."<br />";
			echo "fv_msg: ".$fv_msg."<br />";
			echo "fv_active: ".$fv_active."<br />";
		endif;

		//--- send data for store process --------------------------------------------------------------------
		
		$err = fdq_ms_sesmail_add_func($fv_title, $fv_description, $fv_session_day, $fv_user_type, $fv_subject, $fv_msg, $fv_active);
		//$err = array('temp error');
		if( count($err) > 0 ):
			$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
			$success_msg = "";
		else:
			$success_msg = "The new session mail was successfully created";
			$error_msg = "";
		endif;

	else:
		$fv_title="";
		$fv_description="";
		$fv_session_day=0;
		$fv_user_type="1";
		$fv_subject="";
		$fv_msg="";
		$fv_active="0";
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
		<div class="mscm_page_title">Add New Session Mail</div>
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
				<span class="mscm_field_title">Course session</span><br />
				<input type="number" style="width:50px;" name="fv_session_day" id="fv_session_day" value="<?php echo $fv_session_day; ?>">
			</div>
			<?php /* ?>
			<div class="mscm_row">
				<span class="mscm_field_title">Membership is (*)</span><br />
				<input type="radio" name="fv_user_type" id="fv_user_type1" value="1" <?php if($fv_user_type == '1'){ echo 'checked="checked"'; } ?> > <label for="fv_user_type1"><strong>Paid</strong></label>
				<input type="radio" name="fv_user_type" id="fv_user_type0" value="2" style="margin:0 0 0 10px" <?php if($fv_user_type == '0'){ echo 'checked="checked"'; } ?> > <label for="fv_user_type0"><strong>Free</strong></label>
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
				<input type="hidden" name="action" value="showsesmailAdd" />
				<input type="submit" name="btnAddsesmail" id="btnAddsesmail" value="Save Session Email" class="mscm_btn" />
				<a class="mscm_btn_cancel" href="<?php echo $pageUrl; ?>&action=showsesmailList">Cancel</a>
			</div>
		</form>
	<?php else: ?>
		<div class="mscm_successMsgWrapper">
			<div class="mscm_successMsgInner">
				<div class="mscm_success_msg"><?php echo $success_msg; ?></div>
				<div class="mscm_successMegButtons">
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showsesmailAdd">Add Session Mail</a>
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showsesmailList">Cancel</a>
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