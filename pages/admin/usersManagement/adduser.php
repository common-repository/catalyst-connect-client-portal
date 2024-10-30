    <?php
        /*
        * Created by Mahidul Islam.
        */
    ?>

    <?php

        $activeTab = '';
        $success = false;

        $showAcc = false;
        $showAccAdd = false;
        $existUser = false;
        $exist = false;
        $AccExist = false;

        $errorcrm = false;
        $hideForm = false;
        $contactList = array();
        global $wpdb;

        $ZCRequest = new ZohoCrmRequest();
        $SttClass = new CCGP_SettingsClass();


        $ajaxUrl = wp_nonce_url(site_url()."/wp-admin/admin-ajax.php","ajax_search_request","csrf_token_nonce"); 

        // Add from CRM
        if(isset($_POST['addforExist'])){

                if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'addforExist')){
                    $res = $ZCRequest->addUserforExist($_POST);
                    if($res){ $success = true;}
                else{
                    $errorMsg = true;
                }
            }
        }

        if(isset($_POST['searchContacts'])){
            
            if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'searchContacts')){
                $email = trim(sanitize_email($_POST['searchEmail']));

                $contacts = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE email = '".$email."'");        
                if(!isset($contacts->email)){
    
                    $criteria    = "(Email:equals:".$email.")";
                    $contactList = $ZCRequest->getSearchRecordsN('Contacts', $criteria, array('Full_Name', 'id'));
    
                    if(isset($contactList['data']) && count($contactList['data']) == 1){
                        if(isset($contactList['data'][0]['Account_Name']['id']) || isset($contactList['data'][0]['Account']['id'])){}
                        else $erreAccNotFound = true;
                    }
    
                }else $exist = true;
            }else{
                $errorMsg = true;
            }
        }


        // Add New
        if(isset($_POST['addnewuser'])){
            $activesubTab = "tab-addnew";  
            if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'sign_up_page')){
                $res = $ZCRequest->addUserNew($_POST);
                
                if($res['status'] === "ac_exist"){ 
                    $exist = true;
                    $errMsg = "Account name already exit in CRM";
                }
                if($res['status'] === "con_exist"){ 
                    $exist = true;
                    $errMsg = "Contact already exit in CRM by this Email";
                }
                if($res['status'] === "errorcrm"){ $errorcrm = true;}
                if($res['status'] === "success"){ 
                    $success = true;
                }
            }else{
                $errorMsg = true;
            }    
        }


        if( isset($_POST['checckExist']) ){
          
            if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'sign_up_page')){
                $activesubTab = "tab-addnew";
                $email        = trim(sanitize_email($_POST['email']));

                $existingUser = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE email = '$email'");

                if(!isset($existingUser->crmid)){

                    $CPNzoho = new ZohoCrmRequest();
                    $criteriaC = "(Email:equals:".$email.")";
                    $existC = $CPNzoho->getSearchRecordsN('Contacts', $criteriaC);
                    if(isset($existC['data'][0]['id'])){
                        $exist = true;
                    }else $showAcc = true;
                    $_GET['action'] = 'adduser';
                }else{
                    $existUser = true;
                }
            }else{
                $errorMsg = true;
            }
        }

    ?>

    <?php
        $pagetitle = "Add New User";
        include_once 'header.php';
    ?>

        <!-- <div id="portal-cotenier"> -->

            <?php if($success){
                $_POST = array();
            ?>
                <div class="alert alert-dismissible alert-success">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  User added successfully.</a>
                </div>
            <?php } ?>

            <?php if($existUser){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  This user already exist 
                </div>
            <?php } ?>

            <?php if($exist){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  This email already exist 
                </div>
            <?php } ?>
            <?php if($AccExist){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  This Account already exist 
                </div>
            <?php } ?>

            <?php if($errorcrm){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  User does not added. Please contact with us 
                </div>
            <?php } ?>

            <?php if(isset($erreAccNotFound)){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  Please associate this Contact with an Account in the CRM before processing
                </div>
            <?php } ?>
            <?php if(isset($errorMsg)){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  Somethings went wrong. 
                </div>
            <?php } ?>

            <ul class="nav nav-tabs nav-internal">
                <li class="nav-item active">
                    <a class="nav-link active" data-toggle="tab" href="#addfromexist">Add From CRM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-addnew" data-toggle="tab" href="#addnew">Add New</a>
                </li>
            </ul>
            <div class="clr"></div>


            <div id="myTabContent2" class="tab-content pd_30_20">
                <div class="tab-pane fade " id="addnew">  
                    <?php include 'adduser/addnewuser.php';?>
                </div>
                <div class="tab-pane fade active in" id="addfromexist">
                    <?php include 'adduser/addfromexist.php';?>
                </div>
            </div>

        <!-- </div> -->
    <!-- </div> -->

    <?php if(isset($_POST['searchEmail'])) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $randomPass = substr(str_shuffle($chars),0,6);
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                var randomPass = `<?php echo $randomPass; ?>`;
                var searchEmail = `<?php echo sanitize_email($_POST['searchEmail']); ?>`;

                jQuery(document).on('change', '#selectFrmExContacts', function(event) {
                    event.preventDefault();
                    /* Act on the event */
                    console.log(searchEmail);
                    jQuery("#exContactUn").val(searchEmail);
                    jQuery("#exContactPs").val(randomPass);

                });
            });
        </script>
    <?php } ?>