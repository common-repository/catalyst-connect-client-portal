<?php
/*
    Created By : Mahidul Islam Tamim
*/
unset($_SESSION["ccgcp_login_error"]); 
global $wpdb;
global $wp;
$cppageUrl =  home_url( $wp->request );

$page_id = "";
if(isset($_GET['page_id'])){
    $id = $_GET['page_id'];
    $page_id = "&page_id=".$id;
}


$date_format = get_option('date_format');
$time_format = get_option('time_format');
if(get_option('timezone_string') !="") date_default_timezone_set(get_option('timezone_string'));




$pluginUrl  = CCGP_PLUGIN_URL;

$SttClass = new CCGP_SettingsClass();
$CPNzoho  = new ZohoCrmRequest();  

$logedin = false;
if(isset($_SESSION["ccgcp_loged"]['id'])) {
    $logedin = true;
    $accId = $_SESSION["ccgcp_loged"]['Account_Name']['id'];
}


if(isset($_GET['ppage']) && $_GET['ppage'] == 'sign-up'){

    if($logedin)header("Location: ".$cppageUrl."?ppage=dashboard".$page_id);
    else include_once 'login/sign_up.php';

}elseif(isset($_GET['ppage'],$_GET['key']) && $_GET['ppage'] == 'resetpassword'){

    if($logedin)header("Location: ".$cppageUrl."?ppage=dashboard".$page_id);
    else include_once 'login/resetpassword.php';

}elseif(isset($_GET['ppage']) && $_GET['ppage'] == 'logout'){

    unset($_SESSION["ccgcp_loged"]);  

    $current_user = wp_get_current_user();
    if(isset($current_user->data->user_email)){
        if(isset($current_user->roles[0]) && $current_user->roles[0] == 'client'){
            $_SESSION["ccgcpp_logout"] = "By User";
        }
    }

    header("Location: ".$cppageUrl."?ppage=login".$page_id);

}elseif(isset($_GET['ppage']) && $_GET['ppage'] == 'login'){

        if(isset($_POST['username'])){
            if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'atmpt-login')){
                $usern = trim(sanitize_text_field($_POST['username']));
                $pass = sanitize_text_field($_POST['password']);
                $npass = md5($pass);

                // AND email_verified = 1

                $contacts = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE status = 'active' AND username = '$usern' AND (password = '$pass' OR password = '$npass')");

                if(isset($contacts->crmid)){
                    $conId = $contacts->crmid;
                    $module = $CPNzoho->getRecordsByIdN($conId, 'Contacts', 'user', 'yes');

                    if(isset($module['status']) && ($module['status'] =='failed')){
                        $_SESSION["ccgcp_login_error"] = "Please contact your administrator";
                    }else{
                        if( isset($module['data'][0]['Email']) && count($module['data']) > 0 ){

                            $conDtl = $module['data'][0];
                            // Data save in Session
                            unset($_SESSION["ccgcpp_logout"]);
                            $_SESSION["ccgcp_loged"] = $conDtl;
                            
                            if(isset($_SESSION["ccgcp_loged"]['Account_Name']['id'])){

                                $accId   = $_SESSION["ccgcp_loged"]['Account_Name']['id'];
                                $pro_pic = $CPNzoho->savePrfilePhotoN($accId);

                                // Login to WP
                                $creds = array( 'user_login' => $usern, 'user_password' => $pass, 'remember' => true );                     
                                $user = wp_signon( $creds, false );                        
                                if(isset($user->ID)){
                                    wp_set_current_user($user->ID);
                                    wp_set_auth_cookie( $user->ID);
                                }

                                $accDtl = $CPNzoho->getRecordsByIdN($accId, 'Accounts', 'user', 'yes');



                                if(isset($accDtl['data'][0]['Owner']['id'])){

                                    $_SESSION["ccgcp_loged"]['accDtl'] = $accDtl['data'][0];

                                    $Ownerid = $accDtl['data'][0]['Owner']['id'];
                                    $Owner   = $CPNzoho->getUserByIdN($Ownerid);
                                    $Owner   = json_decode( $Owner, true );

                                    if(isset($Owner['users']) && is_array($Owner['users'][0])){
                                        // $_SESSION["ccgcp_loged"]['AccMngr'] = $Owner['users'][0];
                                        $conDtlArr = array();
                                        $AccMngr = $Owner['users'][0];
                                        $conDtlArr['first_name'] = $AccMngr['first_name'];
                                        $conDtlArr['last_name'] = $AccMngr['last_name'];
                                        $conDtlArr['full_name'] = $AccMngr['full_name'];
                                        $conDtlArr['phone'] = $AccMngr['phone'];
                                        $conDtlArr['email'] = $AccMngr['email'];
                                        $_SESSION["ccgcp_loged"]['AccMngr'] = $conDtlArr;
                                    }
                                }
                                header("Location: ".esc_url($cppageUrl)."?ppage=dashboard".esc_attr($page_id));

                            }else{
                                $_SESSION["ccgcp_login_error"] = "Please contact your administrator";
                            }
                        }else $_SESSION["ccgcp_login_error"] = "Please contact your administrator";//"User delete from CRM";
                    }
                    
                }else $_SESSION["ccgcp_login_error"] = "Invalid User Name / Password";

            }else{
                $_SESSION["ccgcp_login_error"] = "Something went wrong";
            }
        }

        if($logedin)header("Location: ".esc_url($cppageUrl)."?ppage=dashboard".esc_attr($page_id));
        else include_once 'login/home.php';

    }elseif(isset($_GET['ppage']) && $_GET['ppage'] == 'dashboard'){

        if($logedin)include_once 'dashboard.php';
        else header("Location: ".esc_url($cppageUrl)."?ppage=login".esc_attr($page_id));

    }
    elseif(isset($_GET['ppage']) && $_GET['ppage'] == 'profile'){

        if($logedin)include_once 'profile.php';
        else header("Location: ".esc_url($cppageUrl)."?ppage=login".esc_attr($page_id));

    }elseif(isset($_GET['ppage']) && $_GET['ppage'] == 'profile-edit'){

        if($logedin)include_once 'profile-edit.php';
        else header("Location: ".esc_url($cppageUrl)."?ppage=login".esc_attr($page_id));

    }
    elseif(isset($_GET['ppage'],$_GET['origin']) && ($_GET['ppage'] != '' && $_GET['origin'] == 'CRM')){

        if($logedin){
            include_once 'crm_module.php';
        }
        else{
            header("Location: ".esc_url($cppageUrl)."?ppage=login".esc_attr($page_id));
        }
    }
    elseif(isset($_GET['ppage'],$_GET['id'],$_GET['module'],$_GET['attid']) && $_GET['ppage'] == 'downloadatt' && $_GET['id'] != '' && $_GET['attid'] != ''){
        ob_get_clean(); //this is important for this action

        $file_n = $_GET['fn'];
        $target_dir = dirname(__FILE__).'/attachments/'.$_GET['id'].'/'.$_GET['attid'];

        $imageName = $target_dir.'/'.$file_n;

        if (!file_exists($imageName)) {       

            $file = $CPNzoho->downloadFileN($_GET['module'],$_GET['id'],$_GET['attid'], 'user', 'yes');

            if (!file_exists($target_dir))  mkdir($target_dir, 0777, true);
            if($file!='')file_put_contents($imageName, $file);
        }


        $file = $imageName;
        $size = filesize($file);

        @ob_end_clean(); //turn off output buffering to decrease cpu usage

        // required for IE, otherwise Content-Disposition may be ignored
        if(ini_get('zlib.output_compression'))
        ini_set('zlib.output_compression', 'Off');

        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        /* The three lines below basically make the 
        download non-cacheable */
        header("Cache-control: no-cache, pre-check=0, post-check=0");
        header("Cache-control: private");
        header('Pragma: private');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        // multipart-download and download resuming support
        if(isset($_SERVER['HTTP_RANGE']))
        {
            list($a, $range) = explode("=",$_SERVER['HTTP_RANGE'],2);
            list($range) = explode(",",$range,2);
            list($range, $range_end) = explode("-", $range);
            $range=intval($range);
            if(!$range_end) {
                $range_end=$size-1;
            } else {
                $range_end=intval($range_end);
            }

            $new_length = $range_end-$range+1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length=$size;
            header("Content-Length: ".$size);
        }

        /* output the file itself */
        $chunksize = 30*(1024*1024); //you may want to change this
        $bytes_send = 0;
        if ($file = fopen($file, 'rb'))
        {
            if(isset($_SERVER['HTTP_RANGE']))
            fseek($file, $range);

            while
                (!feof($file) && 
                 (!connection_aborted()) && 
                 ($bytes_send<$new_length) )
            {
                $buffer = fread($file, $chunksize);
                print($buffer); //echo($buffer); // is also possible
                flush();
                $bytes_send += strlen($buffer);
            }
        fclose($file);
        } else die('Error - can not open file.');

        die();

    }
    elseif(isset($_GET['ppage'],$_GET['id'],$_GET['module'],$_GET['attid']) && $_GET['ppage'] == 'viewatt' && $_GET['id'] != '' && $_GET['attid'] != ''){

        $file_n = $_GET['fn'];
        $target_dir = dirname(__FILE__).'/attachments/'.$_GET['id'].'/'.$_GET['attid'];

        $imageName = $target_dir.'/'.$file_n;

        if (!file_exists($imageName)) {       
            $file = $CPNzoho->downloadFileN($_GET['module'],$_GET['id'],$_GET['attid'], 'user', 'yes');
            if (!file_exists($target_dir))  mkdir($target_dir, 0777, true);        
            if($file!='')file_put_contents($imageName, $file);
        }

        $pluginUrl = CCGP_PLUGIN_URL . '/pages/user/attachments/'.$_GET['id'].'/'.$_GET['attid'].'/'.$file_n;
        echo "<script type='text/javascript'>window.location.replace('".$pluginUrl."');</script>";

    }
    elseif(isset($_GET['ppage'],$_GET['url']) && $_GET['ppage'] == 'pqlink' && $_GET['url'] !=""){
        if($logedin)include_once 'pqlink.php';
        else header("Location: ".esc_url($cppageUrl)."?ppage=login".esc_attr($page_id));
    }
    elseif(isset($_GET['ppage'],$_GET['url']) && $_GET['ppage'] == 'webtab' && $_GET['url'] !=""){
        if($logedin)include_once 'webtab.php';
        else header("Location: ".esc_url($cppageUrl)."?ppage=login".esc_attr($page_id));
    }
    elseif(isset($_GET['ppage']) && $_GET['ppage'] != ''){
        if($logedin) include_once '404.php';
        else header("Location: ".esc_url($cppageUrl)."?ppage=login".esc_attr($page_id));
    }else{
        if(!isset($_GET['post'])){
            if($logedin) header("Location: ".esc_url($cppageUrl)."?ppage=dashboard".esc_attr($page_id));
            else header("Location: ".esc_url($cppageUrl)."?ppage=login".esc_attr($page_id));
        }

    }
?>