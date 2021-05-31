var jQueryP =  jQuery.noConflict();
var boxObj = '';
jQuery(document).ready(function(){ 

	jQuery("#fdq-ms-close-popup").on('click', function(){
		jQuery("#fdq-ms-vidHtml5Container").html('clear');
		jQuery("#fdq-ms-popup-wrapper").hide();
		jQuery("#popup-grey-bg").hide();
		console.log('audio_url: ' + audio_url);
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
		if( 1 == 1 ){
			jQuery('#jp_audio_0').attr("src",'');	
			jQuery("#popup-orange").hide();
		}else{
			alert('Hola01');
			jQuery(".fdq-ms-popup-close-btn").hide();
			jQuery(".fdq-ms-popup-close-btn01").show();
		}

	});
	jQuery("#fdq-ms-close-popup-orange01").on('click', function(){
		//jQuery('#jp_audio_0').attr("src",'');	
		//jQuery("#popup-orange").hide();
		//alert('Hola02');
		jQuery('#jp_audio_0').attr("src",'');
		jQuery(".fdq-ms-popup-close-btn01").hide();
		jQuery(".fdq-ms-popup-close-btn").show();
		updateStatus();
	});

	jQuery(".open-pop").on('click', function(e){
		e.preventDefault();
		boxObj = jQuery(this).parent().attr('class'); console.log('boxObj:' + boxObj);

		open_id=jQuery(this).attr('data-key');
		
		assig_id     = jQuery("."+boxObj).find('.assig_id').val();
		step         = jQuery("."+boxObj).find('.step').val();
		laststep     = jQuery("."+boxObj).find('.laststep').val();
		redirect_url = jQuery("."+boxObj).find('.redirect_url').val();

		console.log('[open_id=' + open_id + '] [assig_id=' + assig_id + '] [step=' + step + '] [laststep=' + laststep +'] [redirect_url=' + redirect_url + ']');
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

	//---------------------------------------------------------
		jQuery(".hitme").on('click', function(){
			//var padre = jQuery(this).attr('data-key');
			var padre = jQuery(this).parent().attr('class');
			console.log('padre: ' + padre);
			//console.log(jQuery(this).parent().find('.assig_id').val());
			console.log( 'assig_id: ' + jQuery("."+padre).find('.assig_id').val() );
			console.log( 'img: ' + jQuery("."+padre).find('.i_1').attr('data-name') );
		});
	//---------------------------------------------------------
});

function changeclosebtn(){
	//alert('Hola01');
	jQuery(".fdq-ms-popup-close-btn").hide();
	jQuery(".fdq-ms-popup-close-btn01").show();
}

function onClickAudio(link){
	var myCirclePlayer = new CirclePlayer(
				"#jquery_jplayer_1",
				{mp3: link}, 
				{supplied:"mp3,m4a", cssSelectorAncestor: "#cp_container_1"}
			);
	//myCirclePlayer.play(0);
}
function updateStatus(){
	//step = jQuery("."+boxObj).find('')
	console.log('[open_id:' + open_id +  '] [step:' + step +']');
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
				//console.log('respuesta',resp.step);
				//id_icon=jQuery('#i_'+step).attr('data-name');
				//jQuery("#i_"+step).attr("src",id_icon); //complete

				id_icon=jQuery("."+boxObj).find('.i_'+step).attr('data-name');
				jQuery("."+boxObj).find('.i_'+step).attr('src', id_icon); // set icon complete.
				 
				if(resp.step=='0'){
					window.location.href = redirect_url;
				}
				else{							
					//if((jQuery('#a_'+resp.step).attr('data-info')!='')  ||  (jQuery('#a_'+resp.step).attr('data-name')!='' )) { //video url is nothing
					if(	( jQuery("."+boxObj).find('.a_'+resp.step).attr('data-info')!='')  ||  
						( jQuery("."+boxObj).find('.a_'+resp.step).attr('data-name')!='' )) { //if video or audio ok			
						
						//step=resp.step;
						//img_cur=jQuery('#i_'+step).attr('data-info'); //current					     		 
						//jQuery('#i_'+step).attr("src",img_cur);	
						//jQuery('#a_'+step).css( 'cursor', 'pointer' );

						step=resp.step;
						jQuery("."+boxObj).find('.step').val(step);
						img_cur=jQuery("."+boxObj).find('.i_'+step).attr('data-info'); //current					     		 
						jQuery("."+boxObj).find('.i_'+step).attr("src",img_cur);	
						jQuery("."+boxObj).find('.a_'+step).css( 'cursor', 'pointer' );
					}
					else{
						if(laststep==resp.step){
							step=resp.step;
							jQuery("."+boxObj).find('.step').val(step);
							img_cur=jQuery("."+boxObj).find('.i_'+step).attr('data-info'); //current					     		 
							jQuery("."+boxObj).find('.i_'+step).attr("src",img_cur);	
							jQuery("."+boxObj).find('.a_'+step).css( 'cursor', 'pointer' );	
						}
					}		
				}
				jQuery("#popup-orange").hide();
				console.log('step=' + step);
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