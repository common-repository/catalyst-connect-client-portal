<?php
    global $wpdb;
    global $wp;

    if(isset($_GET['module']))$acTab = sanitize_text_field($_GET['module']);
    if(isset($_GET['acTab']))$apkey = sanitize_text_field($_GET['acTab']);
    $module = str_replace("con", "", str_replace("acc", "", $acTab)) ;
    // $moduleFld = $CrmModules->getFields($module);

    $crmMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'lay_View_$acTab'");
    if(isset($crmMdl->option_index))$layViewV = json_decode($crmMdl->option_value, true); 
    // echo "<pre>";var_dump($layViewV);echo "</pre>";
?>

<div class="tab-pane fade active in" id="apTab_View">

    <form role="form" action="" method="post" id="layout_View>">   
        <input type="hidden" name="actionType" value="View">
        <input type="hidden" name="module" value="<?php echo $acTab; ?>">

        <button type="button" class="addRow"><i class="fas fa-plus"></i> Add Section</button>
        <?php if($subformFld !=""){ ?>
            <button type="button" class="addRowSubform"><i class="fas fa-plus"></i> Add Subform Section</button>
        <?php } ?>

        <div class="layoutFields">

            <?php if(isset($layViewV['rowAdded'])){
                $fldP = 0;
                foreach ($layViewV['rowAdded'] as $rowP => $exrow) {
                    $rowAdded = $rowP + 1;
                ?>               

<div class="row layoutRow">
    <div class="layoutRowHeader col-md-12">
        <input type="hidden" class="rowAdded" name="rowAdded[]" value="<?php echo $rowAdded ?>">
        <input type="hidden" class="columnAdded" name="columnAdded[]" value="<?php echo $layViewV['columnAdded'][$rowP]; ?>">
        <button type="button" class="movesection"><i class="fas fa-arrows-alt"></i></button>
        <button type="button" class="addLayColumn"><i class="fas fa-plus"></i> Add Field</button>

        <input type="text" class="form-control sectiontitle" placeholder="Section Title" name="sectiontitle[]" value="<?php echo (isset($layViewV['sectiontitle'][$rowP])) ? $layViewV['sectiontitle'][$rowP] : ""; ?>">

        <button type="button" class="remove removeRow" title="Remove"><i class="fas fa-times"></i></button>

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

            $fieldnameArr = explode("___", $exfldN);
            if(isset($fieldnameArr[1])){
                $fieldApiName = $fieldnameArr[0];
                $fieldDValue = $fieldnameArr[1];
            }else{
                $fieldApiName = $exfldN;
                $fieldDValue = $exfldN;                        
            }
            $selectedField = '<option value="'.$exfldN .'">'.$fieldDValue.'</option>';

            $subfSlct= "";
            $subfFldSlct= "width: calc(100% - 40px) !important;";
            if(isset($layViewV['subformfieldName_'.$fieldApiName])) {
                $isSubfrom = true;
                $subfSlct = "width: calc(100% - 75px) !important;";
            }

            $fldP++;
            ?>

            <div class="layoutColumn col-md-<?php echo $exlyFldClm; ?>">

                <button type="button" class="movecolumn"><i class="fas fa-arrows-alt"></i></button>
                <?php if(!$isSubfrom){ ?>
                    <button type="button" class="makeCol12"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                    <button type="button" class="makeCol6"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                <?php } ?>
                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo $exlyFldClm; ?>">
                <input type="hidden" class="layoutFieldList" name="layoutFieldList[]" value="<?php echo $rowAdded.'_'.$fieldApiName ?>">
                <select class="form-control update_fieldNameValue <?php if($isSubfrom)echo 'subfromSelect'; ?>" style="<?php echo $subfSlct; ?>" name="fieldName[]" required>
                    <option value="">--Select One--</option>
                    <option selected value="<?php echo $exfldN ?>"><?php echo str_replace("_", " ", $fieldDValue) ?></option>
                    <?php 
                        // echo $selectedField;
                    if($isSubfrom){
                    	echo str_replace($selectedField, "", $subformFld);
                    }else{
                        echo str_replace($selectedField, "", $viewAbleFld);
                    }
                        // echo $viewAbleFld;
                    ?>
                </select>
                <button type="button" class="remove removeLColumn"><i class="fas fa-times"></i></button>
                <div class="clr"></div>
                <?php  
                if($isSubfrom){
                	$SubfromCRMFields = $CrmModules->getModuleFieldList($fieldApiName);
                	$sffname = 'subformfieldName_'.$fieldApiName.'[]';
                	?>

		            <div class="slctSubfromLayout">
		                <div class="SubfromCRMFields" style="display: none;">
		                	<div class="layoutSfTbl col-md-3" style="padding: 0px;float: left;margin: 15px 0px 15px 1%;">          
						        <select class="form-control" name="<?php echo $sffname ;?>" style="<?php echo $subfFldSlct; ?>">
						        	<option value="">--Select One--</option>
						        	<?php foreach ($SubfromCRMFields as $sfcrmf) { 
						        		if($sfcrmf['view_type']['view'] == true){
					                        echo '<option value="'.$sfcrmf['api_name'].'___'.$sfcrmf['field_label'] .'">'.$sfcrmf['field_label'].'</option>';
					                    }
						        	} ?>
						        </select>
						        <button type="button" class="remove removeSfColumn"><i class="fas fa-times"></i></button>
						        <div class="clr"></div>
						    </div>
						</div>
		                <div class="" style="padding: 0px;float: left;margin: 15px 0px;">    
		                    <button type="button" class="addFieldSubform" style="padding: 5px;"><i class="fas fa-plus"></i> Add Field</button> 
		                </div>
		                <?php foreach ($layViewV['subformfieldName_'.$fieldApiName] as $sfexf) { 
		                	if($sfexf !=""){
		                	$sfexfArr  = explode("___", $sfexf); ?>
		                
				                <div class="layoutSfTbl col-md-3" style="padding: 0px;float: left;margin: 15px 0px 15px 1%;">          
							        <select class="form-control" name="<?php echo $sffname ;?>" style="<?php echo $subfFldSlct; ?>">
							        	<?php foreach ($SubfromCRMFields as $sfcrmf) { 
							        		if($sfcrmf['view_type']['view'] == true){
							        			if($sfcrmf['api_name'] == $sfexfArr[0]) $slctdsff = "selected"; else $slctdsff = "";
						                        echo '<option '.$slctdsff.' value="'.$sfcrmf['api_name'].'___'.$sfcrmf['field_label'] .'">'.$sfcrmf['field_label'].'</option>';
						                    }
							        	} ?>
							        </select>
							        <button type="button" class="remove removeSfColumn"><i class="fas fa-times"></i></button>
							        <div class="clr"></div>
							    </div>
							<?php }
						} ?>
		            </div>
		        <?php } ?>
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

</div><!-- View -->
