
<style>
	.open-pop-youtube{ cursor: pointer; background-color: #f39364; color: #fff; padding: 10px 10px; border-radius: 3px; }
	.open-pop-youtube:hover{background-color: #57bb89; }
	.open-pop-vimeo{ cursor: pointer; background-color: #f39364; color: #fff; padding: 10px 10px; border-radius: 3px; }
	.open-pop-vimeo:hover{background-color: #57bb89; }

	/*-- INI :: Popup Wrapper --------------------------------------------------------------*/
	.popupWrapper { width:100%; height:100%; position:fixed; top:0; left:0; background:transparent; z-index:999999; display:none; }
	.popupInnerBox{ width:100%; max-width:640px; margin:7% auto 0; background:#fff; border:1px solid #555; box-shadow:2px 2px 40px #222; padding:20px; border-radius: 15px; }
	.popupGreyBg {background:#000;opacity:0.6;position:fixed;width:100%;height:100%;top:0;left:0; z-index: 100000; display:none;}
	.popup-close-btn{ width:100%; text-align:right; height:22px; }
	.popup-close-btn i{ font-size:25px; display:inline-block; margin:-11px -12px 0 0; float:right; cursor: pointer; }

	/*--- responsive video wrapper ---------------------------------------------------------*/
	.resp-videowrapper { float: none;clear: both;width: 100%;position: relative;padding-bottom: 56.25%;padding-top: 10px;height: 0; } /* play with padding-top from 25px to 30px*/
	.resp-videowrapper iframe { position: absolute;top: 0;left: 0;width: 100%;height: 100%;}	


	/*--- ajax resp ------------------------------------------------------------------------*/
	.call-ajax{ cursor: pointer; background-color: #f39364; color: #fff; padding: 10px 10px; border-radius: 3px; }
	.call-ajax:hover{background-color: #57bb89; }

	.ajax-resp{ float:left;width:100%; box-sizing: border-box; padding:20px; }


</style>

<h1 style="margin-bottom:30px;">this page will display all the content for PAID COURSE</h1>

<span class="open-pop-youtube">open popup - youtube</span>
<span class="open-pop-vimeo">open popup - vimeo</span>
<span class="call-ajax">Call AJAX</span>

<div class="ajax-resp"></div>


<div id="popup-wrapper" class="popupWrapper">
	<div class="popupInnerBox" style="text-align:center;">
		<div class="popup-close-btn"><i id="close-popup" class="fa fa-times-circle-o"></i></div>
		<div id="vidHtml5Container"></div>
	</div>
</div>
<div id="popup-grey-bg" class="popupGreyBg"></div>

<script>
	//var jqnc =  jQuery.noConflict();
	/**/
	jQuery(document).ready(function(){

		jQuery("#close-popup").on('click', function(){
			jQuery("#vidHtml5Container").html('clear');
			jQuery("#popup-wrapper").hide();
			jQuery("#popup-grey-bg").hide();
		});

		jQuery(".open-pop-youtube").on('click', function(){
			jQuery("#vidHtml5Container").html('<div class="resp-videowrapper"><iframe width="560" height="315" src="//www.youtube.com/embed/RH3i7qONrT4?rel=0&amp;showinfo=0&amp;controls=1&amp;autoplay=1&amp;color=white&amp;theme=light" frameborder="0" allowfullscreen></iframe></div>');
			jQuery("#popup-wrapper").show();
			jQuery("#popup-grey-bg").show();
		});

		jQuery(".open-pop-vimeo").on('click', function(){
			jQuery("#vidHtml5Container").html('<div class="resp-videowrapper"><iframe src="https://player.vimeo.com/video/87110435?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>');
			jQuery("#popup-wrapper").show();
			jQuery("#popup-grey-bg").show();
		});
	});
	/**/
</script>