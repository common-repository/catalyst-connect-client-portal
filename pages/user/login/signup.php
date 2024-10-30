<style type="text/css">
    .entry-title{display: none;}
    *{
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    /*Sign Up Page*/
    .ccgportal-signUp form input.form-control{width: 100%;}
    
    .ccgportal-signUp{
        box-shadow: 0px 0px 5px 0px #ccc;
        padding: 20px;
        max-width: 450px;
        margin: 0 auto;
    }
    .ccgportal-signUp h3{
        text-align: center;
        font-size: 26px;
        color: #4e4e4e;
        margin: 0px;
        float: left;
    }
    .ccgportal-signUp .userSignUp{
        float: right;
        color: #3379b8;
        text-decoration: none;
    }
    .ccgportal-signUp .form-horizontal .form-group {
         margin-right: 0px; 
         margin-left: 0px; 
         margin-bottom: 15px;
    }

    .btn{
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
    .btn-primary:hover {
        color: #fff;
        background-color: #286090;
        border-color: #204d74;
    }
    i.showpass,
    i.hidepass{
        margin-left: 0px;
        cursor: pointer;
        float: left;
        padding: 10px;
        font-size: 14px;
    }

    #ccgclientportal-content .alert-danger {
        background-color: #f2dede;
        border-color: #ebccd1;
        color: #a94442;
    }
    #ccgclientportal-content .alert-dismissable, #ccgclientportal-content .alert-dismissible {
        padding-right: 35px;
    }
    #ccgclientportal-content .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
</style>
<?php   
    $success = false;
    $successM = "Your account is under review. We will be in touch soon.";
    $showAcc = false;
    $showAccAdd = false;
    $existUser = false;
    $errorMsg = false;
    $exist = false;
    $AccExist = false;
    $errorbks = false;
    $errorcrm = false;
    $hideForm = false;

    $Users = new CCGP_Users();
    $CPNzoho = new ZohoCrmRequest();

    if(isset($_POST['addForExist'])){
        // var_dump($_POST);

        $res = $Users->addUserForExist($_POST);
        if($res['status'] === "errorbks"){ $errorbks = true;}
        if($res['status'] === "errorcrm"){ $errorcrm = true;}
        if($res['status'] === "success"){ 
            $success = true;
            $successM = (isset($res['message'])) ? $res['message'] : $successM;
        }

    }
    if(isset($_POST['addnewuser'])){

        $res = $Users->addUser($_POST);
        if($res['status'] === "ac_exist"){ 
            $exist = true;
            $errMsg = "Account name already exit in CRM";
            $existAcc = $res['data'];
        }
        if($res['status'] === "con_exist"){ 
            $exist = true;
            $errMsg = "Contact already exit in CRM by this Email";
            $existC = $res['data'];
        }
        if($res['status'] === "errorbks"){ $errorbks = true;}
        if($res['status'] === "errorcrm"){ $errorcrm = true;}
        if($res['status'] === "success"){ 
            $success = true;            
            $successM = (isset($res['message'])) ? $res['message'] : $successM;
        }

        unset($_SESSION["ccgcp_login_error"]);
        
    }
    if(isset($_POST['checckExist'])){
        $email = $_POST['email'];

        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'checckExist')){
            $existingUser = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE email = '$email'");

            if(!isset($existingUser->crmid)){

                $criteriaC = "(Email:equals:".$_POST['email'].")";
                $existC = $CPNzoho->getSearchRecordsN('Contacts', $criteriaC);

                if(isset($existC['data'][0]['id'])){
                    $exist = true;
                }else $showAcc = true;
            }else{
                $existUser = true;
            }
        } else{
            $errorMsg = true;
        }

        unset($_SESSION["ccgcp_login_error"]);
    }
    if(isset($_POST['checckAccExist'])){
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'checckExist')){
            $criteriaA = "(Account_Name:equals:".$_POST['Account_Name'].")";
            $existAcc = $CPNzoho->getSearchRecordsN('Accounts', $criteriaA);
            if(isset($existAcc['data'][0]['id'])){
                $AccExist = true;
                // $showAccAdd = true;            
                $showAcc = true;
            }else {
                $showAcc = true;
                // $showAccAdd = true;
                
                $res = $Users->addUser($_POST);
                if($res['status'] === "ac_exist"){ 
                    $exist = true;
                    $errMsg = "Account name already exit in CRM";
                    $existAcc = $res['data'];
                }
                if($res['status'] === "con_exist"){ 
                    $exist = true;
                    $errMsg = "Contact already exit in CRM by this Email";
                    $existC = $res['data'];
                }
                if($res['status'] === "errorbks"){ $errorbks = true;}
                if($res['status'] === "errorcrm"){ $errorcrm = true;}
                if($res['status'] === "success"){ 
                    $success = true;
                    $successM = (isset($res['message'])) ? $res['message'] : $successM;
                }
            }
        } else{
            $errorMsg = true;
        }
        unset($_SESSION["ccgcp_login_error"]);
    }



?>
<div id="ccgclientportal-content">

    <div class="ibox-content ccgportal-signUp" style="background: #FFFFFF;border-radius: 10px;">
        <div class="row">

            <div class="col-md-12">
                <a href="<?php echo esc_url($cppageUrl).'?ppage=login'.esc_attr($page_id);?>" style="font-size: 14px; color: #338dd0;">Already have an account?</a>
            </div>
            
        </div>

      
        <div class="clr"></div>

            <?php if($success){
                $_POST = array();
                $hideForm = true;
            ?>
                <div class="alert alert-dismissible alert-success" style="text-align: center;font-size: 16px;margin-top: 25px;">
                  <strong>Well done ! </strong> Sign Up Completed. <?php echo $successM; ?>.</a>
                </div>
            <?php } ?>

            <?php if($existUser){ ?>
                <div class="alert alert-dismissible alert-danger">
                  This user already exist
                </div>
            <?php } ?>

            <?php if($exist){ ?>
                <div class="alert alert-dismissible alert-danger">
                  Please select an option below.
                </div>
            <?php } ?>
            <?php if($AccExist){ ?>
                <div class="alert alert-dismissible alert-danger">
                  It looks like your Organization is already in our system, would you like to add this new contact to your existing organization ?
                </div>
            <?php } ?>

            <?php if($errorbks){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Ouch ! </strong> <?php if($res['code'] == 3062) echo "This Account name already exists. Please specify a different name."; else echo "User does not added. Please contact with us."; ?>
                </div>
            <?php } ?>

            <?php if($errorcrm){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Ouch ! </strong> We were unable to add your account at this time, please contact provisioned.
                </div>
            <?php } ?>

            <?php if($errorMsg){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Ouch ! </strong> Something went wrong. 
                </div>
            <?php } ?>

        <form action="" method="post" class="form-horizontal form-SignUp" id="SignUp" style="<?php if($hideForm) echo 'display: none;'; ?>">  

            <?php 
                wp_nonce_field('checckExist','csrf_token_nonce');
            ?>

            <div class="contactSection" style="display: <?php if($showAcc)echo "none";?>;">
                <h4 style="font-size: 25px;color: #045294;font-weight: 700;padding-bottom: 20px;padding-left: 15px;">REGISTER</h4>
                <div class="clr"></div>
                <div class="form-group col-md-12">

                    <div class="loginIntput">
                        <label class="labelstyle">First Name</label>
                        <input type="text" name="first_name" class="" required value="<?php if(isset($_POST['email']))echo esc_attr(sanitize_text_field($_POST['first_name'])) ?>" placeholder="type first name">
                    </div>
            

                   
                </div>
                <div class="form-group col-md-12">


                    <div class="loginIntput">
                        <label class="labelstyle">Last Name</label>
                        <input type="text" name="last_name" class="" required value="<?php if(isset($_POST['email']))echo esc_attr(sanitize_text_field($_POST['last_name'])) ?>" placeholder="type last name">
                    </div>

                </div>
                <div class="form-group col-md-12">

                    <div class="loginIntput">
                        <label class="labelstyle">Email</label>
                        <input type="email" name="email" class="primaryEmail" required value="<?php if(isset($_POST['email']))echo esc_attr(sanitize_email($_POST['email'])) ?>" style="border-radius: 10px;" placeholder="type email address">
                    </div>

                </div>            
                <script>
                    jQuery(function () {
                        jQuery(document).on("focusout","input.primaryEmail",function(){
                            var usernameInput = jQuery(this).val();
                            jQuery('input.usernameInput').val(usernameInput);
                        });
                    });
                </script>
                <div class="form-group col-md-12" style="display: none">
                    <label class="col-md-5 control-label">Phone</label>
                    <div class="col-md-7">                                                        
                        <input type="text" name="phone" class="" value="<?php if(isset($_POST['email'])) echo esc_attr(sanitize_text_field($_POST['phone'])); ?>" placeholder="type phone number">
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="form-group col-md-12">

                    <div class="loginIntput">
                        <label class="labelstyle">User Name</label>
                        <input type="email" name="username" class="usernameInput" required value="<?php if(isset($_POST['email']))echo esc_attr(sanitize_text_field($_POST['username'])) ?>" style="border-radius: 10px;" placeholder="type user name">
                    </div>

                </div>
                <div class="form-group col-md-12">

                    <div class="loginIntput">
                        <label class="labelstyle">Password</label>
                        <input name="password" required value="<?php if(isset($_POST['email']))echo esc_attr(sanitize_text_field($_POST['password'])) ?>"  autocomplete="off" type="password" placeholder="type password">
                        <i class="fas fa-eye showpass" onclick="showppass();" style="float: right; display: block; margin-top: -35px;"></i>
                        <i class="fas fa-eye-slash hidepass" style="float: right; display: block; margin-top: -35px;display: none;" onclick="showppass();" ></i>
                     </div>

                </div>
                <div class="clr" style="height: 30px"></div>

            </div>

            <?php if($exist){ ?>
                <?php if(isset($existC['contacts'][0]['contact_id'])){ ?>
                    <input type="hidden" name="booksConId" value="<?php echo $existC['contacts'][0]['contact_id']; ?>">
                <?php } ?>
                <?php if(isset($existC['data'][0]['id'])){ ?>
                    <input type="hidden" name="crmConId" value="<?php echo $existC['data'][0]['id']; ?>">
                <?php } ?>
                <div class="clr"></div>
                <div class="form-group  col-md-12">
                    <div class="col-md-12" style="font-size: 16px;"> 
                        <b>Add with existing</b> <input type="checkbox" class="btn btn-defult" id="addForExistAccept" name="addForExistAccept" style="margin: -3px 0px 0px 14px;">
                    </div>
                </div>
                <div class="form-group  col-md-12">
                    <div class="col-md-12" style="font-size: 16px;"> 
                        <b>Add New</b> <input type="checkbox" class="btn btn-defult" id="addNewAccept" name="addNewAccept" style="margin: -3px 0px 0px 14px;">
                    </div>
                </div>
                <div class="clr"></div>
                <script type="text/javascript">
                    jQuery(document).ready( function () {
                        jQuery('#addForExistAccept').click(function() {                        
                            if (jQuery(this).is(':checked')){
                                jQuery('#addNewAccept').prop('checked', false);

                                jQuery('.checckExist').hide();   
                                jQuery('.addForExist').closest('.form-group.col-md-12').show();
                            }else jQuery('.addForExist').closest('.form-group.col-md-12').hide();                        
                        });

                        jQuery('#addNewAccept').click(function() {                        
                            if (jQuery(this).is(':checked')){
                                jQuery('#addForExistAccept').prop('checked', false);

                                jQuery('.addForExist').closest('.form-group.col-md-12').hide();
                                jQuery('.checckExist').show();
                                jQuery('.primaryEmail').val("");
                                jQuery('.primaryEmail').focus();

                            } else jQuery('.checckExist').hide();                        
                        });
                    } );
                </script>

                <div class="clr"></div>
                <div class="form-group col-md-12" style="display: none;">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-primary addForExist" style="float: right;" name="addForExist" value="Save">
                    </div>
                </div>
                <div class="clr"></div>
            <?php } ?>
            

            <?php if($showAcc){ ?>
            <div class="accountSection"  style="display: <?php if($showAccAdd && !$AccExist)echo "none";?>;">
                <h4 style="padding-left: 15px;padding-bottom: 10px;">Account Information</h4>
                <div class="clr"></div>

                 <div class="form-group col-md-12">

                    <div class="loginIntput">
                        <label class="labelstyle">Account Name</label>
                        <input type="text" name="Account_Name" id="account_Name" class="form-control" required value="<?php if(isset($_POST['website']))echo esc_attr(sanitize_text_field($_POST['Account_Name'])) ?>" style="border-radius: 10px;">
                    </div>

                </div>

       <!--          <div class="form-group col-md-6">
                    <label class="col-md-5 control-label">Account Name</label>
                    <div class="col-md-7">                                                        
                        <input type="text" name="Account_Name" id="account_Name" class="form-control" required value="<?php// if(isset($_POST['website']))echo $_POST['Account_Name'] ?>">
                    </div>
                </div> -->

          <!--       <div class="form-group col-md-6">
                    <label class="col-md-5 control-label">Website</label>
                    <div class="col-md-7">                                                        
                        <input type="text" name="website" class="form-control" value="<?php if(isset($_POST['website']))echo $_POST['website'] ?>">
                    </div>
                </div>
 -->
                <div class="form-group col-md-12">

                    <div class="loginIntput">
                        <label class="labelstyle">Website</label>
                        <input type="text" name="website" class="form-control" value="<?php if(isset($_POST['website']))echo esc_url(sanitize_url($_POST['website'])) ?>" style="border-radius: 10px;">
                     
                    </div>

                </div>

            </div>

            <?php } ?>


            <?php if($AccExist){ ?>
                <?php if(isset($existAcc['data'][0]['id'])){ ?>
                    <input type="hidden" name="crmAccId" value="<?php echo esc_url($existAcc['data'][0]['id']); ?>">
                <?php } ?>
                <div class="clr"></div>
             <!--    <div class="form-group  col-md-12">
                    <div class="col-md-12" style="font-size: 16px;"> 
                        <b>Yes, add me to my organization</b> <input type="checkbox" class="btn btn-defult" id="addForExistAccAccept" name="addForExistAccAccept" style="margin: -3px 0px 0px 14px;">
                    </div>
                </div> -->

                <div class="form-group col-md-12">

                    <div class="loginIntput">
                        <div class="col-md-12" style="font-size: 16px;"> 
                            <b>Yes, add me to my organization</b> <input type="checkbox" class="btn btn-defult" id="addForExistAccAccept" name="addForExistAccAccept" style="margin: -3px 0px 0px 14px;">
                        </div>
                     
                    </div>

                </div>


                <div class="form-group col-md-12">

                    <div class="loginIntput">
                        <div class="col-md-12" style="font-size: 16px;"> 
                            <b>No, Create a new organization</b> <input type="checkbox" class="btn btn-defult" id="addNewAccAccept" name="addNewAccAccept" style="margin: -3px 0px 0px 14px;">
                        </div>
                     
                    </div>

                </div>


<!-- 
                <div class="form-group  col-md-12">
                    <div class="col-md-12" style="font-size: 16px;"> 
                        <b>No, Create a new organization</b> <input type="checkbox" class="btn btn-defult" id="addNewAccAccept" name="addNewAccAccept" style="margin: -3px 0px 0px 14px;">
                    </div>
                </div> -->

                <div class="clr"></div>
                <script type="text/javascript">
                    jQuery(document).ready( function () {
                        jQuery('#addForExistAccAccept').click(function() {                        
                            if (jQuery(this).is(':checked')){
                                jQuery('#addNewAccAccept').prop('checked', false);

                                jQuery('.checckAccExist').hide();   
                                jQuery('.addForAccExist').closest('.form-group.col-md-12').show();
                            }else jQuery('.addForAccExist').closest('.form-group.col-md-12').hide();                        
                        });

                        jQuery('#addNewAccAccept').click(function() {                        
                            if (jQuery(this).is(':checked')){
                                jQuery('#addForExistAccAccept').prop('checked', false);

                                jQuery('.addForAccExist').closest('.form-group.col-md-12').hide();
                                jQuery('.checckAccExist').show();
                                jQuery('#account_Name').val("");
                                jQuery('#account_Name').focus();

                            } else jQuery('.checckAccExist').hide();                        
                        });
                    } );
                </script>

                <div class="clr"></div>
                <div class="form-group col-md-12" style="display: none;">
                    <div class="col-md-12">
                        <input type="submit" class="btn btn-primary addForAccExist" style="float: right;" name="addnewuser" value="Save">
                    </div>
                </div>
                <div class="clr"></div>
            <?php } ?>

            <div class="clr"></div>
            <div class="form-group col-md-12">
                <div class="">
                    <?php if(!$showAcc){ ?>
                        <input type="submit" class="btn btn-primary checckExist" style="float: right;display:<?php if($exist)echo 'none';?>;background: #2B9CF2;border-radius: 10px;padding-left: 25px;padding-right: 25px;" name="checckExist" value="Next">
                    <?php } ?>  
                    <?php if($showAcc && !$showAccAdd){ ?>
                        <input type="submit" class="btn btn-primary checckAccExist" style="float: right;display:<?php if($exist)echo 'none';?>;background: #2B9CF2;border-radius: 10px;padding-left: 25px;padding-right: 25px;" name="checckAccExist" value="Save">
                    <?php } ?>                
                    <?php if($showAccAdd && !$AccExist){ ?>
                        <input type="submit" class="btn btn-primary" style="float: right;background: #2B9CF2;border-radius: 10px;padding-left: 25px;padding-right: 25px;" name="addnewuser" value="Save">
                    <?php } ?>
                </div>
            </div>
            <div class="clr"></div>

        </form>

        <div class="clr"></div>

    </div>
    <div class="clr"></div>
</div>
<script>
    jQuery(function () {
        jQuery(document).on('click', '.showpass', function(event) {
            jQuery(this).closest('div').find('.showpass').hide();
            jQuery(this).closest('div').find('input').attr('type', 'text');
            jQuery(this).closest('div').find('.decrptpass').show();
            jQuery(this).closest('div').find('.hidepass').show();
        });
        jQuery(document).on('click', '.hidepass', function(event) {
            jQuery(this).closest('div').find('.hidepass').hide();
            jQuery(this).closest('div').find('input').attr('type', 'password');
            jQuery(this).closest('div').find('.encptpass').show();
            jQuery(this).closest('div').find('.showpass').show();
        });
    });
</script>