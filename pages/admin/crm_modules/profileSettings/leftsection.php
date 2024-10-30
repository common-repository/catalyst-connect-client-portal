<!-- Field sync -->
<form role="form" action="" method="post" id="syncFields" class="box-shadow pd-30" style="margin: 0px -15px 0px -15px;border:none;    padding-top: 40px;">
    <?php 
        wp_nonce_field('syncFields','csrf_token_nonce');
    ?>
    <div class="form-group" style="margin-bottom: 0px;">

            <label class="control-label" style="margin-right: 20px;">Get the new fields Sync</label>
            <input type="hidden" name="module" value="<?php echo esc_attr($module); ?>">

            <input type="submit" class="btn btn-primary btn-padding newSubmitBtnDesign" name="syncFields" value="Sync Now" style="float: left;padding: 3px;padding-left: 15px;padding-right: 15px;">
            
            <div class="clr"></div>
   
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
</form>
<div class="clr"></div>

<!-- Show permission -->
<form role="form" action="" method="post" id="showPermission" class="box-shadow pd-30 pd-t-0"  style="margin: 0px -15px 0px -15px;border:none">
    
    <?php 
        wp_nonce_field('showPermission','csrf_token_nonce');
    ?>
    <input type="hidden" name="module" value="<?php echo esc_attr($acTab); ?>">
    <input type="hidden" name="showPermission">

    <div class="form-group">
        <!-- <label class="control-label" style="margin-right: 20px;">Show in user portal</label>
        <label class="switch">
            <input type="checkbox" <?php //echo (isset($spMdlV['showMenu'])) ? 'checked' : '';?> name="showMenu">
            <span class="slider round"></span>
        </label> -->

            <div class="crm-module-list getnewmodule">
                 <label class="control-label" style="margin-right: 20px;margin-top: -9px;">Show in user portal</label>

                    <label class="switch control-label switch-appearence" style="margin-top: -10px;">
                        <input type="checkbox" <?php echo (isset($spMdlV['showMenu'])) ? 'checked' : '';?> name="showMenu">

                      <span class="slider round slider-appearence"></span>
                    </label>
            </div>


        <div class="clr"></div>
    </div>




    <div class="clr"></div>

    <div class="form-group" id="menuLabel">
       <!--  <label class="control-label" style="margin-right: 20px;">Menu Label</label>
        <input type="text" class="form-control" name="menuLabel" value="<?php //echo (isset($spMdlV['menuLabel']) && $spMdlV['menuLabel'] !="") ? $spMdlV['menuLabel'] : "Profile" ?>"> -->

        <label class="control-label" style="margin-right: 0px;">Menu Label</label>
        <input type="text" class="form-control col-md-12" name="menuLabel" value="<?php echo (isset($spMdlV['menuLabel'])) ? esc_attr($spMdlV['menuLabel']) : str_replace("_", " ", esc_attr($module)) ?>" style="max-width: 100%;height: 35px !important;">


        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div class="form-group">
        <label class="control-label" style="margin-right: 0px;"> Parent Account Field </label>
        <select class="form-control " name="parentaccountField" style="max-width: 100%;">
            <option value="">--Select One--</option>
            <?php 
            $selectedAcField = "";
            if(isset($spMdlV['parentaccountField']) && ($spMdlV['parentaccountField'] !="")){

                $parentaccountField = $spMdlV['parentaccountField'];
                $fieldAcArr = explode("___", $parentaccountField);
                if(isset($fieldAcArr[1])){
                    $fieldAcDValue = $fieldAcArr[1];
                }else{
                    $fieldAcDValue = $parentaccountField;                        
                }
                $selectedAcField = '<option value="'.$parentaccountField .'">'.$fieldAcDValue.'</option>';
             ?>
                <option selected value="<?php echo $parentaccountField ?>"><?php echo str_replace("_", " ", $fieldAcDValue) ?></option>

            <?php } ?>
            
            <?php 
                echo str_replace($selectedAcField, "", $lookUpFld);
                // echo $lookUpFld;
            ?>
        </select>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>    

    <!--div class="form-group">
        <label class="control-label" style="margin-right: 20px;">Show "Member Accounts" in user portal</label>
        <label class="switch">
            <input type="checkbox" <?php echo (isset($spMdlV['showMACMenu'])) ? 'checked' : '';?> name="showMACMenu">
            <span class="slider round"></span>
        </label>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>    

    <div class="form-group" id="menuLabel">
        <label class="control-label" style="margin-right: 20px;">Member Accounts Menu Label</label>
        <input type="text" class="form-control" name="MAmenuLabel" value="<?php echo (isset($spMdlV['MAmenuLabel']) && $spMdlV['MAmenuLabel'] !="") ? $spMdlV['MAmenuLabel'] : "Member Accounts" ?>">
        <div class="clr"></div>
    </div>
    <div class="clr"></div-->

    <div class="form-group">
        <input type="submit" class="btn btn-primary btn-padding  newSubmitBtn float-right" name="updateModuleStt" value="Update">
    </div>
    <div class="clr"></div>
</form>
<div class="clr"></div>   


<form role="form" action="" method="post" class="box-shadow pd-30 moduleTitleDescription" style="margin: 0px -15px 0px -15px;border:none">    
    
    <?php 
        wp_nonce_field('moduleTitleDescription','csrf_token_nonce');
    ?>

    <input type="hidden" name="module" value="<?php echo esc_attr($acTab); ?>">
    <input type="hidden" name="moduleTitleDescription">

    <p style="font-size: 13px;font-weight: bold;">If you would like to add a title and description above the content in tab, select the checkbox and enter your content.</p>
    <hr style="width: 100%;margin-top: 0px;">


    <div class="form-group">
            <div class="crm-module-list getnewmodule">
                 <label class="control-label" style="margin-right: 20px;margin-top: -9px;">Show Title</label>

                    <label class="switch control-label switch-appearence" style="margin-top: -10px;">
                        <input type="checkbox" <?php echo (isset($mtdV['Title'])) ? 'checked' : '';?>  name="Title" class="titlePermission" style="max-width: 100%">

                      <span class="slider round slider-appearence"></span>
                    </label>
            </div>


      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div class="form-group TitleContent">
       
         <label class="control-label" style="margin-right: 0px;">Title Content</label>
            <input type="text" class="form-control" name="TitleContent" value="<?php echo (isset($mtdV['TitleContent'])) ? esc_attr(stripslashes($mtdV['TitleContent'])) : '';?>" style="height: 38px !important;">
          <div class="clr"></div>

      <div class="clr"></div>
    </div>

    <div class="form-group">
          <div class="crm-module-list getnewmodule">
             <label class="control-label" style="margin-right: 20px;margin-top: -9px;">Show Description</label>

                <label class="switch control-label switch-appearence" style="margin-top: -10px;">
                    <input type="checkbox" <?php echo (isset($mtdV['Description']))?'checked':'';?>  name="Description" class="descPermission" style="max-width: 100%">

                  <span class="slider round slider-appearence"></span>
                </label>
            </div>

      <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div class="form-group DescriptionContent">
        <label class="control-label" style="margin-right: 0px;">Description Content</label>
        <textarea style="height: 90px;border-radius: 10px;" class="form-control" name="DescriptionContent"><?php echo (isset($mtdV['DescriptionContent'])) ? stripslashes($mtdV['DescriptionContent']) : '';?></textarea>
      <div class="clr"></div>
    </div>
    <div class="clr" style="height: 0px"></div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary btn-padding newSubmitBtn  float-right" name="updateModuleTD" value="Update">
    </div>
    <div class="clr"></div>
</form>