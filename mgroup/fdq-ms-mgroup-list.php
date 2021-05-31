<?php
	global $wpdb;
	$gvar = get_vars();
	//echo "<pre>";print_r($gvar);echo "</pre>";
	
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-mgroup.php"; //echo "pageUrl: ".$pageUrl."<br />";

	$qry_title = "";
	$qry_user_type = "";
	$qry_active = "";

	//--- INI :: email group activation/ deactivation / delete -----------------------------------------------------
		if( isset($_REQUEST['cod01']) and trim($_REQUEST['cod01']) != "" ):
			if( isset($_REQUEST['op']) ):
				$err = fdq_ms_mgroup_change_status_func($_REQUEST['cod01'], $_REQUEST['op']);
				if( count($err) > 0 ):
					$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
					$success_msg = "";
				else:
					if($_REQUEST['op'] == '1'):
						$success_msg = "The email group was successfully activated";
					else:
						$success_msg = "The email group was successfully deactivated";
					endif;
					$error_msg = "";
				endif;
			else:
				$err = fdq_ms_mgroup_del_func($_REQUEST['cod01']);
				if( count($err) > 0 ):
					$error_msg = setErrorMsg($err);
					$success_msg = "";
				else:
					$success_msg = "The email was successfully deleted";
					$error_msg = "";
				endif;
			endif;
		endif;
	//--- END :: email group activation/ deactivation / delete -----------------------------------------------------

	//-------------------------------------------------------------------------------------------------
	if( isset($_REQUEST['btnMgroupSearch']) ):

		$s_title = (isset($_REQUEST['s_title']) && trim($_REQUEST['s_title']) != "" ) ? $_REQUEST['s_title'] : "" ;
		$s_title = sanitize_text_field($s_title);

		//$s_user_type = (isset($_REQUEST['s_user_type']) && trim($_REQUEST['s_user_type']) != "" ) ? $_REQUEST['s_user_type'] : "" ;
		//$s_user_type = sanitize_text_field($s_user_type);
		$s_user_type = "all";

		$s_active = (isset($_REQUEST['s_active']) && trim($_REQUEST['s_active']) != "" ) ? $_REQUEST['s_active'] : "" ;
		$s_active = sanitize_text_field($s_active);

	else:
		$s_title = '';
		$s_user_type = 'all';
		$s_active = 'both';
	endif;

	if( trim($s_title) != "" ):
		$qry_title = "AND title like '%".$s_title."%' ";
	endif;

	/*
	if($s_user_type == '1'):
		$qry_user_type = "AND user_type = '1' ";
	elseif($s_user_type == '2'):
		$qry_user_type = "AND user_type = '2' ";
	else:
		$qry_user_type = "";
	endif;
	/**/
	$qry_user_type = "";

	if($s_active == '1'):
		$qry_active = "AND active = '1' ";
	elseif($s_active == '0'):
		$qry_active = "AND active = '0' ";
	else:
		$qry_active = "";
	endif;

	/*--- INI :: PAGINATION -----------------------------------------------------------*/
		/**/
		$pgItems = "30"; //number of records to display per page
		$pg = ( isset($_REQUEST['pg']) && trim($_REQUEST['pg']) != "" ) ? $_REQUEST['pg']  : 1 ;

		$sql = '';
		$sql .= "SELECT count(*) FROM wpou_mscm_mgroup ";
		$sql .= "WHERE 1 = 1 ";
		$sql .= $qry_title;
		$sql .= $qry_user_type;
		$sql .= $qry_active;
		$sql .= "ORDER BY mgroup_id";
		//echo "sql: ".$sql."<br />";

		
		$totalItems = getTotalItems($sql, $pg, $pgItems ); //echo "totalItems: ".$totalItems."<br />";
		
		$arr_pagination = getPaginationInfo($totalItems, $pg, $pgItems);
		//echo "<pre>"; print_r($arr_pagination); echo "</pre>";
			
		$other_params = "";
		
		if(	$s_title != "" ):
			$other_params .= "&s_title=".$s_title;
		endif;
		
		if(	$s_user_type != ""):
			//$other_params .= "&s_user_type=".$s_user_type;
		endif;

		if(	$s_active != ""):
			$other_params .= "&s_active=".$s_active;
		endif;

		$lnk_firstpg = $pageUrl.'&pg='.$arr_pagination['firstpg'].$other_params."&btnMgroupSearch=ok";  //echo "lnk_firstpg: ".$lnk_firstpg."<br />";
		$lnk_prevpg  = $pageUrl.'&pg='.$arr_pagination['prevpg'].$other_params."&btnMgroupSearch=ok";   //echo "lnk_prevpg: ".$lnk_prevpg."<br />";
		$lnk_nextpg  = $pageUrl.'&pg='.$arr_pagination['nextpg'].$other_params."&btnMgroupSearch=ok";   //echo "lnk_nextpg: ".$lnk_nextpg."<br />";
		$lnk_lastpg  = $pageUrl.'&pg='.$arr_pagination['lastpg'].$other_params."&btnMgroupSearch=ok";   //echo "lnk_lastpg: ".$lnk_lastpg."<br />";
	/*--- END :: PAGINATION -----------------------------------------------------------*/

	/*--- INI :: BUILD MAIN QUERY -----------------------------------------------------*/
		$qry = '';
		$qry .= "SELECT * FROM wpou_mscm_mgroup ";
		$qry .= "WHERE 1 = 1 ";
		$qry .= $qry_title;
		$qry .= $qry_user_type;
		$qry .= $qry_active;
		$qry .= "ORDER BY mgroup_id ";
		$qry .= "LIMIT ".$arr_pagination['lim01'].", ".$arr_pagination['lim02'];
		//echo "qry: ".$qry."<br />";

		$rs = $wpdb->get_results($qry, ARRAY_A);
		//echo "<pre>";print_r($rs);echo "</pre>";
	/*--- END :: BUILD MAIN QUERY -----------------------------------------------------*/
	
?>

<div class="mscm_main_wrapper">
	<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
	<?php if( $success_msg != "" ): ?><div class="mscm_success_msg01"><?php echo $success_msg; ?></div><?php endif; ?>
	<div class="mscm_addNewWrapper">
		<p>Email Event: <a class="mscm_btn_add" href="<?php echo $pageUrl; ?>&action=showMgroupAdd">Add New</a></p>
	</div>
	<div class="mscm_search_wrapper">
		<form action="<?php echo $pageUrl; ?>" name="c_search_form" id="c_search_form" method="POST">
			<div class="mscm_search_col01">
				Title:<br />
				<input type="text" name="s_title" id="rcat_serach" value="<?php echo $s_title; ?>">
			</div>
			<div class="mscm_search_col02" style="display:none;">
				Free/Paid:<br />
				<select name="s_user_type" id="s_user_type">
					<option value="all" <?php if($s_user_type == 'all'){ echo 'selected="selected"'; } ?>>All</option>
					<option value="1" <?php if($s_user_type == '1'){ echo 'selected="selected"'; } ?>>paid</option>
					<option value="0" <?php if($s_user_type == '0'){ echo 'selected="selected"'; } ?>>free</option>
				</select>
			</div>
			<div class="mscm_search_col03">
				Active/Inactive:<br />
				<select name="s_active" id="s_active">
					<option value="both" <?php if($s_active == 'both'){ echo 'selected="selected"'; } ?>>both</option>
					<option value="1" <?php if($s_active == '1'){ echo 'selected="selected"'; } ?>>active</option>
					<option value="0" <?php if($s_active == '0'){ echo 'selected="selected"'; } ?>>inactive</option>
				</select>
			</div>
			<div class="mscm_search_col04">
				<input type="hidden" name="action" value="showMgroupList">
				<input type="submit" name="btnMgroupSearch" id="btnMgroupSearch" value="search">
			</div>
		</form>
	</div>
	<div class="mscm_list_wrapper">
		<div class="mscm_list_title_wrapper">
			<div class="mscm_list_title" style="width:22%">Title</div>
			<div class="mscm_list_title" style="width:22%">Subject</div>
			<div class="mscm_list_title" style="width:10%">Member</div>
			<div class="mscm_list_title" style="width:10%">Inactivity</div>
			<div class="mscm_list_title" style="width:10%">Date</div>
			<div class="mscm_list_title" style="width:10%">Active</div>
			<div class="mscm_list_title" style="width:16%">Action</div>
		</div>
		<?php if( count($rs) > 0 ): ?>
			<?php foreach( $rs as $val ): ?>
				<?php
					$_inact = "";
					if( $val['active'] == '0' ):
						$_inact = "mscm_inactive";
					endif;
				?>
				<div class="mscm_list_content_wrapper">
					<?php if( 1 == 1 ): ?>
						<div class="mscm_list_content <?php echo $_inact; ?>" style="width:22%"><?php echo $val['title']; ?></div>
					<?php else: ?>
						<div class="mscm_list_content <?php echo $_inact; ?>" style="width:22%"><?php echo "[".$val['mgroup_id']."]".$val['title']; ?></div>
					<?php endif;?>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:22%"><?php echo $val['subject']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php if( $val['user_type'] == 1 ){ echo "free"; }else{ echo "paid"; }?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $val['absence_days']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $val['cdate']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $val['active']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:16%">
						<a href="<?php echo $pageUrl; ?>&action=showMgroupEdit&cod01=<?php echo $val['mgroup_id']; ?>" title="Edit Email Group" >edit</a><br />
						<?php
							$_ac = '1';
							$_ac_label ='activate';
							if( $val['active'] == '1' ):
								$_ac = '0';
								$_ac_label ='deactivate';
							endif;
						?>
						<a href="<?php echo $pageUrl; ?>&action=showMgroupList&cod01=<?php echo $val['mgroup_id']; ?>&op=<?php echo $_ac; ?>" title="<?php echo $_ac_label; ?> Content"><?php echo $_ac_label; ?></a><br /> 
						<a href="<?php echo $pageUrl; ?>&action=showMgroupList&cod01=<?php echo $val['mgroup_id']; ?>" onclick="return confirm('Are you sure you want to delete this?');" title="Deactivate Email Event">delete</a>
					</div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="mscm_list_content_wrapper">
				<div class="mscm_list_content" style="width:98.5%">No items found</div>
			</div>
		<?php endif; ?>
	</div>
	<?php //--- INI :: pagination section  ---------------------------------------------------------------------- ?>
	<?php if($arr_pagination['num_pages'] > 0): ?>
		<div class="paginationOuter">
			<div class="paginationWrapper">
				<div class="cssFirstPg"><a href="<?php echo $lnk_firstpg; ?>"><<</a></div>
				<div class="cssPrevPg"><a href="<?php echo $lnk_prevpg; ?>"><</a></div>
				<div class="cssCurPg">
					<select id="curpg" name="curpg">
						<?php
							for($i=0; $i<$arr_pagination['num_pages']; $i++):
								if( $arr_pagination['curpg'] == ($i+1) ):
									echo '<option value="'.($i+1).'" selected="selected">'.($i+1).'</option>';
								else:
									echo '<option value="'.($i+1).'">'.($i+1).'</option>';
								endif;
							endfor;
						?>
					</select> of <?php echo $arr_pagination['num_pages'] ?>
				</div>
				<div class="cssNextPg"><a href="<?php echo $lnk_nextpg; ?>">></a></div>
				<div class="cssLastPg"><a href="<?php echo $lnk_lastpg; ?>">>></a></div>
				<div style="clear:both;"></div>
			</div>
		</div>
	<?php endif; ?>
	<?php //--- END :: pagination section  ---------------------------------------------------------------------- ?>
	<div style="clear:both;"></div>
</div>