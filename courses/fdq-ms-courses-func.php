<?php 
	//--- this is just a test function the real one is in fdq-ms-courses-func.php ---
	function fdq_ms_call_aws_api01($epoint, $data_arr){
		if( count($data_arr) > 0 ):
			if ( $_SERVER['SERVER_NAME'] == 'www.mindfulscience.loc' ):
				$endpoint = 'http://www.apiserver2.loc/ws/'.$epoint;
				//$endpoint = "http://www.apiserver2.loc/ws/curlgetreq";
			else:
				$endpoint = 'http://18.221.118.14/ws/'.$epoint;
			endif;


			$params = "";
			foreach($data_arr as $val):
				$params .= "/".$val;
			endforeach;

			//$endpoint = "http://www.apiserver2.loc/ws/curlgetreq".$params;
			$endpoint = $endpoint.$params;
			echo "endoint: ".$endpoint."<br />";

			//$ch = curl_init("http://www.apiserver2.loc/ws/curlgetreq/fdaza/123456");
			$ch = curl_init($endpoint);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			//curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			$response = curl_exec($ch);
			echo "<pre>"; print_r($response); echo "</pre>";
			curl_close($ch);
			if(!$response):
			    return false;
			else:
				return $response; //print_r($response);
			endif;
		else:
			return false;
		endif;
	}
	//--- INI :: COURSES FUNCTIONS ------------------------------------------------------------------------------------------------------------
		function fdq_ms_category_data(){
			$data_arr = array();
			$data_arr[0][0] = 1;
			$data_arr[0][1] = 'Programas';
			$data_arr[1][0] = 2;
			$data_arr[1][1] = 'Individuales';
			$data_arr[2][0] = 3;
			$data_arr[2][1] = 'Niñas y Niños';
			$data_arr[3][0] = 4;
			$data_arr[3][1] = 'Ebooks';
			$data_arr[4][0] = 5;
			$data_arr[4][1] = 'Recursos';
			return $data_arr;
		}
		function fdq_ms_course_add_api_func($ccode, $title, $paid, $cdate, $active, $cat_pid){
			$epoint_func = "aws_course_add";
			$data_arr = array();

			$data_arr[] = $ccode;
			$data_arr[] = urlencode($title);
			$data_arr[] = $paid;
			$data_arr[] = $cdate;
			$data_arr[] = $active;
			$data_arr[] = $cat_pid;
			
			$res = fdq_ms_call_aws_api($epoint_func, $data_arr);
			//$res = fdq_ms_call_aws_api01($epoint_func, $data_arr);
			//echo "<pre>"; print_r($res); echo "</pre>";
			$res_arr = json_decode($res, true);
			$api_status = false;
			if( $res_arr['status'] == 'ok' ):
				$api_status = true;
			endif;

			return $api_status;
		}

		function fdq_ms_course_add_func($fv_ccode, $fv_title, $fv_paid, $fv_active, $fv_cat_pid){
			global $wpdb;
			$arr_validation = array();

			//$fv_field = $_REQUEST['fv_field'];
			
			if( trim($fv_ccode) == "" ):
				$arr_validation[] = 'Course code is empty.';
			else:
				$fv_ccode = sanitize_text_field($fv_ccode);
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

			if( trim($fv_cat_pid) == "00" ):
				$arr_validation[] = 'Please, Select a category';
			else:
				if( $fv_cat_pid > 5 or $fv_cat_pid < 1 ):
					$arr_validation[] = 'Please select a valid category.';	
				endif;
			endif;

			if( count($arr_validation) < 1 ):
				
				//--- call the API and send the info to be added --------------
				$cat_pid = $fv_cat_pid;
				$cdate = date('Y-m-d');
				$res = fdq_ms_course_add_api_func($fv_ccode, $fv_title, $fv_paid, $cdate, $fv_active, $cat_pid);
				if($res):
					$qry = "INSERT INTO `wpou_mscm_course`( `ccode`, `title`, `paid`, `cdate`, `active`, `cat_pid` ) 
							VALUES ('".$fv_ccode."', '".$fv_title."', '".$fv_paid."', '".$cdate."', '".$fv_active."', '".$cat_pid."')";
					//echo "qry: ".$qry."<br />";
					$rs = $wpdb->query($qry);
					if($rs): 
						$arr_validation = array(); //echo "the course was stored<br />"; 
					else:
						$arr_validation[] = 'There was an error saving the course, please try again.'; //echo "the course was not stored<br />"; 
					endif;
				else:
					$arr_validation[] = 'There was an error saving the course, please try again.'; //echo "the course was not stored<br />"; 
				endif;
			endif;

			//$arr_validation[] = 'temp error message!!! ';

			return $arr_validation;
		}

		function fdq_ms_course_edit_api_func($course_id, $ccode, $title, $paid, $active, $cat_pid){
			$epoint_func = "aws_course_edit";
			$data_arr = array();

			$data_arr[] = $course_id;
			$data_arr[] = $ccode;
			$data_arr[] = urlencode($title);
			$data_arr[] = $paid;
			$data_arr[] = $active;
			$data_arr[] = $cat_pid;
			
			$res = fdq_ms_call_aws_api($epoint_func, $data_arr);
			//$res = fdq_ms_call_aws_api01($epoint_func, $data_arr);
			//echo "<pre>"; print_r($res); echo "</pre>";
			$res_arr = json_decode($res, true);
			$api_status = false;
			if( $res_arr['status'] == 'ok' ):
				$api_status = true;
			endif;

			return $api_status;
		}

		function fdq_ms_course_edit_func($fv_ccode, $fv_title, $fv_paid, $fv_active, $fv_cat_pid, $cod01){
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

			if( trim($fv_cat_pid) == "00" ):
				$arr_validation[] = 'Please, Select a category';
			else:
				if( $fv_cat_pid > 5 or $fv_cat_pid < 1 ):
					$arr_validation[] = 'Please select a valid category.';	
				endif;
			endif;

			if( count($arr_validation) < 1 ):
				$cat_pid = $fv_cat_pid;
				$res = fdq_ms_course_edit_api_func($cod01, $fv_ccode, $fv_title, $fv_paid, $fv_active, $cat_pid);
				if($res):
					$qry = "UPDATE `wpou_mscm_course` SET 
					`ccode`='".$fv_ccode."', 
					`title`='".$fv_title."', 
					`paid`='".$fv_paid."', 
					`active`='".$fv_active."', 
					`cat_pid`='".$fv_cat_pid."' 
					WHERE course_id = '".$cod01."'";  //echo "qry: ".$qry."<br />";
					$rs = $wpdb->query($qry);  //echo "<pre>"; print_r($rs); echo "</pre>";
					if($rs !== false): 
						$arr_validation = array(); //echo "the course was stored<br />"; 
					else:
						$arr_validation[] = '01There was an error updating the course, please try again.'; //echo "the course was not stored<br />"; 
					endif;
				else:
					$arr_validation[] = '02There was an error updating the course, please try again.'; //echo "the course was not stored<br />"; 
				endif;
			endif;

			//$arr_validation[] = 'this is a temp error message.';
			return $arr_validation;
		}

		function fdq_ms_course_deactivate_api_func($course_id){
			$epoint_func = "aws_course_deactivate";
			$data_arr = array();

			$data_arr[] = $course_id;
			
			$res = fdq_ms_call_aws_api($epoint_func, $data_arr);
			//$res = fdq_ms_call_aws_api01($epoint_func, $data_arr);
			//echo "<pre>"; print_r($res); echo "</pre>";
			$res_arr = json_decode($res, true);
			$api_status = false;
			if( $res_arr['status'] == 'ok' ):
				$api_status = true;
			endif;

			return $api_status;
		}
		
		function fdq_ms_course_deactivate_func($cod01){
			global $wpdb;
			$arr_validation = array();

			$res = fdq_ms_course_deactivate_api_func($cod01);
			if($res):
				$qry = "UPDATE `wpou_mscm_course` SET active = '0' WHERE course_id = '".$cod01."'"; //echo "qry: ".$qry."<br />";
				$rs = $wpdb->query($qry);  //echo "<pre>"; print_r($rs); echo "</pre>";
				if($rs !== false): 
					$arr_validation = array(); //echo "the course was stored<br />"; 
				else:
					$arr_validation[] = 'There was an error deactivating the course, please try again.'; //echo "the course was not stored<br />"; 
				endif;

				//$arr_validation[] = 'this is a temp error, in the deactivation process!';
			else:
				$arr_validation[] = 'There was an error deactivating the course, please try again.'; //echo "the course was not stored<br />"; 
			endif;
			
			return $arr_validation;
		}
		
		function fdq_ms_course_del_func(){
			
			global $wpdb;
		}
	//--- END :: COURSES FUNCTIONS ------------------------------------------------------------------------------------------------------------

	//--- INI :: CONTENT FUNCTIONS ------------------------------------------------------------------------------------------------------------
		function fdq_ms_content_add_api_func($course_pid, $icon_pid, $title, $video_title, $video_url, $audio_title, $audio_url, $day_number, $redir_url, $cdate, $active){
			$epoint_func = "aws_content_add";
			$data_arr = array();

			$data_arr[] = $course_pid;
			$data_arr[] = $icon_pid;
			$data_arr[] = ( trim($title)== "" ) ? '[-]' : urlencode($title) ;
			$data_arr[] = ( trim($video_title)== "" ) ? '[-]' : urlencode($video_title) ;
			$data_arr[] = ( trim($video_url)== "" ) ? '[-]' : base64_encode($video_url) ;
			$data_arr[] = ( trim($audio_title)== "" ) ? '[-]' : urlencode($audio_title) ;
			$data_arr[] = ( trim($audio_url)== "" ) ? '[-]' : base64_encode($audio_url) ;
			$data_arr[] = $day_number;
			$data_arr[] = ( trim($redir_url)== "" ) ? '[-]' : base64_encode($redir_url) ;
			$data_arr[] = $cdate;
			$data_arr[] = $active;
			
			$res = fdq_ms_call_aws_api($epoint_func, $data_arr);
			//$res = fdq_ms_call_aws_api01($epoint_func, $data_arr);
			//echo "<pre>"; print_r($res); echo "</pre>";
			$res_arr = json_decode($res, true);
			$api_status = false;
			if( $res_arr['status'] == 'ok' ):
				$api_status = true;
			endif;

			return $api_status;
		}

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
			/**/
			if( count($arr_validation) < 1  and 1 == 1 ):
				
				$cdate = date('Y-m-d');

				$res = fdq_ms_content_add_api_func($cod01, $fv_icon, $fv_title, $fv_video_title, $fv_video_url, $fv_audio_title, $fv_audio_url, $fv_day, $fv_redir_url, $cdate, $fv_active);
				if( $res ):
					$qry = "INSERT INTO `wpou_mscm_content`( `course_pid`, `icon_pid`, `title`, `video_title`, `video_url`, `audio_title`, `audio_url`, `day_number`, `redir_url`, `cdate`, `active` ) 
						VALUES ('".$cod01."', '".$fv_icon."', '".$fv_title."', '".$fv_video_title."', '".$fv_video_url."', '".$fv_audio_title."', '".$fv_audio_url."', '".$fv_day."', '".$fv_redir_url."', '".$cdate."', '".$fv_active."')";

					//echo "qry: ".$qry."<br />";
					$rs = $wpdb->query($qry);
					
					if($rs and 1 == 1): 
						$arr_validation = array(); //echo "the course was stored<br />"; 
					else:
						$arr_validation[] = 'There was an error saving the course content, please try again.'; //echo "the course was not stored<br />"; 
					endif;
				else:
					$arr_validation[] = 'There was an error saving the course content, please try again.'; //echo "the course was not stored<br />"; 
				endif;
			endif;

			//$arr_validation[] = 'temp error message!!! ';

			return $arr_validation;
		}

		function fdq_ms_content_edit_api_func($content_id, $course_pid, $icon_pid, $title, $video_title, $video_url, $audio_title, $audio_url, $day_number, $redir_url, $active){
			$epoint_func = "aws_content_edit";
			$data_arr = array();

			$data_arr[] = $content_id;
			$data_arr[] = $course_pid;
			$data_arr[] = $icon_pid;
			$data_arr[] = ( trim($title)== "" ) ? '[-]' : urlencode($title) ;
			$data_arr[] = ( trim($video_title)== "" ) ? '[-]' : urlencode($video_title) ;
			$data_arr[] = ( trim($video_url)== "" ) ? '[-]' : base64_encode($video_url) ;
			$data_arr[] = ( trim($audio_title)== "" ) ? '[-]' : urlencode($audio_title) ;
			$data_arr[] = ( trim($audio_url)== "" ) ? '[-]' : base64_encode($audio_url) ;
			$data_arr[] = $day_number;
			$data_arr[] = ( trim($redir_url)== "" ) ? '[-]' : base64_encode($redir_url) ;
			$data_arr[] = $active;
			
			$res = fdq_ms_call_aws_api($epoint_func, $data_arr);
			//$res = fdq_ms_call_aws_api01($epoint_func, $data_arr);
			//echo "<pre>"; print_r($res); echo "</pre>";
			$res_arr = json_decode($res, true);
			$api_status = false;
			if( $res_arr['status'] == 'ok' ):
				$api_status = true;
			endif;

			return $api_status;
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
				
				$res = fdq_ms_content_edit_api_func($cod02, $cod01, $fv_icon, $fv_title, $fv_video_title, $fv_video_url, $fv_audio_title, $fv_audio_url, $fv_day, $fv_redir_url, $fv_active);
				if( $res ):
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
				else:
					$arr_validation[] = 'There was an error saving the course, please try again.'; //echo "the course was not stored<br />"; 	
				endif;
			endif;

			//$arr_validation[] = 'temp error message!!! ';

			return $arr_validation;
		}

		function fdq_ms_content_deactivate_api_func($content_id, $op){
			$epoint_func = "aws_content_deactivate";
			$data_arr = array();

			$data_arr[] = $content_id;
			$data_arr[] = $op;

			$res = fdq_ms_call_aws_api($epoint_func, $data_arr);
			//$res = fdq_ms_call_aws_api01($epoint_func, $data_arr);
			//echo "<pre>"; print_r($res); echo "</pre>";
			$res_arr = json_decode($res, true);
			$api_status = false;
			if( $res_arr['status'] == 'ok' ):
				$api_status = true;
			endif;

			return $api_status;
		}
		
		function fdq_ms_content_deactivate_func($cod02, $op){
			global $wpdb;
			$arr_validation = array();

			$act = 1;
			if( $op != '1'):
				$act = 0;
			endif;

			$res = fdq_ms_content_deactivate_api_func($cod02, $op);
			if( $res ):
				$qry = "UPDATE `wpou_mscm_content` SET active = '".$act."' WHERE content_id = '".$cod02."'"; //echo "qry: ".$qry."<br />";
				$rs = $wpdb->query($qry);  //echo "<pre>"; print_r($rs); echo "</pre>";
				if($rs !== false): 
					$arr_validation = array(); //echo "the course was stored<br />"; 
				else:
					$arr_validation[] = 'There was an error deactivating the content, please try again.'; //echo "the course was not stored<br />"; 
				endif;
			else:
				$arr_validation[] = 'There was an error deactivating the content, please try again.'; //echo "the course was not stored<br />"; 
			endif;

			//$arr_validation[] = 'this is a temp error, in the deactivation process!';

			return $arr_validation;
		}
	//--- END :: CONTENT FUNCTIONS ------------------------------------------------------------------------------------------------------------
?>