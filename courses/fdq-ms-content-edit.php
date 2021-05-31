<?php
	global $wpdb;
	$gvar = get_vars(); //echo "<pre>";print_r($gvar);echo "</pre>";
	$pageUrl = $gvar['site_url']."wp-admin/admin.php?page=fdq-ms-courses.php"; //echo "pageUrl: ".$pageUrl."<br />";
	$imgDir = $gvar['img_dir'];

	$cod01 = ( isset($_REQUEST['cod01']) && trim($_REQUEST['cod01']) != "" ) ? $_REQUEST['cod01'] : "" ;  //echo "cod01: ".$cod01."<br />";

	$error_msg = "";
	$success_msg = "";
	$err = array();

	if( trim($cod01) != "" ):
		
		$cod02 = ( isset($_REQUEST['cod02']) && trim($_REQUEST['cod02']) != "" ) ? $_REQUEST['cod02'] : "" ;
		
		if( trim($cod02) != "" ):
			
			if( isset($_REQUEST['btnEditContent']) ):
				$fv_title = ( isset($_REQUEST['fv_title']) && trim($_REQUEST['fv_title']) != "" ) ? $_REQUEST['fv_title'] : "" ;
				$fv_icon = ( isset($_REQUEST['fv_icon']) && trim($_REQUEST['fv_icon']) != "" ) ? $_REQUEST['fv_icon'] : "" ;
				
				$fv_video_title = ( isset($_REQUEST['fv_video_title']) && trim($_REQUEST['fv_video_title']) != "" ) ? $_REQUEST['fv_video_title'] : "" ;
				$fv_video_url = ( isset($_REQUEST['fv_video_url']) && trim($_REQUEST['fv_video_url']) != "" ) ? $_REQUEST['fv_video_url'] : "" ;
				$fv_audio_title = ( isset($_REQUEST['fv_audio_title']) && trim($_REQUEST['fv_audio_title']) != "" ) ? $_REQUEST['fv_audio_title'] : "" ;
				$fv_audio_url = ( isset($_REQUEST['fv_audio_url']) && trim($_REQUEST['fv_audio_url']) != "" ) ? $_REQUEST['fv_audio_url'] : "" ;
				$fv_day = ( isset($_REQUEST['fv_day']) && trim($_REQUEST['fv_day']) != "" ) ? $_REQUEST['fv_day'] : "" ;
				$fv_redir_url = ( isset($_REQUEST['fv_redir_url']) && trim($_REQUEST['fv_redir_url']) != "" ) ? $_REQUEST['fv_redir_url'] : "" ;

				$fv_active = ( isset($_REQUEST['fv_active']) && trim($_REQUEST['fv_active']) != "" ) ? $_REQUEST['fv_active'] : "" ;

				//--- send data for store process --------------------------------------------------------------------
				$err = fdq_ms_content_edit_func($fv_title, $fv_icon, $fv_video_title, $fv_video_url, $fv_audio_title, $fv_audio_url, $fv_day, $fv_redir_url, $fv_active, $cod01, $cod02);
				//$err[] = 'temp error message';
				if( count($err) > 0 ):
					$error_msg = setErrorMsg($err); //"Error: the recipe was not created";
					$success_msg = "";
				else:
					$success_msg = "The new course content was successfully created";
					$error_msg = "";
				endif;

			else:
				if( trim($cod02) != "" ):
					$qry01 = "SELECT * FROM `wpou_mscm_content` WHERE content_id='".$cod02."'";
					$rs01 = $wpdb->get_results($qry01, ARRAY_A);
					//echo "<pre>";print_r($rs01);echo "</pre>";
					$fv_title=$rs01[0]['title'];
					$fv_icon=$rs01[0]['icon_pid'];
					$fv_video_title=$rs01[0]['video_title'];
					$fv_video_url=$rs01[0]['video_url'];
					$fv_audio_title=$rs01[0]['audio_title'];
					$fv_audio_url=$rs01[0]['audio_url'];
					$fv_day=$rs01[0]['day_number'];
					$fv_redir_url = $rs01[0]['redir_url'];
					$fv_active = $rs01[0]['active'];
				else:
					$fv_title="";
					$fv_icon='';
					$fv_video_title='';
					$fv_video_url='';
					$fv_audio_title='';
					$fv_audio_url='';
					$fv_day='';
					$fv_active = "1";
				endif;
			endif;

			//--- INI :: get icon data --------------------------------------------------------------
				$qryIcon = "SELECT * FROM `wpou_mscm_icon` WHERE active='1' ORDER BY icon_day_number";
				$rsIcon = $wpdb->get_results($qryIcon, ARRAY_A);
				//echo "<pre>"; print_r($rsIcon); echo "</pre>";
			//--- END :: get icon data --------------------------------------------------------------
		
		endif;
	endif;

?>
<?php if( trim($cod01) != "" ): ?>
	<?php if( trim($cod02) != "" ): ?>
		<div class="mscm_main_wrapper">
			<?php if( $success_msg == "" ): ?>
				<?php if( $error_msg != "" ): ?><div class="mscm_error_msg"><?php echo $error_msg; ?></div><?php endif; ?>
				<div class="mscm_page_title">Add new course content</div>
				<form name="recipe_add_form" id="recipe_add_form" action="<?php echo $pageUrl; ?>" method="post">
					<div class="mscm_row">
						<span class="mscm_field_title">Name</span><br />
						<input type="text" name="fv_title" id="fv_title" value="<?php echo $fv_title; ?>">
					</div>
					<div class="mscm_row">
						<span class="mscm_field_title">Icon</span><br />
						<select name="fv_icon" id="fv_icon">
							<option value="00">Select Icon</option>
							<?php
								foreach( $rsIcon as $v_icon ):
									if( $v_icon['icon_id'] == $fv_icon ):
										echo '<option value="'.$v_icon['icon_id'].'" selected="selected">Day '.$v_icon['icon_day_number'].'</option>';
									else:
										echo '<option value="'.$v_icon['icon_id'].'">Day '.$v_icon['icon_day_number'].'</option>';
									endif;
								endforeach; 
							?>
						</select>
					</div>
					<div class="mscm_row">
						<span class="mscm_field_title">Video Title</span><br />
						<input type="text" name="fv_video_title" id="fv_video_title" value="<?php echo $fv_video_title; ?>">
					</div>
					<div class="mscm_row">
						<span class="mscm_field_title">Video Url</span><br />
						<input type="text" name="fv_video_url" id="fv_video_url" value="<?php echo $fv_video_url; ?>">
					</div>
					<div class="mscm_row">
						<span class="mscm_field_title">Audio Title</span><br />
						<input type="text" name="fv_audio_title" id="fv_audio_title" value="<?php echo $fv_audio_title; ?>">
					</div>
					<div class="mscm_row">
						<span class="mscm_field_title">Audio Url</span><br />
						<input type="text" name="fv_audio_url" id="fv_audio_url" value="<?php echo $fv_audio_url; ?>">
					</div>
					<div class="mscm_row">
						<span class="mscm_field_title">Day Number</span><br />
						<input type="number" style="width:60px;" name="fv_day" id="fv_day" value="<?php echo $fv_day; ?>" min="1" max="100">
					</div>
					<div class="mscm_row">
						<span class="mscm_field_title">Url for redirection</span><br />
						<input type="text" name="fv_redir_url" id="fv_redir_url" value="<?php echo $fv_redir_url; ?>">
					</div>
					<div class="mscm_row">
						<span class="mscm_field_title" style="display:inline-block;margin-bottom:8px;">Active:</span><br />
						<input type="radio" name="fv_active" id="fv_active1" value="1" <?php if($fv_active == '1'){ echo 'checked="checked"'; } ?> > <label for="fv_active1"><strong>Active</strong></label>
						<input type="radio" name="fv_active" id="fv_active0" value="0" style="margin:0 0 0 10px" <?php if($fv_active == '0'){ echo 'checked="checked"'; } ?> > <label for="fv_active0"><strong>Inactive</strong></label>
					</div>
					<div class="mscm_btn_row">
						<input type="hidden" name="action" value="showContentEdit" />
						<input type="hidden" name="cod01" value="<?php echo $cod01; ?>" />
						<input type="hidden" name="cod02" value="<?php echo $cod02; ?>" />
						<input type="submit" name="btnEditContent" id="btnEditContent" value="Save Course Content" class="mscm_btn" />
						<a class="mscm_btn_cancel" href="<?php echo $pageUrl; ?>&action=showContentList&cod01=<?php echo $cod01; ?>">Cancel</a>
					</div>
				</form>
			<?php else: ?>
				<div class="mscm_successMsgWrapper">
					<div class="mscm_successMsgInner">
						<div class="mscm_success_msg"><?php echo $success_msg; ?></div>
						<div class="mscm_successMegButtons">
							<?php /* ?><a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showContentEdit&cod01=<?php echo $cod01; ?>">Add Course Content</a><?php /**/ ?>
							<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showContentList&cod01=<?php echo $cod01; ?>">Go back to listing page</a>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php else: ?>
		<div class="mscm_main_wrapper">
			<div class="mscm_successMsgWrapper">
				<div class="mscm_successMsgInner">
					<div class="mscm_success_msg">there was an error with the data, please go back and try again</div>
					<div class="mscm_successMegButtons">
						<a class="mscm_btn" href="<?php echo $pageUrl; ?>&action=showContentList&cod01=<?php echo $cod01; ?>">Go back</a>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
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