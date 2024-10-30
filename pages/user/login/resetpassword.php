<style type="text/css">

    /*Login Page*/
    .ccgportal-login form input.form-control{width: 100%;}
    
    .ccgportal-login{
        width: 360px !important;
        margin: 5% auto;
        box-shadow: 0px 0px 5px 0px #ccc;
        padding: 15px;
        background: #FFFFFF;
    }
    .ccgportal-login h3{
        text-align: center;
        font-size: 22px;
        color: #4e4e4e;
        margin: 10px 0px;
    }
    .ccgportal-login .userSignUp{
        float: right;
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
    .ccgportal-login input[type="text"]{
        height: 29px;
        padding-top: 0;
        padding-bottom: 0;
        width: 100%;
    }
</style>
<?php 
    if(!isset($_GET['key'])){

        header("Location: ".$cppageUrl."?ppage=login".$page_id);

    }else
    if(isset($_GET['key']) && $_GET['key'] !=""){
        $key = $_GET['key'];
        $rstkey = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'resetpassword' AND option_value = '$key'");

        if(!isset($rstkey->option_value)){
            $invalidKey = true;
        }
        else{
            $conId = $rstkey->option_index;
        }
    }


    
    if(isset($_POST['confirmpassword'])){

        $newpassword = trim($_POST['newpassword']);
        $password = trim($_POST['confirmpassword']);
        $conId = $_POST['conId'];

        if($newpassword == $password){

            $contacts = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE status = 'active' AND crmid = '$conId'");

            if(isset($contacts->crmid)){
                $conId = $contacts->crmid;
                $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'resetpassword', 'option_index' => $conId) );
                $wpdb->update( "ccgclientportal_users", 
                    array( 'password' => $password),
                    array('id'=>$contacts->id)
                );

                $email = $contacts->email;
                $exists = email_exists($email);
                if ( $exists ){
                    $user_id = (int) $exists; // correct ID
                    $res = wp_update_user( array(
                        'ID' => $user_id,
                        'user_pass' => $password
                    ) );
                }
                $passrsts = true;
            }else{
                $passrsterror = true;
                $passrsterrorM = "Ouch ! something went wrong. Please contact your administrator";
            }
        }else{            
            $passrsterror = true;
            $passrsterrorM = "New Password and Confirm Password does not match";
        }

    }

    $uPgnrlV = array();
    $apGp = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'userpermission' AND option_index = 'generalPermission'");
    if(isset($apGp->option_value))$uPgnrlV = json_decode($apGp->option_value, true);
    $aiprfl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'accoutn_info' AND option_index = 'profile'");

    if(isset($aiprfl->option_value))$aiprflV = json_decode($aiprfl->option_value, true);

    $pcolor = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'general-settings' AND option_index = 'primarycolor'");
      if(isset($pcolor->option_value))$primarycolor = $pcolor->option_value;
      else $primarycolor ="";

    $portalTitleDt = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'general-settings' AND option_index = 'portalTitle'");
    if(isset($portalTitleDt->option_value))$portalTitle = $portalTitleDt->option_value;
    else $portalTitle ="Client Portal";

?>

<div class="ibox-content ccgportal-login login-form" style="padding: 30px">
    <?php if(isset($invalidKey)){ ?>
        <h3 style="color: red;text-align: center;"> Invalid key </h3>
    <?php } else{ ?>

        <?php if(isset($passrsts)){ ?>

            <p style="color: green;text-align: center;font-size: 14px;">Password reset successfully</p>

        <?php }else{ ?>
        <!-- <h3>Welcome to <?php echo $portalTitle; ?></h3> -->
        <form class="m-t" role="form" action="" method="post" style="color: #000;">


                <?php if(isset($passrsterror)){ ?>
                    <p style="color: red;text-align: center;font-size: 14px;"><?php echo esc_html($passrsterrorM); ?></p>
                <?php } ?>

                <input type="hidden" name="conId" value="<?php echo esc_attr($conId); ?>">

                <div class="form-group">
                    <input placeholder="New Password" name="newpassword" required="" autofocus="" type="password">
                </div>
                <div class="form-group">
                    <input placeholder="Confirm Password" name="confirmpassword" required="" type="password">
                </div>
                <button type="submit" class="btn btn-primary btn-fw " style="width: 100%;background: <?php echo esc_attr($primarycolor);?>">Reset</button>

            </form>
            <div style="clear: both;height: 15px;"></div>
        <?php } ?>
            <div style="text-align: center;">
                <a href="<?php echo esc_url($cppageUrl);?>?ppage=login<?php echo esc_attr($page_id); ?>" >Login</a>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>

    <?php } ?>
</div>
