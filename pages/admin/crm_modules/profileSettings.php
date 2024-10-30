<?php
    global $wpdb;
    global $wp;
    $moduleFlds = array();
    $acpMdlV = array();
    $acadrsV = array();

    $acTab = "zcrm_Accounts";
    if(isset($_GET['actTab']))$actTab = $_GET['actTab']; 
    else $actTab = 'View';


    $CrmModules = new CrmModules();
    $CPNzoho = new ZohoCrmRequest();
    $SttClass = new CCGP_SettingsClass();

    $crmMdl = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE '%$acTab'");
    if(count($crmMdl) > 0){
        foreach ($crmMdl as $crmMdlV) {
            if($crmMdlV->option_index == 'sp_'.$acTab)$spMdlV = json_decode($crmMdlV->option_value, true);
            else if($crmMdlV->option_index == 'acp_'.$acTab)$acpMdlV = json_decode($crmMdlV->option_value, true);
            else if($crmMdlV->option_index == 'mtd_'.$acTab)$mtdV = json_decode($crmMdlV->option_value, true);
            else if($crmMdlV->option_index == 'acadrs_'.$acTab)$acadrsV = json_decode($crmMdlV->option_value, true);
        }
    }

    $gglint = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'integrations' AND option_index = 'Google'");
    if(isset($gglint->option_index) && $gglint->option_value == "on"){
        
        $gglApiKey = $wpdb->get_row("SELECT `access_token` FROM ccgclientportal_auth WHERE apifor = 'google'");
        if(isset($gglApiKey->access_token))$gglintOn = true;
    } 


    $module = str_replace("zcrm_", "", $acTab) ;
    $moduleFlds = $CrmModules->getModuleFieldList($module);
    $moduleFlds = json_encode($moduleFlds);
    $moduleFlds = json_decode($moduleFlds);

?>

<style type="text/css">
    form .form-group label.control-label{
        min-width: 125px;
        margin: 0px;
        float: left;
    }
    form#showPermission .form-group input.form-control,
    form#showPermission .form-group input.form-control,
    form#tableColumn .form-group select.form-control,
    form#tableColumn .form-group select.form-control{
        max-width: 350px;
        float: left;
    }
    #tableColumn #addColumn{margin-left: 8px;}
    #tableColumn .removeColumn{margin-left: 8px;}
    #errorMessage{    
        color: red;
        margin: 8px 0px;
    }
    .layoutColumn select.form-control {
        width: calc(100% - 250px) !important;
    }


    .fieldAcp{ float: right; }
    .layoutColumn .fieldAcp .form-group{margin: 5px 15px 0px 0px !important;float: left;}
    .layoutColumn .fieldAcp .form-group label{margin-bottom: 0px !important;}
    .fieldAcp .switch {
        width: 22px;
    }

    .fieldAcp .switch .fas{
        color: #b7b7b7;
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 2px;
        font-size: 22px;
    }
    .fieldAcp .switch span.active .fas{
        /*color: green;*/
        color: #ffffff;
    }
    .subform-field .layoutColumn select.subfromSelect{
        width: calc(100% - 165px) !important;
    }

 
</style>


<?php 
$allFld = "";
$lookUpFld = "";
$viewAbleFld = "";
$editAbleFld = "";
$subformFld = "";
$ignorFieldType = array('ownerlookup','subform','multiselectlookup','profileimage');
foreach ($moduleFlds as $key => $fldDtl) {
    if(!in_array($fldDtl->data_type, $ignorFieldType)){
        $allFld .='<option value="'. $fldDtl->api_name .'___'. $fldDtl->field_label .'">'. $fldDtl->field_label .'</option>';
        if($fldDtl->view_type->view == true){ 
            $viewAbleFld .='<option value="'. $fldDtl->api_name .'___'. $fldDtl->field_label .'">'. $fldDtl->field_label .'</option>';
        }
        if($fldDtl->view_type->edit == true){ 
            $editAbleFld .='<option value="'. $fldDtl->api_name .'___'. $fldDtl->field_label .'">'. $fldDtl->field_label .'</option>';
        }
    }
    if($fldDtl->data_type == 'lookup'){ 
        $lookUpFld .='<option value="'. $fldDtl->api_name .'___'. $fldDtl->field_label .'">'. $fldDtl->field_label .'</option>';
    }
    if($fldDtl->data_type == 'subform'){ 
        $subformFld .='<option value="'. $fldDtl->api_name .'___'. $fldDtl->field_label .'">'. $fldDtl->field_label .'</option>';
    }
} 
?>

<div class="col-md-6 left-section">

    <?php include 'profileSettings/leftsection.php'; ?>

</div>

<div class="col-md-6 right-section">
    <?php include 'profileSettings/rightsection.php'; ?>

</div>
<div class="clr"></div>


<div class="clr" style=""></div>
<h3 class="pg-ttl instruction_title wd-100 pd-l-0">Layouts</h3>
<div class="clr" style="height: 20px;"></div>
<div class="tab-content">
<div>
<!-- last two div start for bottom-->

<!-- <hr style="margin: 15px;"></hr>
<h4 class="instruction_title" >Layouts</h4> -->

<div class="profile-layout box-shadow pd-30" style="margin: -40px -15px 0px;border: none;">

    <?php
    $apkey = "";
    $lf = 0;
    $numOfexC = 0;
    $fldP = 0;
    $exfldN = "";
    $acpMdlV['View'] = true;

    include_once 'action_Layouts/profile_layout.php';
    include_once 'action_Layouts/common.php';
    ?>

    

</div>

<!-- last two div end for top-->
</div>
</div>