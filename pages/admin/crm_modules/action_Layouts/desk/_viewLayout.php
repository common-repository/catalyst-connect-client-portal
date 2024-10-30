<?php
    global $wpdb;
    global $wp;

    if(isset($_GET['module']))$acTab = sanitize_text_field($_GET['module']);
    if(isset($_GET['acTab']))$apkey = sanitize_text_field($_GET['acTab']);
    $module = str_replace("con", "", str_replace("acc", "", $acTab)) ;
    // $moduleFld = $CrmModules->getFields($module);

    $crmMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'booksModules' AND option_index LIKE 'lay_View_$acTab'");
    if(isset($crmMdl->option_index))$layViewV = json_decode($crmMdl->option_value, true); 
?>


<form role="form" action="" method="post" id="layout_View>">   
    <input type="hidden" name="actionType" value="View">
    <input type="hidden" name="module" value="<?php echo $acTab; ?>">


    <button type="button" class="btn btn-primary addRow" style="color: #fff;float: right;margin-bottom: 0px;"><i class="fas fa-plus"></i> Add Section</button>

    <div class="clr"></div>

    <div class="layoutFields">

        <?php if(isset($layViewV['rowAdded'])){
            $fldP = 0;
            foreach ($layViewV['rowAdded'] as $rowP => $value) {
                $rowAdded = $rowP + 1;
            ?>               

<div class="row layoutRow">
<div class="layoutRowHeader col-md-12">
    <input type="hidden" class="rowAdded" name="rowAdded[]" value="<?php echo $rowAdded ?>">
    <input type="hidden" class="columnAdded" name="columnAdded[]" value="<?php echo $layViewV['columnAdded'][$rowP]; ?>">

    <div class="row">

     <div class="col-md-8">
        <span class="noclickthis movesection" style="margin-right: 0px !important;padding: 0px !important;float: left;">
            <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
        </span>

        <input type="text" class="form-control sectiontitle" placeholder="Section Title" name="sectiontitle[]" value="<?php echo (isset($layViewV['sectiontitle'][$rowP])) ? $layViewV['sectiontitle'][$rowP] : ""; ?>">
    </div>

    <div class="col-md-4">
        <button type="button" class="remove removeRow" title="Remove" style="float: right;"><i class="fas fa-times" style="font-size: 18px;"></i></button>
        <button type="button" class="btn btn-primary addLayColumn" style="float: right;"><i class="fas fa-plus"></i> Add Field</button>
      
    </div>
      
    </div>
    <div class="clr"></div>
</div>

<div class="layoutColumnContainer">
    
    <?php for ($i=0; $i < $layViewV['columnAdded'][$rowP]; $i++) {
        $numOfexC = $layViewV['columnAdded'][$rowP];
        $exlyFldClm = $layViewV['layoutFldColumn'][$fldP];
        $exfldN = $layViewV['fieldName'][$fldP];
        $fldP++;
        ?>

        <div class="layoutColumn col-md-<?php echo $exlyFldClm; ?>">

            <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;float: left;">
                <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
            </span>

            <img class="makeCol12" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px">
            <img class="makeCol6" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px">

            <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo $exlyFldClm; ?>">
            <input type="hidden" class="layoutFieldList" name="layoutFieldList[]" value="<?php echo $rowAdded.'_'.$exfldN ?>">
            <select class="form-control update_fieldNameValue" name="fieldName[]" required>
                <option value="">--Select One--</option>
                <option selected value="<?php echo $exfldN ?>"><?php echo ucfirst(str_replace("_", " ", $exfldN)) ?></option>
                <?php echo $viewAbleFld ?>
            </select>
            <button type="button" class="remove removeLColumn"><i class="fas fa-times"></i></button>
            <div class="clr"></div>
        </div>

    <?php } ?>
</div>
<div class="clr"></div>
</div>
                        
            <?php }

        } ?>
        <div class="clr"></div>
    </div>


    <div class="clr" style="height: 40px"></div>
 
    <input type="submit" class="btn btn-primary newSubmitBtn" name="updateLayoutDesk" value="Update" style="padding: 12px 60px !important;">
    
    <div class="clr"></div>
</form>

