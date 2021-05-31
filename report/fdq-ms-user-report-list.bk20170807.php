<?php
	global $wpdb;
	$ddebug = false;
	$gvar = get_vars();
	//echo "<pre>";print_r($gvar);echo "</pre>";

	
	if( $_SERVER['SERVER_NAME'] == 'locallab.dev'):
		$user_tbl = 'wpou_users';
	else:
		$user_tbl = 'wp_users';
	endif; 
	
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-user-report.php"; //echo "pageUrl: ".$pageUrl."<br />";
	$formUrl = $gvar['site_url']."wp-admin/admin.php?";

	$result_arr = array();
	$getResultOK = false;
	$listTitle = "Please Select Course, Session and Number of Days";
	$showCsvBtn = false;
	$showDownloadBtn = false;

	$fv_course = ( isset($_REQUEST['fv_course']) && trim($_REQUEST['fv_course']) != "" ) ? $_REQUEST['fv_course'] : "--" ;
	$fv_session = ( isset($_REQUEST['fv_session']) && trim($_REQUEST['fv_session']) != "" ) ? $_REQUEST['fv_session'] : "--" ;
	$fv_days = ( isset($_REQUEST['fv_days']) && trim($_REQUEST['fv_days']) != "" ) ? $_REQUEST['fv_days'] : 0 ;
	$fv_autosubmit = $_REQUEST['autosubmit'];
	
	if($ddebug):
		echo "<br /><br /><br />";
		echo "autosubmit: ".$_REQUEST['autosubmit']."<br />";
		echo "fv_course: ".$fv_course."<br />";
		echo "fv_session: ".$fv_session."<br />";
		echo "fv_days: ".$fv_days."<br />";
	endif;

	//wpou_users wpou_mscm_assignment wpou_mscm_course wpou_mscm_content

	if( isset($_REQUEST['formdata']) && trim($_REQUEST['formdata']) != "" ):
		
		if( $fv_autosubmit == 'y' ):
			$fv_session = '--';
			$fv_days = 0;
		else:
			//--- INI :: Get Course Name --------------------------------------------------------------------------
				if( trim($fv_course) != "--" ):
					$courseName = fdq_ms_get_course_name($fv_course);
				else:
					$courseName = "--";
				endif;
				if( $ddebug ) echo "<b>courseName:</b> ".$courseName."<br />";
			//--- END :: Get Course Name --------------------------------------------------------------------------

			//--- INI ::Get Session Name --------------------------------------------------------------------------
				if( trim($fv_session) != "--" ):
					$ses_arr = fdq_ms_get_session_name($fv_session);
					$sessionName = $ses_arr['ses_name'];
					$sessionNum = $ses_arr['ses_num'];
					$sessionTot = fdq_ms_get_session_total($fv_course);
				else:
					$sessionName = "--";
					$sessionNum = '--';
					$sessionTot = "--";
				endif;
				if( $ddebug ):
					echo "<b>sessionName:</b> ".$sessionName."<br />";
					echo "<b>sessionNum:</b> ".$sessionNum."<br />";
					echo "<b>sessionTot:</b> ".$sessionTot."<br />";
				endif;
			//--- END ::Get Session Name --------------------------------------------------------------------------


			if( trim($fv_course) != "--" && trim($fv_session) != "--" && isNumber($fv_days) && $fv_days > 0 ):
				if( $ddebug ) echo "search for course/session with days<br />";
				$listTitle = "List of users currently in session ".$sessionNum." of the Course \"".$courseName."\" and have not returned in ".$fv_days;

				$qry =  "SELECT u.ID, DATE(u.user_registered) as rdate, u.display_name, u.user_login, u.user_email, a.last_visit, a.finished, ";
				$qry .= "DATE(a.last_visit) as last_login, TIMESTAMPDIFF(DAY,DATE(last_visit),CURDATE()) AS days FROM wpou_mscm_assignment a ";
				$qry .= "INNER JOIN ".$user_tbl." u ON a.user_pid=u.ID ";
				$qry .= "WHERE a.finished = '0' ";
				$qry .= "AND a.course_pid = '".$fv_course."' ";
				$qry .= "AND advanced='".$sessionNum."' ";
				$qry .= "AND TIMESTAMPDIFF(DAY,DATE(last_visit),CURDATE()) = '".$fv_days."'";
				if( $ddebug ) echo "qry: ".$qry."<br />";

				$getResultOK = true;
			elseif( trim($fv_course) != "--" && trim($fv_session) != "--" ):
				if( $ddebug ) echo "search for course/session without days<br />";
				$listTitle = "List of users currently in session ".$sessionNum." of the Course \"".$courseName."\"";

				$qry = "SELECT u.ID, DATE(u.user_registered) as rdate, u.display_name, u.user_login, u.user_email, a.last_visit, a.finished, ";
				$qry .= "DATE(a.last_visit) as last_login, TIMESTAMPDIFF(DAY,DATE(last_visit),CURDATE()) AS days FROM wpou_mscm_assignment a ";
				$qry .= "INNER JOIN ".$user_tbl." u ON a.user_pid=u.ID ";
				$qry .= "WHERE a.finished = '0' ";
				$qry .= "AND a.course_pid = '".$fv_course."' ";
				$qry .= "AND advanced='".$sessionNum."'";
				if( $ddebug ) echo "qry: ".$qry."<br />";

				$getResultOK = true;
			elseif( trim($fv_course) != "--" && isNumber($fv_days) && $fv_days > 0 ):
				if( $ddebug ) echo "search for course with days<br />";
				$listTitle = "List of Users that has Completed Course \"".$courseName."\" and have not returned in ".$fv_days;

				$qry = "SELECT u.ID, DATE(u.user_registered) as rdate, u.display_name, u.user_login, u.user_email, a.last_visit, a.finished, ";
				$qry .= "DATE(a.last_visit) as last_login, TIMESTAMPDIFF(DAY,DATE(last_visit),CURDATE()) AS days FROM wpou_mscm_assignment a ";
				$qry .= "INNER JOIN ".$user_tbl." u ON a.user_pid=u.ID ";
				$qry .= "WHERE a.finished = '1' ";
				$qry .= "AND a.course_pid = '".$fv_course."' ";
				$qry .= "AND TIMESTAMPDIFF(DAY,DATE(last_visit),CURDATE()) = '".$fv_days."'";
				if( $ddebug ) echo "qry: ".$qry."<br />";

				$getResultOK = true;
			elseif( trim($fv_course) != "--" ):
				if( $ddebug ) echo "search for course without days<br />";
				
				$listTitle = "List of Users that has Completed Course \"".$courseName."\"";

				$qry = "SELECT u.ID, DATE(u.user_registered) as rdate, u.display_name, u.user_login, u.user_email, a.last_visit, a.finished, ";
				$qry .= "DATE(a.last_visit) as last_login, TIMESTAMPDIFF(DAY,DATE(last_visit),CURDATE()) AS days FROM wpou_mscm_assignment a ";
				$qry .= "INNER JOIN ".$user_tbl." u ON a.user_pid=u.ID ";
				$qry .= "WHERE a.finished = '1' ";
				$qry .= "AND a.course_pid = '".$fv_course."'";
				if( $ddebug ) echo "qry: ".$qry."<br />";

				$getResultOK = true;
			else:
				
				if( $ddebug ) echo "no search is performed<br />";
			endif;
		endif;
	endif;

	
	//--- INI :: Built result_arr ----------------------------------------------------
		$totalItems = 0;
		if( $getResultOK ):
			$res = $wpdb->get_results($qry, ARRAY_A);
			//echo "<pre>"; print_r($res); echo "</pre>";
				
			$c = 0;
			foreach($res as $value):

				//$strtime = strtotime($res[$c]['last_visit']);
				//$last_login = date("l jS F Y", $strtime);

				//$curdate = strtotime(date('Y-m-d'));           //echo "curdate: ".$curdate."<br />";
				//$olddate = strtotime(date('Y-m-d', $strtime)); //echo "olddate: ".$olddate."<br />";
				//$ndate = (($curdate - $olddate) / 86400);

				$result_arr[$c]['user_id'] = $value['ID'];
				$result_arr[$c]['rdate'] = $value['rdate'];
				$result_arr[$c]['name'] = $value['display_name'];
				$result_arr[$c]['username'] = $value['user_login'];
				$result_arr[$c]['email'] = $value['user_email'];
				$result_arr[$c]['course'] =$courseName;
				$result_arr[$c]['session'] = $sessionName." [".$sessionNum." of ".$sessionTot."]";
				$result_arr[$c]['last_login'] = $value['last_login'];
				$result_arr[$c]['days'] = $value['days'];
				$c++;
			endforeach;
			$totalItems = count($result_arr);
			if( $ddebug ) echo "totalItems: ".$totalItems."<br />";
			//echo "<pre>";print_r($result_arr); echo "</pre>";
		endif;
	//--- END :: Built result_arr ----------------------------------------------------

	//--- INI :: Generate CSV ---------------------------------------------------------
		if( isset($_REQUEST['btnGenCsv']) && count($result_arr) > 0 ):
			$csv_sep = ",";

			$csv_url =  $gvar['plugin_dir']."csv_files/"; //echo "csv_url: ".$csv_url."<br />";
			$csv_path = $gvar['plugins_dir_realpath']."/csv_files/"; //echo "csv_path: ".$csv_path."<br />";
			$csv_filename = "user_report".date('Ymd-Hi').".csv"; //echo "csv_filename: ".$csv_filename."<br />";	
			$csv_header = "";
			$csv_row = "";

			$csv_file = realpath($csv_path)."/".$csv_filename; //echo "csv_file: ".$csv_file."<br />"; 
			$fp = fopen($csv_file, 'w');

			$csv_arr_header = ["User_id","Registration Date","Name", "Username", "Email", "Course", "Session", "Last Login", "Days"];
			$csv_header = implode($csv_sep, $csv_arr_header);
			$csv_header = $csv_header.PHP_EOL;
			fputs($fp, $csv_header);

			foreach( $result_arr as $val ):
				$csv_row = $val['user_id'].$csv_sep.$val['rdate'].$csv_sep.$val['name'].$csv_sep.$val['username'].$csv_sep;
				$csv_row .= $val['email'].$csv_sep.$val['course'].$csv_sep.$val['session'].$csv_sep;
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
	
	//--- INI :: get courses list ----------------------------------------------------
		$qry = "SELECT * FROM wpou_mscm_course WHERE active = '1'";
		$courseRs = $wpdb->get_results($qry, ARRAY_A);
		//echo "<pre>"; print_r($courseRs); echo "</pre>";
	//--- END :: get courses list ----------------------------------------------------

	//--- INI :: get sessions --------------------------------------------------------
		if( $fv_course != "--" ):
			$qry = "SELECT * FROM wpou_mscm_content WHERE course_pid = '".$fv_course."' AND active = '1'";
			$sessRs = $wpdb->get_results($qry, ARRAY_A);
			//echo "<pre>"; print_r($sessRs); echo "</pre>";
			//isInteger
		endif;
	//--- INI :: get sessions --------------------------------------------------------

	//--- INI :: pagination ----------------------------------------------------------
		$pgItems = "30"; //number of records to display per page
		$pg = ( isset($_REQUEST['pg']) && trim($_REQUEST['pg']) != "" ) ? $_REQUEST['pg']  : 1 ;

		//getPaginationInfo($totalItems, $pg, $pgItems)
		$arr_pagination = getPaginationInfo($totalItems, $pg, $pgItems);
		//echo "<pre>"; print_r($arr_pagination); echo "</pre>";

		$other_params = "";
		$other_params .= "&fv_course=".$fv_course;
		$other_params .= "&fv_session=".$fv_session;
		$other_params .= "&fv_days=".$fv_days;
		$other_params .= "&formdata=ok";
		$other_params .= "&action=showUserReportList";
		$other_params .= "&autosubmit=n";

		$lnk_firstpg = $pageUrl.'&pg='.$arr_pagination['firstpg'].$other_params."&btnUsearch=ok";  if($ddebug) echo "lnk_firstpg: ".$lnk_firstpg."<br />";
		$lnk_prevpg  = $pageUrl.'&pg='.$arr_pagination['prevpg'].$other_params."&btnUsearch=ok";   if($ddebug) echo "lnk_prevpg: ".$lnk_prevpg."<br />";
		$lnk_nextpg  = $pageUrl.'&pg='.$arr_pagination['nextpg'].$other_params."&btnUsearch=ok";   if($ddebug) echo "lnk_nextpg: ".$lnk_nextpg."<br />";
		$lnk_lastpg  = $pageUrl.'&pg='.$arr_pagination['lastpg'].$other_params."&btnUsearch=ok";   if($ddebug) echo "lnk_lastpg: ".$lnk_lastpg."<br />";

		$rowini = $arr_pagination['lim01'];	if($ddebug) echo "rowini: ".$rowini."<br />";
		//$rowend = ($rowini + $arr_pagination['lim02']) - 1; if($ddebug) echo "rowend: ".$rowend."<br />";
		$rowend = $arr_pagination['lim02']; if($ddebug) echo "rowend: ".$rowend."<br />";
	//--- END :: pagination ----------------------------------------------------------

	if( count($result_arr) > 0 ):
		$showCsvBtn = true;
	endif;
?>

<div class="mscm_main_wrapper">
	<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
	<?php if( $success_msg != "" ): ?><div class="mscm_success_msg01"><?php echo $success_msg; ?></div><?php endif; ?>
	
	<div class="mscm_search_wrapper">
		<form action="<?php echo $formUrl; ?>" name="user_search_form" id="user_search_form" method="GET">
			<input type="hidden" name="page" value="fdq-ms-user-report.php" />
			<div style="width:100%;box-sizing:border-box; padding:15px 0;">
				<h2 style="text-transform:uppercase;margin:0;"><?php echo $listTitle; ?></h2>
				<p style="margin:1px0 0 0;"><span style="background:#ff0;color:#f00;font-weight:700;padding:5px 5px;">Total items: <?php echo $totalItems; ?></span	></p>
			</div>
			<div class="mscm_search_col01" style="max-width:210px;">
				Courses:<br />
				<select name="fv_course" id="fv_course">
					<option value="--">Select a course</option>
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
			<div class="mscm_search_col02" style="max-width:140px;">
				Search by:<br />
				<select name="fv_session" id="fv_session">
					<option value="--">Select a session</option>
					<?php
						foreach($sessRs as $val):
							if( $fv_session == $val['content_id'] ):
								echo '<option value="'.$val['content_id'].'" selected="selected">'.$val['day_number']." - ".$val['title'].'</option>';
							else:
								echo '<option value="'.$val['content_id'].'">'.$val['day_number']." - ".$val['title'].'</option>';
							endif;
						endforeach;
					?>
				</select>
			</div>
			<div class="mscm_search_col03" style="max-width:130px;">
				days:<br />
				<input type="number" name="fv_days" id="fv_days" value="<?php echo $fv_days; ?>" min="0" max="500" style="width:50px;"/>
				<input type="hidden" name="formdata" value="ok">
				<input type="hidden" name="action" value="showUserReportList">
				<input type="hidden" name="autosubmit" class="autosubmit" value="n" style="width:20px;" />
				<input type="submit" name="btnUsearch" id="btnUsearch" value="search">
			</div>
			<div class="mscm_search_col04" style="max-width:230px;">
				
				<?php if( $showCsvBtn || 1 == 2 ): ?><input type="submit" name="btnGenCsv" id="btnGenCsv" class="fdq_mscm_btn_generatecsv" value="Gen CSV"><?php endif; ?>
				<?php if( $showDownloadBtn || 1 == 2 ): ?><a class="fdq_mscm_btn_generatecsv" href="<?php echo $csv_download_link; ?>">Download</a><?php endif; ?>
			</div>
		</form>
	</div>
	<div class="mscm_list_wrapper">
		<div class="mscm_list_title_wrapper">
			<div class="mscm_list_title" style="width:10%">ID/RegDate</div>
			<div class="mscm_list_title" style="width:15%">Name</div>
			<div class="mscm_list_title" style="width:14%">Username</div>
			<div class="mscm_list_title" style="width:14%">Email</div>
			<div class="mscm_list_title" style="width:15%">Course</div>
			<div class="mscm_list_title" style="width:15%">Session</div>
			<div class="mscm_list_title" style="width:10%">Last Login</div>
			<div class="mscm_list_title" style="width:7%">Days</div>
		</div>
		<?php if( count($result_arr) > 0 ): ?>
			<?php $nu = 0; ?>
			<?php //foreach( $result_arr as $val ): ?>
			<?php //for( $i=$rowini; $i<=$rowend; $i++ ): ?>
			<?php while( $nu < $rowend && ($nu + $rowini) < $totalItems ): ?>
				<?php $i = $nu + $rowini; ?>
				<div class="mscm_list_content_wrapper">
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $result_arr[$i]['user_id']."<br />".$result_arr[$i]['rdate']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:15%"><?php echo $result_arr[$i]['name']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:14%"><?php echo $result_arr[$i]['username']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:14%"><?php echo $result_arr[$i]['email']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:15%"><?php echo $result_arr[$i]['course']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:15%"><?php echo $result_arr[$i]['session']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:10%"><?php echo $result_arr[$i]['last_login']; ?></div>
					<div class="mscm_list_content <?php echo $_inact; ?>" style="width:7%"><?php echo $result_arr[$i]['days']; ?></div>
				</div>
			<?php $nu++; endwhile; ?>
			<?php //endfor; ?>
			<?php //endforeach; ?>
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
<script>
	jQuery(document).ready(function(){
		jQuery('#fv_course').on('change', function(){
			var sel_value = jQuery('#fv_course').val();
			jQuery('.autosubmit').val('y');
			jQuery("#user_search_form" ).submit();
		});
	});
</script>