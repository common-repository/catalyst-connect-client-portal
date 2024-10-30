<?php
global $wpdb;
global $wp;

if(isset($_GET['module']))$acTab = sanitize_text_field($_GET['module']);
if(isset($_GET['acTab']))$apkey  = sanitize_text_field($_GET['acTab']);
$module = str_replace("con", "", str_replace("acc", "", $acTab)) ;

$layViewV = array();
$crmMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'layout_$acTab'");
if(isset($crmMdl->option_index))$layViewV = json_decode($crmMdl->option_value, true); 
if(isset($layViewV['fieldName'])) $allLayout = true;
else $layViewV = array();


// echo "<pre>";print_r($layViewV);echo "</pre>";

$exFldHstry = array();
if(!isset($allLayout)){
    $exView = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'lay_View_$acTab'");
    if(isset($exView->option_index)){
        $ExlayView = json_decode($exView->option_value, true);
        if(isset($ExlayView['fieldName'])){
            foreach ($ExlayView['fieldName'] as $key => $fname) {
                $exFldHstry[$fname]['view'] = "yes";
            }
            $layViewV = $ExlayView;        
        }

    }
    $exEdit = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'lay_Edit_$acTab'");
    if(isset($exEdit->option_index)){
        $ExlayEdit = json_decode($exEdit->option_value, true);
        if(isset($ExlayEdit['fieldName'])){
            foreach ($ExlayEdit['fieldName'] as $key => $fname) {
                $exFldHstry[$fname]['edit'] = "yes";
            }
        }
    }
    $exAdd = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'lay_Add_$acTab'");
    if(isset($exAdd->option_index)){
        $ExlayAdd = json_decode($exAdd->option_value, true);
        if(isset($ExlayAdd['fieldName'])){
            foreach ($ExlayAdd['fieldName'] as $key => $fname) {
                $exFldHstry[$fname]['add'] = "yes";
            }
        }
    }
}

?>

<form role="form" action="" method="post" id="layout_View>">
    <?php 
        wp_nonce_field('updateAllLayout','csrf_token_nonce');
    ?>
    <input type="hidden" name="module" value="<?php echo esc_attr($acTab); ?>">

  
   
    <?php if($subformFld !=""){ ?>
        <button type="button" class="btn btn-success addRowSubform" style="color: #fff;float: right;"><i class="fas fa-plus"></i> Add Subform Section</button>
    <?php } ?>

      <button type="button" class="btn btn-success addRow" style="color: #fff;float: right;margin-right: 10px;"><i class="fas fa-plus"></i> Add Section</button>
       <br><br>
    <div class="layoutFields">

        <?php if(isset($layViewV['rowAdded'])){
            $fldP = 0;
            foreach ($layViewV['rowAdded'] as $rowP => $exrow) {
                $rowAdded = $rowP + 1;
                $sectiontype = (isset($layViewV['sectiontype'][$rowP])) ? $layViewV['sectiontype'][$rowP] : "normal";
            ?>               

<div class="row layoutRow">

    <div class="layoutRowHeader col-md-12">

        <input type="hidden" class="rowAdded" name="rowAdded[]" value="<?php echo esc_attr($rowAdded); ?>">
        <input type="hidden" class="columnAdded" name="columnAdded[]" value="<?php echo esc_attr($layViewV['columnAdded'][$rowP]); ?>">
        <input type="hidden" class="sectiontype" name="sectiontype[]" value="<?php echo esc_attr($sectiontype); ?>">

          <div class="row">

             <div class="col-md-8">
                <span class="noclickthis movesection" style="margin-right: 0px !important;padding: 0px !important;float: left;">
                    <img src="<?php echo esc_url($pluginUrl) ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                </span>
                <input type="text" class="form-control sectiontitle" placeholder="Section Title" name="sectiontitle[]" value="<?php echo (isset($layViewV['sectiontitle'][$rowP])) ? esc_attr($layViewV['sectiontitle'][$rowP]) : ""; ?>">
            </div>

             <div class="col-md-4">
                <button type="button" class="remove removeRow" title="Remove" style="float: right;"><i class="fas fa-times" style="font-size: 18px;"></i></button>

                <?php if($sectiontype == "subform"){ ?>                    
                    <button type="button" class="btn btn-primary addLayColumnSubform" style="float: right;    border-radius: 10px;"><i class="fas fa-plus"></i> Add Field</button>
                <?php }else{ ?>                    
                    <button type="button" class="btn btn-primary addLayColumn" style="float: right"><i class="fas fa-plus"></i> Add Field</button>
                    <?php if(isset($gglintOn)){ ?>
                        <button type="button" class="btn btn-primary addLayAddSrcColumn" style="float: right"><i class="fas fa-plus"></i> Address Searchbar</button>
                    <?php } ?>
                <?php  } ?>

              </div>
              
          </div>

        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div class="layoutColumnContainer">
        
        <?php 

        for ($i=0; $i < $layViewV['columnAdded'][$rowP]; $i++) {
        	$isSubfrom = false;

            $numOfexC = $layViewV['columnAdded'][$rowP];
            $exlyFldClm = $layViewV['layoutFldColumn'][$fldP];
            $exfldN = $layViewV['fieldName'][$fldP];

            $fldView="";$fldAdd="";$fldEdit="";
            if(isset($layViewV['fldView']))$fldView = $layViewV['fldView'][$fldP];
            if(isset($layViewV['fldAdd']))$fldAdd = $layViewV['fldAdd'][$fldP];
            if(isset($layViewV['fldEdit']))$fldEdit = $layViewV['fldEdit'][$fldP];

            if(isset($exFldHstry[$exfldN]['view'])) $fldView = 'yes';
            if(isset($exFldHstry[$exfldN]['edit'])) $fldEdit = 'yes';
            if(isset($exFldHstry[$exfldN]['add'])) $fldAdd = 'yes';
            

            $fieldnameArr = explode("___", $exfldN);
            if(isset($fieldnameArr[1])){
                $fieldApiName = $fieldnameArr[0];
                $fieldDValue = $fieldnameArr[1];
            }else{
                $fieldApiName = $exfldN;
                $fieldDValue = $exfldN;                        
            }
            $selectedField = '<option value="'.stripslashes($exfldN) .'">'.stripslashes($fieldDValue).'</option>';

            $subfSlct= "";
            if(isset($layViewV['subformfieldName_'.$fieldApiName])) {
                $isSubfrom = true;
                $subfSlct = "width: calc(100% - 200px) !important;";
            }

            $fldP++;
            ?>
    <?php if($isSubfrom){ ?> <div class="subform-field"> <?php } ?>
    <div class="layoutColumn col-md-<?php echo $exlyFldClm; ?>" <?php if($isSubfrom){ ?>style="width: calc(100% - 150px) !important;"<?php } ?>>

        <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;float: left;">
            <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
        </span>

        <?php if(!$isSubfrom){ ?>
            <img class="makeCol12" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px"style="">
            <img class="makeCol6" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="">
        <?php } ?>

        <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo esc_attr($exlyFldClm); ?>">
        <input type="hidden" class="layoutFieldList" name="layoutFieldList[]" value="<?php echo esc_attr($rowAdded.'_'.$fieldApiName) ?>">

        <select class="form-control update_fieldNameValue <?php if($isSubfrom)echo 'subfromSelect'; ?>" style="<?php echo $subfSlct; ?>" name="fieldName[]" required>
            <option value="">--Select One--</option>
            <option selected value="<?php echo stripslashes($exfldN) ?>"><?php echo str_replace("_", " ", stripslashes($fieldDValue)) ?></option>
            <?php 
                if($isSubfrom){
                	echo str_replace($selectedField, "", $subformFld);
                }else{
                    echo str_replace($selectedField, "", $allFld);
                }
            ?>
        </select>
        <button type="button" class="remove <?php echo($isSubfrom) ? "removeSfFiels" : "removeLColumn"; ?>"><i class="fas fa-times"></i></button>               


        <!-- <div class="clr"></div> -->
        <div class="fieldAcp">
            <?php if(isset($acpMdlV['View'])){ ?>
            <div class="form-group">
                <label class="switch" title="View">
                    <input type="hidden" name="fldView[]" class="fldView" value="<?php echo ($fldView == "yes") ? 'yes' : 'no';?>">
                    <input class="viewAbleCheckBox" type="checkbox" <?php echo ($fldView == "yes") ? 'checked' : '';?> >
                    <span class="<?php echo ($fldView == "yes") ? 'active' : '';?>"><i class="fas fa-eye"></i></span>
                </label>
                <div class="clr"></div>
            </div>
            <?php } ?>
            <?php if(isset($acpMdlV['Add'])){ ?>
            <div class="form-group">
                <label class="switch" title="Add">
                    <input type="hidden" name="fldAdd[]" class="fldAdd" value="<?php echo ($fldAdd == "yes") ? 'yes' : 'no';?>">
                    <input class="addAbleCheckBox" type="checkbox" <?php echo ($fldAdd == "yes") ? 'checked' : '';?>>
                    <span class="<?php echo ($fldAdd == "yes") ? 'active' : '';?>"><i class="fas fa-plus-square"></i> </span>
                </label>
                <div class="clr"></div>
            </div>
            <?php } ?>
            <?php if(isset($acpMdlV['Edit'])){ ?>
            <div class="form-group">
                <label class="switch" title="Edit">
                    <input type="hidden" name="fldEdit[]" class="fldEdit" value="<?php echo ($fldEdit == "yes") ? 'yes' : 'no';?>">
                    <input class="editAbleCheckBox" type="checkbox" <?php echo ($fldEdit == "yes") ? 'checked' : '';?>>
                    <!-- <span class="slider round"></span> -->
                    <span class="<?php echo ($fldEdit == "yes") ? 'active' : '';?>"><i class="fas fa-edit"></i></i></span>
                </label>
                <div class="clr"></div>
            </div>            
            <?php } ?>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>

    </div>

    <?php  
    if($isSubfrom){
        $SubfromCRMFields = $CrmModules->getSubformFieldList($module.'__'.$fieldApiName);           $sffname = 'subformfieldName_'.$fieldApiName.'[]';
        ?>
        <button type="button" class="btn btn-primary addFieldSubform" style="margin-top: 30px;"><i class="fas fa-plus"></i> Add Field</button>
        <div class="clr"></div>

        <div class="slctSubfromLayout">
            <div class="SubfromCRMFields" style="display: none;">
                <div class="layoutSfTbl col-md-3">          
                    <select class="form-control" name="<?php echo $sffname ;?>" style="width: calc(100% - 40px) !important;float: left;">
                        <option value="">--Select One--</option>
                        <?php foreach ($SubfromCRMFields as $sfcrmf) { 
                            if($sfcrmf['view_type']['view'] == true){
                                echo '<option value="'.stripslashes($sfcrmf['api_name']).'___'.stripslashes($sfcrmf['field_label']) .'">'.stripslashes($sfcrmf['field_label']).'</option>';
                            }
                        } ?>
                    </select>
                    <button type="button" class="remove removeSfColumn"><i class="fas fa-times"></i></button>
                    <div class="clr"></div>
                </div>
            </div>
            <?php foreach ($layViewV['subformfieldName_'.$fieldApiName] as $sfexf) { 
                if($sfexf !=""){
                $sfexfArr  = explode("___", $sfexf); ?>
            
                    <div class="layoutSfTbl col-md-3">          
                        <select class="form-control" name="<?php echo $sffname ;?>" style="width: calc(100% - 40px) !important;float: left;">
                            <?php foreach ($SubfromCRMFields as $sfcrmf) { 
                                if($sfcrmf['view_type']['view'] == true){
                                    if($sfcrmf['api_name'] == $sfexfArr[0]) $slctdsff = "selected"; else $slctdsff = "";
                                    echo '<option '.$slctdsff.' value="'.stripslashes($sfcrmf['api_name']).'___'.stripslashes($sfcrmf['field_label']) .'">'.stripslashes($sfcrmf['field_label']).'</option>';
                                }
                            } ?>
                        </select>
                        <button type="button" class="remove removeSfColumn"><i class="fas fa-times"></i></button>
                        <div class="clr"></div>
                    </div>
                <?php }
            } ?>
        </div>
        <div class="clr"></div>
    <?php } ?>
    <?php if($isSubfrom){ ?> </div> <?php } ?>

        <?php } ?>
    </div>
    <div class="clr"></div>
</div>
                            
            <?php }

        } ?>
        <div class="clr"></div>
    </div>


    <div class="clr" style="height: 40px"></div>
    <input type="submit" class="btn btn-primary newSubmitBtn  float-right" name="updateAllLayout" value="Update" style="padding: 12px 60px !important;">
    
    <div class="clr"></div>
</form>


