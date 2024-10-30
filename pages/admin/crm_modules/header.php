
<style type="text/css">
    
    #portal-cotenier #crm_modules .nav-tabs>li>a {
        /*border: 1px solid #0091d8;*/
        padding: 10px 20px;
    }
    #portal-cotenier #crm_modules .nav-tabs>li>a {
        margin-right: 0px;
        margin-bottom: 3px;
        font-size: 14px;
        font-weight: 700;
        border-bottom: 6px solid #064c6d00;
    }
    #portal-cotenier  #crm_modules .nav-tabs {
        border-bottom: 0px;
        box-shadow: -4px 10px 5px -7px rgba(4, 4, 4, 0.19);
        background: #fff;
        border-radius: 10px 10px 0px 10px;
        position: relative;
        padding-left: 0px;

    }
    .pointer-none{
        pointer-events: none;
    }
    .extraBarAdd{
        /* margin:-10px !important; */
        margin: 0px !important;
    }

#zohocrmapisettings .nav-tabs>li {
    text-align: left;
    padding: 10px;
    padding-left: 0px;
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
    float: left;
    margin-bottom: 3px;
    min-width: 100%;
}

#ccgclientportal-content .brd-top-off {
    border-radius: 0px 10px 10px 0px;
}

#portal-cotenier #crm_modules .nav-tabs>li>a:focus, #portal-cotenier #crm_modules .nav-tabs>li>a:hover {
    text-decoration: none;
    background-color: #FFFFFF;
    box-shadow: none;
    color: #004887;
    border: 0;
    border-bottom: 6px solid #064c6d;
}

#portal-cotenier #crm_modules .nav-tabs>li>.active{
       border-bottom: 6px solid #064c6d;
}

#ccgclientportal-content .btn {
    padding: 5px 8px;
}


</style>



<script type="text/javascript">
    var drake = window.dragula();

    function setupDragula(){
        drake.destroy();
        drake = dragula([document.getElementById("layoutFields")])
        .on('drag', function (el) {

        }).on('drop', function(el) {
            
        });
    }

    function setupDragula2(){
        drake.destroy();
        drake = dragula([document.getElementById("layoutColumnContainer")])
        .on('drag', function (el) {
            jQuery(".pointer-none").removeClass('pointer-none');
            jQuery(el).closest('.layoutColumnContainer').find(".movecolumn").addClass('pointer-none');
        }).on('drop', function(el) {
            jQuery(el).closest('.layoutColumnContainer').find(".pointer-none").removeClass('pointer-none');
        });
    }

    jQuery(document).ready( function () {

        jQuery('.layoutFields').on('mouseover', '.movesection', function(){
            jQuery(".pointer-none").removeClass('pointer-none');
            jQuery(this).closest('.layoutFields').attr('id', 'layoutFields');
            setupDragula();
        }).on("mouseout", function() {
            jQuery(this).closest('.layoutFields').attr('id', '');
        });

        jQuery('.layoutFields').on('mouseover', '.movecolumn', function(){
            jQuery(".pointer-none").removeClass('pointer-none');
            
            jQuery(this).closest('.layoutFields').find('.layoutColumnContainer').attr('id', '');
            jQuery(this).closest('.layoutColumnContainer').attr('id', 'layoutColumnContainer');            
            setupDragula2();
        }).on("mouseout", function() {
            jQuery(this).closest('.layoutFields').find('.layoutColumnContainer').attr('id', '');
        });

    } );

</script>

<?php
$CrmModules = new CrmModules();
$CPNzoho    = new ZohoCrmRequest();
$SttClass   = new CCGP_SettingsClass();

$acTab = 'selectModule';

if(isset($_GET['module']))$acTab = $_GET['module'];

if(isset($_POST['savewebtab'])){
    $activeTab = "webtab";
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'savewebtab')){
        $upStt = $SttClass->updatewebtab($_POST);
        if($upStt) $upssc = true;
    }else{
        $uperr = false;
    }
}

$wtres = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'general-settings' AND option_index = 'webtab'");
if(isset($wtres->option_index) && $wtres->option_index == 'webtab')$webtab = json_decode($wtres->option_value, true);

if(isset($_POST['syncFields'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'syncFields')){
        if($CrmModules->syncModuleField($_POST['module'])) $upssc = true;
        else $uperr = true;
    }else{
        $uperr = false;
    }
}
if(isset($_POST['useModules'])){

    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'useModules')){
        $organized_data = array();

        if( isset( $_POST['data'] ) ){
            foreach ($_POST['data'] as $key => $value) {
                $organized_data[$key] = $value;
            }
            unset( $_POST['data'] );
        }

        foreach ($_POST as $key => $value) {
            $organized_data[$key] = $value;
        }

        if($CrmModules->update_useModules($organized_data)) $upssc = true;
        else $uperr = true;
    }else{
        $uperr = true;
    }


}
if(isset($_POST['showPermission'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'showPermission')){
        if($CrmModules->update_Module_sp($_POST)) $upssc = true;
        else $uperr = true;
    }else{
        $uperr = true;
    }
}
if(isset($_POST['listTableColumn'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'listTableColumn')){
        if($CrmModules->update_Module_listTableColumn($_POST)) $upssc = true;
        else $uperr = true;
    }else{
        $uperr = true;
    }
}
if(isset($_POST['actionPermission'])){
    
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'actionPermission')){
        if($CrmModules->update_Module_actionPermission($_POST)) $upssc = true;
        else $uperr = true;
    }else{
        $uperr = true;
    }
}
if(isset($_POST['moduleTitleDescription'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'moduleTitleDescription')){
        if($CrmModules->update_Module_TitleDescription($_POST)) $upssc = true;
        else $uperr = true;
    }else{
        $uperr = true;
    }
}
if(isset($_POST['updateLayout'])){
    if($CrmModules->update_Module_updateLayout($_POST)) $upssc = true;
    else $uperr = true;
}

if(isset($_POST['updateAllLayout'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'updateAllLayout')){
        if($CrmModules->update_Module_updateAllLayout($_POST)) $upssc = true;
        else $uperr = true;
    }else{
        $uperr = true;
    }    
}


if(isset($_POST['addFilter'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'addFilter')){
        if($CrmModules->addFilter($_POST)) $upssc = true;
        else $uperr = true;
    }else{
        $uperr = true;
    }    
}

$useMdlV = array();
$useModules = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index = 'useModules'");
if(isset($useModules->option_value)) $useMdlV = json_decode($useModules->option_value, true);

$tabOrder = array();
$ordrres = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'customization' AND option_index = 'taborder'");
if(isset($ordrres->option_value)) $tabOrder = json_decode($ordrres->option_value, true);

$ignoreArr = array();
?>


<script type="text/javascript">

    jQuery(document).ready( function () {

        function tabFormPost(argument) {
            console.log("update taborder");
            var form = jQuery("#crmModulesTabsForm");
            var fromData = form.serialize();
            var addintional = 'action=ccgpp_ajaxrequest&for=saveCustomizationTabOrder';
            var data_save = fromData + '&' + addintional;
            
            jQuery.ajax({
                type:'POST',
                data:data_save,
                url: "<?php echo site_url(); ?>/wp-admin/admin-ajax.php",
                success: function(response) {
                }
            });
        };



        var dragtab = window.dragula();
            dragtab.destroy();
            dragtab = dragula([document.getElementById("crmModulesTabs")])
            .on('drop', function (el) {
                tabFormPost();
            });;

        <?php if($tabOrder == null){ ?>
            setTimeout(function () { 
                tabFormPost();                
            }, 1000);
        <?php } ?>

    });


</script>

<div class="ccgclient-portal">
    <h3 class="page-heading">Customization</h3>
    <div class="clr"></div>

    <div id="portal-cotenier">

        <div id="crm_modules">

            <?php if(isset($upssc)){ ?>
                <div class="alert alert-dismissible alert-success">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  Update completed successfully </a>.
                </div>
            <?php } ?>
            <?php if(isset($uperr)){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  Data does not update. Please try again.
                </div>
            <?php } ?>

            <?php $admin_url = admin_url().'admin.php?page=customization&module=';?>


        <div class="left-tab">


          <form action="" method="post" id="crmModulesTabsForm">

            <ul class="nav nav-tabs crmModulesTabs" id="crmModulesTabs">

                <?php
                $crmModuleMenu = array();
                foreach ($useMdlV as $key => $value) {
                    if(!in_array($key, $ignoreArr)){

                        $tbac = ($acTab == $key) ? "active" : "";

                        if (strpos($value, 'on_custom_') === 0) {
                           $crmModuleMenu[$key] = '<li class="nav-item '.$tbac.'"><a class="nav-link '.$tbac.'" href="'. $admin_url . $key.'">'.str_replace("_", " ",str_replace("on_custom_", "", $value)) .'</a><input type="hidden" name="tabName[]" value="'.$key.'"></li>';
                        }else{
                            $crmModuleMenu[$key] = '<li class="nav-item '.$tbac.'"><a class="nav-link '.$tbac.'" href="'. $admin_url . $key.'">'.str_replace("_", " ",str_replace("zcrm_", "", $key)) .'</a><input type="hidden" name="tabName[]" value="'.$key.'"></li>';
                        }

                    }
                }

                $wtMenu = array();
                if(isset($webtab['content'])){
                    $webtabV = json_decode($webtab['content'], true);
                    if(isset($webtabV['link']) && $webtabV['link'] !=""){
                        $webtabl = ($webtabV['link'] != null)?json_decode($webtabV['link']):[];
                        $webtabt = ($webtabV['title'] != null)?json_decode($webtabV['title']):[];

                        if(!empty($webtabl)){
                            foreach ($webtabl as $wt => $wtt) { 
                                if($webtabt[$wt] !=""){    
                                    $tbac = ($acTab == $webtabt[$wt]) ? "active" : "" ;
                                    $wtTitle = stripslashes($webtabt[$wt]);
                                    $wtMenu["wt_".$wtTitle] = '<li class="nav-item '.$tbac.'"><a class="nav-link '.$tbac.'" href="#">'.$wtTitle .'</a><input type="hidden" name="tabName[]" value="wt_'.$wtTitle.'"></li>';
                                    
                                }
                            }
                        }
                    }
                }
                ?>

                <?php if($tabOrder != null){
                    foreach ($tabOrder as $key => $value) {
                        $value = stripslashes($value);
                        ?>

                        <?php if($value == "Home"){ ?>
                        <li class="nav-item <?php if($acTab == 'selectModule')echo 'active';?>">
                            <a class="nav-link <?php if($acTab == 'selectModule')echo 'active';?>" href="<?php echo $admin_url;?>selectModule">Home</a>
                            <input type="hidden" name="tabName[]" value="Home">
                        </li>
                        <?php } ?>

                        <?php if($value == "Accounts"){ ?>
                        <li class="nav-item <?php if($acTab == 'zcrm_Accounts')echo 'active';?>">
                            <a class="nav-link <?php if($acTab == 'zcrm_Accounts')echo 'active';?>" href="<?php echo $admin_url;?>zcrm_Accounts">Accounts</a>
                            <input type="hidden" name="tabName[]" value="Accounts">
                        </li>                        
                        <?php } ?>



                        <?php 
                            if(isset($crmModuleMenu[$value])) echo $crmModuleMenu[$value];
                            unset($crmModuleMenu[$value]);
                        ?>


                        <?php 
                            if(isset($wtMenu[$value])) echo $wtMenu[$value];
                            unset($wtMenu[$value]);
                        ?>

                    <?php } ?>

                    <?php foreach ($crmModuleMenu as $key => $value) {
                        echo $value;
                    } ?>

                    <?php foreach ($wtMenu as $wt_menu) {
                        echo $wt_menu;
                    } ?>


                <?php }else{ ?>
                    <li class="nav-item <?php if($acTab == 'selectModule')echo 'active';?>">
                        <a class="nav-link <?php if($acTab == 'selectModule')echo 'active';?>" href="<?php echo $admin_url;?>selectModule">Home</a>
                        <input type="hidden" name="tabName[]" value="Home">
                    </li>
                    <li class="nav-item <?php if($acTab == 'zcrm_Accounts')echo 'active';?>">
                        <a class="nav-link <?php if($acTab == 'zcrm_Accounts')echo 'active';?>" href="<?php echo $admin_url;?>zcrm_Accounts">Accounts</a>
                        <input type="hidden" name="tabName[]" value="Accounts">
                    </li>
                    
                    <?php foreach ($crmModuleMenu as $key => $value) {
                        echo $value;
                    } ?>

                    <?php foreach ($wtMenu as $wt_menu) {
                        echo $wt_menu;
                    } ?>

                <?php } ?>
                <div class="clr"></div>
            </ul>

             </form>

        </div>



            <div id="myTabContent" class="tab-content brd-top-off">
                <div class="tab-pane fade active in" id="selectModule" style="padding-bottom: 0px;">
