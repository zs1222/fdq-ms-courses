<div id="fdq-ms-popup-wrapper" class="fdq-ms-popupWrapper">
	<div class="fdq-ms-popupInnerBox" style="text-align:center;">
		<div class="fdq-ms-popup-close-btn02"><i id="fdq-ms-close-popup" class="fa fa-times-circle-o"></i></div>
		<div id="fdq-ms-vidHtml5Container"></div>
	</div>
</div>
<div id="popup-grey-bg" class="popupGreyBg"></div>



<div id="popup-orange" class="popupOrangeBg">
	<div class="fdq-ms-popupInnerBox-Orange" style="text-align:center;">

		<?php if( 1  == 2 ): ?>
			<div class="fdq-ms-popup-close-btn" style="display:initial;" ><i id="fdq-ms-close-popup-orange" class="fa fa-times-circle-o fabclass"></i></div>
			<div class="fdq-ms-popup-close-btn01" style="display:none;"><i id="fdq-ms-close-popup-orange01" class="fa fa-times-circle-o fabclass"></i></div>
		<?php else: ?>
			<div class="buttonsWrapper">
				<?php $lnk = plugins_url()."/fdq-ms-courses/images/"; ?>
				<div class="btnColLeft">
					<div class="btnCancel"><img src="<?php echo $lnk."/btn_cancel.png"; ?>" alt="" /><span>Interrumpir<br />esta practica</span></div>
				</div>
				<div class="btnColRight">
					<div class="btnCheck"><img src="<?php echo $lnk."/btn_check.png"; ?>" alt="" /><span>Marcar como<br />completa</span></div>
				</div>
			</div>
		<?php endif; ?>

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







