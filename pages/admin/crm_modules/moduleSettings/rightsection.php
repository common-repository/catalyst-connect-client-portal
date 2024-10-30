<form role="form" action="" method="post" id="apForm" class=" box-shadow pd-30" style="margin: 0px -15px 0px -15px;border:none">    
        
    <?php 
        wp_nonce_field('actionPermission','csrf_token_nonce');
    ?>
    <input type="hidden" name="module" value="<?php echo esc_attr($acTab); ?>">
    <input type="hidden" name="actionPermission">

    <h4 style="color: #333;font-weight: 700">Action Permission</h4>
    

    <div class="form-group">
    <div class="crm-module-list getnewmodule">
        <label class="control-label" style="margin-right: 20px;margin-top: -9px;font-size: 12px;"><?php echo str_replace("_", " ", $module); ?> View</label>
        <label class="switch control-label switch-appearence" style="margin-top: -10px;">
            <input type="checkbox" <?php echo (isset($acpMdlV['View'])) ? 'checked' : '';?> name="View">

          <span class="slider round slider-appearence"></span>
        </label>
    </div>

      <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div class="form-group">

    <div class="crm-module-list getnewmodule">
        
        <label class="control-label" style="margin-right: 20px;margin-top: -9px;font-size: 12px;"><?php echo str_replace("_", " ", $module); ?> Add</label>
        <label class="switch control-label switch-appearence" style="margin-top: -10px;">
            <input type="checkbox" <?php echo (isset($acpMdlV['Add'])) ? 'checked' : '';?> name="Add">
          <span class="slider round slider-appearence"></span>
        </label>

    </div>


      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div class="form-group">

    <div class="crm-module-list getnewmodule">
        
        <label class="control-label" style="margin-right: 20px;margin-top: -9px;font-size: 12px;"><?php echo str_replace("_", " ", $module); ?> Edit</label>
        <label class="switch control-label switch-appearence" style="margin-top: -10px;">
            <input type="checkbox" <?php echo (isset($acpMdlV['Edit'])) ? 'checked' : '';?> name="Edit">
          <span class="slider round slider-appearence"></span>
        </label>

    </div>


      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div class="form-group">
    <div class="crm-module-list getnewmodule">
        
        <label class="control-label" style="margin-right: 20px;margin-top: -9px;font-size: 12px;"><?php echo str_replace("_", " ", $module); ?> Delete</label>
        <label class="switch control-label switch-appearence" style="margin-top: -10px;">
            <input type="checkbox" <?php echo (isset($acpMdlV['Delete'])) ? 'checked' : '';?> name="Delete">
          <span class="slider round slider-appearence"></span>
        </label>

    </div>


      <div class="clr"></div>
    </div>


    <div class="clr"></div>
    <div class="form-group">

    <div class="crm-module-list getnewmodule">
        
        <label class="control-label" style="margin-right: 20px;margin-top: -9px;font-size: 12px;">Attachment Delete</label>
        <label class="switch control-label switch-appearence" style="margin-top: -10px;">
            <input type="checkbox" <?php echo (isset($acpMdlV['AttcDelete'])) ? 'checked' : '';?> name="AttcDelete">
          <span class="slider round slider-appearence"></span>
        </label>

    </div>


      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    
    <!--div class="clr" style="height: 35px"></div>
    <div class="form-group">
        <label class="control-label" style="margin-right: 20px;">Notes View</label>
        <label class="switch">
            <input type="checkbox" <?php echo (isset($acpMdlV['NoteView'])) ? 'checked' : '';?> name="NoteView">
            <span class="slider round"></span>
        </label>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div class="form-group">
        <label class="control-label" style="margin-right: 20px;">Notes Add</label>
        <label class="switch">
            <input type="checkbox" <?php echo (isset($acpMdlV['NoteAdd'])) ? 'checked' : '';?> name="NoteAdd">
            <span class="slider round"></span>
        </label>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div class="form-group">
        <label class="control-label" style="margin-right: 20px;">Notes Edit</label>
        <label class="switch">
            <input type="checkbox" <?php echo (isset($acpMdlV['NoteEdit'])) ? 'checked' : '';?> name="NoteEdit">
            <span class="slider round"></span>
        </label>
      <div class="clr"></div>
    </div>
    <div class="clr"></div-->


    <div class="form-group">
        <input type="submit" class="btn btn-primary btn-padding  float-right newSubmitBtn" name="updateModuleAcp" value="Update">
    </div>
    <div class="clr"></div>
</form>

<div class="clr"></div>

<div class="modulelistviewstt box-shadow pd-30"  style="margin: 0px -15px 0px -15px;border:none;    padding-top: 0px;">
    <form role="form" action="" method="post" id="tableColumn"  class="">  
        
        <?php 
            wp_nonce_field('listTableColumn','csrf_token_nonce');
        ?>
        <input type="hidden" name="module" value="<?php echo esc_attr($acTab); ?>">
        <input type="hidden" name="listTableColumn">

        <h4 style="color: #333;font-weight: 700">List View Table Column</h4>
      
        <div id="tableColumnList">

            <div id="errorMessage" style="display: none;"></div>
            <?php
            $i=0;
            $lstvwpclst = array();
            if(isset($ltcMdlV['column'])){
                
                foreach ($ltcMdlV['column'] as $key => $value) {
                    $fieldnameArr = explode("___", $value);
                    if(isset($fieldnameArr[1])){
                        $fieldApiName = $fieldnameArr[0];
                        $fieldDValue = $fieldnameArr[1];
                    }else{
                        $fieldApiName = $value;
                        $fieldDValue = $value;                        
                    }
                    if($module == "Deals" && $fieldApiName == "Stage"){}
                    else{
                        if(!empty($CrmModules->getPicListValue($module, $fieldApiName))){
                            $lstvwpclst[$fieldApiName] = array('api' => $fieldApiName, 'dv' => $fieldDValue);
                        }
                    }
                    $selectedField = '<option value="'.$value .'">'.$fieldDValue.'</option>';
                    $i++;
                    ?>            
                    <div class="form-group">
                        <select class="form-control" name="column[]" style="max-width: <?php if($key >0) echo '90%'; else echo '100%';?>">
                        <option value="">--Select One--</option>
                        <option selected value="<?php echo $value ?>"><?php echo str_replace("_", " ", $fieldDValue) ?></option>
                            <?php 
                                // echo $selectedField;
                                echo str_replace($selectedField, "", $viewAbleFld);
                            ?>
                        </select>
                        <?php if($i !=1){ ?>
                            <button type="button" class="btn btn-danger removeColumn" ><i class="fas fa-minus"></i></button>
                        <?php } ?>
                        <div class="clr"></div>
                    </div>
                    <div class="clr"></div>
                <?php }
            }else { 
                $i=1;
                ?>

                <div class="form-group">
                    <select class="form-control" name="column[]" required style="max-width: <?php  echo '100%';?>">
                        <option value="">--Select One--</option>
                        <?php echo $viewAbleFld ?>
                    </select>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            <?php } ?>

        </div>

        <div class="clr"></div>

        <div class="form-group" style="max-width: 100%;">
            <input type="submit" class="btn btn-primary btn-padding  newSubmitBtn" name="updateModuleStt" value="Update" style="float: left;">
            <button type="button" id="addColumn" class="btn btn-primary btn-padding float-right newSubmitBtn" style="float: right;">
                <!-- <i class="fas fa-plus"></i> -->Add Column
            </button>
        </div>
        <div class="clr"></div>
    </form>
    <script type="text/javascript">
        var numOfC = <?php echo $i;?>;
        jQuery(document).on('click', '.removeColumn', function(event) {
            event.preventDefault();
            jQuery(this).closest('.form-group').remove();
            numOfC--;
        });
        jQuery(document).on('click', '#addColumn', function(event) {
            // if(numOfC >= 4 ){
            //     jQuery("#errorMessage").html("You can add maximum 4 column");
            //     jQuery("#errorMessage").show();
            //     setTimeout(function() {
            //         $('#errorMessage').hide(1000);
            //     }, 3000);
            //     return false;
            // }else{
                numOfC++;
                event.preventDefault();
                var html_ = jQuery("#crm-viewable-fields").html();
                jQuery("#tableColumnList").append(html_);
            // }
        });   
    </script>

    <?php
    if(!empty($lstvwpclst)){
        ?>
        <div class="clr" style="height: 35px;"></div>
        <form role="form" action="" method="post" id="addFilter">  

            <?php 
                wp_nonce_field('addFilter','csrf_token_nonce');
            ?>

            <input type="hidden" name="module" value="<?php echo $acTab; ?>">
            <input type="hidden" name="addFilter">

            <h4 style="color: #0091d8;">Add Filter</h4>
            <!-- <hr style="width: 100%;margin-top: 0px;">/// -->

            <div class="form-group">

                <div class="crm-module-list getnewmodule">
                    
                    <label class="control-label" style="margin-right: 20px;margin-top: -9px;">Status</label>
                    <label class="switch control-label switch-appearence" style="margin-top: -10px;">
                          <input type="checkbox" <?php echo (isset($mfltr['status'])) ? 'checked' : '';?> name="status">
                      <span class="slider round slider-appearence"></span>
                    </label>

                </div>


              <div class="clr"></div>
            </div>
            <div class="clr"></div>        

            <div class="form-group">
                <label class="control-label" style="margin-right: 20px;">Field Name</label>
                <select class="form-control" required name="filterfld" style="width: 100%;max-width: 100%;">
                    <option value="">-None-</option>
                    <?php 
                    foreach ($lstvwpclst as $lstvwpclstv) {
                        $fldnms = $lstvwpclstv['api'].'___'.$lstvwpclstv['dv'];
                        $slctd = (isset($mfltr['filterfld']) && ($mfltr['filterfld'] == $fldnms)) ? "selected" : "";
                        echo '<option '.$slctd.' value="'.$fldnms.'">'.$lstvwpclstv['dv'].'</option>';
                    }
                    ?>                
                </select>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>

            <div class="form-group" style="">
                <input type="submit" class="btn btn-primary btn-padding float-right newSubmitBtn" name="addFilter" value="Update">
            </div>
            <div class="clr"></div>

        </form>
    <?php } ?>
</div>