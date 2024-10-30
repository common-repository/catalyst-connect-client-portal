<?php
/*
    Created By : Mahidul Islam Tamim
*/    
    include_once 'common/header.php';



    global $wp;
    global $wpdb;
    
    $plugin_dir = CCGP_PLUGIN_URL;
    $activeTab  = '';
    $gact       = false;
    $upssc      = false;
    $sscmsg     = "Update successfully";
    $accsscmsg  = "Access token generated successfully";
    $upMenupSsc = false;

    $ZCrmRequest = new ZohoCrmRequest();
    if(isset($_GET['org_id'], $_GET['zservice'], $_GET['access_token']) && $_GET['zservice'] =="crm"){
        $wpdb->delete( 'ccgclientportal_auth', array( 'apifor' => 'crm' ) );
        $wpdb->insert("ccgclientportal_auth", [
            'apifor' => 'crm',
            'orgid' => $_GET['org_id'],
            'access_token' => $_GET['access_token'],
            'refresh_token' => $_GET['refresh_token'],
            'create_time' => date('Y-m-d H:i:s')
        ]);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'apiAlert', 'option_index' => 'crm' ) );

        $gact = true;
        $accsscmsg = "Your credential for Zoho CRM is updated";
    }

    if(isset($_GET['org_id'], $_GET['zservice'], $_GET['error']) && $_GET['zservice'] =="crm"){
        $error = true;
    }

    if(isset($_POST['save_domain_type'])){

        global $wpdb;
        
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'save_domain_type')){
            $data = array();

            $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'accoutn_info', 'option_index' => "user_domain" ) );

            $update = $wpdb->insert("ccgclientportal_options", [

                'option_name' => 'accoutn_info',

                'option_index' => "user_domain",

                'option_value' =>  $_POST['domain']

            ]);

            if($update) $uduss = true;

            else $uderror = true;
        }else{
            $uderror = true;
        }

         

    }

    $auth = $wpdb->get_row("SELECT * FROM ccgclientportal_auth WHERE apifor = 'crm' ORDER BY id DESC"); 

    $sqldomain = "SELECT * FROM ccgclientportal_options WHERE option_name = 'accoutn_info' AND option_index = 'user_domain'";

    $resdomain = $wpdb->get_row($sqldomain);

?>

    <style type="text/css">
        form.MenuPermission .form-group label.control-label{
            min-width: 125px;
            margin: 0px;
        }
        #zohocrmapisettings .nav-tabs>li>a{
            margin-right: 8px;
        }
        #zohocrmapisettings .nav-item .badge.incomplete{
            margin-top: 1px;
            font-size: 10px;
            position: absolute;
            /*margin-left: -34px;        */
            /* margin-left: -5px;         */
            margin-left: 140px;        
            background: rgb(255 255 255);
            border: solid 1px #ff8d00;
            color: #ff8d00;
        }
        #ccgclientportal-content .nav-tabs>li>a {
            border: 1px solid #0091d8;
            padding: 10px 20px;
        }
        #ccgclientportal-content .vertical-tab .nav-tabs.tab-basic > li.nav-item > a.nav-link{
            padding: 10px;
        }
        .nav-link{
            font-size: 15px;
        }
        #ccgclientportal-content .nav-tabs>li>a.disable:focus{
            box-shadow: none;
        }
        #ccgclientportal-content .nav-tabs>li>a.disable {
            /*border: 1px solid #ccc;
            background: #dcdcdc;*/
            border: 1px solid #FFEFE0;
            background: #FFEFE0;
            color: #B6AFA7;
            border-radius: 0;
        }
        #zohocrmapisettings .nav-tabs {
            border-bottom: 1px solid #0091d8;
        }
        #zohocrmapisettings .nav-tabs>li {
            text-align: left;
            padding: 10px;
            padding-left: 0px;
        }

        #ccgclientportal-content .nav-tabs>li.active>a, #ccgclientportal-content .nav-tabs>li.active>a:focus, #ccgclientportal-content .nav-tabs>li.active>a:hover {
            box-shadow: none;
            color: #064c6d;
            background-color: #0091d800;
            font-weight: 700;
            font-size: 15px;
        }


        /*new*/
        
        #portal-cotenier .nav-tabs>li>a {
            /*border: 1px solid #0091d8;*/
            padding: 5px 10px;
            padding-left: 20px;
        }
        #portal-cotenier .nav-tabs>li>a {
            margin-right: 8px;
        }
        #portal-cotenier .nav-tabs {
            border-bottom: 0px;
            box-shadow: -4px 10px 5px -7px rgba(4, 4, 4, 0.19);
            background: #fff;
            border-radius: 10px 10px 0px 0px;
            position: relative;
            padding-left: 15px;
        }
        #ccgclientportal-content .badge{
            background-color: #FEA147;
            padding: 3px 3px;
            font-size: 8px;
        }

        #ccgclientportal-content .nav-tabs > li.active > a, #ccgclientportal-content .nav-tabs > li.active > a:focus, #ccgclientportal-content .nav-tabs > li.active > a:hover {
            border-bottom: 0;
        }
        #ccgclientportal-content .nav-tabs > li.active{
            border-bottom: 6px solid #064c6d;
        }

    .left-tab {
        float: left;
        width: 230px;
        background: rgb(255, 255, 255);
        box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.16);
        border-radius: 8px 0 0 8px;
    }

        #myTabContent {
            float: right;
            width: calc(100% - 231px);
            box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.16);
            background: rgb(255, 255, 255);
            min-height: 600px;
            border-radius: 0px 8px 8px 8px;
        }

        #ccgclientportal-content .nav-tabs > li {
            float: none !important;
            margin-bottom: 3px !important;
        }

        #portal-cotenier h4.instruction_title {
            padding: 12px 15px;
            color: rgb(0, 145, 216);
            margin-top: 0px;
            font-size: 22px;
        }

    .cp_ins_img_main {
        margin: 35px 0px;
        text-align: center;
    }

#ccgclientportal-content .ccgclient-portal .takeheadingDomain {
    margin-top: 15px;
    margin-bottom: 20px;
    padding: 30px;
    background: #fff;
    box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.16);
    border-radius: 10px;
}
    </style>

    <div class="ccgclient-portal">
       <h3 class="page-heading">Zoho Connections  <a class='btn btn-primary instruction' ><i class="fas fa-info-circle"></i> Instructions</a></h3>

<script type="text/javascript">
    jQuery(document).ready(function(){
         jQuery(document).on('click', '.instruction', function() {
        
            jQuery('#instructionApi').modal('show');

        });
    });
</script>

    <div id="instructionApi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div id='instruction_content' style='padding: 9px;background:#fff;width: fit-content;margin-left: 35%;margin-top: 80px;'>

            <div >
                <div class="modal-body">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/n7D65hG3bYU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="clr"></div>
            </div>

        </div>
    </div>

        <div class="clr"></div>


    <div id="portal-cotenier" class="takeheadingDomain">

        <?php if(isset($uduss) && $uduss == true){ ?>

            <div class="alert alert-dismissible alert-success">

              <button type="button" class="close" data-dismiss="alert">&times;</button>

              Domain update successfully

            </div>

        <?php } ?>

        <?php if(isset($uderror) && $uderror == true){ ?>

            <div class="alert alert-dismissible alert-danger">

              <button type="button" class="close" data-dismiss="alert">&times;</button>

              Domain does not update. Please try again later.

            </div>

        <?php } ?>



        <form action="" method="post">

            <input type="hidden" name="license" value="">

            <?php 
                wp_nonce_field('save_domain_type','csrf_token_nonce');
            ?>


            <div class="form-group  col-lg-9  col-sm-9" style="margin-bottom: 0px;">

                <label class="col-md-4  col-sm-4 control-label" >Choose your domain type</label>

                <div class="col-md-8 col-sm-8">

                    <select class="form-control " name="domain" required>

                        <?php if(isset($resdomain->option_value)) $udomain = $resdomain->option_value; else $udomain = ""; ?>
                        
                        <option value="">--Select One--</option>

                        <option <?php echo ($udomain == ".com") ? "selected" : ""; ?> value=".com">https://zoho.com</option>

                        <option <?php echo ($udomain == ".com.au") ? "selected" : ""; ?> value=".com.au">https://zoho.com.au</option>

                        <option <?php echo ($udomain == ".eu") ? "selected" : ""; ?> value=".eu">https://zoho.eu</option>

                        <option <?php echo ($udomain == ".in") ? "selected" : ""; ?> value=".in">https://zoho.in</option>

                        <option <?php echo ($udomain == ".com.cn") ? "selected" : ""; ?> value=".com.cn">https://zoho.com.cn</option>



                    </select>

                </div>

                <div class="clr"></div>



            </div>

            <div class="form-group col-lg-3  col-sm-3 buttonDomain" style="margin-bottom: 0px;">

                <input type="submit" class="btn btn-primary newSubmitBtn" name="save_domain_type" value="Update" style="float: left;border-radius:8px !important">        

            </div>

            <div class="clr"></div>



        </form>

        <div class="clr"></div>

    </div>

    <div class="clr"></div>



        <div id="portal-cotenier">

            <div id="zohocrmapisettings" style="margin-right: 0px;">
                <?php if(isset($upssc) && $upssc==true){ ?>
                <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php } ?>
                <?php if(isset($error)){ ?>
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                  Data does not update. Please try again later.
                </div>
                <?php } ?>
                <?php if($gact){ ?>
                <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <?php echo $accsscmsg;?>.
                </div>
                <?php } ?>

                <div class="left-tab">

                    <ul class="nav nav-tabs tab-basic" style="border-bottom: none;padding-left: 0px;    border-bottom-left-radius: 10px;">
                    <?php
                    //ccgclient-portal-free
                    
                    if(isset($auth->client_id)){
                        $crmcnctd = true;
                    }else{
                        $crmcnctd = false;
                    }
                    ?>
                    <li class="nav-item active">
                        <a class="nav-link active" data-toggle="tab" id="tab-crmapiinformation" href="#crmapiinformation">
                            <?php if(!isset($auth->id)){ ?>
                                <span class="badge badge-pill incomplete">Incomplete</span><div class="clr"></div>
                            <?php } ?>
                            <!-- <img src="<?php //echo $pluginUrl ?>/assets/images/icon/favicon_crm.ico" style="height: 18px;"> CRM -->
                            
                            <img src="<?php echo esc_url( plugins_url( 'assets/images/icon/favicon_crm.ico', dirname(dirname(__FILE__)) ) ) ?>" style="height: 18px;"> CRM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <img src="<?php echo esc_url( plugins_url( 'assets/images/icon/favicon_books.ico', dirname(dirname(__FILE__)) ) ) ?>" style="height: 18px;"> Books <span class="badge badge-pill">PRO</span>
                            <div class="clr"></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <img src="<?php echo esc_url( plugins_url( 'assets/images/icon/favicon_desk.png', dirname(dirname(__FILE__)) ) ) ?>" style="height: 18px;"> Desk <span class="badge badge-pill">PRO</span>
                            <div class="clr"></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <img src="<?php echo esc_url( plugins_url( 'assets/images/icon/favicon_sub.ico', dirname(dirname(__FILE__)) ) ) ?>" style="height: 18px;"> Subscriptions <span class="badge badge-pill">PRO</span>
                            <div class="clr"></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <img src="<?php echo esc_url( plugins_url( 'assets/images/icon/favicon_sign.ico', dirname(dirname(__FILE__)) ) ) ?>" style="height: 18px;"> ZohoSign Documents <span class="badge badge-pill">PRO</span>
                            <div class="clr"></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <img src="<?php echo esc_url( plugins_url( 'assets/images/icon/vault.ico', dirname(dirname(__FILE__)) ) ) ?>" style="height: 18px;"> Vault <span class="badge badge-pill">PRO</span>
                            <div class="clr"></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <img src="<?php echo esc_url( plugins_url( 'assets/images/icon/projects.ico', dirname(dirname(__FILE__)) ) ) ?>" style="height: 18px;"> Projects <span class="badge badge-pill">PRO</span>
                            <div class="clr"></div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <img src="<?php echo esc_url( plugins_url( 'assets/images/icon/workdrive-icon-20.png', dirname(dirname(__FILE__)) ) ) ?>" style="height: 18px;"> WorkDrive <span class="badge badge-pill">PRO</span>
                            <div class="clr"></div>
                        </a>
                    </li>
                    <div class="clr"></div>
                    </ul>

                </div>

                <div id="myTabContent" class="tab-content pd-t-30 pd-b-30 brd-top-off" style="border-top-right-radius: 10px;">
                    <div class="tab-pane fade active in" id="crmapiinformation" style="border-bottom: none;">
                        <?php include 'zohocrmapi/crmapiinformation.php'; ?>
                    </div>
                    <div class="clr"></div>
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function(){ 
            jQuery(document).on('click', '.copyScopeToClipboard', function(event) {
                var temp = jQuery("<input>");
                jQuery("body").append(temp);
                var scope = jQuery(this).closest('div.scope').find('input.ins_scope_input').val();
                temp.val(scope).select();
                document.execCommand("copy");
                temp.remove();
                jQuery(this).closest('div.scope').find(".copyedtoclipboard").text('Copied to clipboard');
                setTimeout(function () {
                    jQuery('div.scope').find(".copyedtoclipboard").text("");
                }, 2000);
            });
        });
    </script>

<?php 
include_once 'common/footer.php';
?>