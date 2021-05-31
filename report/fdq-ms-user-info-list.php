<?php
	global $wpdb;
	$gvar = get_vars();
	//echo "<pre>";print_r($gvar);echo "</pre>";

	if( $_SERVER['SERVER_NAME'] == 'locallab.dev'):
		$user_tbl = 'wpou_users';
	else:
		$user_tbl = 'wp_users';
	endif; 
	
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-user-info.php"; //echo "pageUrl: ".$pageUrl."<br />";

	$qry_criteria = "";
	$qry_searchBy = "";
	$qry_active = "";

	//--- INI :: SEARCH management ------------------------------------------------------------------------------------------
		if( isset($_REQUEST['btnUsearch']) ):

			$s_criteria = (isset($_REQUEST['s_criteria']) && trim($_REQUEST['s_criteria']) != "" ) ? $_REQUEST['s_criteria'] : "" ;
			$s_criteria = sanitize_text_field($s_criteria);

			$searchBy = (isset($_REQUEST['searchBy']) && trim($_REQUEST['searchBy']) != "" ) ? $_REQUEST['searchBy'] : "" ;
			$searchBy = sanitize_text_field($searchBy);

		else:
			$s_criteria = '';
			$searchBy = 'n';
		endif;
	//--- END :: SEARCH management ------------------------------------------------------------------------------------------
	
	//--- 
		if( trim($s_criteria) != "" ):
			if($searchBy == 'n'):
				$qry_criteria = "AND b.display_name like '%".$s_criteria."%' ";
			elseif($searchBy == 'u'):
				$qry_criteria = "AND b.user_login like '%".$s_criteria."%' ";
			elseif($searchBy == 'e'):
				$qry_criteria = "AND b.user_email like '%".$s_criteria."%' ";
			else:
				$qry_searchBy = "";
			endif;
		else:
			$qry_searchBy = "";
		endif;
	//---

	/*--- INI :: PAGINATION -----------------------------------------------------------*/
		/**/
		$pgItems = "50"; //number of records to display per page
		$pg = ( isset($_REQUEST['pg']) && trim($_REQUEST['pg']) != "" ) ? $_REQUEST['pg']  : 1 ;

		$sql = '';
		//$sql .= "SELECT DISTINCT a.user_pid, b.display_name, b.user_login, b.user_email FROM wpou_mscm_assignment a ";
		$sql .= "SELECT count(DISTINCT a.user_pid), b.display_name, b.user_login, b.user_email FROM wpou_mscm_assignment a ";
		$sql .= "INNER JOIN ".$user_tbl." b ON a.user_pid = b.ID ";
		$sql .= "WHERE 1 = 1 ";
		$sql .= $qry_criteria;
		$qry .= "ORDER BY a.user_pid ";
		//echo "sql: ".$sql."<br />";

		
		$totalItems = getTotalItems($sql, $pg, $pgItems ); //echo "totalItems: ".$totalItems."<br />";
		
		$arr_pagination = getPaginationInfo($totalItems, $pg, $pgItems);
		//echo "<pre>"; print_r($arr_pagination); echo "</pre>";
		
		$other_params = "";
		
		if(	$s_criteria != "" ):
			$other_params .= "&s_criteria=".$s_criteria;
		endif;
		
		if(	$searchBy != ""):
			$other_params .= "&searchBy=".$searchBy;
		endif;

		$lnk_firstpg = $pageUrl.'&pg='.$arr_pagination['firstpg'].$other_params."&btnUsearch=ok";  //echo "lnk_firstpg: ".$lnk_firstpg."<br />";
		$lnk_prevpg  = $pageUrl.'&pg='.$arr_pagination['prevpg'].$other_params."&btnUsearch=ok";   //echo "lnk_prevpg: ".$lnk_prevpg."<br />";
		$lnk_nextpg  = $pageUrl.'&pg='.$arr_pagination['nextpg'].$other_params."&btnUsearch=ok";   //echo "lnk_nextpg: ".$lnk_nextpg."<br />";
		$lnk_lastpg  = $pageUrl.'&pg='.$arr_pagination['lastpg'].$other_params."&btnUsearch=ok";   //echo "lnk_lastpg: ".$lnk_lastpg."<br />";
		/**/
	/*--- END :: PAGINATION -----------------------------------------------------------*/

	/*--- INI :: BUILD MAIN QUERY -----------------------------------------------------*/
		$qry = '';
		$qry .= "SELECT DISTINCT a.user_pid, b.display_name, b.user_login, b.user_email FROM wpou_mscm_assignment a ";
		$qry .= "INNER JOIN ".$user_tbl." b ON a.user_pid = b.ID ";
		$qry .= "WHERE 1 = 1 ";
		$qry .= $qry_criteria;
		$qry .= "ORDER BY a.user_pid ";
		$qry .= "LIMIT ".$arr_pagination['lim01'].", ".$arr_pagination['lim02'];
		//echo "qry: ".$qry."<br />";

		$rs = $wpdb->get_results($qry, ARRAY_A);
		//echo "<pre>";print_r($rs);echo "</pre>";
	/*--- END :: BUILD MAIN QUERY -----------------------------------------------------*/
	
?>

<div class="mscm_main_wrapper">
	<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
	<?php if( $success_msg != "" ): ?><div class="mscm_success_msg01"><?php echo $success_msg; ?></div><?php endif; ?>
	
	<div class="mscm_search_wrapper">
		<form action="<?php echo $pageUrl; ?>" name="c_search_form" id="c_search_form" method="POST">
			<div style="width:100%;box-sizing:border-box; padding:15px 0;">
				<h2>User list, pick a user and hit "Get Info"</h2>
			</div>
			<div class="mscm_search_col01" style="max-width:300px;">
				Search criteria	:<br />
				<input type="text" name="s_criteria" id="rcat_serach" value="<?php echo $s_criteria; ?>" style="width:95%" />
			</div>
			<div class="mscm_search_col02" style="max-width:100px;">
				Search by:<br />
				<select name="searchBy" id="searchBy">
					<option value="n" <?php if($searchBy == 'n'){ echo 'selected="selected"'; } ?>>Name</option>
					<option value="u" <?php if($searchBy == 'u'){ echo 'selected="selected"'; } ?>>username</option>
					<option value="e" <?php if($searchBy == 'e'){ echo 'selected="selected"'; } ?>>email</option>
				</select>
			</div>
			<?php /*<div class="mscm_search_col03">
				Active/Inactive:<br />
				<select name="s_active" id="s_active">
					<option value="both" <?php if($s_active == 'both'){ echo 'selected="selected"'; } ?>>both</option>
					<option value="1" <?php if($s_active == '1'){ echo 'selected="selected"'; } ?>>active</option>
					<option value="0" <?php if($s_active == '0'){ echo 'selected="selected"'; } ?>>inactive</option>
				</select>
			</div> */?>
			<div class="mscm_search_col04">
				<input type="hidden" name="action" value="showUserInfoList">
				<input type="submit" name="btnUsearch" id="btnUsearch" value="search">
			</div>
		</form>
	</div>
	<div class="mscm_list_wrapper">
		<div class="mscm_list_title_wrapper">
			<div class="mscm_list_title" style="width:10%">UserID</div>
			<div class="mscm_list_title" style="width:28%">Name</div>
			<div class="mscm_list_title" style="width:14%">Username</div>
			<div class="mscm_list_title" style="width:28%">Email</div>
			<div class="mscm_list_title" style="width:20%">Action</div>
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
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $val['user_pid']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:28%"><?php echo $val['display_name']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:14%"><?php echo $val['user_login']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:28%"><?php echo $val['user_email']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:20%">
						<a href="<?php echo $pageUrl; ?>&action=showUserInfoDetail&cod01=<?php echo $val['user_pid']; ?>" title="Get User Information" >Get Info</a>
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