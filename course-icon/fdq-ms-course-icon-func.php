<?php 
	function fdq_ms_course_icon_upload_func($icon, $target_dir, $icon_name){
		//$target_dir = "../wp-content/uploads/ms-course-images/";
		
		$arr_validation = array();
		$pic_name = basename($icon["name"]);
		$target_file = $target_dir . $pic_name;
		$pic_newname = $icon_name;
		//$uploadOk = false;
		$imgInfo = "";

		$imgInfo = 	pathinfo($target_file);
		$pic_newname .= ".".$imgInfo['extension'];
		$target_file01 = $target_dir.$pic_newname;

		//echo "picName: ".$pic_name."<br />";
		//echo "picnewname: ".$pic_newname."<br />";
		//echo "imageFileType: ".$imgInfo['extension']."<br />";

		//echo "function  :: fdq_ms_course_icon_upload_func<br />";
		//echo "<pre>"; print_r($icon); echo "</pre>";
		
		//--- INI :: file verification --------------------------------------------------
		if( trim($pic_name) != "" ): 
			//--- check if the image is a real image and not a fake one ----
			$checkImg = getimagesize($icon["tmp_name"]);
			//echo "checkImg:<br />"; echo "<pre>";print_r($checkImg);echo "</pre>";
			if($checkImg !== false): 
				//--- check if the image already exists ------------------------
				//if ( !file_exists($target_file01) ): 
					//--- check file zise ------------------------------------------
					// 0.7MB = 716800bytes :: 1MB = 1048576bytes :: 2 MB = 2097152 bytes
					if ($icon["size"] <= 400000 ): 
						//--- check for allowed image formats jpg, jpeg, gif, png  -----
						$_ext = strtolower($imgInfo['extension']); //echo "ext:".$_ext."<br />";
						if( $_ext == "jpg" || $_ext == "jpeg" || $_ext == "png" || $_ext == "gif" ): 
							//--- start the imge upload -----------------------------------------------
							//if (move_uploaded_file($icon["tmp_name"], $target_file)):
							if (move_uploaded_file($icon["tmp_name"], $target_file01)):
								//echo "The file ". basename( $icon["name"]). " has been uploaded.";
								//$uploadOk = true;
							else:
								//$uploadOk = false;
								$arr_validation[] = 'There was an error uploading your file, please try again';
							endif;
							//$uploadOk = true;				
						else:
							//$uploadOk = false;
							$arr_validation[] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed';
						endif;
					else:
						//$uploadOk = false;
						$arr_validation[] = 'The file is larger that maximum limit (400KB)';
					endif;
				//else:
					//$uploadOk = false;
					//$arr_validation[] = 'The file already exists.';
				//endif;
			else:
				//$uploadOk = false;
				$arr_validation[] = 'The file is not an image';
			endif;
		else:
			//$uploadOk = false;
			$arr_validation[] = 'File is empty!!';
		endif;

		return $arr_validation;
	}

	function fdq_ms_course_icon_add_api_func($fv_icon_day_number, $iconComplete, $iconCurrent, $iconBlocked, $cdate, $fv_active ){
		$epoint_func = "aws_icon_add";
		$data_arr = array();

		$data_arr[] = urlencode($fv_icon_day_number);
		$data_arr[] = $iconComplete;
		$data_arr[] = $iconCurrent;
		$data_arr[] = $iconBlocked;
		$data_arr[] = $cdate;
		$data_arr[] = $fv_active;
		
		$res = fdq_ms_call_aws_api($epoint_func, $data_arr);
		//echo "<pre>"; print_r($res); echo "</pre>";
		$res_arr = json_decode($res, true);
		$api_status = false;
		if( $res_arr['status'] == 'ok' ):
			$api_status = true;
		endif;

		return $api_status;
	}

	function fdq_ms_course_icon_add_func($iconComplete, $iconCurrent, $iconBlocked, $fv_active, $fv_icon_day_number){
		global $wpdb;
		$cdate = date('Y-m-d');
		$arr_validation = array();
		$target_dir = "../wp-content/uploads/ms-course-images/";
		$icon_name01 = "";
		$icon_name02 = "";
		$icon_name03 = "";

		$str = $fv_icon_day_number;
		$str_encoded = utf8_encode($fv_icon_day_number);
		$str_decoded = utf8_decode($str_encoded);
		//echo "icon_name: ".$str."<br />";
		//echo "icon_name utf8Encoded: ".$str_encoded."<br />";
		//echo "icon_name utf8Decoded: ".$str_decoded."<br />";
		//echo "icon_name sanitize: ".sanitize_text_field($fv_icon_day_number)."<br />";

		//---- INI :: validate icon complete ----------------------------------------
			//echo "icon01: ".basename($iconComplete["name"])."<br />";
			if( basename($iconComplete["name"]) != "" ):
				$icon_name01 = "c_icon_".date('YmdHis')."co";
				$arr_validation = fdq_ms_course_icon_upload_func($iconComplete, $target_dir, $icon_name01);
				if( count($arr_validation) < 1 ):
					$_file = explode(".", basename($iconComplete["name"]));
					$c = count($_file) - 1;
					$icon_name01 = $icon_name01 . "." . $_file[$c];
				endif;
			endif;
		//---- END :: validate icon complete ----------------------------------------
		
		//---- INI :: validate icon current -----------------------------------------
			//echo "icon02: ".basename($iconCurrent["name"])."<br />";
			if( basename($iconCurrent["name"]) != "" && count($arr_validation) < 1 ):
				$icon_name02 = "c_icon_".date('YmdHis')."cu";
				$arr_validation = fdq_ms_course_icon_upload_func($iconCurrent, $target_dir, $icon_name02);
				if( count($arr_validation) < 1 ):
					$_file = explode(".", basename($iconCurrent["name"]));
					$c = count($_file) - 1;
					$icon_name02 = $icon_name02 . "." . $_file[$c];
				endif;
			endif;
		//---- END :: validate icon current -----------------------------------------
		
		//---- INI :: validate icon blocked -----------------------------------------
			//echo "icon03: ".basename($iconBlocked["name"])."<br />";
			if( basename($iconBlocked["name"]) !="" && count($arr_validation) < 1 ):
				$icon_name03 = "c_icon_".date('YmdHis')."bl";
				$arr_validation = fdq_ms_course_icon_upload_func($iconBlocked, $target_dir, $icon_name03);
				if( count($arr_validation) < 1 ):
					$_file = explode(".", basename($iconBlocked["name"]));
					$c = count($_file) - 1;
					$icon_name03 = $icon_name03 . "." . $_file[$c];
				endif;
			endif;
		//---- END :: validate icon blocked -----------------------------------------

		//---- INI :: validate icon name --------------------------------------------
			if( trim($fv_icon_day_number) == "" ):
				$arr_validation[] = 'Icon name is empty.';
			else:
				if( !isAlphaNum($fv_icon_day_number) ):
					$arr_validation[] = 'Icon name invalid data.';
				else:
					$fv_icon_day_number = sanitize_text_field($fv_icon_day_number);
				endif;
			endif;
		//---- END :: validate icon name --------------------------------------------
		
		//--- INI :: store the information in the database --------------------------
			if( count($arr_validation) < 1 ):
				
				$res = fdq_ms_course_icon_add_api_func($fv_icon_day_number, $icon_name01, $icon_name02, $icon_name03, $cdate, $fv_active );
				if( $res ):
					//--- save data in WP --------------------------------------------------------
					$qry = "INSERT INTO `wpou_mscm_icon` (`icon_day_number`, `icon_complete`, `icon_current`, `icon_blocked`, `cdate`, `active`) 
							VALUES('".$fv_icon_day_number."', '".$icon_name01."', '".$icon_name02."', '".$icon_name03."', '".$cdate."', '".$fv_active."')";
					//echo "qry: ".$qry."<br />";
					$rs = $wpdb->query($qry);
					if($rs): 
						$arr_validation = array(); //echo "the course was stored<br />"; 
					else:
						$arr_validation[] = 'There was an error saving the icon(s), please try again.'; //echo "the course was not stored<br />"; 
					endif;
				else:
					$arr_validation[] = 'There was an error saving the icon(s), please try again.'; //echo "the course was not stored<br />"; 
				endif;
			endif;
		//--- END :: store the information in the database --------------------------
		//$arr_validation[] = 'temp error message!!! ';

		return $arr_validation;
	}

	function fdq_ms_course_icon_edit_api_func($icon_id, $fv_icon_day_number, $iconComplete, $iconCurrent, $iconBlocked, $fv_active){
		$epoint_func = "aws_icon_edit";

		$data_arr = array();
		$data_arr[] = $icon_id;
		$data_arr[] = urlencode($fv_icon_day_number);
		$data_arr[] = ( trim($iconComplete) == "" ) ? '[-]' : $iconComplete ;
		$data_arr[] = ( trim($iconCurrent) == "" ) ? '[-]' : $iconCurrent ;
		$data_arr[] = ( trim($iconBlocked) == "" ) ? '[-]' : $iconBlocked ;
		$data_arr[] = $fv_active;
		
		//echo "<pre>"; print_r($data_arr); echo "</pre>";

		$res = fdq_ms_call_aws_api($epoint_func, $data_arr);
		//echo "<pre>"; print_r($res); echo "</pre>";
		$res_arr = json_decode($res, true);
		$api_status = false;
		if( $res_arr['status'] == 'ok' ):
			$api_status = true;
		endif;

		return $api_status;
	}

	function fdq_ms_course_icon_edit_func($iconComplete, $iconCurrent, $iconBlocked, $fv_active, $fv_icon_day_number, $cod01){
		global $wpdb;
		$arr_validation = array();
		$target_dir = "../wp-content/uploads/ms-course-images/";
		$icon_name01 = "";
		$icon_name02 = "";
		$icon_name03 = "";
		//---- INI :: validate icon complete ----------------------------------------	
			//echo "icon01: ".basename($iconComplete["name"])."<br />";
			if( basename($iconComplete["name"]) != "" ):
				$icon_name01 = "c_icon_".date('YmdHis')."co";
				$arr_validation = fdq_ms_course_icon_upload_func($iconComplete, $target_dir, $icon_name01);
				if( count($arr_validation) < 1 ):
					$_file = explode(".", basename($iconComplete["name"]));
					$c = count($_file) - 1;
					$icon_name01 = $icon_name01 . "." . $_file[$c];
				endif;
			endif;
		//---- END :: validate icon complete ----------------------------------------
		
		//---- INI :: validate icon current -----------------------------------------
			//echo "icon02: ".basename($iconCurrent["name"])."<br />";
			if( basename($iconCurrent["name"]) != "" && count($arr_validation) < 1 ):
				$icon_name02 = "c_icon_".date('YmdHis')."cu";
				$arr_validation = fdq_ms_course_icon_upload_func($iconCurrent, $target_dir, $icon_name02);
				if( count($arr_validation) < 1 ):
					$_file = explode(".", basename($iconCurrent["name"]));
					$c = count($_file) - 1;
					$icon_name02 = $icon_name02 . "." . $_file[$c];
				endif;
			endif;
		//---- END :: validate icon current -----------------------------------------
		
		//---- INI :: validate icon blocked -----------------------------------------
			//echo "icon03: ".basename($iconBlocked["name"])."<br />";
			if( basename($iconBlocked["name"]) !="" && count($arr_validation) < 1 ):
				$icon_name03 = "c_icon_".date('YmdHis')."bl";
				$arr_validation = fdq_ms_course_icon_upload_func($iconBlocked, $target_dir, $icon_name03);
				if( count($arr_validation) < 1 ):
					$_file = explode(".", basename($iconBlocked["name"]));
					$c = count($_file) - 1;
					$icon_name03 = $icon_name03 . "." . $_file[$c];
				endif;
			endif;
		//---- END :: validate icon blocked -----------------------------------------

		//---- INI :: validate icon name --------------------------------------------
			if( trim($fv_icon_day_number) == "" ):
				$arr_validation[] = 'Icon name is empty.';
			else:
				if( !isAlphaNum($fv_icon_day_number) ):
					$arr_validation[] = 'Icon name invalid data.';
				else:
					$fv_title = sanitize_text_field($fv_title);
				endif;
			endif;
		//---- END :: validate icon name --------------------------------------------

		//--- INI :: store the information in the database --------------------------
			if( count($arr_validation) < 1 ):
				//$qry = "INSERT INTO `wpou_mscm_icon` (`icon_complete`, `icon_current`, `icon_blocked`, `cdate`, `active`) 
				//		VALUES('".$icon_name01."', '".$icon_name02."', '".$icon_name03."', '".date('Y-m-d')."', '".$fv_active."')";
				$res = fdq_ms_course_icon_edit_api_func($cod01, $fv_icon_day_number, $icon_name01, $icon_name02, $icon_name03, $fv_active );
				if($res):
					$qry = "";
					$qry .= "UPDATE `wpou_mscm_icon` SET ";
					$qry .= "active =  '".$fv_active."'";
					$qry .= ", icon_day_number =  '".$fv_icon_day_number."'";
					if( $icon_name01 != "" ):
						$qry .= ", icon_complete =  '".$icon_name01."'";
					endif;
					if( $icon_name02 != "" ):
						$qry .= ", icon_current =  '".$icon_name02."'";
					endif;
					if( $icon_name03 != "" ):
						$qry .= ", icon_blocked =  '".$icon_name03."'";
					endif;
					$qry .= " WHERE icon_id ='".$cod01."'";

					//echo "qry: ".$qry."<br />";
					$rs = $wpdb->query($qry);
					if($rs): 
						$arr_validation = array(); //echo "the course was stored<br />"; 
					else:
						$arr_validation[] = 'There was an error updating the icon(s), please try again.'; //echo "the course was not stored<br />"; 
					endif;
				else:
					$arr_validation[] = 'There was an error updating the icon(Ss), please try again.'; //echo "the course was not stored<br />"; 
				endif;
			endif;
		//--- END :: store the information in the database --------------------------

		//$arr_validation[] = 'temp error message!!! ';

		return $arr_validation;
	}

	function fdq_ms_course_icon_deactivate_api_func($icon_id){
		$epoint_func = "aws_icon_deactivate";

		$data_arr = array();
		$data_arr[] = $icon_id;
		//echo "<pre>"; print_r($data_arr); echo "</pre>";

		$res = fdq_ms_call_aws_api($epoint_func, $data_arr);
		//echo "<pre>"; print_r($res); echo "</pre>";
		$res_arr = json_decode($res, true);
		$api_status = false;
		if( $res_arr['status'] == 'ok' ):
			$api_status = true;
		endif;

		return $api_status;
	}

	function fdq_ms_course_icon_deactivate_func($cod01){
		global $wpdb;
		$arr_validation = array();

		$res = fdq_ms_course_icon_deactivate_api_func($cod01);

		if($res):
			$qry = "UPDATE `wpou_mscm_icon` SET active = '0' WHERE icon_id = '".$cod01."'"; //echo "qry: ".$qry."<br />";
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
	
	function fdq_ms_course_icon_del_func(){
		global $wpdb;
	}
?>