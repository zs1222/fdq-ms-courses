<?php
	global $wpdb;
	$ddebug = false;
	$gvar = get_vars(); //echo "<pre>";print_r($gvar);echo "</pre>";
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-user-info.php"; //echo "pageUrl: ".$pageUrl."<br />";
	$formUrl = $gvar['site_url']."wp-admin/admin.php"; //echo "formUrl: ".$pageUrl."<br />";
	
	if( $_SERVER['SERVER_NAME'] == 'locallab.dev'):
		$user_tbl = 'wpou_users';
	else:
		$user_tbl = 'wp_users';
	endif; 

	$showCsvBtn = false;
	$showDownloadBtn = false;

	$rs = array();

	$cod01 = ( isset($_REQUEST['cod01']) && trim($_REQUEST['cod01']) != "" ) ? $_REQUEST['cod01'] : "" ;

	if( isset($_REQUEST['btnCsearch']) || isset($_REQUEST['btnGenCsv']) ):
		$fv_course = ( isset($_REQUEST['fv_course']) && trim($_REQUEST['fv_course']) != "" ) ? $_REQUEST['fv_course'] : "" ;
		if( $fv_course != "--" ):
			//--- get the User information -----------------------------------------------------------------------------------
			if( $fv_course == "all" ):
				$qry  = "SELECT a.ID, DATE(a.user_registered) as rdate, a.display_name, a.user_login, a.user_email, b.last_visit, b.advanced, b.course_pid FROM ".$user_tbl." a ";
				$qry .= "INNER JOIN wpou_mscm_assignment b ON a.ID = b.user_pid ";
				$qry .= "WHERE b.user_pid = '".$cod01."'";
				//echo "qry: ".$qry."<br />";
				$rs = $wpdb->get_results($qry, ARRAY_A);
				//echo "<pre>"; print_r($rs); echo "</pre>";
			else:
				$qry  = "SELECT a.ID, DATE(a.user_registered) as rdate, a.display_name, a.user_login, a.user_email, b.last_visit, b.advanced, b.course_pid FROM ".$user_tbl." a ";
				$qry .= "INNER JOIN wpou_mscm_assignment b ON a.ID = b.user_pid AND b.course_pid = '".$fv_course."' ";
				$qry .= "WHERE a.ID = '".$cod01."'";
				$rs = $wpdb->get_results($qry, ARRAY_A);
			endif;

		endif;
	else:
		$fv_course = '--';
		$sessionNum = 0;
	endif;

	if($ddebug):
		echo "cod01: ".$cod01."<br />";
		echo "fv_course: ".$fv_course."<br />";
	endif;
	
	//--- INI :: get courses list ----------------------------------------------------
		$qry = "SELECT * FROM wpou_mscm_course WHERE active = '1'";
		$courseRs = $wpdb->get_results($qry, ARRAY_A);
		//echo "<pre>"; print_r($courseRs); echo "</pre>";
	//--- END :: get courses list ----------------------------------------------------

	//--- INI :: Built array ----------------------------------------------------------
		$rs_arr = array();
		$co = 0;
		if( count($rs)>0 ):
			//echo "<pre>"; print_r($rs); echo "</pre>";
			foreach($rs as $value):
				$strtime = strtotime($value['last_visit']);
				//$last_login = date("l jS F Y", $strtime);
				//$last_login = date("jS F Y", $strtime);
				//$last_login = date("F d, Y", $strtime);
				$last_login = date("Y-m-d", $strtime);

				$curdate = strtotime(date('Y-m-d'));           //echo "curdate: ".$curdate."<br />";
				$olddate = strtotime(date('Y-m-d', $strtime)); //echo "olddate: ".$olddate."<br />";
				
				$ndate = (($curdate - $olddate) / 86400);

				//--- get the number of sessions a course have -------------------------------------------------------------------
				$sql = "SELECT count(*) from wpou_mscm_content WHERE course_pid = '".$value['course_pid']."'";
				$sessionNum = $wpdb->get_var($sql);

				//--- get the Course name ----------------------------------------------------------------------------------------
				$sql = "SELECT title from wpou_mscm_course WHERE course_id = '".$value['course_pid']."'";
				$cName = $wpdb->get_var($sql);

				$rs_arr[$co]['user_id'] = $value['ID'];
				$rs_arr[$co]['rdate'] = $value['rdate'];
				$rs_arr[$co]['name'] = $value['display_name'];
				$rs_arr[$co]['username'] = $value['user_login'];
				$rs_arr[$co]['email'] = $value['user_email'];
				$rs_arr[$co]['course_name'] = $cName;
				$rs_arr[$co]['sessions'] = $sessionNum;
				$rs_arr[$co]['ses_advanced'] = $value['advanced'];
				$rs_arr[$co]['last_login'] = $last_login;
				$rs_arr[$co]['days'] = $ndate; //." days ago";
				$co++;
			endforeach;
			
			//echo "<pre>"; print_r($rs_arr); echo "</pre>";
		endif;
	//--- END :: Built array ----------------------------------------------------------

	//--- INI :: Generate CSV ---------------------------------------------------------
		if( isset($_REQUEST['btnGenCsv']) && count($rs_arr) > 0 ):
			$csv_sep = ",";

			$csv_url =  $gvar['plugin_dir']."csv_files/"; //echo "csv_url: ".$csv_url."<br />";
			$csv_path = $gvar['plugins_dir_realpath']."/csv_files/"; //echo "csv_path: ".$csv_path."<br />";
			$csv_filename = "user_info".date('Ymd-Hi').".csv"; //echo "csv_filename: ".$csv_filename."<br />";	
			$csv_header = "";
			$csv_row = "";

			$csv_file = realpath($csv_path)."/".$csv_filename; //echo "csv_file: ".$csv_file."<br />"; 
			$fp = fopen($csv_file, 'w');

			$csv_arr_header = ["USER_ID","Registration Date","Name", "Username", "email", "Course Name", "Completed Sessions", "Last Login", "Days"];
			$csv_header = implode($csv_sep, $csv_arr_header);
			$csv_header = $csv_header.PHP_EOL;
			fputs($fp, $csv_header);

			foreach( $rs_arr as $val ):
				$csv_row = $val['user_id'].$csv_sep.$val['rdate'].$csv_sep.$val['name'].$csv_sep.$val['username'].$csv_sep;
				$csv_row .= $val['email'].$csv_sep.$val['course_name'].$csv_sep.$val['ses_advanced']." of ".$val['sessions'].$csv_sep;
				$csv_row .= $val['last_login'].$csv_sep.$val['days'].PHP_EOL;
				fputs($fp, $csv_row);
			endforeach;
			fclose($fp);

			$csv_download_link = $csv_url.$csv_filename;
			$showDownloadBtn = true;
		else:
			//echo "no entro<br />";
		endif;
	//--- END :: Generate CSV ---------------------------------------------------------

	if( count($rs_arr) > 0 ):
		$showCsvBtn = true;
	endif;
?>
<div class="mscm_main_wrapper">
	<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
	<?php if( $success_msg != "" ): ?><div class="mscm_success_msg01"><?php echo $success_msg; ?></div><?php endif; ?>
	
	<div class="mscm_search_wrapper">
		<form action="<?php echo $formUrl; ?>" name="c_search_form" id="c_search_form" method="GET">
			<div style="width:100%;box-sizing:border-box; padding:15px 0;">
				<h2>User Information: Select a course and hit search</h2>
				<input type="hidden" name="page" value="fdq-ms-user-info.php">
			</div>
			<div class="mscm_search_col01" style="max-width:230px;">
				Course	:<br />
				<select name="fv_course" id="fv_course">
					<option value="--">Select a course</option>
					<option value="all" <?php if($fv_course == 'all') echo 'selected="selected"'; ?> >all courses</option>
					<?php
						foreach($courseRs as $course):
							if( $fv_course == $course['course_id'] ):
								echo '<option value="'.$course['course_id'].'" selected="selected">'.$course['title'].'</option>';
							else:
								echo '<option value="'.$course['course_id'].'">'.$course['title'].'</option>';
							endif;
						endforeach;
					?>
				</select>
			</div>
			<div class="mscm_search_col02" style="padding:16px 0 1px;">
				<input type="hidden" name="action" value="showUserInfoDetail">
				<input type="hidden" name="cod01" value="<?php echo $cod01; ?>">
				<input type="submit" name="btnCsearch" id="btnCsearch" value="search">
			</div>
			<div class="mscm_search_col03" style="max-width:290px;padding:16px 0 1px;">
				<?php if( $showCsvBtn || 1 == 2 ): ?><input type="submit" name="btnGenCsv" id="btnGenCsv" class="fdq_mscm_btn_generatecsv" value="Gen CSV"><?php endif; ?>
				<?php if( $showDownloadBtn || 1 == 2 ): ?><a class="fdq_mscm_btn_generatecsv" href="<?php echo $csv_download_link; ?>">Download</a><?php endif; ?>
			</div>
			<?php /*
				<div class="mscm_search_col04">
				</div>
			*/?>
		</form>
	</div>
	<div class="mscm_list_wrapper">
		<div class="mscm_list_title_wrapper">
			<div class="mscm_list_title" style="width:9%">ID/RegDate</div>
			<div class="mscm_list_title" style="width:12%">Name</div>
			<div class="mscm_list_title" style="width:20%">Username</div>
			<div class="mscm_list_title" style="width:20%">Email</div>
			<div class="mscm_list_title" style="width:9%">Course</div>
			<div class="mscm_list_title" style="width:10%">C. Sessions</div>
			<div class="mscm_list_title" style="width:10%">Last Login</div>
			<div class="mscm_list_title" style="width:10%">days</div>
		</div>
		<?php if( count($rs_arr) > 0 ): ?>
			<?php foreach( $rs_arr as $val ): ?>
				<div class="mscm_list_content_wrapper">
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:9%"><?php echo $val['user_id']."<br />".$val['rdate']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:12%"><?php echo $val['name']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:20%"><?php echo $val['username']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:20%"><?php echo $val['email']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:9%"><?php echo $val['course_name']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $val['ses_advanced']." of ".$val['sessions']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $val['last_login']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $val['days']; ?></div>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<div class="mscm_list_content_wrapper">
				<div class="mscm_list_content" style="width:98.5%">No match found</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="mscm_btn_row" style="margin:20px 0 0 0;">
		<a class="mscm_btn_cancel" href="<?php echo $pageUrl; ?>&action=showUserInfoList">Cancel</a>
	</div>
	<div style="clear:both;"></div>
</div>