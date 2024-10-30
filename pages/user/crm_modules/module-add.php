<?php
/*
    Created By : Mahidul Islam Tamim
*/
    $succrss = false;
    $error = false;
    $errorm = "Please try again later or contact your administrator.";
    $recordDtl = false;

    $module = ucfirst($_GET['module']);

    $page = $_GET['ppage'];
    $rl   = 'zcrm_';

    $layViewV = array();
    $crmMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'layout_".$rl.$module."'");
    if(isset($crmMdl->option_index))$layViewV = json_decode($crmMdl->option_value, true); 
    if(isset($layViewV['fieldName'])) $allLayout = true;
    else $layViewV = array();

    $exFldHstry = array();
    if(!isset($allLayout)){
        $exView = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'lay_Add_".$rl.$module."'");
        if(isset($exView->option_index)){
            $ExlayView = json_decode($exView->option_value, true);
            if(isset($ExlayView['fieldName'])){
                foreach ($ExlayView['fieldName'] as $key => $fname) {
                    $exFldHstry[$fname]['add'] = "yes";
                }
                $layViewV = $ExlayView;        
            }
        }
    }
    
    $fieldList        = $CrmModules->getModuleFieldList($module);

    $system_mandatory = array();

    foreach ($fieldList as $key => $fldDetails) {
        if($fldDetails['system_mandatory']){
            $system_mandatory[$key."___".$fldDetails['field_label']] = $fldDetails;
        }
    }

    if(isset($layViewV['rowAdded'])){

        if(isset($_SESSION["ccgcp_loged"]['id'])){

            if(isset($_POST['insertModule'])){

                if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'insertModule')){
                    unset($_POST['insertModule']);
                    
                    foreach ($layViewV['fieldName'] as $flfapinm) {
                        $flfapinmArr = explode("___", $flfapinm);
                        if(isset($flfapinmArr[1])){
                            $fldApiN = $flfapinmArr[0];
                        }else{
                            $fldApiN = $flfapinm;                   
                        }

                        if(isset($fieldList[$fldApiN]['data_type'])){
                            $fdtype = $fieldList[$fldApiN]['data_type'];

                            if(isset($_POST[$fldApiN])){
                                $value = $_POST[$fldApiN];
                                if($fdtype == 'boolean')$_POST[$fldApiN] = ($value == "on") ? true : false;
                                elseif($fdtype == 'integer')$_POST[$fldApiN] = (int) $value;           
                                elseif($fdtype == 'currency')$_POST[$fldApiN] = (float) $value;           
                                elseif($fdtype == 'lookup'){
                                    if(isset($_POST[$fldApiN."___id"])){
                                        if($_POST[$fldApiN."___id"] !=""){
                                            $record_id = $_POST[$fldApiN."___id"];
                                            $_POST[$fldApiN] =  array('name' => $value,'id' => $record_id);
                                        }
                                        unset($_POST[$fldApiN."___id"]);
                                    }
                                }   
                            }else if($fdtype == 'subform'){

                                $fieldListSF = $CrmModules->getModuleFieldList($fldApiN);

                                $_POST[$fldApiN] = array();
                                foreach ($layViewV['subformfieldName_'.$fldApiN] as $sfexf) {
                                    if($sfexf !=""){

                                    $sfexfArr  = explode("___", $sfexf);
                                    $sffldApiN = $sfexfArr[0];

                                    if(isset($_POST[$fldApiN.'---'.$sffldApiN])){

                                        $sfRcArr = $_POST[$fldApiN.'---'.$sffldApiN];                                    
                                        foreach ($sfRcArr as $key => $sfvalue) {

                                        if ($sfvalue !="") {

                                            if(isset($fieldListSF[$sffldApiN]['data_type'])){
                                                $sffdtype = $fieldListSF[$sffldApiN]['data_type'];

                                                if($sffdtype == 'boolean')$_POST[$fldApiN][$key][$sffldApiN] = ($sfvalue == "on") ? true : false;
                                                else if($sffdtype == 'integer')$_POST[$fldApiN][$key][$sffldApiN] = (int) $sfvalue;
                                                elseif($sffdtype == 'lookup'){
                                                    $record_id = $_POST[$fldApiN.'---'.$sffldApiN."___id"][$key];
                                                    $_POST[$fldApiN][$key][$sffldApiN] =  array('name' => $sfvalue,'id' => $record_id);

                                                }else{
                                                    $_POST[$fldApiN][$key][$sffldApiN] =  $sfvalue;
                                                }

                                            }
                                            } 
                                        }

                                        unset($_POST[$fldApiN.'---'.$sffldApiN]);
                                        unset($_POST[$fldApiN.'---'.$sffldApiN."___id"]);

                                    }
                                    }
                                }
                                unset($_POST[$fldApiN.'---'.$sffldApiN]);  

                            }else{

                                if($fdtype == 'boolean')$_POST[$fldApiN] = ($value == "on") ? true : false;                        
                            }
                        }
                    }
                    $accId = $_SESSION["ccgcp_loged"]['Account_Name']['id'];

                    if(isset($layViewV['accountField']) && ($layViewV['accountField'] !="")){
                        $accountField = $layViewV['accountField'];
                        $fieldAcArr = explode("___", $accountField);
                        if(isset($fieldAcArr[1])){
                            $fieldAcApiN = $fieldAcArr[0];
                        }else{
                            $fieldAcApiN = $accountField;                        
                        }
                    }

                    $spMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'sp_zcrm_$module'");        
                    if(isset($spMdl->option_value)) $spMdlV = json_decode($spMdl->option_value, true); else $spMdlV = array();
                    if(isset($spMdlV['accountField']) && ($spMdlV['accountField'] !="")){

                    $accountField = $spMdlV['accountField'];
                    $fieldAcArr = explode("___", $accountField);
                    if(isset($fieldAcArr[0])) $fieldAcApiN = $fieldAcArr[0];
                    }

                    if(isset($fieldAcApiN)){
                        $_POST[$fieldAcApiN] = array('id'=> $accId);                
                    }else{
                        $_POST['Account_Name'] = array('id'=> $accId);
                        $_POST['Account'] = array('id'=> $accId);
                    }
                    $zohoArr = array($_POST);
                    
                    $update = $CPNzoho->insertRecordsN($zohoArr, $module, 'user', 'yes');
                    
                    if(isset($update->data[0]->code) && $update->data[0]->code == "SUCCESS"){
                        $succrss = "SUCCESS";
                        echo "<script type='text/javascript'>window.location.replace('".$cppageUrl."?ppage=list&module=".$_GET['module']."&origin=CRM&msg=SUCCESS".$page_id."');</script>";

                    }else{ 
                        $error = true;
                        if(isset($update->data[0]->code) && $update->data[0]->code == "MANDATORY_NOT_FOUND"){
                            $errorm = "Mandatory field not found. Please contact your administrator.";
                        }
                    }
                
                }else{
                    $error = true;
                    $errorm = "Something Went wrong. Please contact your administrator."; 
                }
            }
    	}
    }
?>

<div id="portal-cotenier">
    <div class="col-md-12">
		<div class="add-module-page page-body">

            <?php if($succrss == "SUCCESS"){ ?>
                <div class="alert alert-dismissible alert-success">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Well done!</strong> Added successfully </a>.
                </div>
            <?php } ?>

            <?php if($error){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Oh snap!</strong> <?php echo str_replace("_", " ", $module);?> were not added. <?php echo $errorm; ?>
                </div>
            <?php } ?>

            <?php if(isset($layViewV['rowAdded'], $layViewV['layoutFldColumn'])){ ?>
                <form role="form" action="" method="post">
                <?php 
                    wp_nonce_field('insertModule','csrf_token_nonce');
                ?>
                <h4 class="section-title"><?php echo ($layViewV['sectiontitle'][0]) ? $layViewV['sectiontitle'][0] : "&nbsp;";?></h4>

                    <div class="newtabSection">
                    <?php 
                    $fldP = 0;
                    $rowN = 0;
                    $colN = 0;
                    $ttlcol = 0;
                    $lookupModule = "";
                    foreach ($layViewV['layoutFldColumn'] as $rowP => $col) {
                        $colN++;

                        $exfldN = $layViewV['fieldName'][$rowP]; 

                        $fieldnameArr = explode("___", $exfldN);
                        if(isset($fieldnameArr[1])){
                            $fieldApiName = $fieldnameArr[0];
                            $fieldDValue = $fieldnameArr[1];
                        }else{
                            $fieldApiName = $exfldN;
                            $fieldDValue = $exfldN;                        
                        }


                        $fldAdd="";                            
                        if(isset($layViewV['fldAdd']))$fldAdd = $layViewV['fldAdd'][$fldP];
                        if(isset($exFldHstry[$exfldN]['add'])) $fldAdd = 'yes';
                        $fldP++;

                        if($fldAdd == "yes"){                            
                        $ttlcol = $ttlcol + (int)$col;


                            if(array_key_exists($fieldApiName, $fieldList)){                         

                                $type = $fieldList[$fieldApiName]['data_type']; 
                                if($fieldList[$fieldApiName]['system_mandatory']) $mandatory = 'required'; else  $mandatory ="";
                                if( ($type == 'lookup') ){
                                    $lookupModule = $fieldList[$fieldApiName]['lookup']['module'];
                                }
                                ?>

                                <div class="form-group pd-0 col-md-<?php echo $col; ?>">
                                    <label class="col-md-12 control-label">
                                        <?php echo $fieldList[$fieldApiName]['field_label'] ?>                                    
                                    </label>
                                    <div class="col-md-12">
                                        
                                                <?php
                                                if($type == 'subform'){ ?>


                        <?php 

                        if(isset($layViewV['subformfieldName_'.$fieldApiName])) $isSubfrom = true;
                        else $isSubfrom = false;

                        if($isSubfrom){
                            $sfclumn = $layViewV['subformfieldName_'.$fieldApiName];

                            $fieldListSF = $CrmModules->getModuleFieldList($fieldApiName);
                            ?>
                            <table class="table table-hover col-md-12 subform-record-list" style="margin-top: 15px;">
                                <thead class="headtable">
                                    <tr>
                                        <?php 
                                        foreach ($sfclumn as $sfexf) {
                                            if($sfexf !=""){
                                                $fieldDValueArr = explode("___", $sfexf);
                                                if(isset($fieldDValueArr[1])){
                                                    $sfexfDValue = $fieldDValueArr[1];
                                                }else{
                                                    $sfexfDValue = $exfldN;                        
                                                }
                                                ?>
                                                <th><?php echo esc_html($sfexfDValue); ?></th>
                                            <?php }
                                        } ?>
                                        <th style="width: 100px;">Action</th>
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="subform-recordRow subform-recordRowN ">
                                    <?php 
                                        foreach ($sfclumn as $sfexf) {

                                            if($sfexf !=""){ ?>

                                                <?php $fieldDValueArr = explode("___", $sfexf);
                                                if(isset($fieldDValueArr[1])) $sfexfApiName = $fieldDValueArr[0];
                                                else $sfexfApiName = $exfldN;    

                                                ?>
                                                <td> <?php 
                                                    $type = $fieldListSF[$sfexfApiName]['data_type'];
                                                    if($type == 'lookup')$lookupModule = $fieldListSF[$sfexfApiName]['lookup']['module'];
                                                    $fValue = "";
                                                    include 'defineSFInputTypeAdd.php';
                                                 ?>
                                                </td>
                                            <?php
                                            } 
                                        } ?>
                                        <td>
                                            <button type="button" class="addSfItm"><i class="fas fa-plus"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        <?php } ?>
                                                <?php }else{
                                                    include 'defineInputTypeAdd.php'; 
                                                }
                                                ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if($ttlcol == 12) {
                                $ttlcol = 0;
                             ?>
                                <div class="clr"></div>
                            <?php }  ?>
                            <?php if($colN == $layViewV['columnAdded'][$rowN]) {
                                $colN = 0; $rowN = $rowN + 1; ?>
                                <div class="clr"></div>
                                </div> <!-- newtabSection -->
                                <div style="clear: both;height: 40px;"></div>
                                <?php if(isset($layViewV['sectiontitle'][$rowN])){ ?>
                                    <h4 class="section-title"><?php echo $layViewV['sectiontitle'][$rowN];?></h4>
                                <?php }  ?>
                                <?php if(isset($layViewV['sectiontitle'][$rowN])){ ?>
                                    <div class="newtabSection">
                                <?php }  ?>
                            <?php }  ?>

                        <?php }  ?>

                    <?php } ?>


                    <div class="clr"></div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-primary saveButton" style="float: right;" name="insertModule" value="Create">
                        </div>
                    </div>
                    <div class="clr"></div>

                </form>
				

			<?php } else { ?>
                <p style="text-align: center;margin: 0px;">Customization for <?php echo str_replace("_", " ", $module);?> is not complete. Please Contact your administrator. </p></td>                        

            <?php } ?>

		</div>  
	</div>  

</div>





<style type="text/css">
    .showDialogLookup{cursor: pointer;}
    #showDialogLookup .modal-body .searchstr{width: calc(100% - 75px);float: left;}
    .ccgclientportal-content button.remove {
        background: #FF7D7E !important;
        color: #fff !important;
        border: solid 2px #FF7D7E !important;
        border-radius: 4px !important;
        padding: 5px 10px !important;
    }
    .ccgclientportal-content button.addSfItm {
        background: #7BDA7C !important;
        color: #fff !important;
        border: solid 2px #7BDA7C !important;
        border-radius: 4px !important;
        padding: 5px 10px !important;
    }
</style>
<div style='display:none'>
    <div id='showDialogLookup' style='padding:10px; background:#fff;height: 200px;'>
        <div class="modal-body">
        <input type="hidden" class="moduleName" value="">
        <input type="hidden" class="fieldname" value="">

        <div class="form-group" style="margin-bottom: 15px;">
            <input class="form-control searchstr" type="text">
            <button class="btn btn-primary saveButton searchrecord" style="float: left;">Search</button>
            <div class="clr"></div>
        </div>

        <div class="form-group searchingtext" style="display: none; margin-bottom: 15px;">Please wait ..... </div>
        <div class="form-group cmrModuleList" style="display: none; margin-bottom: 15px;">
            <select class="form-control selectrecord" style="width: 100%">
                <option value="">--Select One--</option>
            </select>
            <div class="clr"></div>
        </div>

      </div>
      <div class="modal-footer" style="display: none;">
        <button type="button" class="btn btn-default btn-selectrecord" style="">Add</button>
      </div>

    </div>
</div>
<script type="text/javascript">

    function getmodule(searchstr,moduleName, fieldname, _this){
        jQuery.ajax({
            type:'POST',
            data:{ module: moduleName, string: searchstr, for: "searchModule", action:'ccgpp_ajaxrequest'},
            url: "<?php echo site_url(); ?>/wp-admin/admin-ajax.php",
            success: function(response) {
                jQuery(_this).closest('.modal-body').find('.searchingtext').hide(); 
                if(response !='<option value="">--Select One--</option>'){        
                    jQuery('#showDialogLookup').find('.modal-footer').show();   
                    jQuery(_this).closest('.modal-body').find('.cmrModuleList').show(); 
                }else{
                    jQuery('#showDialogLookup').find('.modal-footer').hide();  
                    alert("No record found !");
                }
                jQuery(_this).closest('.modal-body').find('.cmrModuleList select').html(response);          

            }
        });
    }

    jQuery('.btn-selectrecord').click(function () {
        var record_id = jQuery("#showDialogLookup .selectrecord").val();
        var record_name = jQuery("#showDialogLookup .selectrecord option:selected").text();
        if(record_id == ""){
            jQuery('#showDialogLookup .selectrecord').focus();
            console.log("focus");
            return false;
        }
        var fieldname = jQuery('#showDialogLookup').find('.fieldname').val();
        jQuery('#'+fieldname+"_lookup.sleectLookupFld").find('.record_name').val(record_name);
        jQuery('#'+fieldname+"_lookup.sleectLookupFld").find('.record_id').val(record_id);        
        jQuery('#'+fieldname+"_lookup.sleectLookupFld").removeClass('sleectLookupFld');  
        jQuery.colorbox.close();
    });
</script>

<a style="display: none;" class='showDialog' href="#showDialogLookup"></a>
<script type="text/javascript">
    jQuery(document).ready(function(){     

        jQuery(document).on('click', '.removeTd', function(event) {
            jQuery(this).closest('.subform-recordRow').remove();
        });

        jQuery(document).on('click', '.addSfItm', function(event) {
            var itemHtml = jQuery(this).closest('tbody').find('.subform-recordRowN').html();
            jQuery(this).closest('tbody').append('<tr class="subform-recordRow adddrw">'+itemHtml+'</tr>');
            jQuery(this).closest('tbody').find('.adddrw .addSfItm').addClass('remove removeTd');
            jQuery(this).closest('tbody').find('.adddrw .addSfItm').removeClass('addSfItm');
            jQuery(this).closest('tbody').find('.adddrw .fa-plus').addClass('fa-times');
            jQuery(this).closest('tbody').find('.adddrw .fa-plus').removeClass('fa-plus');
        });

    });
</script>
<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery(document).on('click', '.showDialogLookup', function(event) {
            event.preventDefault();
            /* Act on the event */
            jQuery('#showDialogLookup').find('.moduleName').val("");
            jQuery('#showDialogLookup').find('.fieldname').val("");
            jQuery('#showDialogLookup').find('.searchstr').val("");
            jQuery('#showDialogLookup').find('.cmrModuleList').hide(); 
            jQuery('#showDialogLookup').find('.modal-footer').hide();
            jQuery('#showDialogLookup').find('.cmrModuleList select').html('<option value="">--Select One--</option>'); 

            jQuery(".sleectLookupFld").removeClass('sleectLookupFld');   
            jQuery(this).closest('.input-group').addClass('sleectLookupFld');
            var moduleName = jQuery(this).closest('.input-group').find('.modulename').val();
            var fieldname = jQuery(this).closest('.input-group').find('.fieldname').val();
            jQuery("#showDialogLookup").find('.moduleName').val(moduleName);
            jQuery("#showDialogLookup").find('.fieldname').val(fieldname);

            jQuery(".showDialog").click();
        });
        jQuery(".showDialog").colorbox({inline:true, width:"450px", height:"auto"});
        
        jQuery('.searchrecord').click(function () {
            var searchstr = jQuery(this).closest('#showDialogLookup').find('.searchstr').val();
            if(searchstr == ""){
                jQuery(this).closest('#showDialogLookup').find('.searchstr').focus();
                return false;
            }

            jQuery(this).closest('.modal-body').find('.searchingtext').show();  
            jQuery(this).closest('.modal-body').find('.cmrModuleList').hide();
            jQuery('#showDialogLookup').find('.modal-footer').hide();

            var moduleName = jQuery(this).closest('#showDialogLookup').find('.moduleName').val();
            var fieldname = jQuery(this).closest('#showDialogLookup').find('.fieldname').val();
            getmodule(searchstr, moduleName, fieldname, jQuery(this));
        });
    });
</script>
