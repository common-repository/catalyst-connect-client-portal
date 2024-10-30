<?php 
    global $wp;
    $cppageUrl =  home_url( $wp->request );
    
    if(isset($_GET['email'],$_GET['first_name'],$_GET['last_name'])){
        header("Location: ".$cppageUrl."?ppage=sign-up&email=".$_GET['email']."&first_name=".$_GET['first_name']."&last_name=".$_GET['last_name'].$page_id);
    }
    $acform = "login";
    if(isset($_POST['user_email'])){
        $acform = "forgetpass";
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'reset_password')){
            $user_email = trim(sanitize_email($_POST['user_email']));

            $contacts = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE status = 'active' AND username = '$user_email'");

            if(isset($contacts->crmid)){
                $conId = $contacts->crmid;
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $resetKey = substr(str_shuffle($chars),0,30);
                $wpdb->insert("ccgclientportal_options", [
                    'option_name' => 'resetpassword',
                    'option_index' => $conId,
                    'option_value' => $resetKey                
                ]);

                $resetUrl = $cppageUrl."?ppage=resetpassword&key=".$resetKey.$page_id;
                $to = $user_email;
                $subject = "Reset Password";
                $content = "For reset password click <a href='".$resetUrl."' >here</a> or got to the link below <br></br>"; 
                $content .= $resetUrl;

                // To send HTML mail, the 'Content-type' header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";


                $res = wp_mail($to, $subject, $content, $headers);
                if ($res) {
                    $rsts = true;
                    $rstm = "Please Check your email for reset password.";
                }else{
                    $rsterror = true;
                    $rstm = "Something went wrong. Please contact your administrator.";                
                }

            }else{
                $rsterror = true;
                $rstm = "Invalid email address.";
            }
        }else{
            $rsterror = true;
            $rstm = "Something went wrong";
        }
    }

    if(!isset($_SESSION["ccgcpp_logout"])){
        $current_user = wp_get_current_user();
        if(isset($current_user->data->user_email)){
            if(isset($current_user->roles[0]) && $current_user->roles[0] == 'client'){
                $email = $current_user->data->user_email;

                $Users = new CCGP_Users();
                $Users->loginUserByWpUser($email,$page_id);
            }

        }
    }

    $uPgnrlV = array();
    $apGp = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'userpermission' AND option_index = 'generalPermission'");
    if(isset($apGp->option_value))$uPgnrlV = json_decode($apGp->option_value, true);
    $aiprfl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'accoutn_info' AND option_index = 'profile'");

    if(isset($aiprfl->option_value))$aiprflV = json_decode($aiprfl->option_value, true);

    $pcolor = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'general-settings' AND option_index = 'primarycolor'");
      if(isset($pcolor->option_value))$primarycolor = $pcolor->option_value;
      else $primarycolor ="#228be6";

    $portalTitleDt = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'general-settings' AND option_index = 'portalTitle'");
    if(isset($portalTitleDt->option_value))$portalTitle = $portalTitleDt->option_value;
    else $portalTitle ="Client Portal";
?>
<?php 
    $apData = array();
    $apData = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'appearance'");
    foreach ($apData as $apOval) {
      if($apOval->option_index == 'menucolor') $apMenC = json_decode($apOval->option_value, true);
      if($apOval->option_index == 'buttoncolor')$apbtnC = json_decode($apOval->option_value, true);
      if($apOval->option_index == 'font_color')$apfontC = json_decode($apOval->option_value, true);
    }
?>

<style type="text/css">
	.entry-content > *:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.is-style-wide) {
    		max-width: -webkit-fill-available;
    		width: 50%;

	}

    @-moz-document url-prefix() {
        .entry-content > *:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.is-style-wide) {
        		max-width: 100%;
        		width: 50%;

    	}
    }

	.post-inner{
		background: #fff !important;
	}
    .ccgclient_portal{
        padding: 0px !important;
    }
</style>
<style type="text/css">

    /*Login Page*/
    .ccgportal-login form input.form-control{width: 100%;}
    
    .ccgportal-login{
        max-width: 450px;
        /*margin: 15% auto;*/
        margin: 0 auto;
        box-shadow: 0px 0px 5px 0px #ccc;
        padding: 30px;
        background: #FFFFFF;
    }
    .ccgportal-login h3{
        text-align: left;
        font-size: 22px;
        color: #2F558F;
        margin: 10px 0px;
        padding-top: 35px;
    }
    .ccgportal-login .showForgetPassFrom,
    .ccgportal-login .showLoginFrom,
    .ccgportal-login .userSignUp{
        color: #3379b8;
        text-decoration: none;
    }
    .ccgportal-login .form-group {
        margin-bottom: 15px;
    }
    .ccgportal-login .btn-primary {
        color: #fff;
        background-color: #337ab7;
        border-color: #2e6da4;
    }
    .ccgportal-login .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    .ccgportal-login input[type="email"], 
    .ccgportal-login input[type="password"], 
    .ccgportal-login input[type="text"],
    .form-SignUp input[type="email"], 
    .form-SignUp input[type="password"], 
    .form-SignUp input[type="text"]{
        height: 29px;
        padding-top: 0;
        padding-bottom: 0;
        width: 100%;
    }
    ::-webkit-input-placeholder { /* WebKit, Blink, Edge */
        color:    #cccccc;
    }
    :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
       color:    #cccccc;
       opacity:  1;
    }
    ::-moz-placeholder { /* Mozilla Firefox 19+ */
       color:    #cccccc;
       opacity:  1;
    }
    :-ms-input-placeholder { /* Internet Explorer 10-11 */
       color:    #cccccc;
    }
    ::-ms-input-placeholder { /* Microsoft Edge */
       color:    #cccccc;
    }

    ::placeholder { /* Most modern browsers support this now. */
       color:    #cccccc;
    }
    body {
        background: #F3F9FF;
    }
    .loginIntput{
        border: 1px solid <?php echo $primarycolor; ?>;
        border-radius: 10px;
        padding: 20px;
        float: left;
        width: 100%;
    }

    .labelstyle{
        color: <?php echo esc_html($primarycolor); ?>;
        font-weight: 700;
    }

    .loginIntput input{
        border: none;
        background: rgb(255, 255, 255);
        padding: 0px;
    }    

    #ccgclientportal-content h1,
    #ccgclientportal-content h2,
    #ccgclientportal-content h3,
    #ccgclientportal-content h4{
        color: <?php echo (isset($apfontC['fontcolor'])) ? $apfontC['fontcolor'] : 'rgb(34, 139, 230)';  ?>;
    }
</style>


<div id="ccgclientportal-content">

    <?php
    $col ='12';
    ?>
     	
     	<div class="col-md-<?php echo esc_attr($col)?>" id="login-form" style="background: #f3f9ff;padding: 50px;height: auto;">
     		 <?php include_once 'login.php'; ?>
     	</div>



    <div class="clr"></div>
</div>
<div class="clr"></div>


<script type="text/javascript">

    function setbodyContentHeight() {
        var sgheight = jQuery('#signup-form').height();
        var lgheight = jQuery('#login-form').height();
        // console.log(sgheight);

        if(sgheight > lgheight){
            jQuery('#login-form').css("min-height", (sgheight + 100));
        }
        if(lgheight > sgheight){
            jQuery('#signup-form').css("min-height", (lgheight + 100));
        }
    }

    setTimeout( "setbodyContentHeight()", 200);

</script>


