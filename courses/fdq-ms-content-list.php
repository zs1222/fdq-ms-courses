<?php
	global $wpdb;
	$gvar = get_vars(); //echo "<pre>";print_r($gvar);echo "</pre>";
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-courses.php"; //echo "pageUrl: ".$pageUrl."<br />";
	$imgDir = $gvar['img_dir'];

	$error_msg = "";
	$success_msg = "";
	$err = array();

	$cod01 = ( isset($_REQUEST['cod01']) && trim($_REQUEST['cod01']) != "" ) ? $_REQUEST['cod01'] : "" ;
	//echo "code01: ".$cod01."<br />";

	if( trim($cod01) != "" ):

		//--- INI :: course deactivation -------------------------------------------------------------------------------
			if( isset($_REQUEST['cod02']) && trim($_REQUEST['cod02']) != "" && isset($_REQUEST['op']) && trim($_REQUEST['op']) != "" ):
				$err = fdq_ms_content_deactivate_func($_REQUEST['cod02'], $_REQUEST['op']);
				if( count($err) > 0 ):
					$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
					$success_msg = "";
				else:
					if($_REQUEST['op'] == '1'):
						$success_msg = "The course was successfully activated";
					else:
						$success_msg = "The course was successfully deactivated";
					endif;
					$error_msg = "";
				endif;
			endif;
		//--- END :: course deactivation -------------------------------------------------------------------------------


		//--- all good -----
		$qry01 = "SELECT * FROM `wpou_mscm_content` WHERE `course_pid` = '".$cod01."'"; //echo "qry01: ".$qry01."<br />";
		$rs01 = $wpdb->get_results($qry01, ARRAY_A); //echo "<pre>"; print_r($rs01); echo "</pre>";

		$qry02 = "SELECT * FROM `wpou_mscm_course` WHERE course_id = '".$cod01."'";
		$rs02 = $wpdb->get_results($qry02, ARRAY_A); //echo "<pre>"; print_r($rs02); echo "</pre>";
	endif;

	//$error_msg = "this is a temporary error message";
	//$success_msg = "this is a temporary success message";
?>
<?php if(trim($cod01) != "" && 1 == 1 ): ?>
	<div class="mscm_main_wrapper">
		<div class="mscm_top_btn_wrapper">
			<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseList">Go back to courses</a>
		</div>
		<div class="mscm_top_txt">
			<span class="tlabel">Course name:</span> <span class="tname"><?php echo $rs02[0]['title'] ?></span><br />
			<span class="tlabel">Course is:</span> <span class="tname"><?php if( $rs02[0]['paid'] == '0' ){ echo "Free"; }else{ echo "Paid"; }  ?></span>
		</div>
		<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
		<?php if( $success_msg != "" ): ?><div class="mscm_success_msg01"><?php echo $success_msg; ?></div><?php endif; ?>
		<div class="mscm_addNewWrapper">
			<p>Courses Content: <a class="mscm_btn_add" href="<?php echo $pageUrl; ?>&action=showContentAdd&cod01=<?php echo $cod01; ?>">Add New</a></p>
		</div>
		<div class="mscm_list_wrapper">
			<div class="mscm_list_title_wrapper">
				<div class="mscm_list_title" style="width:25%">Title</div>
				<div class="mscm_list_title" style="width:8%">Icon</div>
				<div class="mscm_list_title" style="width:15%">Video</div>
				<div class="mscm_list_title" style="width:15%">Audio</div>
				<div class="mscm_list_title" style="width:8%">Day</div>
				<div class="mscm_list_title" style="width:12%">Date</div>
				<div class="mscm_list_title" style="width:8%">Active</div>
				<div class="mscm_list_title" style="width:9%">Action</div>
			</div>
			<?php if( count($rs01) > 0 ): ?>
				<?php foreach( $rs01 as $val ): ?>
					<?php
						$_inact = "";
						if( $val['active'] == '0' ):
							$_inact = "mscm_inactive";
						endif;
					?>
					<div class="mscm_list_content_wrapper">
						<?php if( 1 == 1 ): ?>
							<div class="mscm_list_content <?php echo $_inact; ?>" style="width:25%"><?php echo $val['title']; ?></div>
						<?php else: ?>
							<div class="mscm_list_content <?php echo $_inact; ?>" style="width:25%"><?php echo "[".$val['content_id']."]".$val['title']; ?></div>
						<?php endif; ?>
						<div class="mscm_list_content <?php echo $_inact; ?>" style="width:8%">
							<?php 
								$qry03 = "SELECT * FROM `wpou_mscm_icon` WHERE icon_id='".$val['icon_pid']."'";
								$rs03 = $wpdb->get_results($qry03, ARRAY_A);
								if( count($rs03) > 0 ):
									echo '<img class="course_icon" src="'.$imgDir.$rs03[0]['icon_complete'].'" alt="" />'; 
								else:
									echo "No";
								endif;
								
							?>
						</div>
						<div class="mscm_list_content <?php echo $_inact; ?>" style="width:15%">
							<?php if( $val['video_title'] != "" && 1 == 1 ): ?>
								<span class="mscm_playVideo" title="URL: <?php echo $val['video_url']; ?>" data-video="<?php echo $val['video_url']; ?>"><?php echo $val['video_title']; ?></span>
							<?php else: ?>
								<span class="mscm_playVideo" title="URL: <?php echo $val['video_url']; ?>" data-video="<?php echo $val['video_url']; ?>">[No Title]</span>
							<?php endif; ?>
						</div>
						<div class="mscm_list_content <?php echo $_inact; ?>" style="width:15%">
							<?php if( $val['audio_title'] != "" && 1 == 1 ): ?>
								<span class="mscm_playAudio" title="URL: <?php echo $val['audio_url']; ?>" data-audio="<?php echo $val['audio_url']; ?>"><?php echo $val['audio_title']; ?></span>
							<?php else: ?>
								<span class="mscm_playAudio" title="URL: <?php echo $val['audio_url']; ?>" data-audio="<?php echo $val['audio_url']; ?>">[No Title]</span>
							<?php endif ?>
						</div>
						<div class="mscm_list_content <?php echo $_inact; ?>" style="width:8%"><?php echo $val['day_number']; ?></div>
						<div class="mscm_list_content <?php echo $_inact; ?>" style="width:12%"><?php echo $val['cdate']; ?></div>
						<div class="mscm_list_content <?php echo $_inact; ?>" style="width:8%"><?php echo $val['active']; ?></div>
						<div class="mscm_list_content <?php echo $_inact; ?>" style="width:9%">
							<?php
								$_ac = '1';
								$_ac_label ='activate';
								if( $val['active'] == '1' ):
									$_ac = '0';
									$_ac_label ='deactivate';
								endif;
							?>
							<a href="<?php echo $pageUrl; ?>&action=showContentEdit&cod01=<?php echo $cod01; ?>&cod02=<?php echo $val['content_id']; ?>" title="Edit Course" >edit</a><br /> 
							<a href="<?php echo $pageUrl; ?>&action=showContentList&cod01=<?php echo $cod01; ?>&cod02=<?php echo $val['content_id']; ?>&op=<?php echo $_ac; ?>" title="<?php echo $_ac_label; ?> Content"><?php echo $_ac_label; ?></a><br /> 
							<?php /* ?><a href="<?php echo $pageUrl; ?>&action=showCourseView&cod01=<?php echo $val['course_id']; ?>">view</a><?php /**/ ?>
							<?php /* ?><a href="<?php echo $pageUrl; ?>&action=showCourseDel&cod01=<?php echo $val['course_id']; ?>">del</a><?php /**/ ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="mscm_list_content_wrapper">
					<div class="mscm_list_content" style="width:98.5%">No items found</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php else: ?>
	<div class="mscm_main_wrapper">
		<div class="mscm_successMsgWrapper">
			<div class="mscm_successMsgInner">
				<div class="mscm_success_msg">there was an error with the data, please go back and try again</div>
				<div class="mscm_successMegButtons">
					<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showCourseList">Go back</a>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<div id="mscm_adm_popup-wrapper" class="mscm_adm_popupWrapper">
	<div class="mscm_adm_popupInnerBox">
		<div class="mscm_adm_popup-close-btn"><span id="mscm_adm_close-popup">[x]</span></div>
		<div id="mscm_adm_popupContainer">asdasdasd</div>
		
	</div>
</div>
<div id="mscm_adm_popup-grey-bg" class="mscm_adm_popupGreyBg"></div>

<script>
jQuery(document).ready(function(){
	var picURL = '<?php echo $picDir ?>' ;	
	jQuery("#mscm_adm_close-popup").on('click', function(){
		jQuery("#mscm_adm_popupContainer").html('');
		jQuery("#mscm_adm_popup-wrapper").hide();
		jQuery("#mscm_adm_popup-grey-bg").hide();
	});

	jQuery('.mscm_playVideo').on('click', function(){
		var curVid = '<div class="mscm_adm_videowrapper"><iframe src="' + jQuery(this).attr('data-video') + '?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
		jQuery("#mscm_adm_popupContainer").html(curVid);
		jQuery("#mscm_adm_popup-wrapper").show();
		jQuery("#mscm_adm_popup-grey-bg").show();
	});

	jQuery('.mscm_playAudio').on('click', function(){
		var curAudio = '<audio controls="controls"><source src="' + jQuery(this).attr('data-audio') + '" type="audio/mpeg" />Your browser does not support the audio element.</audio>';
		jQuery("#mscm_adm_popupContainer").html(curAudio);
		jQuery("#mscm_adm_popup-wrapper").show();
		jQuery("#mscm_adm_popup-grey-bg").show();
	});

	

});
</script>