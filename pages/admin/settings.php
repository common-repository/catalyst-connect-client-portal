<?php
    /**
     * Created by Mahidul Islam .
     */
    
    include_once 'common/header.php';
?>
<?php

    $plugin_dir = CCGP_PLUGIN_URL;

    $activeTab = '';
    $upssc = false;
    global $wpdb;
   
    $ajaxUrl = wp_nonce_url(site_url()."/wp-admin/admin-ajax.php","updatedefaultApperance","csrf_token_nonce"); 

    $SettingsClass = new CCGP_SettingsClass();

    if(isset($_POST['general-settings'])){
        $activeTab = "general-settings";
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'general-settings')){
            $upStt = $SettingsClass->updateSettingls($_POST);
            if($upStt) $upssc = true;
        }else{
            $uperr = false;
        }
    }
    if(isset($_POST['dashboardContent'])){
        $activeTab = "dashboardContent";
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'dashboardContent')){
            $upStt = $SettingsClass->updateOp_dashboardContent($_POST);
            if($upStt) $upssc = true;
        }else{
            $uperr = true;
        }
    }
    if(isset($_POST['dcLayoutupdate'])){
        $activeTab = "dashboardContent";
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'dcLayoutupdate')){
            $upStt = $SettingsClass->updateLayout_dashboardContent($_POST);
            if($upStt) $upssc = true;
        }else{
            $uperr = true;
        }
    }
    if(isset($_POST['ap_menuColor'])){
        $activeTab = "appearance";
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'ap_menuColor')){
            $upStt = $SettingsClass->updateap_menuColor($_POST);
            if($upStt) $upssc = true;
        }else{
            $uperr = true;
        }
    }
    if(isset($_POST['ap_btnColor'])){
        $activeTab = "appearance";
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'ap_btnColor')){

            $upStt = $SettingsClass->updateap_btnColor($_POST);
            if($upStt) $upssc = true;
        } else{
            $uperr = true;
        }
    }
    if(isset($_POST['ap_fontColor'])){
        $activeTab = "appearance";
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'ap_fontColor')){
            $upStt = $SettingsClass->updateap_fontColor($_POST);
            if($upStt) $upssc = true;
        }else{
            $uperr = true;
        }

    }
    if(isset($_POST['savewebtab'])){
        $activeTab = "webtab";
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'savewebtab')){            
            $upStt = $SettingsClass->updatewebtab($_POST);
            if($upStt) $upssc = true;
        }else{
            $uperr = false;
        }
    }


    $dsbOptions = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'dashboard'");
    foreach ($dsbOptions as $dbOval) {
      if($dbOval->option_index == 'sliderimages')$dbBnr = json_decode($dbOval->option_value, true);
      if($dbOval->option_index == 'aboutUstext')$dbOat = json_decode($dbOval->option_value, true);
      if($dbOval->option_index == 'dashboardiframe')$dsbif = json_decode($dbOval->option_value, true);
      if($dbOval->option_index == 'quickLink')$dbOql = json_decode($dbOval->option_value, true);
      if($dbOval->option_index == 'iframeembed')$dsbifem = json_decode($dbOval->option_value, true);
      if($dbOval->option_index == 'color')$dbColor = json_decode($dbOval->option_value, true);
      if($dbOval->option_index == 'Layout_dashboardContent')$dbcLayout = json_decode($dbOval->option_value, true);
    }

    $apData = array();
    $apData = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'appearance'");
    foreach ($apData as $apOval) {
      if($apOval->option_index == 'menucolor') $apMenC = json_decode($apOval->option_value, true);
      if($apOval->option_index == 'buttoncolor')$apbtnC = json_decode($apOval->option_value, true);
      if($apOval->option_index == 'font_color')$apfontC = json_decode($apOval->option_value, true);
    }

    $gnrlData = array();
    $gnrlData = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'general-settings'");
    foreach ($gnrlData as $gnrlOval) {
      if($gnrlOval->option_index == 'portal-main-menu') $prtlmm = $gnrlOval;
      if($gnrlOval->option_index == 'menuResponsive') $menuResponsive = $gnrlOval->option_value;
      if($gnrlOval->option_index == 'portalTitle')$portalTitle = $gnrlOval->option_value;
      if($gnrlOval->option_index == 'portalWidth')$portalWidth = $gnrlOval->option_value;
      if($gnrlOval->option_index == 'dealStages')$dealStages = $gnrlOval->option_value;
      if($gnrlOval->option_index == 'primarycolor')$prcolor = $gnrlOval->option_value;
      if($gnrlOval->option_index == 'showAccMngr')$showAccMngr = $gnrlOval->option_value;
      if($gnrlOval->option_index == 'webtab')$webtab = json_decode($gnrlOval->option_value, true);
    }

    $CPNzoho = new ZohoCrmRequest();
    $viewAbleFld = "";
    $accountsFld = $CPNzoho->getFieldsN('Accounts');
    if(isset($accountsFld->fields[0])){
        $accountsFlds = $accountsFld->fields;

        $ignorFieldType = array('lookup', 'ownerlookup','subform','multiselectlookup','profileimage');
        foreach ($accountsFlds as $key => $fldDtl) {
            if(!in_array($fldDtl->data_type, $ignorFieldType)){
                if($fldDtl->view_type->view == true){ 
                    $viewAbleFld .='<option value="'. $fldDtl->api_name .'___'. $fldDtl->field_label .'">'. $fldDtl->field_label .'</option>';
                }
            }
        }
    }

?>

<style type="text/css">
    .dashboardContentForm .form-group label input{float: left;}
    .dashboardContentForm .form-group label input[type=text]{margin-left: 5px; width: calc(100% - 50px);}

    /*#ccgclientportal-content #portal-cotenier .nav-tabs>li>a {
        border: 1px solid #0091d8;
        padding: 10px 20px;
        margin-right: 8px;
    }
    #portal-cotenier .nav-tabs {
        border-bottom: none;
        padding-left: 15px;
    }
    #ccgclientportal-content #portal-cotenier .dashboardContent-layout input[type=checkbox]{
        margin-top: 8px;
    }
    .data-save-message{
        padding: 12px 30px !important;
        border: solid 1px #ccc;
        position: fixed;
        right: 25px;
        text-align: center;
        background: #fff;   
        z-index: 111;
    }
    .data-save-message.error{
        box-shadow: 0px 0px 3px red;
        width: 210px;
    }
    .data-save-message.success{
        box-shadow: 0px 0px 3px #55c755;
        width: 180px;
    }

    .nav-item{
        margin-bottom: 0px !important;
    }
    #ccgclientportal-content #portal-cotenier .nav-tabs>li>a {

        border:none !important;
    }

 
    .active{
        border-bottom: 6px solid #064c6d;
    }

    #ccgclientportal-content .nav-tabs>li.active>a, #ccgclientportal-content .nav-tabs>li.active>a:focus, #ccgclientportal-content .nav-tabs>li.active>a:hover {
        box-shadow: none;
        color: #064c6d;
        background-color: #0091d800;
        font-weight: 700;
        font-size: 15px;
    }

    .nav-link {
        font-size: 15px;
    }

    .nav-tabs{
        box-shadow: 0px 5px 10px -6px;
        padding-left: 10px;
    }*/

     input[type=text] {
        height: 35px !important;
    }

        /*.crm-module-list {
            padding: 8px 15px !important;
            border: solid 1px #696565;
            border-radius: 8px;
            box-shadow: 0px 0px 6px -2px rgb(228, 228, 228);
        }*/


        /*new*/
        #portal-cotenier .nav-tabs>li>a {
            /*border: 1px solid #0091d8;*/
            padding: 10px 20px;
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
            padding-top: 5px;
        }
        #ccgclientportal-content .badge{
            background-color: #FEA147;
        }

   
</style>
<div class="data-save-message success" style="display: none;color: #1fc11f;position: absolute;margin-left: 100px;">
    <i class="far fa-check-circle" style="font-size: 30px;float: left;"></i>
    <font style="font-size: 24px;float: left;margin: 4px 0px 0px 10px;">Saved</font>    
</div>
<div class="data-save-message error" style="display: none;color: red;position: absolute;margin-left: 100px;">
    <i class="far fa-times-circle" style="font-size: 30px;float: left;"></i>
    <font style="font-size: 24px;float: left;margin: 4px 0px 0px 10px;">Not Save</font>    
</div>
<div class="ccgclient-portal admin-settings">

    <h3 class="page-heading">Settings</h3>
    <div class="clr"></div>    

    <div id="portal-cotenier" class="portal-setting-header" style="">

        <?php if($upssc){ ?>
            <div class="alert alert-dismissible alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <!-- <strong>Well done!</strong> --> Update completed successfully </a>.
            </div>
        <?php } ?>

        <?php if(isset($uperr)){ ?>
            <div class="alert alert-dismissible alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
                Token Expired
            </div>
        <?php } ?>


        <ul class="nav nav-tabs">
                
            <li class="nav-item active">
                <a class="nav-link active" data-toggle="tab" id="tab-general-settings" href="#general-settings">                    
                    General
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" id="tab-dashboardContent" href="#dashboardContent">                    
                    Dashboard Content
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" id="tab-appearance" href="#appearance">                    
                    Appearance
                </a>
            </li>
            <!--li class="nav-item">
                <a class="nav-link" data-toggle="tab" id="tab-webtab" href="#webtab">                    
                    Web Tab
                </a>
            </li-->
            <div class="clr"></div>
        </ul>


        <div id="myTabContent" class="tab-content pd-t-30 pd-b-30 brd-top-off">

            <div class="tab-pane fade active in" id="general-settings" style="border-bottom: none">
                <?php include 'settings/general-settings.php'; ?>
            </div>
            
            <div class="tab-pane fade" id="dashboardContent" style="border-bottom: none">
                <?php include 'settings/dashboardContent.php'; ?>
            </div>
            
            <div class="tab-pane fade" id="appearance" style="border-bottom: none">
                <?php include 'settings/appearance.php'; ?>
            </div>
            
            <div class="tab-pane fade" id="webtab" >
                <?php include 'settings/webtab.php'; ?>
            </div>
        </div>



    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){ 
        jQuery('#tab-<?php echo $activeTab; ?>').click();
    });
</script>

<link rel="stylesheet" type="text/css" href="<?php echo esc_url($pluginUrl) ?>assets/css/plugin/dragula/dist/dragula.min.css">
<script src="<?php echo esc_url($pluginUrl) ?>assets/css/plugin/dragula/dist/dragula.min.js"></script>





<script type="text/javascript">
    jQuery(document).ready( function () {
        var layoutChange = false;

        function setonbeforeunload() {

            var message = "You have not filled out the form.";
            window.onbeforeunload = function(event) {
                var e = e || window.event;
                if (e) {
                    e.returnValue = message;
                }
                return message;
            };
            
        }
        jQuery(document).on('click', '.dcLayoutupdate', function(event) {
            window.onbeforeunload = function(event) {
                // blank function do nothing
            };
        });



        var drake = window.dragula();

        function setupDragula2(){
            drake.destroy();

            drake = dragula([document.getElementById("layoutColumnContainer")], {
              // revertOnSpill: true
            }).on('drop', function(el) {
              setonbeforeunload();
            });

            
        }
        setupDragula2();


        jQuery(document).on('click', '.makeCol12', function(event) {
            jQuery(this).parent().parent().parent('.layoutColumn').removeClass('col-md-6');
            jQuery(this).parent().parent().parent('.layoutColumn').addClass('col-md-12');
            jQuery(this).parent().parent().parent('.layoutColumn').find('.layoutFldColumn').val('12');
            jQuery(this).next('.show6col').show();
            // jQuery(this).previous('.show12col').show();
           // console.log(gethtml);
            jQuery(this).hide();
            setonbeforeunload();
        });

         jQuery(document).on('click', '.show6col', function(event) {
            jQuery(this).parent().parent().parent('.layoutColumn').removeClass('col-md-12');
            jQuery(this).parent().parent().parent('.layoutColumn').addClass('col-md-6'); 
            jQuery(this).parent().parent().parent('.layoutColumn').find('.layoutFldColumn').val('6'); 
            jQuery(this).prev('.makeCol12').show();
            // jQuery(this).previous('.show12col').show();
           // console.log(gethtml);
            jQuery(this).hide();
            setonbeforeunload();
        });


        jQuery(document).on('click', '.makeCol6', function(event) {
           //var gethtml = jQuery(this).parent().parent().parent('.layoutColumn').attr('class');
            // console.log(gethtml);
            jQuery(this).parent().parent().parent('.layoutColumn').removeClass('col-md-12');
            jQuery(this).parent().parent().parent('.layoutColumn').addClass('col-md-6'); 
            jQuery(this).parent().parent().parent('.layoutColumn').find('.layoutFldColumn').val('6'); 
            jQuery(this).next('.show12col').show();
            // jQuery(this).previous('.show6col').show();
            jQuery(this).hide();
            setonbeforeunload();
        });

         jQuery(document).on('click', '.show12col', function(event) {
            jQuery(this).parent().parent().parent('.layoutColumn').removeClass('col-md-6');
            jQuery(this).parent().parent().parent('.layoutColumn').addClass('col-md-12');
            jQuery(this).parent().parent().parent('.layoutColumn').find('.layoutFldColumn').val('12');
            jQuery(this).prev('.makeCol6').show();
            // jQuery(this).previous('.show6col').show();
            jQuery(this).hide();
            setonbeforeunload();
        });
    } );

</script>



<script type="text/javascript">
    jQuery(document).ready(function($) {

        
        jQuery(document).on('focus', 'input.upload-btn', function(event) {
            jQuery(".image_url").addClass('acinput');
        }); 
        jQuery(document).on('focus', 'input.form-control', function(event) {
            jQuery(this).addClass('acinput');
        }); 
        jQuery(document).on('focus', 'select.form-control', function(event) {
            jQuery(this).addClass('acinput');
        });
        jQuery(document).on('focus', 'textarea.form-control', function(event) {
            jQuery(this).addClass('acinput');
        });

        jQuery(document).on('change', '.acinput', function(event) {
            jQuery(this).addClass('updatedata');
        });

        function updatedata(this_) {
            var formid = jQuery(this_).closest('form').find('.formid').val();
            // console.log(formid);

            if (typeof formid === "undefined") {
                return false ;
            }else {

                jQuery.ajax({
                    type:'POST',
                    data : $('#'+formid).serialize() + "&for="+formid+"&action=ccgpp_autosave",
                    url: "<?php echo esc_url(site_url()); ?>/wp-admin/admin-ajax.php",
                    success: function(result) {
                        // console.log(result.trim());
                        var res = result.trim();
                        if (res == 'save') {
                            var tmpClass = 'tmpId_'+parseInt(Math.random()*1000000000, 10);
                            jQuery('.data-save-message.success').attr("id","");
                            jQuery('.data-save-message.success').attr("id",tmpClass);

                            jQuery('.data-save-message.success').hide();
                            jQuery('.data-save-message.success').slideDown( "slow" );
                            // jQuery('.data-save-message').show();
                            setTimeout(function() { 
                                jQuery('#'+tmpClass).hide();
                                jQuery('#'+tmpClass).attr("id","");
                            }, 4000);
                        }else{
                            var tmpClass = 'tmpId_'+parseInt(Math.random()*1000000000, 10);
                            jQuery('.data-save-message.error').attr("id","");
                            jQuery('.data-save-message.error').attr("id",tmpClass);

                            jQuery('.data-save-message.error').hide();
                            jQuery('.data-save-message.error').slideDown( "slow" );
                            setTimeout(function() { 
                                jQuery('#'+tmpClass).hide();
                                jQuery('#'+tmpClass).attr("id","");
                            }, 4000);
                        }
                    }
                });
            }
        }
        jQuery(document).on('focusout', '.updatedata', function(event) {
            updatedata(jQuery(this));
        });

        jQuery(document).on('click', '.updatedefaultApperance', function(event) {

            jQuery.ajax({
                type:'POST',
                data : "for=updatedefaultApperance&action=ccgpp_autosave",
                url: "<?php echo $ajaxUrl ?>",
                success: function(result) {
                    console.log(result.trim());
                    var res = result.trim();
                    if (res == 'save') {
                        var tmpClass = 'tmpId_'+parseInt(Math.random()*1000000000, 10);
                        jQuery('.data-save-message.success').attr("id","");
                        jQuery('.data-save-message.success').attr("id",tmpClass);

                        jQuery('.data-save-message.success').hide();
                        jQuery('.data-save-message.success').slideDown( "slow" );
                        // jQuery('.data-save-message').show();
                        window.location.reload(true);
                        setTimeout(function() { 
                            jQuery('#'+tmpClass).hide();
                            jQuery('#'+tmpClass).attr("id","");
                        }, 4000);
                    }else{
                        var tmpClass = 'tmpId_'+parseInt(Math.random()*1000000000, 10);
                        jQuery('.data-save-message.error').attr("id","");
                        jQuery('.data-save-message.error').attr("id",tmpClass);

                        jQuery('.data-save-message.error').hide();
                        jQuery('.data-save-message.error').slideDown( "slow" );
                        setTimeout(function() { 
                            jQuery('#'+tmpClass).hide();
                            jQuery('#'+tmpClass).attr("id","");
                        }, 4000);
                    }
                }
            });
            
        });
    

        $('.upload-btn').click(function(e) {
            var ubtn = $(this);
            e.preventDefault();
            var image = wp.media({ 
                title: 'Upload Image',
                // mutiple: true if you want to upload multiple files at once
                multiple: false
            }).open()
            .on('select', function(e){
                // This will return the selected image from the Media Uploader, the result is an object
                var uploaded_image = image.state().get('selection').first();
                // We convert uploaded_image to a JSON object to make accessing it easier
                // Output to the console uploaded_image
                var image_url = uploaded_image.toJSON().url;
                // Let's assign the url value to the input field
                ubtn.closest('.sImageCon').find('.image_url').val(image_url);
                ubtn.closest('.sImageCon').find('.image_url').focus();
                ubtn.closest('.sImageCon').find('.image_url').trigger('change');
            });
        });
    });
</script>

<?php 
    include_once 'common/footer.php';
?>