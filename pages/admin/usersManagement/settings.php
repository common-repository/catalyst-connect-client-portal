<?php
/*
    Created By : Mahidul Islam Tamim
*/
    $plugin_dir = CCGP_PLUGIN_PATH;
?>

    <div class="col-md-6 box-shadow pd-30 ">
        <form role="form" action="" method="post" style="">

        <?php 
            wp_nonce_field('um-settings','csrf_token_nonce');
        ?>

            <h4 class="mt-0" style="color: #333;font-weight: 700;">Send credentials to the CRM</h4>
            <div class="clr"></div>
            
            <div class="form-group">
                <div class="crm-module-list">
                    <label class="control-label">Status</label>
                    <label class="switch control-label switch-appearence">
                        <input type="checkbox" name="sctoCRMp" <?php echo (isset($umsetresV['sctoCRMp'])) ?"checked":"";  ?>>
                        <span class="slider round slider-appearence"></span>
                    </label>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>

            <div class="form-group">
                <label class="control-label">User Name</label>                                                     
                <select name="username" class="form-control wd-100">
                    <?php foreach ($fieldList as $fldDetails) {

                        if(isset($umsetresV['username']) && ($umsetresV['username'] == $fldDetails['api_name']))$slct = "selected";
                        else $slct = "";
                        ?>
                        <option <?php echo $slct;?> value="<?php echo $fldDetails['api_name']; ?>"><?php echo $fldDetails['field_label']; ?></option>
                    <?php } ?>
                </select>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>

            <!-- <div class="form-group">
                <label class="control-label">Password</label>                                                  
                <select name="password" class="form-control wd-100">
                    <?php foreach ($fieldList as $fldDetails) {
                        if(isset($umsetresV['password']) && ($umsetresV['password'] == $fldDetails['api_name']))$slct = "selected";
                        else $slct = "";
                        ?>
                        <option <?php echo $slct;?> value="<?php echo $fldDetails['api_name']; ?>"><?php echo $fldDetails['field_label']; ?></option>
                    <?php } ?>
                </select>
                <div class="clr"></div>
            </div> -->
            <div class="clr"></div>
            <div class="form-group">
                <div class="col-md-12 pd-0">
                    <input type="submit" class="btn btn-primary float-right btn-padding" name="um-settings" value="Update">
                </div>
            </div>
            <div class="clr"></div>

        </form> 
        <div class="clr"></div>
    </div>


    <div class="col-md-6 box-shadow pd-30 float-right">
        <form role="form" action="" method="post" style="">

            <?php 
                wp_nonce_field('um-settings-addstt','csrf_token_nonce');
            ?>             

            <h4  style="color: #333;font-weight: 700;">Additional Settings</h4>
            <div class="clr"></div>
            
            <div class="form-group">
                <div class="crm-module-list">
                    <label class="control-label">Auto Approve new Accounts and Users</label>
                
                    <label class="switch control-label switch-appearence">
                        <input type="checkbox" name="userautoapve" <?php echo (isset($umatoapv['userautoapve'])) ?"checked":""; ?>>
                        <span class="slider round slider-appearence"></span>
                    </label>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>

            <div class="form-group">
                <div class="col-md-12 pd-0">
                    <input type="submit" class="btn btn-primary float-right btn-padding" name="um-settings-addstt" value="Update">
                </div>
            </div>
            <div class="clr"></div>

        </form> 
        <div class="clr"></div>
    </div>