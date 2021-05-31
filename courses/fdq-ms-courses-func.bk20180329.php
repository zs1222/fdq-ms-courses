<?php 
	//--- INI :: COURSES FUNCTIONS ------------------------------------------------------------------------------------------------------------
		function fdq_ms_course_add_func($fv_ccode, $fv_title, $fv_paid, $fv_active){
			global $wpdb;
			$arr_validation = array();

			//$fv_field = $_REQUEST['fv_field'];
			
			if( trim($fv_ccode) == "" ):
				$arr_validation[] = 'Course code is empty.';
			else:
				$fv_title = sanitize_text_field($fv_title);
			endif;

			if( trim($fv_title) == "" ):
				$arr_validation[] = 'Course name is empty.';
			else:
				$fv_title = sanitize_text_field($fv_title);
			endif;

			if( trim($fv_paid) == "" ):
				$arr_validation[] = 'Course paid/free is empty.';
			else:
				$fv_paid =  sanitize_text_field($fv_paid);
			endif;

			if( trim($fv_active) == "" ):
				$arr_validation[] = 'Course active is empty.';
			else:
				$fv_active =  sanitize_text_field($fv_active);
			endif;

			if( count($arr_validation) < 1 ):
				$qry = "INSERT INTO `wpou_mscm_course`( `ccode`, `title`, `paid`, `cdate`, `active` ) 
						VALUES ('".$fv_ccode."', '".$fv_title."', '".$fv_paid."', '".date('Y-m-d')."', '".$fv_active."')";
				//echo "qry: ".$qry."<br />";
				$rs = $wpdb->query($qry);
				if($rs): 
					$arr_validation = array(); //echo "the course was stored<br />"; 
				else:
					$arr_validation[] = 'There was an error saving the course, please try again.'; //echo "the course was not stored<br />"; 
				endif;
			endif;

			//$arr_validation[] = 'temp error message!!! ';

			return $arr_validation;
		}

		function fdq_ms_course_edit_func($fv_ccode, $fv_title, $fv_paid, $fv_active, $cod01){
			global $wpdb;
			$arr_validation = array();
			
			if( trim($fv_ccode) == "" ):
				$arr_validation[] = 'Course code is empty.';
			else:
				$fv_title = sanitize_text_field($fv_title);
			endif;

			if( trim($fv_title) == "" ):
				$arr_validation[] = 'Course name is empty.';
			else:
				$fv_title = sanitize_text_field($fv_title);
			endif;

			if( trim($fv_paid) == "" ):
				$arr_validation[] = 'Course paid/free is empty.';
			else:
				$fv_paid =  sanitize_text_field($fv_paid);
			endif;

			if( trim($fv_active) == "" ):
				$arr_validation[] = 'Course active is empty.';
			else:
				$fv_active =  sanitize_text_field($fv_active);
			endif;

			if( count($arr_validation) < 1 ):
				$qry = "UPDATE `wpou_mscm_course` SET 
				`ccode`='".$fv_ccode."', 
				`title`='".$fv_title."', 
				`paid`='".$fv_paid."', 
				`active`='".$fv_active."' 
				WHERE course_id = '".$cod01."'";  //echo "qry: ".$qry."<br />";
				$rs = $wpdb->query($qry);  //echo "<pre>"; print_r($rs); echo "</pre>";
				if($rs !== false): 
					$arr_validation = array(); //echo "the course was stored<br />"; 
				else:
					$arr_validation[] = 'There was an error updating the course, please try again.'; //echo "the course was not stored<br />"; 
				endif;
			endif;

			//$arr_validation[] = 'this is a temp error message.';
			return $arr_validation;
		}

		function fdq_ms_course_deactivate_func($cod01){
			global $wpdb;
			$arr_validation = array();

			$qry = "UPDATE `wpou_mscm_course` SET active = '0' WHERE course_id = '".$cod01."'"; //echo "qry: ".$qry."<br />";
			$rs = $wpdb->query($qry);  //echo "<pre>"; print_r($rs); echo "</pre>";
			if($rs !== false): 
				$arr_validation = array(); //echo "the course was stored<br />"; 
			else:
				$arr_validation[] = 'There was an error deactivating the course, please try again.'; //echo "the course was not stored<br />"; 
			endif;

			//$arr_validation[] = 'this is a temp error, in the deactivation process!';

			return $arr_validation;
		}
		
		function fdq_ms_course_del_func(){
			global $wpdb;
		}
	//--- END :: COURSES FUNCTIONS ------------------------------------------------------------------------------------------------------------

	//--- INI :: CONTENT FUNCTIONS ------------------------------------------------------------------------------------------------------------
		function fdq_ms_content_add_func($fv_title, $fv_icon, $fv_video_title, $fv_video_url, $fv_audio_title, $fv_audio_url, $fv_day, $fv_redir_url, $fv_active, $cod01){
			global $wpdb;
			$arr_validation = array();

			//$fv_field = $_REQUEST['fv_field'];
			
			if( trim($fv_title) == "" ):
				$arr_validation[] = 'Course name is empty.';
			else:
				$fv_title = sanitize_text_field($fv_title);
			endif;

			if( trim($fv_icon) == "" ):
				$arr_validation[] = 'Icon is empty.';
			else:
				if( trim($fv_icon) == "00" ):
					$arr_validation[] = 'Icon: please select an Icon.';	
				else:
					$fv_icon = sanitize_text_field($fv_icon);
				endif;
			endif;

			if( trim($fv_video_title) != "" ):
				//$arr_validation[] = 'Video Title is empty.';
				//else:
				$fv_video_title = sanitize_text_field($fv_video_title);
			endif;

			if( trim($fv_video_url) != "" ):
				//$arr_validation[] = 'Video Url is empty.';
				//else:
				if( !isWebsite($fv_video_url) ):
					$arr_validation[] = 'Video Url invalid data.';
				else:
					$fv_video_url = sanitize_text_field($fv_video_url);
				endif;
			endif;

			if( trim($fv_audio_title) != "" ):
				//$arr_validation[] = 'Video Title is empty.';
				//else:
				$fv_audio_title = sanitize_text_field($fv_audio_title);
			endif;

			if( trim($fv_audio_url) != "" ):
				//$arr_validation[] = 'Audio Url is empty.';
				//else:
				if( !isWebsite($fv_audio_url) ):
					$arr_validation[] = 'Audio Url invalid data.';
				else:
					$fv_audio_url = sanitize_text_field($fv_audio_url);
				endif;
			endif;
			
			if( trim($fv_day) == "" ):
				$arr_validation[] = 'Day number is empty.';
			else:
				if(trim($fv_day) == "0"):
					$arr_validation[] = 'Day number needs to be greater than 0.';
				else:
					if( !isInteger($fv_day) ):
						$arr_validation[] = 'Day number invalid data.';
					else:
						$fv_day = sanitize_text_field($fv_day);
					endif;
				endif;
			endif;
			
			if( trim($fv_redir_url) != "" ):
				//only validates if the field has content
				if( !isWebsite($fv_redir_url) ):
					$arr_validation[] = 'Url for redirection invalid data.';
				else:
					$fv_redir_url = sanitize_text_field($fv_redir_url);
				endif;
			endif;

			if( trim($fv_active) == "" ):
				$arr_validation[] = 'Course active is empty.';
			else:
				$fv_active =  sanitize_text_field($fv_active);
			endif;

			


			if( count($arr_validation) < 1  and 1 == 1 ):
				$qry = "INSERT INTO `wpou_mscm_content`( `course_pid`, `icon_pid`, `title`, `video_title`, `video_url`, `audio_title`, `audio_url`, `day_number`, `redir_url`, `cdate`, `active` ) 
						VALUES ('".$cod01."', '".$fv_icon."', '".$fv_title."', '".$fv_video_title."', '".$fv_video_url."', '".$fv_audio_title."', '".$fv_audio_url."', '".$fv_day."', '".$fv_redir_url."', '".date('Y-m-d')."', '".$fv_active."')";

				//echo "qry: ".$qry."<br />";
				$rs = $wpdb->query($qry);
				
				if($rs and 1 == 1): 
					$arr_validation = array(); //echo "the course was stored<br />"; 
				else:
					$arr_validation[] = 'There was an error saving the course, please try again.'; //echo "the course was not stored<br />"; 
				endif;
			endif;

			//$arr_validation[] = 'temp error message!!! ';

			return $arr_validation;
		}

		function fdq_ms_content_edit_func($fv_title, $fv_icon, $fv_video_title, $fv_video_url, $fv_audio_title, $fv_audio_url, $fv_day, $fv_redir_url, $fv_active, $cod01, $cod02){
			global $wpdb;
			$arr_validation = array();

			//$fv_field = $_REQUEST['fv_field'];
			
			if( trim($fv_title) == "" ):
				$arr_validation[] = 'Course name is empty.';
			else:
				$fv_title = sanitize_text_field($fv_title);
			endif;

			if( trim($fv_icon) == "" ):
				$arr_validation[] = 'Icon is empty.';
			else:
				if( trim($fv_icon) == "00" ):
					$arr_validation[] = 'Icon: please select an Icon.';	
				else:
					$fv_icon = sanitize_text_field($fv_icon);
				endif;
			endif;

			if( trim($fv_video_title) != "" ):
				//$arr_validation[] = 'Video Title is empty.';
				//else:
				$fv_video_title = sanitize_text_field($fv_video_title);
			endif;

			if( trim($fv_video_url) != "" ):
				//$arr_validation[] = 'Video Url is empty.';
				//else:
				if( !isWebsite($fv_video_url) ):
					$arr_validation[] = 'Video Url invalide data.';
				else:
					$fv_video_url = sanitize_text_field($fv_video_url);
				endif;
			endif;

			if( trim($fv_audio_title) != "" ):
				//$arr_validation[] = 'Video Title is empty.';
				//else:
				$fv_audio_title = sanitize_text_field($fv_audio_title);
			endif;

			if( trim($fv_audio_url) != "" ):
				//$arr_validation[] = 'Audio Url is empty.';
				//else:
				if( !isWebsite($fv_audio_url) ):
					$arr_validation[] = 'Audio Url invalide data.';
				else:
					$fv_audio_url = sanitize_text_field($fv_audio_url);
				endif;
			endif;

			if( trim($fv_day) == "" ):
				$arr_validation[] = 'Day number is empty.';
			else:
				if(trim($fv_day) == "0"):
					$arr_validation[] = 'Day number needs to be greater than 0.';
				else:
					if( !isInteger($fv_day) ):
						$arr_validation[] = 'Day number invalide data.';
					else:
						$fv_day = sanitize_text_field($fv_day);
					endif;
				endif;
			endif;

			if( trim($fv_redir_url) != "" ):
				//only validates if the field has content
				if( !isWebsite($fv_redir_url) ):
					$arr_validation[] = 'Url for redirection invalid data.';
				else:
					$fv_redir_url = sanitize_text_field($fv_redir_url);
				endif;
			endif;
			

			if( trim($fv_active) == "" ):
				$arr_validation[] = 'Course active is empty.';
			else:
				$fv_active =  sanitize_text_field($fv_active);
			endif;

			


			if( count($arr_validation) < 1  and 1 == 1 ):
				$qry = "UPDATE `wpou_mscm_content` SET 
						`icon_pid`='".$fv_icon."', 
						`title`='".$fv_title."', 
						`video_title`='".$fv_video_title."', 
						`video_url`='".$fv_video_url."', 
						`audio_title`='".$fv_audio_title."', 
						`audio_url`='".$fv_audio_url."', 
						`day_number`='".$fv_day."', 
						`redir_url`='".$fv_redir_url."', 
						`active`= '".$fv_active."' 
						WHERE content_id = '".$cod02."'"; 
				//echo "qry: ".$qry."<br />";
				$rs = $wpdb->query($qry);
				//echo "<pre>"; print_r($rs); echo "</pre>";
				
				if($rs !== false): 
					$arr_validation = array(); //echo "the course was stored<br />"; 
				else:
					$arr_validation[] = 'There was an error saving the course, please try again.'; //echo "the course was not stored<br />"; 
				endif;
			endif;

			//$arr_validation[] = 'temp error message!!! ';

			return $arr_validation;
		}

		function fdq_ms_content_deactivate_func($cod02, $op){
			global $wpdb;
			$arr_validation = array();

			$act = 1;
			if( $op != '1'):
				$act = 0;
			endif;

			$qry = "UPDATE `wpou_mscm_content` SET active = '".$act."' WHERE content_id = '".$cod02."'"; //echo "qry: ".$qry."<br />";
			$rs = $wpdb->query($qry);  //echo "<pre>"; print_r($rs); echo "</pre>";
			if($rs !== false): 
				$arr_validation = array(); //echo "the course was stored<br />"; 
			else:
				$arr_validation[] = 'There was an error deactivating the content, please try again.'; //echo "the course was not stored<br />"; 
			endif;

			//$arr_validation[] = 'this is a temp error, in the deactivation process!';

			return $arr_validation;
		}
	//--- END :: CONTENT FUNCTIONS ------------------------------------------------------------------------------------------------------------
?>