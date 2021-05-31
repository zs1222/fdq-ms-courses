<?php
	function fdq_ms_mgroup_add_func($fv_title, $fv_description, $fv_absence_days, $fv_user_type, $fv_subject, $fv_msg, $fv_active){
		global $wpdb;
		$arr_validation = array();

		if( trim($fv_title) == "" ):
			$arr_validation[] = 'Course name is empty.';
		else:
			$fv_title = sanitize_text_field($fv_title);
		endif;

		if( trim($fv_description) != "" ):
			$fv_description = sanitize_text_field($fv_description);
		endif;

		if( trim($fv_absence_days) == "" ):
			$arr_validation[] = 'Days of inactivity is empty.';
		else:
			if(trim($fv_absence_days) == "0"):
				$arr_validation[] = 'Days of inactivity needs to be greater than 0.';
			else:
				if( !isInteger($fv_absence_days) ):
					$arr_validation[] = 'Days of inactivity invalid data.';
				else:
					$fv_absence_days = sanitize_text_field($fv_absence_days);
				endif;
			endif;
		endif;

		//if( trim($fv_user_type) != "1" && trim($fv_user_type) != "2" ): $arr_validation[] = 'Membership, invalid data.'; endif;

		if( trim($fv_subject) == "" ):
			$arr_validation[] = 'Email subject name is empty.';
		else:
			$fv_subject = sanitize_text_field($fv_subject);
		endif;

		if( trim($fv_msg) == "" ):
			$arr_validation[] = 'Email message name is empty.';
		else:
			//$fv_msg = sanitize_text_field($fv_msg);
		endif;

		if( trim($fv_active) == "" ):
			$arr_validation[] = 'Course active is empty.';
		else:
			$fv_active =  sanitize_text_field($fv_active);
		endif;

		


		if( count($arr_validation) < 1  and 1 == 1 ):
			$qry = "INSERT INTO `wpou_mscm_mgroup`(`title`, `description`, `absence_days`, `user_type`, `subject`, `msg`, `active`, `cdate`) 
					VALUES ('".$fv_title."', '".$fv_description."', '".$fv_absence_days."', '".$fv_user_type."', '".$fv_subject."', '".$fv_msg."', '".$fv_active."', '".date('Y-m-d')."')";

			//echo "qry: ".$qry."<br />";
			$rs = $wpdb->query($qry);
			
			if($rs and 1 == 1): 
				$arr_validation = array(); //echo "the course was stored<br />"; 
			else:
				$arr_validation[] = 'There was an error saving the email group, please try again.'; //echo "the course was not stored<br />"; 
			endif;
		endif;

		//$arr_validation[] = 'temp error message!!! ';

		return $arr_validation;
	}

	function fdq_ms_mgroup_edit_func($fv_title, $fv_description, $fv_absence_days, $fv_user_type, $fv_subject, $fv_msg, $fv_active, $cod01){
		global $wpdb;
		$arr_validation = array();

		if( trim($fv_title) == "" ):
			$arr_validation[] = 'Course name is empty.';
		else:
			$fv_title = sanitize_text_field($fv_title);
		endif;

		if( trim($fv_description) != "" ):
			$fv_description = sanitize_text_field($fv_description);
		endif;

		if( trim($fv_absence_days) == "" ):
			$arr_validation[] = 'Days of inactivity is empty.';
		else:
			if(trim($fv_absence_days) == "0"):
				$arr_validation[] = 'Days of inactivity needs to be greater than 0.';
			else:
				if( !isInteger($fv_absence_days) ):
					$arr_validation[] = 'Days of inactivity invalid data.';
				else:
					$fv_absence_days = sanitize_text_field($fv_absence_days);
				endif;
			endif;
		endif;

		//if( trim($fv_user_type) != "1" && trim($fv_user_type) != "2" ): $arr_validation[] = 'Membership, invalid data.'; endif;

		if( trim($fv_subject) == "" ):
			$arr_validation[] = 'Email subject name is empty.';
		else:
			$fv_subject = sanitize_text_field($fv_subject);
		endif;

		if( trim($fv_msg) == "" ):
			$arr_validation[] = 'Email message name is empty.';
		else:
			//$fv_msg = sanitize_text_field($fv_msg);
		endif;

		if( trim($fv_active) == "" ):
			$arr_validation[] = 'Course active is empty.';
		else:
			$fv_active =  sanitize_text_field($fv_active);
		endif;

		


		if( count($arr_validation) < 1  and 1 == 1 ):
			$qry = "UPDATE `wpou_mscm_mgroup` SET 
					`title`='".$fv_title."', 
					`description`='".$fv_description."', 
					`absence_days`='".$fv_absence_days."', 
					`user_type`='".$fv_user_type."', 
					`subject`='".$fv_subject."', 
					`msg`='".$fv_msg."', 
					`active`='".$fv_active."' 
					WHERE mgroup_id = '".$cod01."'"; 
			//echo "qry: ".$qry."<br />";
			$rs = $wpdb->query($qry);
			//echo "<pre>"; print_r($rs); echo "</pre>";
			
			if($rs !== false): 
				$arr_validation = array(); //echo "the course was stored<br />"; 
			else:
				$arr_validation[] = 'There was an error saving the email group, please try again.'; //echo "the course was not stored<br />"; 
			endif;
		endif;

		//$arr_validation[] = 'temp error message!!! ';

		return $arr_validation;
	}

	function fdq_ms_mgroup_del_func($cod01){
		global $wpdb;
		$arr_validation = array();

		$qry = "DELETE FROM `wpou_mscm_mgroup` WHERE mgroup_id = '".$cod01."'"; //echo "qry: ".$qry."<br />";
		$rs = $wpdb->query($qry);

		if($rs !== false): 
			$arr_validation = array(); //echo "the course was stored<br />"; 
		else:
			$arr_validation[] = 'There was an error deleting the email group, please try again.'; //echo "the course was not stored<br />"; 
		endif;

		return $arr_validation;
	}

	function fdq_ms_mgroup_change_status_func($cod01, $op){
		global $wpdb;
		$arr_validation = array();

		$act = 1;
		if( $op != '1'):
			$act = 0;
		endif;

		$qry = "UPDATE `wpou_mscm_mgroup` SET active = '".$act."' WHERE mgroup_id = '".$cod01."'"; //echo "qry: ".$qry."<br />";
		$rs = $wpdb->query($qry);  //echo "<pre>"; print_r($rs); echo "</pre>";
		if($rs !== false): 
			$arr_validation = array(); //echo "the course was stored<br />"; 
		else:
			$arr_validation[] = 'There was an error deactivating the email group, please try again.'; //echo "the course was not stored<br />"; 
		endif;

		return $arr_validation;
	}
?>