<!-- Action Permission -->
<form role="form" action="" method="post" id="actionPermission" class=" box-shadow pd-30" style="margin: 0px -15px 0px -15px;border:none">  
    <h4 style="color: #0091d8;margin-bottom: 20px;">Action Permission</h4>
    
    <?php 
        wp_nonce_field('actionPermission','csrf_token_nonce');
    ?>
    <input type="hidden" name="module" value="<?php echo esc_attr($acTab); ?>">
    <input type="hidden" name="actionPermission">

    <div class="form-group">

    <!--     <label class="control-label" style="margin-right: 20px;"><?php echo str_replace("_", " ", $module); ?> Edit</label>
        <label class="switch">
            <input type="checkbox" <?php //echo (isset($acpMdlV['Edit'])) ? 'checked' : '';?> name="Edit">
            <span class="slider round"></span>
        </label>
 -->
    <div class="crm-module-list getnewmodule">
        <label class="control-label" style="margin-right: 20px;margin-top: -9px;"><?php echo str_replace("_", " ", $module); ?> Edit</label>
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
        
        <label class="control-label" style="margin-right: 20px;margin-top: -9px;">Attachment Delete</label>
        <label class="switch control-label switch-appearence" style="margin-top: -10px;">
            <input type="checkbox" <?php echo (isset($acpMdlV['AttcDelete'])) ? 'checked' : '';?> name="AttcDelete">
          <span class="slider round slider-appearence"></span>
        </label>

    </div>

<!-- 
        <label class="control-label" style="margin-right: 20px;">Attachment Delete</label>
        <label class="switch">
            <input type="checkbox" <php// echo (isset($acpMdlV['AttcDelete'])) ? 'checked' : '';?> name="AttcDelete">
            <span class="slider round"></span>
        </label> -->
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


<?php if(isset($gglintOn)) { ?>
<!-- Auto complete address field map -->
<form role="form" action="" method="post" id="autocompleteaddress" class="box-shadow pd-30"  style="margin: 0px -15px 0px -15px;border:none">
    
    <input type="hidden" name="module" value="<?php echo esc_attr($acTab); ?>">
    <input type="hidden" name="addressfildmap">

    <div class="form-group">


    <div class="crm-module-list getnewmodule">
        
        <label class="control-label" style="margin-right: 20px;margin-top: -9px;">Address auto complete</label>
        <label class="switch control-label switch-appearence" style="margin-top: -10px;">
            <input type="checkbox" <?php echo (isset($acadrsV['status'])) ? 'checked' : '';?> name="status">
          <span class="slider round slider-appearence"></span>
        </label>

    </div>


<!-- 
        <label class="control-label" style="margin-right: 20px;">Address auto complete</label>
        <label class="switch">
            <input type="checkbox" <?php// echo (isset($acadrsV['status'])) ? 'checked' : '';?> name="status">
            <span class="slider round"></span>
        </label> -->
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div class="form-group">
        <h4 class="control-label" style="margin-right: 20px;">Fiels map</h4>            
    </div>
    <div class="clr"></div>

    <div class="form-group">
        <label class="control-label" style="margin-right: 0px;"> Street </label>
        <select class="form-control" required name="street_number" style="width: 100%;max-width: 100%;">
            <option value="">--Select One--</option>
            <?php 
            $slctdAddFld = "";
            if(isset($acadrsV['street_number']) && ($acadrsV['street_number'] !="")){

                $street = $acadrsV['street_number'];
                $streetArr = explode("___", $street);
                if(isset($streetArr[1])) $streetDV = $streetArr[1];
                else $streetDV = $street;  
                $slctdAddFld = '<option value="'.$street .'">'.$streetDV.'</option>';
                ?>
                <option selected value="<?php echo $street ?>"><?php echo str_replace("_", " ", $streetDV) ?></option>

            <?php } 
            echo str_replace($slctdAddFld, "", $editAbleFld);
            ?>
        </select>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div class="form-group">
        <label class="control-label" style="margin-right: 0px;"> City </label>
        <select class="form-control" required name="locality" style="width: 100%;max-width: 100%;">
            <option value="">--Select One--</option>
            <?php 
            $slctdAddFld = "";
            if(isset($acadrsV['locality']) && ($acadrsV['locality'] !="")){

                $city = $acadrsV['locality'];
                $cityArr = explode("___", $city);
                if(isset($cityArr[1])) $cityDV = $cityArr[1];
                else $cityDV = $city;  
                $slctdAddFld = '<option value="'.$city .'">'.$cityDV.'</option>';
                ?>
                <option selected value="<?php echo $city ?>"><?php echo str_replace("_", " ", $cityDV) ?></option>

            <?php } 
            echo str_replace($slctdAddFld, "", $editAbleFld);
            ?>
        </select>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div class="form-group">
        <label class="control-label" style="margin-right: 0px;"> State </label>
        <select class="form-control" required name="administrative_area_level_1" style="width: 100%;max-width: 100%;">
            <option value="">--Select One--</option>
            <?php 
            $slctdAddFld = "";
            if(isset($acadrsV['administrative_area_level_1']) && ($acadrsV['administrative_area_level_1'] !="")){

                $state = $acadrsV['administrative_area_level_1'];
                $stateArr = explode("___", $state);
                if(isset($stateArr[1])) $stateDV = $stateArr[1];
                else $stateDV = $state;  
                $slctdAddFld = '<option value="'.$state .'">'.$stateDV.'</option>';
                ?>
                <option selected value="<?php echo $state ?>"><?php echo str_replace("_", " ", $stateDV) ?></option>

            <?php } 
            echo str_replace($slctdAddFld, "", $editAbleFld);
            ?>
        </select>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div class="form-group">
        <label class="control-label" style="margin-right: 0px;"> Zip code </label>
        <select class="form-control" required name="postal_code" style="width: 100%;max-width: 100%;">
            <option value="">--Select One--</option>
            <?php 
            $slctdAddFld = "";
            if(isset($acadrsV['postal_code']) && ($acadrsV['postal_code'] !="")){

                $zipcode = $acadrsV['postal_code'];
                $zipcodeArr = explode("___", $zipcode);
                if(isset($zipcodeArr[1])) $zipcodeDV = $zipcodeArr[1];
                else $zipcodeDV = $zipcode;  
                $slctdAddFld = '<option value="'.$zipcode .'">'.$zipcodeDV.'</option>';
                ?>
                <option selected value="<?php echo $zipcode ?>"><?php echo str_replace("_", " ", $zipcodeDV) ?></option>

            <?php } 
            echo str_replace($slctdAddFld, "", $editAbleFld);
            ?>
        </select>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div class="form-group">
        <label class="control-label" style="margin-right: 0px;"> Country </label>
        <select class="form-control" required name="country" style="width: 100%;max-width: 100%;">
            <option value="">--Select One--</option>
            <?php 
            $slctdAddFld = "";
            if(isset($acadrsV['country']) && ($acadrsV['country'] !="")){

                $country = $acadrsV['country'];
                $countryArr = explode("___", $country);
                if(isset($countryArr[1])) $countryDV = $countryArr[1];
                else $countryDV = $country;  
                $slctdAddFld = '<option value="'.$country .'">'.$countryDV.'</option>';
                ?>
                <option selected value="<?php echo $country ?>"><?php echo str_replace("_", " ", $countryDV) ?></option>

            <?php } 
            echo str_replace($slctdAddFld, "", $editAbleFld);
            ?>
        </select>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary btn-padding  float-right newSubmitBtn" name="addressfildmap" value="Update">
    </div>
    <div class="clr"></div>
</form>
<div class="clr"></div>

<?php } ?>