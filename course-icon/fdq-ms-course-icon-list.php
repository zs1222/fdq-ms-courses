<?php
	global $wpdb;
	$gvar = get_vars();
	//echo "<pre>";print_r($gvar);echo "</pre>";
	
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-course-icon.php"; //echo "pageUrl: ".$pageUrl."<br />";
	$imgDir = $gvar['img_dir'];

	$qry_title = "";
	$qry_paid = "";
	$qry_active = "";

	//--- INI :: course icon deactivation -------------------------------------------------------------------------------
		if( isset($_REQUEST['cod01']) and trim($_REQUEST['cod01']) != "" ):
			$err = fdq_ms_course_icon_deactivate_func($_REQUEST['cod01']);
			if( count($err) > 0 ):
				$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
				$success_msg = "";
			else:
				$success_msg = "The course was successfully deactivated";
				$error_msg = "";
			endif;
		endif;
	//--- END :: course deactivation -------------------------------------------------------------------------------

	//-------------------------------------------------------------------------------------------------
	if( isset($_REQUEST['btnCsearch']) ):

		$s_title = (isset($_REQUEST['s_title']) && trim($_REQUEST['s_title']) != "" ) ? $_REQUEST['s_title'] : "" ;
		$s_title = sanitize_text_field($s_title);

		$s_active = (isset($_REQUEST['s_active']) && trim($_REQUEST['s_active']) != "" ) ? $_REQUEST['s_active'] : "" ;
		$s_active = sanitize_text_field($s_active);

	else:
		$s_title = '';
		$s_active = '1';
	endif;

	if( trim($s_title) != "" ):
		$qry_title = "AND icon_complete like '%".$s_title."%' ";
	endif;

	if($s_active == '1'):
		$qry_active = "AND active = '1' ";
	elseif($s_active == '0'):
		$qry_active = "AND active = '0' ";
	else:
		$qry_active = "";
	endif;

	/*--- INI :: PAGINATION -----------------------------------------------------------*/
		/**/
		$pgItems = "15"; //number of records to display per page
		$pg = ( isset($_REQUEST['pg']) && trim($_REQUEST['pg']) != "" ) ? $_REQUEST['pg']  : 1 ;

		$sql = '';
		$sql .= "SELECT count(*) FROM wpou_mscm_icon ";
		$sql .= "WHERE 1 = 1 ";
		$sql .= $qry_title;
		$sql .= $qry_active;
		$sql .= "ORDER BY icon_id";
		//echo "sql: ".$sql."<br />";

		
		$totalItems = getTotalItems($sql, $pg, $pgItems ); //echo "totalItems: ".$totalItems."<br />";
		
		$arr_pagination = getPaginationInfo($totalItems, $pg, $pgItems);
		//echo "<pre>"; print_r($arr_pagination); echo "</pre>";
		
		$other_params = "";
		
		if(	$s_title != "" ):
			$other_params .= "&s_title=".$s_title;
		endif;

		if(	$s_active != ""):
			$other_params .= "&s_active=".$s_active;
		endif;

		$lnk_firstpg = $pageUrl.'&pg='.$arr_pagination['firstpg'].$other_params."&btnCsearch=ok";  //echo "lnk_firstpg: ".$lnk_firstpg."<br />";
		$lnk_prevpg  = $pageUrl.'&pg='.$arr_pagination['prevpg'].$other_params."&btnCsearch=ok";   //echo "lnk_prevpg: ".$lnk_prevpg."<br />";
		$lnk_nextpg  = $pageUrl.'&pg='.$arr_pagination['nextpg'].$other_params."&btnCsearch=ok";   //echo "lnk_nextpg: ".$lnk_nextpg."<br />";
		$lnk_lastpg  = $pageUrl.'&pg='.$arr_pagination['lastpg'].$other_params."&btnCsearch=ok";   //echo "lnk_lastpg: ".$lnk_lastpg."<br />";
	/*--- END :: PAGINATION -----------------------------------------------------------*/

	/*--- INI :: BUILD MAIN QUERY -----------------------------------------------------*/
		$qry = '';
		$qry .= "SELECT * FROM wpou_mscm_icon ";
		$qry .= "WHERE 1 = 1 ";
		$qry .= $qry_title;
		$qry .= $qry_active;
		$qry .= "ORDER BY icon_id ";
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
		<p>Icons: <a class="mscm_btn_add" href="<?php echo $pageUrl; ?>&action=showCourseIconAdd">Add New</a></p>
	</div>
	<div class="mscm_search_wrapper">
		<form action="<?php echo $pageUrl; ?>" name="c_search_form" id="c_search_form" method="POST">
			<div class="mscm_search_col01">
				Icon name:<br />
				<input type="text" name="s_title" id="rcat_serach" value="<?php echo $s_title; ?>">
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
				<input type="hidden" name="action" value="showCourseIconList">
				<input type="submit" name="btnCsearch" id="btnCsearch" value="search">
			</div>
		</form>
	</div>
	<div class="mscm_list_wrapper">
		<div class="mscm_list_title_wrapper">
			<div class="mscm_list_title" style="width:12%">Icon Name</div>
			<div class="mscm_list_title" style="width:18%">Icon Complete</div>
			<div class="mscm_list_title" style="width:18%">Icon Current</div>
			<div class="mscm_list_title" style="width:18%">Icon Inactive</div>
			<div class="mscm_list_title" style="width:12%">Date</div>
			<div class="mscm_list_title" style="width:10%">Active</div>
			<div class="mscm_list_title" style="width:12%">Action</div>
		</div>
		<?php if( count($rs) > 0 ): ?>
			<?php foreach( $rs as $val ): ?>
				<?php
					$_inact = "";
					if( $val['active'] == '0' ):
						$_inact = "mscm_inactive";
					endif;
					
					$_icon01 = $_icon02 = $_icon03 = "";
					if( trim($val['icon_complete']) != "" ):
						$_icon01 = '<img class="course_icon" src="'.$imgDir.$val['icon_complete'].'" alt="'.$val['icon_complete'].'" title="'.$val['icon_complete'].'" />';
					endif;
					if( trim($val['icon_current']) != "" ):
						$_icon02 = '<img class="course_icon" src="'.$imgDir.$val['icon_current'].'" alt="'.$val['icon_current'].'" title="'.$val['icon_current'].'" />';
					endif;
					if( trim($val['icon_blocked']) != "" ):
						$_icon03 = '<img class="course_icon" src="'.$imgDir.$val['icon_blocked'].'" alt="'.$val['icon_blocked'].'" title="'.$val['icon_blocked'].'" />';
					endif;
				?>
				<div class="mscm_list_content_wrapper">
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:12%"><?php echo $val['icon_day_number']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:18%"><?php echo $_icon01; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:18%"><?php echo $_icon02; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:18%"><?php echo $_icon03; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:12%"><?php echo $val['cdate']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $val['active']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:12%">
						<a href="<?php echo $pageUrl; ?>&action=showCourseIconEdit&cod01=<?php echo $val['icon_id']; ?>">edit</a> | 
						<a href="<?php echo $pageUrl; ?>&action=showCourseIconList&cod01=<?php echo $val['icon_id']; ?>">deactivate</a> 
						<?php /* ?><a href="<?php echo $pageUrl; ?>&action=showCourseView&cod01=<?php echo $val['icon_id']; ?>">view</a><?php /**/ ?>
						<?php /* ?><a href="<?php echo $pageUrl; ?>&action=showCourseDel&cod01=<?php echo $val['icon_id']; ?>">del</a><?php /**/ ?>
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