<?php
/**
 * Created by Mahidul Islam .
 */
    
include_once 'common/header.php';

$plugin_dir = CCGP_PLUGIN_URL;
$activeTab = '';
$upssc = false;
global $wpdb;   
?>

<div class="ccgclient-portal admin-settings">

    <style type="text/css">
        .ccg_instruction_page .tab-content{
            width: 100%;
            padding: 35px 0px;
        }
        /*#portal-cotenier.ccg_instruction_page .nav-tabs>li>a{
            margin-right: 8px;
            border: 1px solid #0091d8;
            padding: 10px 20px;
        }*/
        /*#portal-cotenier.ccg_instruction_page .nav-tabs {
            border-bottom: 1px solid #0091d8;
        }*/
        .nav-tabs.tab-basic > li.nav-item > a.nav-link{
            padding: 10px;
        }
        .nav-tabs>li>a.disable:focus{
            box-shadow: none;
        }
        .nav-tabs>li>a.disable {
            border: 1px solid #ccc;
            background: #dcdcdc;
        }
        .nav-tabs>li {
            text-align: center;
        }
        #portalpagesetup .far.fa-copy{
            padding: 6px 8px;
            border: solid 1px #ccc;
            cursor: pointer;
        }
        .nav-item .badge.incomplete{
            margin-top: -20px;
            font-size: 10px;
            position: absolute;
            margin-left: -39px;        
            background: #fff;
            border: solid 1px #ff8d00;
            color: #ff8d00;
        }
        /*new*/
        
        #ccgclientportal-content .nav-tabs > li {
            text-align: center;
            padding: 10px;
            padding-right: 0;
        }
        #ccgclientportal-content .nav-tabs>li>a {
            /*border: 1px solid #0091d8;*/
            /*padding: 10px 20px;*/
        }
        #ccgclientportal-content .vertical-tab .nav-tabs.tab-basic > li.nav-item > a.nav-link{
            padding: 10px;
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
        


        #portal-cotenier .nav-tabs>li>a {
            /*border: 1px solid #0091d8;*/
            padding: 3px 10px;
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
        #ccgclientportal-content .nav>li>a:focus, #ccgclientportal-content .nav>li>a:hover {
            border-bottom: 0;
        }
        #ccgclientportal-content .nav-tabs > li.active{
            border-bottom: 6px solid #064c6d;
        }
        .cp_ins_img_main{
            margin: 20px 0px;
        }
        .cp_ins_img_main .cp_ins_img {
            border-radius: 0;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: none;
            width: 100%;
            max-width: 500px;
            border: 1px solid #707070;
        }
        .cl-blue{
            color: #3B72A2;
        }
        .ins_nb {
            color: #FF8384;
        }

    




    </style>


   <h3 class="page-heading">Info <!-- <a class='btn btn-primary instruction' href="#instructionIns"><i class="fas fa-info-circle"></i> Instructions</a> --></h3>
    <div class="clr"></div>  

    <div class="clr" style="height: 20px;"></div> 
    <!-- <a href="<?php echo admin_url(); ?>admin.php?page=ccgpp-instructions&action=releaseNotes" class="btn btn-primary add-new headerButton <?php if(isset($_GET['action']) && $_GET['action'] == 'releaseNotes') echo 'activeUser' ?> " > Release Notes</a> -->
    <a href="https://catalystconnect.com/privacy-policy" target="_blank" class="btn btn-primary add-new headerButton "> Privacy Policy</a>
    <a href="https://catalystconnect.com/terms-of-service/" target="_blank" class="btn btn-primary add-new headerButton" > Terms of Service</a>
    <a href="<?php echo admin_url(); ?>admin.php?page=ccgpp-instructions&action=error" class="btn btn-primary add-new headerButton <?php if(isset($_GET['action']) && $_GET['action'] == 'error') echo 'activeUser' ?>"> Error Log</a>


<?php if(isset($_GET['action']) && $_GET['action'] == 'releaseNotes') { ?>

            <div id="portal-cotenier" class="ccg_instruction_page portal-setting-header">

                    <div  class="tab-pane" id="releaseNotes" style="border-bottom: none">
                       

                        <div class="ccgclient-portal error-log">

                            <iframe src="https://analytics.zoho.com/open-view/971413000006724051/2e8fdeba328524036c11a8373ea982f1" style="width: 100%;height: 100vh;"></iframe>
                            
                        </div>

                    </div>


            </div>

  <?php } elseif(isset($_GET['action']) && $_GET['action'] == 'error') { ?>

                <div  class="tab-pane" id="errorlog" style="border-bottom: none">
                        <?php

                            $plugin_dir = WP_PLUGIN_URL . '/ccgclient-portal/';

                            global $wpdb;
                            $options = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'errorLog' ORDER BY id DESC");
                            
                        ?>


                        <div class="ccgclient-portal error-log">

                            <!-- <h3 class="page-heading">Error Log</h3> -->
                            <div class="clr"></div>    

                            <div id="portal-cotenierss">
                                <div class="col-md-12">
                                    <table class="datatable table table-hover contacts-list" style="    padding-top: 15px;">
                                        <thead>
                                            <tr>
                                                <th style="border-top-left-radius: 10px;">Application</th>
                                                <th>Message</th>
                                                <th style="border-top-right-radius: 10px;">Create Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php if($options){
                                                foreach ($options as $key => $option) { ?>
                                                    <tr>
                                                        <td><?php 
                                                            if($option->option_index == "crm") echo "Zoho CRM"; 
                                                            elseif($option->option_index == "subs") echo "Zoho Subscription"; 
                                                            elseif($option->option_index == "desk") echo "Zoho Desk"; 
                                                            elseif($option->option_index == "books") echo "Zoho Books"; 
                                                            elseif($option->option_index == "projects") echo "Zoho Projects"; 
                                                            elseif($option->option_index == "workdrive") echo "Zoho Workdrive"; 
                                                            elseif($option->option_index == "vault") echo "Zoho Vault"; 
                                                        ?></td>
                                                        <td><?php echo $option->option_value; ?></td>
                                                        <td><?php echo $option->create_time; ?></td>
                                                    </tr>
                                                <?php }
                                            } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>  
                                <div class="clr"></div>

                            </div>
                        </div>

                     </div>


                 <?php } else { ?>


    <div id="portal-cotenier" class="ccg_instruction_page">
        <div style="text-align: center;"></div>
        <div>
        <!-- tabs -->
            <div class="tabbable tabs-top" >
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#portalpagesetup" data-toggle="tab">
                        <i class="fas fa-cogs" style="font-size: 18px;"></i> Portal Page Setup
                    </a></li>
                    <li><a href="#crmapiinformation" data-toggle="tab">
                        <img src="<?php echo $pluginUrl ?>assets/images/icon/favicon_crm.ico" style="height: 18px;"> CRM
                    </a></li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <!-- <span class="badge badge-pill incomplete">Pro Version</span><div class="clr"></div> -->
                            <img src="<?php echo $pluginUrl ?>assets/images/icon/favicon_books.ico" style="height: 18px;"> Books <span class="badge badge-pill">PRO</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <!-- <span class="badge badge-pill incomplete">Pro Version</span><div class="clr"></div> -->
                            <img src="<?php echo $pluginUrl ?>assets/images/icon/favicon_desk.png" style="height: 18px;"> Desk <span class="badge badge-pill">PRO</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                           <!--  <span class="badge badge-pill incomplete">Pro Version</span><div class="clr"></div> -->
                            <img src="<?php echo $pluginUrl ?>assets/images/icon/favicon_sub.ico" style="height: 18px;"> Subscriptions <span class="badge badge-pill">PRO</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <!-- <span class="badge badge-pill incomplete">Pro Version</span><div class="clr"></div> -->
                            <img src="<?php echo $pluginUrl ?>assets/images/icon/favicon_sign.ico" style="height: 18px;"> ZohoSign Documents <span class="badge badge-pill">PRO</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <!-- <span class="badge badge-pill incomplete">Pro Version</span><div class="clr"></div> -->
                            <img src="<?php echo $pluginUrl ?>assets/images/icon/vault.ico" style="height: 18px;"> Vault <span class="badge badge-pill">PRO</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disable" href="javascript:void(0)">
                            <!-- <span class="badge badge-pill incomplete">Pro Version</span><div class="clr"></div> -->
                            <img src="<?php echo $pluginUrl ?>assets/images/icon/projects.ico" style="height: 18px;"> Projects <span class="badge badge-pill">PRO</span>
                        </a>
                    </li>
                    <div class="clr"></div>
                </ul>
                <div class="tab-content pd-30 brd-top-off">
                    <div class="tab-pane active" id="portalpagesetup">  

                        <div class="col-md-6" style="font-size: 14px; padding-left: 0px">
                            Please copy and paste this shortcode on the page you would like your portal to display on.</br></br>
                            <input type="text" value="[ccgclient_portal_free]" class="cp_bold" style="width: 80%;height: 34px !important;border: 1px solid #004887;border-radius: 10px;"> <span class="copyScops btn btn-primary copyshortcode" onclick="copyToClipboard()">Copy</span><br>
                            <p id="copyedtoclipboard" style="color: #00c100;"></p>

                            <script>
                                function copyToClipboard() {
                                    var $temp = jQuery("<input>");
                                    jQuery("body").append($temp);
                                    $temp.val("[ccgclient_portal_free]").select();
                                    document.execCommand("copy");
                                    $temp.remove();
                                    jQuery("#copyedtoclipboard").text('Copied to clipboard');
                                    setTimeout(function () {
                                        jQuery("#copyedtoclipboard").text('');
                                    }, 2000);
                                }
                            </script>


                            <div class="clr" style="height: 20px"></div>
                            <!-- <div>
                                <a class="btn btn-primary" style="font-size: 18px;padding: 10px 35px;" target="_balnk" href="https://portal-plugin.thecatalystcloud.com/api/v1/uploads/WordPress%20Client%20Portal%20Plugin%20Instrucations.pdf"><img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-05.png" style="width: 22px;"> Documentation </a>
                            </div>
                            <div class="clr"></div> -->


                        </div>
                        <div class="col-md-6" style="padding-right: 0px;">
                            <iframe style="width: 100%; height: 500px;box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.16);" src="https://www.youtube.com/embed/9BfQhPOHVJI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
                        </div>
                        <div class="clr"></div>
                        
                    </div>
                    <div class="tab-pane" id="crmapiinformation">                
                        <div class="">
                            <div >
                                <h4><font class="cl-blue">1.</font>&nbsp;Navigate to <a href="https://accounts.zoho.com/developerconsole" target="_balnk" class="cp_bold">https://accounts.zoho.com/developerconsole</a> and click on the button that says <font class="cp_bold">Add Client ID</font>.</h4> 
                                <p class="cp_bold">When Prompted enter the following Values:</p>
                                <p><font class="cp_bold">Client Name:</font> WP Client Portal</p>
                                <p><font class="cp_bold">Client Domain:</font> https://yourwebsitegoeshere.com</p>
                                <p><font class="cp_bold">Authorized redirect URLs:</font> https://yourwebsitegoeshere.com</p>
                                
                                <div class="cp_ins_img_main">
                                    <img class="cp_ins_img" src="https://portal-plugin.thecatalystcloud.com/api/v1/plugin_instructions/add_client_id.png" alt="Add Client ID">
                                    <div class="cp_ins_img_modal">
                                        <span class="ins_close">&times;</span>
                                        <img class="ins_modal_content" >
                                        <div class="ins_caption"></div>
                                    </div>
                                </div>

                                <h4><font class="cl-blue">2.</font>&nbsp;To get the code, please see next step.</h4>
                                <div class="cp_ins_img_main">
                                    <img class="cp_ins_img" src="https://portal-plugin.thecatalystcloud.com/api/v1/plugin_instructions/plugin_connection.png" alt="See Next Step">
                                    <div class="cp_ins_img_modal">
                                        <span class="ins_close">&times;</span>
                                        <img class="ins_modal_content" >
                                        <div class="ins_caption"></div>
                                    </div>
                                </div>


                                <h4><font class="cl-blue">3.</font>&nbsp;In the client list click the 3 dots to open the menu and select <font class="cp_bold">“Self Client”</font> </h4>
                                <div class="cp_ins_img_main">
                                    <img class="cp_ins_img" src="https://portal-plugin.thecatalystcloud.com/api/v1/plugin_instructions/self_client.png" alt="Select Self Client">
                                    <div class="cp_ins_img_modal">
                                        <span class="ins_close">&times;</span>
                                        <img class="ins_modal_content" >
                                        <div class="ins_caption"></div>
                                    </div>
                                </div>


                                <h4 style="width: 30%;float: left;"><font class="cl-blue">4.</font>&nbsp;Enter the following in the scope field: </h4>

                                <div style="width: 60%;float: left;margin-left: 10px;margin-right: 10px;">
                                    <p class="cp_bold ins_scope" style="border: 1px solid #004887;margin:0px;border-radius: 10px;">ZohoCRM.modules.all,ZohoCRM.settings.all,ZohoCRM.users.all,ZohoCRM.bulk.read,ZohoCRM.mo...</p>
                                    <span class="ins_nb" style="float: right;">NB: Set the Expiry to 10 minutes</span>
                                </div>
                                <div style="width: 8%;float: left; text-align: center;">
                                    <span class=" btn btn-primary " onclick="copyScopeToClipboard()">Copy</span><br>
                                    <p id="copyedsctoclipboard" style="color: #00c100;"></p>

                                    <script>
                                        function copyScopeToClipboard() {
                                            var $temp = jQuery("<input>");
                                            jQuery("body").append($temp);
                                            $temp.val("ZohoCRM.modules.all,ZohoCRM.settings.all,ZohoCRM.users.all,ZohoCRM.bulk.read,ZohoCRM.modules.notes.all,ZohoCRM.settings.roles.all,ZohoCRM.settings.profiles.all,ZohoCRM.org.all").select();
                                            document.execCommand("copy");
                                            $temp.remove();
                                            jQuery("#copyedsctoclipboard").text('Copied to clipboard');
                                            setTimeout(function () {
                                                jQuery("#copyedsctoclipboard").text('');
                                            }, 2000);
                                        }
                                    </script>
                                </div>
                                <div class="clr"></div>

                                <div class="cp_ins_img_main">
                                    <img class="cp_ins_img" src="https://portal-plugin.thecatalystcloud.com/api/v1/plugin_instructions/give_scope.png" alt="Self Client">
                                    <div class="cp_ins_img_modal">
                                        <span class="ins_close">&times;</span>
                                        <img class="ins_modal_content" >
                                        <div class="ins_caption"></div>
                                    </div>
                                </div>


                                <h4><font class="cl-blue">5.</font>&nbsp;A popup will display with the Code to be generated for the CRM. Copy this value and paste it in the code field for CRM.</h4>
                                <div class="cp_ins_img_main">
                                    <img class="cp_ins_img" src="https://portal-plugin.thecatalystcloud.com/api/v1/plugin_instructions/zoho_code.png" alt="Code">
                                    <div class="cp_ins_img_modal">
                                        <span class="ins_close">&times;</span>
                                        <img class="ins_modal_content" >
                                        <div class="ins_caption"></div>
                                    </div>
                                </div>
                                <p class="ins_nb">NB: Make sure you click update after you have entered the code. It is normal for the code to disappear after you have clicked update. </p>


                                <h4><font class="cl-blue">6.</font>&nbsp;You can then copy and paste the values into the fields below. To get the <font class="cp_bold">Organization ID</font> go to <a href="https://crm.zoho.com" target="_balnk">https://crm.zoho.com</a> and copy the org id from the URL, include the org prefix when pasting the Org ID.</h4>
                                <div class="cp_ins_img_main">
                                    <img class="cp_ins_img" src="https://portal-plugin.thecatalystcloud.com/api/v1/plugin_instructions/crm_org.png" alt="Organization ID">
                                    <div class="cp_ins_img_modal">
                                        <span class="ins_close">&times;</span>
                                        <img class="ins_modal_content" >
                                        <div class="ins_caption"></div>
                                    </div>
                                </div>


                            </div>              
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="clr"></div>

                </div>
            </div>
        <!-- /tabs -->
        </div>
        
        <!-- <div class="clr" style=""><hr></div>
        <div>
            <a class="btn btn-primary" style="font-size: 18px;padding: 15px;width: 250px;" target="_balnk" href="https://portal-plugin.thecatalystcloud.com/api/v1/uploads/WordPress%20Client%20Portal%20Plugin%20Instrucations.pdf">Download Documentation </a>
        </div>
        <div class="clr"></div> -->
    </div>
    <div class="clr"></div>
</div> 

 <?php } ?> 

<script type="text/javascript">
    jQuery(document).ready( function () {

        jQuery(document).on('click', '.cp_ins_img', function() {
            var cp_ins_img_modal = jQuery(this).closest('.cp_ins_img_main').find('.cp_ins_img_modal').css("display", "block");
            jQuery(this).closest('.cp_ins_img_main').find('.ins_modal_content').attr('src', jQuery(this).attr('src'));
            jQuery(this).closest('.cp_ins_img_main').find('.ins_caption').html(jQuery(this).attr('alt'));
        });

        jQuery(document).on('click', '.ins_close', function() {
            jQuery(this).closest('.cp_ins_img_modal').css("display", "none");
        });

    });
</script>
<?php 
include_once 'common/footer.php';
?>