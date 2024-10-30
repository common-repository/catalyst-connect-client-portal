<?php
    /*
        Created by : Mahidul Islam
    */



    if(isset($_POST['save_profile'])){
         
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'save_profile')){
            $nzohos = new CPNzoho();

            $leads = array(

                'First_Name'  => sanitize_text_field($_POST['first_name']),
                'Last_Name'   => sanitize_text_field($_POST['last_name']),
                'Company'     => sanitize_text_field($_POST['company_name']),
                'Email'       => sanitize_email($_POST['email']),
                'Phone'       => sanitize_text_field($_POST['phone']),
                'Lead_Source' => 'Wordpress Free Plugin',
                'action'      => 'Free_leads'

            );
            // $results = $nzohos->post_to_zoho_free('https://portal-plugin.thecatalystcloud.com/api/api-v3/CCGAction.php',$leads);
            
            // *ZPFP means "Zportals Free Plugins"
            $results = $nzohos->post_to_zoho_free('https://plugin.zportals.com/v1/api/ZPFPAction.php', $leads);


            unset($_POST['save_profile']);

            $wpdb->insert("ccgclientportal_options", [
                'option_name' => 'accoutn_info',
                'option_index' =>'profile',
                'option_value' => json_encode($_POST)
            ]);

            $wpdb->insert("ccgclientportal_options", [
                'option_name' => 'accoutn_info',
                'option_index' =>'status',
                'option_value' => "active"
            ]);
            
            echo '<div class="alert alert-dismissible alert-success" style="margin-top:30px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Well done!</strong> Account info save successfully. 
            </div>';
            echo "<script type='text/javascript'>setTimeout(function(){ location.reload(); }, 3000);</script>";
        }else{
            echo '<div class="alert alert-dismissible alert-danger" style="margin-top:30px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong></strong> Something went wrong. 
            </div>';
        echo "<script type='text/javascript'>setTimeout(function(){ location.reload(); }, 3000);</script>";
        }

    }



    $sqlaccin = "SELECT * FROM ccgclientportal_options WHERE option_name = 'accoutn_info' AND `option_index` = 'profile'";
    $accinfo  = $wpdb->get_row($sqlaccin);
    if( isset($accinfo->option_index) ) $profile = json_decode($accinfo->option_value, true);

?>


<div class="ccgclient-portal">
    <h3 class="page-heading">Dashboard</h3>
    <div class="clr"></div>
    <style type="text/css">
        .nmLabel{
                font-size: 15px;
        }
        .mb-1{
            margin-bottom: .5rem;
        }
        .dashboard-page .form-group label{
            padding: 0px;
        }
    </style>

        <?php if($success){ ?>
            <div class="alert alert-dismissible alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <?php echo $successMessage; ?>.
            </div>
        <?php } ?>
        <?php if($error){ ?>
            <div class="alert alert-dismissible alert-danger">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <?php echo $errorMessage; ?>
            </div>
        <?php } ?>

    <div id="portal-cotenier" style="padding: 30px;background: #fff;">
        <div class="dashboard-page" style="background: #fff;">            

            <?php if(isset($profile)){ ?>
                          
                <div class="col-md-12 pd-0">
                    <div class="col-md-6">
                        <h4>Company Information</h4>
                        <div class="row mb-1">
                            <div class="col-md-3 nmLabel">
                                <b>Company:</b>  
                            </div>
                            <div class="col-md-9">
                                <?php echo esc_html($profile['company_name']);?>  
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3 nmLabel">
                                <b>Name:</b>  
                            </div>
                            <div class="col-md-9">
                                <?php echo esc_html($profile['first_name'].' '. $profile['last_name']); ?>  
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3 nmLabel">
                                <b>Email:</b>  
                            </div>
                            <div class="col-md-9">
                                <?php echo esc_html($profile['email']); ?>  
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3 nmLabel">
                                <b>Phone:</b>  
                            </div>
                            <div class="col-md-9">
                                <?php echo esc_html($profile['phone']); ?>  
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3 nmLabel">
                                <b>License Type:</b>  
                            </div>
                            <div class="col-md-9">
                                <?php echo "Free"; ?>  
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="clr"></div>
                </div>

            <?php 
            }else{
            ?>

                <form role="form" action="" method="post" class="form-horizontal profile" id="profile" style="padding: 30px;">
                    <?php 
                        wp_nonce_field('save_profile','csrf_token_nonce');
                    ?> 
                    <h4>Company Information</h4>
                    <div class="clr"></div>
                    <div class="form-group col-md-6 pd-0">
                        <label class="col-md-3 control-label">First Name</label>
                        <div class="col-md-9">                                                        
                            <input type="text" name="first_name" class="form-control" required="">
                        </div>
                    </div>
                    <div class="form-group col-md-6 pd-0">
                        <label class="col-md-3 control-label">Last Name</label>
                        <div class="col-md-9">                                                        
                            <input type="text" name="last_name" class="form-control" required="">
                        </div>
                    </div>
                    <div class="form-group col-md-6 pd-0">
                        <label class="col-md-3 control-label">Company Name</label>
                        <div class="col-md-9">                                                        
                            <input type="text" name="company_name" class="form-control" required="">
                        </div>
                    </div>

                    <div class="form-group col-md-6 pd-0">
                        <label class="col-md-3 control-label">Email</label>
                        <div class="col-md-9">                                                        
                            <input type="email" name="email" class="form-control" required="">
                        </div>
                    </div>
                    <div class="form-group col-md-6 pd-0">
                        <label class="col-md-3 control-label">Phone</label>
                        <div class="col-md-9">                                                        
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>

                    <div class="clr"></div>
                    <div class="form-group col-md-12 ">
                        <input type="submit" class="btn btn-primary btn-padding float-right" style="float: right;" name="save_profile" value="Save">
                    </div>
                    <div class="clr"></div>

                </form>

            <?php } ?>

            <div class="clr"></div>
        </div>
    </div>
</div>