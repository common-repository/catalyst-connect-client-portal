<?php
    global $wpdb;
    global $wp;

    if(isset($_GET['module']))$acTab = sanitize_text_field($_GET['module']);
    if(isset($_GET['acTab']))$apkey = sanitize_text_field($_GET['acTab']);
    $module = str_replace("con", "", str_replace("acc", "", $acTab));

    $crmMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'lay_Add_$acTab'");
    if(isset($crmMdl->option_index))$layViewV = json_decode($crmMdl->option_value, true); 
?>
<div class="tab-pane fade active in" id="apTab_Add">

    <form role="form" action="" method="post" id="layout_Add">   
        <input type="hidden" name="actionType" value="Add">
        <input type="hidden" name="module" value="<?php echo esc_attr($acTab); ?>">

        <button type="button" class="addRow"><i class="fas fa-plus"></i> Add Section</button>
        <div class="clr"></div>

        <div class="layoutFields">


            <?php if(isset($layViewV['rowAdded'])){
                $fldP = 0;
                foreach ($layViewV['rowAdded'] as $rowP => $value) {
                    $rowAdded = $rowP + 1;
                ?>

<div class="row layoutRow">
    <div class="layoutRowHeader col-md-12">
        <input type="hidden" class="rowAdded" name="rowAdded[]" value="<?php echo esc_attr($rowAdded) ?>">
        <input type="hidden" class="columnAdded" name="columnAdded[]" value="<?php echo $layViewV['columnAdded'][$rowP]; ?>">
        <button type="button" class="movesection"><i class="fas fa-arrows-alt"></i></button>
        <button type="button" class="addLayColumn"><i class="fas fa-plus"></i> Add Field</button>

        <input type="text" class="form-control sectiontitle" placeholder="Section Title" name="sectiontitle[]" value="<?php echo (isset($layViewV['sectiontitle'][$rowP])) ? $layViewV['sectiontitle'][$rowP] : ""; ?>">

        <button type="button" class="remove removeRow" title="Remove"><i class="fas fa-times"></i></button>

        <div class="clr"></div>
    </div>

    <div class="layoutColumnContainer">
        
        <?php for ($i=0; $i < $layViewV['columnAdded'][$rowP]; $i++) {
            $numOfexC = $layViewV['columnAdded'][$rowP];
            $exlyFldClm = $layViewV['layoutFldColumn'][$fldP];
            $exfldN = $layViewV['fieldName'][$fldP];

            $fieldnameArr = explode("___", $exfldN);
            if(isset($fieldnameArr[1])){
                $fieldApiName = $fieldnameArr[0];
                $fieldDValue = $fieldnameArr[1];
            }else{
                $fieldApiName = $exfldN;
                $fieldDValue = $exfldN;                        
            }
            $selectedField = '<option value="'.$exfldN .'">'.$fieldDValue.'</option>';

            $fldP++;
            ?>

            <div class="layoutColumn col-md-<?php echo $exlyFldClm; ?>">

                <button type="button" class="movecolumn"><i class="fas fa-arrows-alt"></i></button>
                <button type="button" class="makeCol12"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                <button type="button" class="makeCol6"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo $exlyFldClm; ?>">
                <input type="hidden" class="layoutFieldList" name="layoutFieldList[]" value="<?php echo $rowAdded.'_'.$fieldApiName ?>">
                <select class="form-control update_fieldNameValue" name="fieldName[]" required>
                    <option value="">--Select One--</option>
                    <option selected value="<?php echo $exfldN ?>"><?php echo str_replace("_", " ", $fieldDValue) ?></option>
                    <?php 
                        echo str_replace($selectedField, "", $addAbleFld);
                        // echo $editAbleFld;
                    ?>
                </select>
                <button type="button" class="remove removeLColumn" title="Remove"><i class="fas fa-times"></i></button>
                <div class="clr"></div>
            </div>

        <?php } ?>
        <!-- <div class="clr"></div> -->

    </div>
    <div class="clr"></div>
</div>
                            
                <?php }

            } ?>
        	<div class="clr"></div>
        </div>


        <div class="clr" style="height: 20px"></div>
        <hr>
        <input type="submit" class="btn btn-primary" name="updateLayout" value="Update">
        
        <div class="clr"></div>
    </form>

</div><!-- Add -->
