<form role="form" action="" method="post" >
    <?php 
        wp_nonce_field('searchContacts','csrf_token_nonce');
    ?>   
    <div class="form-group col-md-6">
        <label class="col-md-12 control-label pd-0">Email&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
        <div class="col-md-12 pd-0">                                                        
            <input type="text" name="searchEmail" class="form-control" required placeholder="Email" value="<?php if(isset($_POST['searchEmail']))echo esc_attr(sanitize_email($_POST['searchEmail'])) ?>">
        </div>
    </div>
    <div class="clr"></div>

    <div class="form-group  col-md-6">
        <input type="submit" class="btn btn-primary float-right btn-padding" name="searchContacts" value="Search">                        
    </div>
    <div class="clr"></div>

</form>

<div class="clr"></div>


<form role="form" action="" method="post" class="form-horizontal form-Settings" id="Settings" style="<?php if(!isset($contactList['data'])) echo "display: none;" ?>">

    <hr>
    <?php 
        wp_nonce_field('addforExist','csrf_token_nonce');
    ?>
    <div class="form-group col-md-6">
        <label class="col-md-12 pd-0 control-label">Select a User</label>
        <div class="col-md-12 pd-0">       
            <select name="user" class="form-control" required id="selectFrmExContacts">
                <option value="">--Select One--</option>
                <?php if(isset($contactList['data']) && count($contactList['data']) > 0){
                    foreach ($contactList['data'] as $crmCon) {
                        if(isset($crmCon['Account_Name']['id']) || isset($crmCon['Account']['id'])){
                        ?>
                            <option value="<?php echo $crmCon['id'];?>"> <?php echo $crmCon['Full_Name'];?></option>
                        <?php }
                    }
                } ?>
            </select>
        </div>
    </div>
    <div class="clr"></div>

    <div class="form-group col-md-6">
        <label class="col-md-12 pd-0 control-label">User Name&nbsp;<span><i class="fas fa-info-circle" title="test"></i></span></label>
        <div class="col-md-12 pd-0">                                                        
            <input type="email" id="exContactUn" name="username" class="form-control" required value="<?php if(isset($_POST['username']))echo esc_attr(sanitize_text_field($_POST['username'])) ?>">
        </div>
    </div>
    <div class="form-group col-md-6">
        <label class="col-md-12 pd-0 control-label">Password&nbsp;<span><i class="fas fa-info-circle" title="test"></i></span></label>
        <div class="col-md-12 pd-0">                                                        
            <input type="text" id="exContactPs" name="password" class="form-control" required value="<?php if(isset($_POST['password']))echo esc_attr(sanitize_text_field($_POST['password'])) ?>">
        </div>
    </div>

    <div class="clr"></div>
    <div class="form-group">
        <div class="col-md-12 ">
            <input type="submit" class="btn btn-primary btn-padding" style="float: right;" name="addforExist" value="Save">
        </div>
    </div>
    <div class="clr"></div>

</form>