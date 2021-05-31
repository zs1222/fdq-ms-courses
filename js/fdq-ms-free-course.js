 
var jQueryP =  jQuery.noConflict();

jQuery(document).ready(function(){ 
	
		/*var myCirclePlayer = new CirclePlayer("#jquery_jplayer_1",
			{
			 
					mp3: "http://dev.mindfulscience.es/wp-content/uploads/2017/01/reto-dia-1.mp3"
			}, {
				supplied:"mp3,m4a",
				cssSelectorAncestor: "#cp_container_1" 
			});*/
			
  
		jQuery("#close-popup").on('click', function(){

			jQuery("#vidHtml5Container").html('clear');

			jQuery("#popup-wrapper").hide();
			jQuery("#popup-grey-bg").hide();
			
			//jQuery("#vidHtml5Container-OrangeBG").html('<div class="resp-videowrapper"><div id="jquery_jplayer_1" class="cp-jplayer"></div> <div class="prototype-wrapper"> <div id="cp_container_1" class="cp-container" style="width: 400px!important;height: 400px!important;"> <div class="cp-buffer-holder">  <div class="cp-buffer-1"></div> <div class="cp-buffer-2"></div> </div> <div class="cp-progress-holder">  <div class="cp-progress-1"></div> <div class="cp-progress-2"></div></div> <div class="cp-circle-control"></div> <ul class="cp-controls"> <li><a class="cp-play" tabindex="1">play</a></li> <li><a class="cp-pause" style="display:none;" tabindex="1">pause</a></li>  </ul></div> </div></div>');

			 if(open_id==laststep){
				updateStatus();}
				else{
				jQuery("#popup-orange").show();
				jQuery('#jp_audio_0').attr("src",audio_url);		
			  onClickAudio(audio_url);
			 } 
			
		});

		
		jQuery("#close-popup-orange").on('click', function(){

			//jQuery("#vidHtml5Container-OrangeBG").html('clear');
		    jQuery('#jp_audio_0').attr("src",'');	
			jQuery("#popup-orange").hide();
			//location.reload();
			
		});


	 

		jQuery(".open-pop").on('click', function(){
			
			
			open_id=jQuery(this).attr('data-key');
			if(open_id<=step){
				str_video_url=jQuery(this).attr('data-info');
				audio_url= jQuery(this).attr('data-name');
				jQuery("#vidHtml5Container").html('<div class="resp-videowrapper"><iframe id="myvideo" src="' + str_video_url + '?autoplay=1" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>');

				jQuery("#popup-wrapper").show();

				jQuery("#popup-grey-bg").show();
			}
		});
		
		/*jQuery(".cp-play").on('click', function(){
			alert('aa');
			onClickAudio();		
		});*/
		
		

 
	 
	
	
	
});

  function onClickAudio(link){
  
  var myCirclePlayer = new CirclePlayer("#jquery_jplayer_1",
			{
			 
					mp3: link
			}, {
				supplied:"mp3,m4a",
				cssSelectorAncestor: "#cp_container_1" 
			});
			
			//myCirclePlayer.play(0);
  }

	/*$(function () {
		 
	   jQuery.ajax({
		$("#jquery_jplayer_1").jPlayer("destroy");

		var link = "test" + jQuery(this).data('key') + ".mp3";
		//"http://dev.mindfulscience.es/wp-content/uploads/2017/01/reto-dia-1.mp3"
		var myCirclePlayer = new CirclePlayer("#jquery_jplayer_1",
			{
			 
					mp3: link
			}, {
				supplied:"mp3,m4a",
				cssSelectorAncestor: "#cp_container_1" 
			});
			
		 

		player.jPlayer("play", 0);
	});
	});*/
 

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
					console.log('success', resp);
					console.log('respuesta',resp.step);
						id_icon=jQuery('#i_'+step).attr('data-name');
						jQuery("#i_"+step).attr("src",id_icon); //complete
						if(resp.step=='0'){
							window.location.href = redirect_url;
						}
						else{
							step=resp.step;
						    img_cur=jQuery('#i_'+step).attr('data-info'); //current					     		 
							jQuery('#i_'+step).attr("src",img_cur);	
							jQuery('#a_'+step).css( 'cursor', 'pointer' );																			
						}
					   jQuery("#popup-orange").hide();
						
				},
				error:function(err){
					console.log(err);
				}
			});
		   }
		   else
		   {
			 jQuery("#popup-orange").hide();
		   }
	
		}
		
	/*	 function updateStatus(){
        step=step+1;
		var dataString = 'step='+step+'&id='+assig_id;
        jQuery.ajax({
            type: "POST",
            url: "http://dev.mindfulscience.es/wp-content/plugins/fdq-ms-courses/template/fdq-ms-next-course.php",
            data: dataString,
            success: function() {  
				location.reload();
				if( step ==9){
				 //redireccionar a pagos
				}
            }
        });
		
    }*/
	
	 
	
	
 