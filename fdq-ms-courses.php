<?php
/*
Plugin Name: FDQ Mindful Science Courses
Plugin URI: http://localhost.com/
Description: Manage the content of all the courses, free and paid
Version: 1.0
Author: Fabricio Daza
Author URI: http://localhost.com/
License: GPL2
*/


if( is_admin() ) {
	add_action( 'admin_enqueue_scripts',  'fdq_ms_courses_admin_scripts' );
	
	function fdq_ms_courses_admin_scripts(){
		wp_register_style( 'fdq-ms-courses-admin-style', plugins_url( '/css/fdq-ms-courses-admin-style.css', __FILE__ ), array(), null, 'all' );
		wp_enqueue_style ( 'fdq-ms-courses-admin-style' );

		wp_register_script( 'fdq-ms-courses-admin-script', plugins_url( '/js/fdq-ms-courses-admin-script.js', __FILE__ ), array(), null, true );
		wp_enqueue_script( 'fdq-ms-courses-admin-script' );

	}
}
else{
	add_action('wp_enqueue_scripts', 'fdq_ms_courses_scripts');
	
	function fdq_ms_courses_scripts(){
		wp_register_style( 'fdq-ms-courses-style', plugins_url( '/css/fdq-ms-courses-style.css?v=1', __FILE__ ), array(), null, 'all' );
		wp_enqueue_style ( 'fdq-ms-courses-style' );
		
		wp_register_style( 'fdq-ms-nottheskin', plugins_url( '/css/player/not.the.skin.css', __FILE__ ), array(), null, 'all' );
		wp_enqueue_style ( 'fdq-ms-nottheskin' );
		wp_register_style( 'fdq-ms-circleplayer', plugins_url( '/css/player/circle.player.css', __FILE__ ), array(), null, 'all' );
		wp_enqueue_style ( 'fdq-ms-circleplayer' );


		
		wp_register_script( 'fdq-ms-courses-script', plugins_url( '/js/fdq-ms-courses-script.js', __FILE__ ), array(), null, true );
		wp_localize_script( 'fdq-ms-courses-script', 'fdqmspost', array( 'wp_ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script(  'fdq-ms-courses-script' );

		wp_register_script('fdq-ms-fontawesome', 'https://use.fontawesome.com/a03f40fc97.js', false );
		wp_enqueue_script('fdq-ms-fontawesome');

		wp_register_script( 'fdq-ms-transform2d', plugins_url( '/js/player/jquery.transform2d.js', __FILE__ ), array(), null, true );
		wp_enqueue_script( 'fdq-ms-transform2d' );

		wp_register_script( 'fdq-ms-grab', plugins_url( '/js/player/jquery.grab.js', __FILE__ ), array(), null, true );
		wp_enqueue_script( 'fdq-ms-grab' );

		wp_register_script( 'fdq-ms-jplayer', plugins_url( '/js/player/jquery.jplayer.js?v=1', __FILE__ ), array(), null, true );
		wp_enqueue_script( 'fdq-ms-jplayer' );

		wp_register_script( 'fdq-ms-csstransforms', plugins_url( '/js/player/mod.csstransforms.min.js', __FILE__ ), array(), null, true );
		wp_enqueue_script( 'fdq-ms-csstransforms' );

		wp_register_script( 'fdq-ms-ircleplayer', plugins_url( '/js/player/circle.player.js', __FILE__ ), array(), null, true );
		wp_enqueue_script( 'fdq-ms-ircleplayer' );

	}
}

add_action('admin_menu', 'fdq_ms_courses');
function fdq_ms_courses(){
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null )
	add_menu_page('MS Courses', 'MS Courses', 'manage_options', 'fdq-ms-courses.php', 'fdq_ms_manage_courses', null, 99);
	add_submenu_page('fdq-ms-courses.php', 'MS Icons',       'Timeline Icons', 'manage_options', 'fdq-ms-course-icon.php', 'fdq_ms_manage_course_icon', null, 99);
	add_submenu_page('fdq-ms-courses.php', 'MS User Info',   'User Info',      'manage_options', 'fdq-ms-user-info.php',   'fdq_ms_manage_user_info',   null, 99);
	add_submenu_page('fdq-ms-courses.php', 'MS User Report', 'User Report',    'manage_options', 'fdq-ms-user-report.php', 'fdq_ms_manage_user_report', null, 99);
	add_submenu_page('fdq-ms-courses.php', 'Mail Group',     'Mail Group',     'manage_options', 'fdq-ms-mgroup.php',      'fdq_ms_manage_mgroup',      null. 99);
	add_submenu_page('fdq-ms-courses.php', 'Session Mail',   'Session Mail',   'manage_options', 'fdq-ms-sesmail.php',     'fdq_ms_manage_sesmail',     null. 99);
}

//--- INI :: UTILS -------------------------------------------------------------------------------------------------------------
	function setErrorMsg($arr_err){
		$err_list = "";
		if(count($arr_err) > 0 ):
			foreach($arr_err as $val):
				$err_list .= $val."<br />";
			endforeach;
		endif;

		return $err_list;
	}

	function isNumber($val){
		$res = true;
		if( !preg_match('/^[0-9]{0,15}$/', $val) ):
			$res = false;
		endif;
		return $res;
	}

	function isInteger($val){
		$res = true;
		if( !preg_match('/^[1-9][0-9]{0,15}$/', $val) ):
			$res = false;
		endif;
		return $res;
	}

	function isFloat($val){
		$res = true;
		if( !preg_match('/^[0-9.]{0,15}$/', $val) ):
			$res = false;
		endif;
		return $res;
	}

	function isAlphaNum($val){
		$res = true;
		//if( !preg_match('/^[a-zA-Z0-9 ]*$/', $val) ):
		if( !preg_match('/^[a-zA-Z0-9ÑñáéíóúÁÉÍÓÚ ]*$/', $val) ):
			$res = false;
		endif;
		return $res;
	}

	function isEmail($val){
		$res = true;
		if( !preg_match('/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/', $val) ):
			$res = false;
		endif;
		return $res;
	}

	function isPhone($val){
		$res = true;
		//if( !preg_match('/^((\+[1-9]{1,4}[ \-]*)|(\([0-9]{2,3}\)[ \-]*)|([0-9]{2,4})[ \-]*)*?[0-9]{3,4}?[ \-]*[0-9]{3,4}?$/', $val) ):
		if( !preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/', $val) ):
			$res = false;
		endif;
		return $res;
	}

	function isWebsite($val){
		$res = true;
		if ( !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$val) ):
			$res = false;
		endif;
		return $res;
	}

	function get_vars(){
		$vars = array();
		$vars['site_url'] = site_url()."/";
		$vars['img_dir'] = site_url()."/wp-content/uploads/ms-course-images/";
		$vars['plugin_dir'] = plugins_url()."/fdq-ms-courses/";
		$vars['plugins_dir_path'] = plugin_dir_path( __DIR__ );
		$vars['plugin_dir_path'] = plugin_dir_path( __FILE__ );
		$vars['plugins_dir_realpath'] = realpath(plugin_dir_path( __FILE__ ));

		return $vars;
	}

	function getTotalItems($sql, $curpage, $pgItems ){ 
		global $wpdb;
		//echo "sql: ".$sql."<br />";
		$n = $wpdb->get_var($sql);
		return $n; 
	}

	function getPaginationInfo($totalItems, $pg, $pgItems){
		
		//$pg=4;
		//$pgItems = 10;
		//$totalItems = 31;
		//echo "pg: ".$pg."<br />";
		//echo "pgItems: ".$pgItems."<br />";
		//echo "totalItems: ".$totalItems."<br />";
		$arr_pag = array();
		
		$num_pages = ceil( $totalItems / $pgItems ); //echo "num_pages: ".$num_pages."<br />";
		$lim01 = (($pg - 1) * $pgItems); //echo "lim01: ".$lim01."<br />";
		$lim02 = $pgItems; //echo "lim02: ".$lim02."<br />";
		$firstpg = 1; //echo "firstpg: ".$firstpg."<br />";
		$prevpg = ( $pg > 1 ) ? ($pg - 1 ) : 1 ; //echo "prevpg: ".$prevpg."<br />";
		$nextpg = ($pg == $num_pages ) ? ( $num_pages ) : ($pg + 1 ) ; //echo "nextpg: ".$nextpg."<br />";
		$lastpg = $num_pages; //echo "lastpg: ".$lastpg."<br />";
		
		$arr_pag['lim01'] = $lim01;
		$arr_pag['lim02'] = $lim02;
		$arr_pag['num_pages'] = $num_pages;
		$arr_pag['firstpg'] = $firstpg;
		$arr_pag['prevpg'] = $prevpg;
		$arr_pag['curpg'] = $pg;
		$arr_pag['nextpg'] = $nextpg;
		$arr_pag['lastpg'] = $lastpg;

		return $arr_pag;
	}
	
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
//--- END :: UTILS -------------------------------------------------------------------------------------------------------------

//--- INI :: Email system management -------------------------------------------------------------------------------------------
	require_once('mgroup/fdq-ms-mgroup-func.php');
	function fdq_ms_manage_mgroup(){
		if( isset($_REQUEST['action']) and trim($_REQUEST['action']) != "" ):
			$action = $_REQUEST['action'];
		else:
			$action = 'showMgroupList';
		endif;
		//--- INI :: manage page to load --------------------------------------------------
			if( $action == 'showMgroupAdd' ):
				require_once('mgroup/fdq-ms-mgroup-add.php');
			elseif( $action == 'showMgroupEdit' ):
				require_once('mgroup/fdq-ms-mgroup-edit.php');
			elseif( $action == 'showMgroupList' ):
				require_once('mgroup/fdq-ms-mgroup-list.php');
			endif;
		//--- END :: manage page to load --------------------------------------------------
	}
//--- END :: Email system management -------------------------------------------------------------------------------------------

//--- INI :: Session email -----------------------------------------------------------------------------------------------------
	require_once('sesmail/fdq-ms-sesmail-func.php');
	function fdq_ms_manage_sesmail(){
		if( isset($_REQUEST['action']) and trim($_REQUEST['action']) != "" ):
			$action = $_REQUEST['action'];
		else:
			$action = 'showsesmailList';
		endif;
		//--- INI :: manage page to load --------------------------------------------------
			if( $action == 'showsesmailAdd' ):
				require_once('sesmail/fdq-ms-sesmail-add.php');
			elseif( $action == 'showsesmailEdit' ):
				require_once('sesmail/fdq-ms-sesmail-edit.php');
			elseif( $action == 'showsesmailList' ):
				require_once('sesmail/fdq-ms-sesmail-list.php');
			endif;
		//--- END :: manage page to load --------------------------------------------------
	}
//--- END :: Session email -----------------------------------------------------------------------------------------------------

//--- INI :: User report management --------------------------------------------------------------------------------------------
	function fdq_ms_get_course_name($course_id){
		global $wpdb;
		$qry = "SELECT `title` FROM wpou_mscm_course WHERE course_id = '{$course_id}'";
		$courseName = $wpdb->get_var($qry);
		return $courseName;
	}

	function fdq_ms_get_session_name($content_id){
		global $wpdb;
		$ses_arr = array();
		
		$qry = "SELECT * FROM `wpou_mscm_content` WHERE `content_id` = '{$content_id}'";
		$res = $wpdb->get_results($qry, ARRAY_A);
		$ses_arr['ses_num']  = $res[0]['day_number'];
		$ses_arr['ses_name'] = "Session ".$res[0]['day_number']." - ".$res[0]['title'];
	
		return $ses_arr;
	}

	function fdq_ms_get_session_total($course_id){
		global $wpdb;
		$qry = "SELECT count(*) FROM wpou_mscm_content WHERE course_pid = '{$course_id}'"; //echo "qry: ".$qry."<br />";
		$sesNum = $wpdb->get_var($qry);
		return $sesNum;	
	}

	function fdq_ms_manage_user_report(){
		if( isset($_REQUEST['action']) and trim($_REQUEST['action']) != "" ):
			$action = $_REQUEST['action'];
		else:
			$action = 'showUserReportList';
		endif;
		//echo "action: ".$action."<br />";

		//--- INI :: manage page to load --------------------------------------------------
		if( $action == 'showUserReportList' ):
			require_once('report/fdq-ms-user-report-list.php');
		endif;
		//--- END :: manage page to load --------------------------------------------------
	}
//--- END :: User report management --------------------------------------------------------------------------------------------

//--- INI :: User info management ----------------------------------------------------------------------------------------------
	//require_once('report/fdq-ms-user-info-func.php');
	function fdq_ms_user_info_list_func(){ }
	function fdq_ms_user_info_detail_func(){ }

	function fdq_ms_manage_user_info(){
		if( isset($_REQUEST['action']) and trim($_REQUEST['action']) != "" ):
			$action = $_REQUEST['action'];
		else:
			$action = 'showUserInfoList';
		endif;
		//echo "action: ".$action."<br />";

		//--- INI :: manage page to load --------------------------------------------------
		if( $action == 'showUserInfoDetail' ):
			require_once('report/fdq-ms-user-info-detail.php');
		elseif( $action == 'showUserInfoList' ):
			require_once('report/fdq-ms-user-info-list.php');
		endif;
		//--- END :: manage page to load --------------------------------------------------
	}
//--- END :: User info management ----------------------------------------------------------------------------------------------

//--- INI :: Course management -------------------------------------------------------------------------------------------------
	require_once('courses/fdq-ms-courses-func.php');

	function fdq_ms_manage_courses(){

		if( isset($_REQUEST['action']) and trim($_REQUEST['action']) != "" ):
			$action = $_REQUEST['action'];
		else:
			$action = 'showCourseList';
		endif;
		//echo "action: ".$action."<br />";

		//--- INI :: manage page to load --------------------------------------------------
		if( $action == 'showCourseAdd' ):
			require_once('courses/fdq-ms-courses-add.php');
		elseif( $action == 'showCourseEdit' ):
			require_once('courses/fdq-ms-courses-edit.php');
		elseif( $action == 'showCourseDel' ):
			require_once('courses/fdq-ms-courses-del.php');
		elseif( $action == 'showCourseList' ):
			require_once('courses/fdq-ms-courses-list.php');
		elseif( $action == 'showContentAdd' ):
			require_once('courses/fdq-ms-content-add.php');
		elseif( $action == 'showContentEdit' ):
			require_once('courses/fdq-ms-content-edit.php');
		elseif( $action == 'showContentDel' ):
			require_once('courses/fdq-ms-content-del.php');
		elseif( $action == 'showContentList' ):
			require_once('courses/fdq-ms-content-list.php');
		endif;
		//--- END :: manage page to load --------------------------------------------------

	}
//--- END :: Course management -------------------------------------------------------------------------------------------------

//--- INI :: Course Icon management --------------------------------------------------------------------------------------------
	require_once('course-icon/fdq-ms-course-icon-func.php');
	function fdq_ms_manage_course_icon(){
		if( isset($_REQUEST['action']) and trim($_REQUEST['action']) != "" ):
			$action = $_REQUEST['action'];
		else:
			$action = 'showCourseIconList';
		endif;
		//echo "action: ".$action."<br />";

		//--- INI :: manage page to load --------------------------------------------------
		if( $action == 'showCourseIconAdd' ):
			require_once('course-icon/fdq-ms-course-icon-add.php');
		elseif( $action == 'showCourseIconEdit' ):
			require_once('course-icon/fdq-ms-course-icon-edit.php');
		elseif( $action == 'showCourseIconDel' ):
			require_once('course-icon/fdq-ms-course-icon-del.php');
		elseif( $action == 'showCourseIconList' ):
			require_once('course-icon/fdq-ms-course-icon-list.php');
		endif;
		//--- END :: manage page to load --------------------------------------------------
	}
//--- END :: Course Icon management --------------------------------------------------------------------------------------------

//--- INI :: AJAX FUNCTION[not-used] -------------------------------------------------------------------------------------------
	add_action( 'wp_ajax_nopriv_fdq_ms_apicall_func', 'fdq_ms_apicall_func' );
	add_action( 'wp_ajax_fdq_ms_apicall_func', 'fdq_ms_apicall_func' );

	function fdq_ms_apicall_func() {
	  //$fname = $_REQUEST[‘fname’];
	  //$lname = $_REQUEST[‘lname’];
	  //$arr = array('firstname' => $fname, 'lastname' => $lname);
	  
	  echo '{"firstname":"Fabricio","lastname":"Daza"}';
	  //echo json_encode($arr); 
	  

	  //die(); prevents that WP add a 0 at the end of the response
	  // send this       '{"firstname":"Fabricio","lastname":"daza"}'
	  // instead of this '{"firstname":"Fabricio","lastname":"daza"}0'
	  die(); 
	}
//--- INI :: AJAX FUNCTION[not-used] -------------------------------------------------------------------------------------------

//--- INI :: AJAX FUNCTION -----------------------------------------------------------------------------------------------------
	add_action( 'wp_ajax_nopriv_fdq_ms_apicall_func_nextstep', 'fdq_ms_apicall_func_nextstep' );
	add_action( 'wp_ajax_fdq_ms_apicall_func_nextstep', 'fdq_ms_apicall_func_nextstep' );

	function fdq_ms_apicall_func_nextstep() {
		$id = $_REQUEST['id'];
		$step = $_REQUEST['step'];
		global $wpdb;
		$filaAss = $wpdb->get_results( "SELECT * FROM wpou_mscm_assignment WHERE assignment_id =".$id);
		$course_pid=  $filaAss[0]->course_pid;
		$resultado = $wpdb->get_results("SELECT MAX( day_number ) AS laststep FROM wpou_mscm_content WHERE course_pid =".$course_pid);
		$nextstep= $resultado[0]->laststep;
		
		//--- call the function that will sent the mail --------------------
			$sendEmail = true;
			$gratisCourse = 2;
			if( $sendEmail && $course_pid == $gratisCourse && 1 == 1 ){ //this will prevent the email from being sent. 
				$assignment_id = $id;
				$course_ses = $step;
				$user_id = $filaAss[0]->user_pid;
				$course_id = $course_pid;
				$res = sendSessionMail($user_id, $assignment_id, $course_ses, $course_id);
			}
		
		if($step==$nextstep){ 
			$nextstep=0;
			$table='wpou_mscm_assignment';
			$wpdb->update($table, array('finished'=>1), array('assignment_id'=>$id));
		}
		else{
			$nextstep=$step+1;
			$table='wpou_mscm_assignment';
			$wpdb->update($table, array('advanced'=>$nextstep), array('assignment_id'=>$id));
		}
		$arr = array('id' => $id, 'step' => $nextstep);
		echo json_encode($arr); 
		die(); 
	}

	function sendMandrillMail($from_email, $from_name, $recipients, $subject, $msg){
		//$req = "/var/www/mindfulscience.dev/public_html/wp-content/plugins/fdq-ms-courses/mandrill-api-php/src/Mandrill.php";
		$ser = $_SERVER['DOCUMENT_ROOT'];
		$req = $ser."/wp-content/plugins/fdq-ms-courses/mandrill-api-php/src/Mandrill.php";
		//$req = plugins_url()."/fdq-ms-courses/mandrill-api-php/src/Mandrill.php";	

		require_once($req);

		try {
			//$mandrill = new Mandrill('YOUR_API_KEY');
			//$mandrill = new Mandrill('npVTzz4r5JQq498JQHZJbw'); //TEST API KEY
			$mandrill = new Mandrill('oMirgJzAxH26GPNj8oU7Mg'); //API KEY
			$message = array(
				'html' => $msg, //'<p>Example HTML content</p>',
				'text' => 'Example text content',
				'subject' => $subject, //'example subject',
				'from_email' => $from_email, //'message.from_email@example.com',
				'from_name' => $from_name, //'Example Name',
				//'to' => array( array( 'email' => $to_mail, 'name' => $fullname, 'type' => 'to' )),
				'to' => $recipients,
				'headers' => array('Reply-To' => 'message.reply@example.com'),
				'important' => false,
				'track_opens' => null,
				'track_clicks' => null,
				'auto_text' => null,
				'auto_html' => null,
				'inline_css' => null,
				'url_strip_qs' => null,
				'preserve_recipients' => false,
				'view_content_link' => null,
				'bcc_address' => 'message.bcc_address@example.com',
				'tracking_domain' => null,
				'signing_domain' => null,
				'return_path_domain' => null,
				'merge' => true,
				'merge_language' => 'mailchimp',
				'global_merge_vars' => array(
					array(
						'name' => 'merge1',
						'content' => 'merge1 content'
					)
				),
				'merge_vars' => array(
					array(
						'rcpt' => 'recipient.email@example.com',
						'vars' => array(
							array(
								'name' => 'merge2',
								'content' => 'merge2 content'
							)
						)
					)
				)
			);
			$async = false;
			$ip_pool = 'Main Pool';
			$send_at = 'example send_at';
			//$result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
			$result = $mandrill->messages->send($message, $async, $ip_pool);
			//echo "Result: <pre>"; print_r($result); echo "</pre>";
		}
		catch(Mandrill_Error $e){
			// Mandrill errors are thrown as exceptions
			//echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
	}

	function sendSessionMail($user_id, $assignment_id, $course_ses, $course_id){
		global $wpdb;
		//--- get course name -----------------------------------------------------------
			$qry = "SELECT title FROM wpou_mscm_course WHERE course_id ='".$course_id."'"; //echo "qry: ".$qry."<br />";
			$course_name = $wpdb->get_var($qry);

		//--- get user information (name, lastname, email)-------------------------------
			$_usr = new WP_User($user_id);
			//echo "<pre>"; print_r($_usr); echo "</pre>";
			$userEmail = $_usr->user_email;
			$userFname = $_usr->first_name;
			$userLname = $_usr->last_name;
			if( trim($userFname) != "" ){
				$userFullname = $userFname." ".$userLname;
			}
			else{
				$userFullname = $_usr->user_login;
			}
		
		//--- get Mail subject and msg information ---------------------------------------
			$qry = "SELECT * FROM wpou_mscm_sesmail where session_day = '".$course_ses."'";
			$rs = $wpdb->get_results($qry, ARRAY_A);
			$subject = $rs[0]['subject'];
			$msg = $rs[0]['msg'];

		$subject = str_replace("[fullname]", $userFullname, $subject);
		$subject = str_replace("[sesNumber]", $course_ses, $subject);
		$msg = str_replace("[fullname]", $userFullname, $msg);
		$msg = str_replace("[sesNumber]", $course_ses, $msg);
		$msg = str_replace("[nextSession]", ($course_ses+1), $msg);

		
		//--- if count wpou_mscm_sesmailctrl => 500 then clear the table
		$ctrlLimit = 500;
		$addT = 12; //60; // [ 5 min ==>  60*5=300 ] [ 10 min ==>  60*10=600 ]

		$confdate = new DateTime('America/New_York');
		$curDate = $confdate->format('Y-m-d H:i:s'); //date('Y-m-d H:i:s');
		$d = $curDate;
		
		$sentDate = $wpdb->get_var("SELECT cdate FROM `wpou_mscm_sesmailctrl` WHERE `assignment_pid`='".$assignment_id."' ORDER BY sesmailctrl_id DESC LIMIT 1");
		
		$sentDate1 = strtotime($sentDate);
		$curDate1 = strtotime($curDate);
		$nextDate = $sentDate1 + $addT;
		
		//echo "[ sentDate = ".$sentDate." ] [ curDate = ".$curDate." ] [ nextDate = ".date("Y-m-d H:i:s", $nextDate)." ]<br />";
		//echo "[ sentDate1 = ".$sentDate1." ] [ curDate1 = ".$curDate1." ] [ nextDate = ".$nextDate." ]<br />";

		$sendnewMail = false;
		if( $curDate1 >= $nextDate ){ 
			$sendnewMail = true;
			//echo "is OK to send<br />";
		}
		//else{ echo "is not OK to send<br />"; }

		if( $sendnewMail ){

			$ctrlCount = $wpdb->get_var("SELECT count(*) FROM `wpou_mscm_sesmailctrl`");
			//echo "ctrlCount: ".$ctrlCount."<br />";
			//--- this snippet is to clear the table in case the table has more than 500 records because I do not want this table to be big
			if( $ctrlCount >= $ctrlLimit ){
				$qry = "DELETE FROM `wpou_mscm_sesmailctrl` WHERE `sesmailctrl_id` > 0";
				$rs1 = $wpdb->query($qry);
				$qry = "ALTER TABLE `wpou_mscm_sesmailctrl` AUTO_INCREMENT = 1;";
				$rs2 = $wpdb->query($qry);
				//echo "Table cleaned <br />";
			}

			$qry = "INSERT INTO `wpou_mscm_sesmailctrl`(   `user_pid`, `assignment_pid`,     `course_pid`,     `course_session`,  `user_email`,     `user_name`,      `sent`, `cdate`) 
					VALUES                             ('".$user_id."', '".$assignment_id."', '".$course_id."', '".$course_ses."', '".$userEmail."', '".$userFullname."', '1', '".$d."')";
			$rs = $wpdb->query($qry, ARRAY_A);
			//echo "MAIL SENT<br />";

			//--- INI :: Send emails via mandrill ---------------------------------------------------------------
				$mandrillOK = true;
				if( $mandrillOK ){
					
					$from_name = "Mindful Science";
					$from_email = "no-reply@mindfulscience.es";
					//$subject = 
					//$msg
					
					$recipients[] = array(
									'email' => $userEmail,
									'name' => $userFullname,
									'type' => 'to'
								);

					//--- this line was commented becasue we only want the email to be sent when the user has completed the whole course.
					//sendMandrillMail($from_email, $from_name, $recipients, $subject, $msg);

					if( $course_ses == 8 ){ 

						//sendMandrillMail($from_email, $from_name, $recipients, $subject, $msg);  //this will prevent email from being sent to the user.

						$subject = 'El usuario '.$userFullname.' ha completado el Curso Gratis';
						$msg = '';
						$msg .= '<p>El usuario '.$userFullname.' ha completado el Curso Gratis</p>';
						$msg .= '<p>Email del usuario: '.$userEmail.'</p>';
						$msg .= '<p>El equipo de Mindful Science</p>';

						$recipients[] = array(
									'email' => 'ayuda@mindfulscience.es',
									'name' => 'Ayuda',
									'type' => 'to'
								);
						sendMandrillMail($from_email, $from_name, $recipients, $subject, $msg);
					}

				}// endif
			//--- END :: Send emails via mandrill ---------------------------------------------------------------
		}


		if( 1 == 2 ):
			$html = '';
			$html .= '<div style="width:100%;box-sizing:border-box;padding:15px;background-color:#ffc;">';
			$html .= 'ctrlCount='.$ctrlCount."<br />";
			$html .= 'user_id='.$user_id."<br />";
			$html .= 'userName='.$userFullname."<br />";
			$html .= 'userEmail='.$userEmail."<br />";
			$html .= 'assignment_id='.$assignment_id."<br />";
			$html .= 'course_id='.$course_id."<br />";
			$html .= 'courseName='.$course_name."<br />";
			$html .= 'course_ses='.$course_ses."<br />";
			$html .= 'subject='.$subject."<br />";
			$html .= 'msg='.$msg."<br />";
			//$html .= 'req='.$req."<br />";
			//$html .= 'ser='.$ser."<br />";
			//$html .= 'currentUser: '.$curUser->ID."<br />";
			//$html .= 'First name: '.$curUser->first_name;
			$html .= '</div>';
			return $html;
		else:
			return true;
		endif;
	}
//--- END :: AJAX FUNCTION -----------------------------------------------------------------------------------------------------

//--- INI :: API CALL FUNCTION  ------------------------------------------------------------------------------------------------
	function fdq_ms_call_aws_api($epoint, $data_arr){
		if( count($data_arr) > 0 ):
			
			if ( $_SERVER['SERVER_NAME'] == 'www.mindfulscience.loc' and 1 == 2 ):
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

			//$ch = curl_init("http://www.apiserver2.loc/ws/curlgetreq/fdaza/123456");
			$ch = curl_init($endpoint);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			//curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			$response = curl_exec($ch);
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
//--- END :: API CALL FUNCTION  ------------------------------------------------------------------------------------------------

//--- INI :: ADDING CODE TO THE FOOTER -----------------------------------------------------------------------------------------
	add_action('wp_footer', 'fdq_ms_place_popup_in_footer');
	function fdq_ms_place_popup_in_footer() {
		ob_start();
		include('fdq-ms-footer-popup.php');
		$html = ob_get_contents();
		ob_end_clean();
		//$html = '<div style="float:left;width:100%;padding:5px;background-color:#ffc; color:red;">this is a test</div>';
		echo $html;
	}
//--- INI :: ADDING CODE TO THE FOOTER -----------------------------------------------------------------------------------------


//--- INI :: Shortcodes --------------------------------------------------------------------------------------------------------
	function fdq_ms_get_user_id(){
		$user_id = 0;	
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
		}
		return $user_id;
	}

	function fdq_ms_get_free_course_id(){
		global $wpdb;
		$course_id = 0;
		$qry = "SELECT * FROM `wpou_mscm_course` WHERE active = '1' AND paid = '0' ORDER BY course_id";
		$rs = $wpdb->get_results($qry, ARRAY_A);
		if( count($rs) > 0 ){
			foreach($rs as $val):
				$course_id	= $val['course_id'];			
			endforeach;
		}

		return $course_id;
	}

	function fdq_ms_get_paid_course_id($ccode){
		global $wpdb;
		$course_id = 0;
		$qry = "SELECT * FROM `wpou_mscm_course` WHERE active = '1' AND paid = '1' AND `ccode` = '".$ccode."' ORDER BY course_id";
		$rs = $wpdb->get_results($qry, ARRAY_A);
		if( count($rs) > 0 ){
			foreach($rs as $val):
				$course_id	= $val['course_id'];			
			endforeach;
		}

		return $course_id;
	}

	function fdq_ms_check_assignment($_userId, $_courseId){
		global $wpdb;
		$qry01 = "SELECT * FROM `wpou_mscm_assignment` WHERE `user_pid`='".$_userId."' AND `course_pid`='".$_courseId."'" ;
		$rs01 = $wpdb->get_results( $qry01, ARRAY_A );
		if( count($rs01) <  1 ):
			$_date1 = date('Y-m-d H:i:s');
			$_date2 = date('Y-m-d');
			$qry02 = "INSERT INTO `wpou_mscm_assignment`(`user_pid`, `course_pid`, `last_visit`, `cdate`, `advanced`, `finished`) 
					  VALUES ('".$_userId."', '".$_courseId."','".$_date1."', '".$_date1."','1','0') ";
			$rs02 = $wpdb->query($qry02);
		else:
			$assignment_id = 0;
			foreach($rs01 as $val):
				$assignment_id = $val['assignment_id'];
			endforeach;
			$_date1 = date('Y-m-d H:i:s');
			$qry02 = "UPDATE `wpou_mscm_assignment` SET `last_visit`='".$_date1."' WHERE `assignment_id`='".$assignment_id."'";
			$rs02 = $wpdb->query($qry02);
		endif;
	}

	add_shortcode('fdq-ms-course-content-show', 'fdq_ms_course_content_display_func');
	function fdq_ms_course_content_display_func($atts){
		/*
		* how to call this shortcode
		* [fdq-ms-course-content-show course_type="free"] ===> free course
		* [fdq-ms-course-content-show course_type="paid" ccode="03052017"] ==> show the course with the code 03052017
		* [fdq-ms-course-content-show course_type="paid"] ==> show a list of courses where the user can pick
		* -----------------------------------------------------------------------------------------------------------
		* ADDING THE ICON WIDTH CONTROL via shorcode parameter "iconwidth" (in pixels), if no parameter iconwidth=40px
		* [fdq-ms-course-content-show course_type="free" iconwidth="115"] ==> iconwidth is 115px height is auto
		* [fdq-ms-course-content-show course_type="paid" ccode="03052017" iconwidth="115"]
		*/
		$_attr = shortcode_atts( 
					array(
						'course_type' => 'free',
						'ccode' => '0',
						'iconwidth' => '40'
						), 
					$atts 
				);
		$_iconwidth = $_attr['iconwidth'];
		$_backBtn = 0;
		$_userId = fdq_ms_get_user_id();
		//$_userId = 0;
		if( $_userId > 0 ):
			
			if( strtolower($_attr['course_type']) == 'paid' ):
				//--- paid courses -----------------------

				if( $_attr['ccode'] == '0' ):
					$_courseId = ( isset( $_REQUEST['cid'] ) && trim( $_REQUEST['cid'] ) != "" ) ? $_REQUEST['cid'] : 0 ;
				else:
					$_courseId = fdq_ms_get_paid_course_id($_attr['ccode']);
				endif;
				
				
				//--- check if this user has this course already register in table assignment and create it or update the last visit date ----
				if( $_courseId != 0 ):
					fdq_ms_check_assignment($_userId, $_courseId);
				endif;

				if( $_attr['ccode'] == '0' && $_courseId == 0 ): //--- no ccode received via shortcode
					//--- Shows course list ------------------
					ob_start();
					include('template/fdq-ms-paid-course-list.php');
					$html = ob_get_contents();
					ob_end_clean();
				elseif( $_attr['ccode'] != '0' && $_courseId == 0 ): //--- ccode received via shortcode but course not found
					
					//--- show error message
					$html = "";
					$html .= '<div class="fdq-ms-backBtnWrapper" style="margin:0;">';
					$html .= '<p style="text-align:center; margin:0 0 30px 0;">No se ha podido encontrar el curso, si el error persiste, contacta al webmaster.</p>';
					//$html .= '<a class="fdq-ms-btnBack" href="'.site_url()."/registro".'">Registrate Gratis</a>';
					$html .= '</div>';
				else:
					//--- Shows timeline for paid course -----
					if( $_attr['ccode'] == '0' ):
						$_backBtn = 1;
					else:
						$_backBtn = 0;
					endif;
					ob_start();
					include('template/fdq-ms-free-course.php');
					$html = ob_get_contents();
					ob_end_clean();
					//$html = "[ userId=".$_userId."] [courseId=".$_courseId."]<br />";
				endif;
				
			else:
				//--- free courses -----------------------

				//--- get the course ID ------------------
				$_courseId = fdq_ms_get_free_course_id();

				//--- check if this user has this course already register in table assignment and create it or update the last visit date ----
				//echo "[userID = ".$_userId."] [courseID = ".$_courseId."]<br />";
				fdq_ms_check_assignment($_userId, $_courseId);

				//$_courseId = 0;
				if( $_courseId > 0 and 1 == 1 ):
					ob_start();
					include('template/fdq-ms-free-course.php');
					$html = ob_get_contents();
					ob_end_clean();
				else:
					//$html = "[ userId=".$_userId."] [courseId=".$_courseId."]<br />";
					$html = "";
					$html .= "Ha sucedido un error al acceder al curso, por favor trata nuevamente, y si el error persiste contacta al administrador, Gracias.";
				endif;
			endif;
		else:
			$html = "";
			$html .= '<div class="fdq-ms-backBtnWrapper" style="margin:0;">';
			$html .= '<p style="text-align:center; margin:0 0 30px 0;">tienes que registrarte para poder acceder a este contenido.</p>';
			$html .= '<a class="fdq-ms-btnBack" href="'.site_url()."/registro".'">Registrate Gratis</a>';
			$html .= '</div>';
		endif;
		return $html;
	}

	if( 1 == 2 ){
		//use this url: http://msesnew.staging.wpengine.com/0-test/?uid=7947&cid=2&aid=2824&ses=1

		add_shortcode('fdq-ms-mandril-mail', 'fdq_ms_mandrill_mail_func');
		function fdq_ms_mandrill_mail_func(){
			$uid = ( isset($_GET['uid']) ) ? $_GET['uid'] : '1' ;
			$aid = ( isset($_GET['aid']) ) ? $_GET['aid'] : '1' ;
			$ses = ( isset($_GET['ses']) ) ? $_GET['ses'] : '1' ;
			$cid = ( isset($_GET['cid']) ) ? $_GET['cid'] : '2' ;
			$html = sendSessionMail($uid, $aid, $ses, $cid);
			echo $html;
		}
	}
//--- END :: Shortcodes --------------------------------------------------------------------------------------------------------

?>