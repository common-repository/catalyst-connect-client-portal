<?php
/*
    Created By : Mahidul Islam Tamim
*/
    
    global $wpdb;
    global $wp;
    $moduleFlds = array();

    if(isset($_GET['module']))$acTab = $_GET['module'];
    if(isset($_GET['actTab']))$actTab = $_GET['actTab']; 
    else $actTab = 'View';
    

    $CrmModules = new CrmModules();
    $CPNzoho = new ZohoCrmRequest();

    $crmMdl = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE '%$acTab'");
    if(count($crmMdl) > 0){
        foreach ($crmMdl as $crmMdlV) {
            if($crmMdlV->option_index == 'sp_'.$acTab)$spMdlV = json_decode($crmMdlV->option_value, true);
            else if($crmMdlV->option_index == 'ltc_'.$acTab)$ltcMdlV = json_decode($crmMdlV->option_value, true);
            else if($crmMdlV->option_index == 'acp_'.$acTab)$acpMdlV = json_decode($crmMdlV->option_value, true);
            else if($crmMdlV->option_index == 'mtd_'.$acTab)$mtdV = json_decode($crmMdlV->option_value, true);
            else if($crmMdlV->option_index == 'fltr_'.$acTab)$mfltr = json_decode($crmMdlV->option_value, true);
        }
    }


    if($acTab == "cust_Member_Accounts") $module = "Accounts";
    else $module = str_replace("zbooks_", "", str_replace("zcrm_", "", $acTab)) ;
    
    $moduleFlds = $CrmModules->getModuleFieldList($module);
    $moduleFlds = json_encode($moduleFlds);
    $moduleFlds = json_decode($moduleFlds);


    $viewList="";
    $custom_views = $CPNzoho->getCustom_views($module);
    if(isset($custom_views['custom_views']) && count($custom_views['custom_views']) > 0){
        foreach ($custom_views['custom_views'] as $cvdtl) {
            $viewList .='<option value="'.$cvdtl['id'].'___'.$cvdtl['display_value'].'___'.$cvdtl['name'].'">'.$cvdtl['display_value'].'</option>';
        }
    }
    // echo "<pre>"; var_dump($custom_views);echo "</pre>";

    $allFld = "";
    $lookUpFld = "";
    $viewAbleFld = "";
    $editAbleFld = "";
    $addAbleFld = "";
    $subformFld = "";
    $lookupModule = "";

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
            if($fldDtl->view_type->create == true){ 
                $addAbleFld .='<option value="'. $fldDtl->api_name .'___'. $fldDtl->field_label .'">'. $fldDtl->field_label .'</option>';
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
    /*.layoutColumn .fieldAcp{margin-left: 130px;}*/
    .fieldAcp{ float: right; }
    .layoutColumn .fieldAcp .form-group{margin: 5px 15px 0px 0px !important;float: left;}
    .layoutColumn .fieldAcp .form-group label{margin-bottom: 0px !important;}
    .fieldAcp .switch {
        width: 22px;
    }
    .subform-field .layoutColumn select.subfromSelect{
        width: calc(100% - 200px) !important;
    }




</style>



<div class="col-md-6 left-section">
    <?php include 'moduleSettings/leftsection.php'; ?>
</div>


<div id="crm-viewable-fields" style="display: none;">
    <div class="form-group">
        <select class="form-control" name="column[]" required style="max-width:90%">
            <option value="">--Select One--</option>
            <?php echo $viewAbleFld ?>
        </select>
        <button type="button" class="btn btn-danger removeColumn" ><i class="fas fa-minus"></i></button>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
</div>

<div class="col-md-6 right-section">
    <?php include 'moduleSettings/rightsection.php'; ?>
</div>


<div class="clr"></div>


<?php if((isset($acpMdlV)) && (count($acpMdlV) > 0)){ ?>

    <style type="text/css">
        #wpfooter{
            margin-left: 0px;
        }
    </style>

    <div class="clr" style=""></div>
    <h3 class="pg-ttl instruction_title wd-100 pd-l-0">Layouts</h3>
    <div class="clr" style="height: 20px;"></div>
    <div class="tab-content">
    <div>

        <div class="profile-layout box-shadow pd-30" style="margin: -40px -15px 0px;border: none;">

            <?php
                $apkey = "";
                $lf = 0;
                $numOfexC = 0;
                $fldP = 0;
                $exfldN = "";

                include_once 'action_Layouts/layout.php';
                include_once 'action_Layouts/common.php';
            ?>


     </div>

     <!-- last two div end for top-->



<?php } ?>



