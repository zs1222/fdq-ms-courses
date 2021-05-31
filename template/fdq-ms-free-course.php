

<?php
	//echo "[ userId=".$_userId."] [courseId=".$_courseId."]<br />";
	$directory= plugins_url()."/fdq-ms-courses/"; //echo "directory: ".$directory."<br />";
	$gvar = get_vars();
	//echo "<pre>"; print_r($gvar); echo "</pre>";
	$pluginImg = $gvar['site_url']."wp-content/plugins/fdq-ms-courses/images/";
	$line_h = $pluginImg."line_h.png";
	$line_v = $pluginImg."line_v.png";
	//$current_user = wp_get_current_user(); //echo "<pre>"; print_r($current_user); echo "</pre>";
	//echo "backBtn=".$_backBtn."<br />";
	
	//--- get go back link -----------------------------------------
	$_pg = "//".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; //echo "pg: ".$_pg."<br />";
	$findme = "?cid=";
	$pos = strpos($_pg, $findme); //echo "pos: ".$pos."<br />";
	if( $pos !== false ):
		$backUrl = substr($_pg, 0, $pos); //echo "rest: ".$rest."<br />";
	endif;
?>
<?php
	global $wpdb;
	//$user_pid=1;
	
	//$qry01 = "SELECT * FROM wpou_mscm_assignment WHERE user_pid=".$user_pid;
	$qry01 = "SELECT * FROM `wpou_mscm_assignment` WHERE `user_pid`='".$_userId."' AND `course_pid`='".$_courseId."'" ; //echo "qry; ".$qry01."<br />";
	$rs01 = $wpdb->get_results( $qry01 );
	foreach ( $rs01 as $row01 ):
		$course_pid= $row01->course_pid;
		$advanced= $row01->advanced;
		$assig_id=$row01->assignment_id;
		$finished =$row01->finished;
	endforeach;

	
	//$qry02 = "SELECT * FROM wpou_mscm_course WHERE course_id=".$course_pid;
	$qry02 = "SELECT * FROM wpou_mscm_course WHERE course_id=".$_courseId;
	$rs02 = $wpdb->get_results( $qry02 );
	foreach ( $rs02 as $row02 ):
		$title= $row02->title;
		$paid= $row02->paid;
	endforeach;
	$ran = generateRandomString(5); //echo "ranNum: ".$ran."<br />";
	$tlineWrapper = 'timelineWrapper_'.$ran;
?>
<style>
	<?php echo ".".$tlineWrapper?> img{ width:<?php echo $_iconwidth."px"; ?>; height: auto; }

	@media only screen and (max-width: 767px) { 
		<?php echo ".".$tlineWrapper?>{ width:<?php echo $_iconwidth."px"; ?>; margin:0 auto; }
	}
</style>
<div class="timelineWrapper <?php echo $tlineWrapper?>">
	<?php if( $finished==1 ): ?>
	<div class="timeLineBox" style="background-position: 0 10px;">
	<?php else: ?>
	<div class="timeLineBox">
	<?php endif; ?>
		<div class="icons_<?php echo $ran; ?>">
			<?php  
				$gvars=get_vars();
				$dir=$gvars['img_dir'];
				$qry03 = "SELECT * FROM wpou_mscm_content WHERE course_pid=".$course_pid." order by day_number";
				$rs03 = $wpdb->get_results( $qry03 );
				//echo "<pre>"; print_r($rs03); echo "</pre>";
				$c=0;
				
				foreach ( $rs03 as $rowContent ): ;
					$c++;
					//echo "[c = ".$c."] [count:".$rowContent->count."]<br />";
					$content_id= $rowContent->content_id;
					$icon_pid= $rowContent->icon_pid;
					$day_number= $rowContent->day_number;
					//$Audio_Session="Session ".$rowContent->day_number;
					$Audio_Session="Sesión ".$rowContent->day_number;
					$qry04 = "SELECT * FROM wpou_mscm_icon WHERE icon_id=".$icon_pid;
					$rs04 = $wpdb->get_results( $qry04 );
					foreach ( $rs04 as $row04 ):
						$icon_complete= $row04->icon_complete;
						$icon_current= $row04->icon_current;
						$icon_blocked =$row04->icon_blocked;
					endforeach;
					if($finished==0){
						if(trim($rowContent->audio_url)=='' and trim($rowContent->video_url)=='' and $c<$rowContent->count){
							//icon_blocked
							echo	'	<span class="a_'.$day_number.'" style="cursor:default" 
										data-id="a_'.$day_number.'" 
										data-key="'.$day_number.'" 
										data-name="" 
										data-info="" 
										data-title="" 
										data-sesion="">
											<img class="i_'.$day_number.'"  
												data-id="i_'.$day_number.'" 
												data-info="'.$dir.$icon_current.'" 
												data-name="'.$dir.$icon_complete.'"  
												src="'.$dir.$icon_blocked.'">
										'.'<div class="crsttl">'.$rowContent->title.'</div></span>';
						}
						else{		
							if($advanced>$day_number){
								//icon_complete
								echo	'	<span class="open-pop a_'.$day_number.'" 
											data-id="a_'.$day_number.'" 
											data-key="'.$day_number.'" 
											data-name="'.$rowContent->audio_url.'" 
											data-title="'.$rowContent->audio_title.'" 
											data-sesion="'.$Audio_Session.'" 
											data-info="'.$rowContent->video_url.'" >
											<img class="i_'.$day_number.'" 
												data-id="i_'.$day_number.'" 
												src="'.$dir.$icon_complete.'">
										'.'<div class="crsttl">'.$rowContent->title.'</div></span>';
							}
							if($advanced==$day_number) {
								//icon_current
								echo	'	<span class="open-pop a_'.$day_number.'" 
											data-id="a_'.$day_number.'" 
											data-key="'.$day_number.'" 
											data-name="'.$rowContent->audio_url.'" 
											data-title="'.$rowContent->audio_title.'" 
											data-sesion="'.$Audio_Session.'" 
											data-info="'.$rowContent->video_url.'" >
											<img class="i_'.$day_number.'" 
												data-id="i_'.$day_number.'" 
												data-name="'.$dir.$icon_complete.'" 
												src="'.$dir.$icon_current.'">
										'.'<div class="crsttl">'.$rowContent->title.'</div></span>';

								$video_url= $rowContent->video_url;
								$audio_url= $rowContent->audio_url;
								$img_current = "i_".$day_number;
								$icon_complete_current= $icon_complete;
							}
							if($advanced<$day_number){
								//icon_blocked
								echo	'<span class="open-pop a_'.$day_number.'" style="cursor:default" 
											data-id="a_'.$day_number.'" 
											data-key="'.$day_number.'" 
											data-name="'.$rowContent->audio_url.'" 
											data-title="'.$rowContent->audio_title.'" 
											data-sesion="'.$Audio_Session.'" 
											data-info="'.$rowContent->video_url.'" >
											<img class="i_'.$day_number.'" 
												data-id="i_'.$day_number.'" 
												data-info="'.$dir.$icon_current.'" 
												data-name="'.$dir.$icon_complete.'" 
												src="'.$dir.$icon_blocked.'">
										'.'<div class="crsttl">'.$rowContent->title.'</div></span>';
							}
						}
					}
					else{
						echo	'<span class="open-pop a_'.$day_number.'" 
									data-id="a_'.$day_number.'" 
									data-key="'.$day_number.'" 
									data-name="'.$rowContent->audio_url.'"  
									data-title="'.$rowContent->audio_title.'" 
									data-sesion="'.$Audio_Session.'" 
									data-info="'.$rowContent->video_url.'" >
										<img class="i_'.$day_number.'" 
											data-id="i_'.$day_number.'" 
											src="'.$dir.$icon_complete.'">
									'.'<div class="crsttl">'.$rowContent->title.'</div></span>';
					}
					$redirect_url=$rowContent->redir_url;
					$laststep=$day_number;
				endforeach;	
			?>
			<input type="hidden" name="fv_assig_id" id="fv_assig_id" class="assig_id" value="<?php echo $assig_id;?>" style="width:50px;"/>
			<input type="hidden" name="fv_step" id="fv_step" class="step" value="<?php echo $advanced;?>" style="width:30px;"/>
			<input type="hidden" name="fv_laststep" id="fv_laststep" class="laststep" value="<?php echo $laststep;?>" style="width:30px;"/>
			<input type="hidden" name="fv_redirect_url" id="fv_redirect_url" class="redirect_url" value="<?php echo $redirect_url;?>" style="width:250px;"/>
			<?php /* ?><span class="hitme" data-key="hitme_<?php echo $ran; ?>">hit me</span><?php /**/ ?>
		</div>
	</div>
</div>
<?php if( $_backBtn == '1' ): ?>
<div class="fdq-ms-backBtnWrapper">
	<a class="fdq-ms-btnBack" href="<?php echo $backUrl; ?>">Volver a la Página Principal</a>
</div>
<?php endif; ?>
<?php /* ?><div style="float:left;width:100%;margin:30px 0 37px 0;height:1px;background-color:#dedede;"></div><?php /**/ ?>
<?php /* ?>
	<div id="fdq-ms-popup-wrapper" class="fdq-ms-popupWrapper">
		<div class="fdq-ms-popupInnerBox" style="text-align:center;">
			<div class="fdq-ms-popup-close-btn"><i id="fdq-ms-close-popup" class="fa fa-times-circle-o"></i></div>
			<div id="fdq-ms-vidHtml5Container"></div>
		</div>
	</div>
	<div id="popup-grey-bg" class="popupGreyBg"></div>

	<div id="popup-orange" class="popupOrangeBg">
		<div class="fdq-ms-popupInnerBox-Orange" style="text-align:center;">
			<div class="fdq-ms-popup-close-btn"><i id="fdq-ms-close-popup-orange" class="fa fa-times-circle-o"></i></div>
			<div id="fdq-ms-vidHtml5Container-OrangeBG">
				<div class="resp-videowrapper">
					<div id="jquery_jplayer_1" class="cp-jplayer"></div>
					<div class="prototype-wrapper">  
						<div id="cp_container_1" class="cp-container">
							<div class="audioTitleWrapper">
								<span id="AudioTitle" class="audioTitle"></span><br />
								<span id="AudioSession" class="audioSession"></span>
							</div>
							<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
							<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
							<div class="cp-buffer-holder">  
								<div class="cp-buffer-1"></div>
								<div class="cp-buffer-2"></div>
							</div>
							<div class="cp-progress-holder">  
								<div class="cp-progress-1"></div>
								<div class="cp-progress-2"></div>
							</div>
							<div class="cp-circle-control"></div>
							<ul class="cp-controls">
								<li><a class="cp-play" tabindex="1">play</a></li>
								<li><a class="cp-pause" style="display:none;" tabindex="1">pause</a></li> 
							</ul> 
						</div>
					</div>	 
				</div>	 
			</div>
		</div>
	</div>
<?php /**/ ?>
<?php /* ?>
<script>
	var video_url = '';
	var step = '<?php echo $advanced;?>';
	var assig_id = '<?php echo $assig_id;?>';
	var audio_url = '' 
	var img_current = '';
	var open_id = '';
	var redirect_url = '<?php echo $redirect_url;?>';
	var laststep = '<?php echo $laststep;?>';
</script>
<?php /**/ ?>
<?php /* ?>
<script>

	var jQueryP =  jQuery.noConflict();

	jQuery(document).ready(function(){ 

		jQuery("#fdq-ms-close-popup").on('click', function(){
			jQuery("#fdq-ms-vidHtml5Container").html('clear');
			jQuery("#fdq-ms-popup-wrapper").hide();
			jQuery("#popup-grey-bg").hide();
			//jQuery("#fdq-ms-vidHtml5Container-OrangeBG").html('<div class="resp-videowrapper"><div id="jquery_jplayer_1" class="cp-jplayer"></div> <div class="prototype-wrapper"> <div id="cp_container_1" class="cp-container" style="width: 400px!important;height: 400px!important;"> <div class="cp-buffer-holder">  <div class="cp-buffer-1"></div> <div class="cp-buffer-2"></div> </div> <div class="cp-progress-holder">  <div class="cp-progress-1"></div> <div class="cp-progress-2"></div></div> <div class="cp-circle-control"></div> <ul class="cp-controls"> <li><a class="cp-play" tabindex="1">play</a></li> <li><a class="cp-pause" style="display:none;" tabindex="1">pause</a></li>  </ul></div> </div></div>');
			//if(open_id==laststep){
			if(audio_url.trim()==''){
				updateStatus();
			}
			else{
				jQuery("#popup-orange").show();
				jQuery('#jp_audio_0').attr("src",audio_url);		
				onClickAudio(audio_url);
			} 
		});

		jQuery("#fdq-ms-close-popup-orange").on('click', function(){
			//jQuery("#fdq-ms-vidHtml5Container-OrangeBG").html('clear');
			jQuery('#jp_audio_0').attr("src",'');	
			jQuery("#popup-orange").hide();
			//location.reload();
		});

		jQuery(".open-pop").on('click', function(e){
			e.preventDefault();
			open_id=jQuery(this).attr('data-key');
			if( parseInt(open_id) <= parseInt(step) ){
				str_video_url=jQuery(this).attr('data-info');
				audio_url= jQuery(this).attr('data-name');
				audio_title= jQuery(this).attr('data-title');
				audio_sesion= jQuery(this).attr('data-sesion');
				jQuery('#AudioTitle').text(audio_title);
				jQuery('#AudioSession').text(audio_sesion+'/'+laststep);
				if(str_video_url.trim()=='' && audio_url.trim()=='' &&  open_id==laststep){ //Nothing and last content
					updateStatus();
				}
				else{				
					if(str_video_url.trim()!=''){
						jQuery("#fdq-ms-vidHtml5Container").html('<div class="resp-videowrapper"><iframe id="myvideo" src="' + str_video_url + '?autoplay=1" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>');
						jQuery("#fdq-ms-popup-wrapper").show();
						jQuery("#popup-grey-bg").show();
					}
					else{
						if(audio_url.trim()!=''){
							jQuery("#popup-orange").show();
							jQuery('#jp_audio_0').attr("src",audio_url);		
							onClickAudio(audio_url);
						}
					}
				}	
			}
		});
	});

	function onClickAudio(link){
		var myCirclePlayer = new CirclePlayer(
					"#jquery_jplayer_1",
					{mp3: link}, 
					{supplied:"mp3,m4a", cssSelectorAncestor: "#cp_container_1"}
				);
		//myCirclePlayer.play(0);
	}

	function updateStatus(){
		if(open_id==step){
			var senddata = 'id='+assig_id+'&step='+step+'&action=fdq_ms_apicall_func_nextstep'; 
			console.log(fdqmspost.wp_ajax_url);
			console.log('senddata: ' + senddata);
			jQuery.ajax({
				type: 'GET',
				url: fdqmspost.wp_ajax_url,
				dataType: 'JSON',
				data: senddata,
				success: function(resp){
					//console.log('success', resp);
					//console.log('respuesta',resp.step);
					id_icon=jQuery('#i_'+step).attr('data-name');
					jQuery("#i_"+step).attr("src",id_icon); //complete
					 
					if(resp.step=='0'){
						window.location.href = redirect_url;
					}
					else{							
						if((jQuery('#a_'+resp.step).attr('data-info')!='')  ||  (jQuery('#a_'+resp.step).attr('data-name')!='' )) {		//video url is nothing					
							step=resp.step;
							img_cur=jQuery('#i_'+step).attr('data-info'); //current					     		 
							jQuery('#i_'+step).attr("src",img_cur);	
							jQuery('#a_'+step).css( 'cursor', 'pointer' );	
						}
						else{
							if(laststep==resp.step){
								step=resp.step;
								img_cur=jQuery('#i_'+step).attr('data-info'); //current					     		 
								jQuery('#i_'+step).attr("src",img_cur);	
								jQuery('#a_'+step).css( 'cursor', 'pointer' );	
							}
						}		
					}
					jQuery("#popup-orange").hide();
				},
				error:function(err){
					console.log(err);
				}
			});
		}
		else{
			jQuery("#popup-orange").hide();
		}
	}
</script>
<?php /**/ ?>


