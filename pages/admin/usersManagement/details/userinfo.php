<?php 
$amprmsn = $CommonClass->getOptionBy_nmindx('dashboard', 'account_manager_permission');
if(isset($amprmsn->option_value)){
    $amprmsnV = json_decode($amprmsn->option_value);
}

$uPLvlV = "ReadWrite";
$usrPrmsnLvl = $CommonClass->getOptionBy_nmindx('userdata-pl', $crmConId);
if(isset($usrPrmsnLvl->option_value)){
    $uPLvlV = $usrPrmsnLvl->option_value;
}

$userdata_ql = $CommonClass->getOptionBy_nmindx('userdata-ql', $uid);
if(isset($userdata_ql->option_value)){
    $dbOpqlV = json_decode($userdata_ql->option_value, true);
}
?>

<style type="text/css">
    input.readonly, input[readonly], textarea.readonly, textarea[readonly] {
        background-color: #fff;
    }
</style>

<!-- User Information Start -->
<div class="clr"></div>
<div class="card col-md-12" style="border: none">
    <h4 class="" style="border-bottom: none;padding-left: 40px;">User Info</h4>
    <div class="card-body ">

    <div class="col-md-6">
        <div class="takepadding">
            <label class="control-label" style="padding-left: 0px;">Name</label> 
             <input type="text" name="" class="form-control checkpadding" value="<?php echo esc_attr($contacts->fullname); ?>" readonly>
        </div>
    </div>
     <div class="col-md-6">
        <div class="takepadding">
             <label class="control-label" style="padding-left: 0px;">Email</label> 
             <input type="text" name="" class="form-control checkpadding" value="<?php echo esc_attr($contacts->email); ?>" readonly>
           <!--  <label>Email</label> : <?php //echo $contacts->email); ?> -->
        </div>
    </div>
     <div class="col-md-6">
        <div class="takepadding">
            <label class="control-label" style="padding-left: 0px;">Phone</label> 
             <input type="text" name="" class="form-control checkpadding" value="<?php echo esc_attr($contacts->phone); ?>" readonly>
            <!-- <label>Phone</label> : <?php echo $contacts->phone; ?> -->
        </div>
    </div>
     <div class="col-md-6">
        <div class="takepadding">
             <label class="control-label" style="padding-left: 0px;">Status</label> 
             <input type="text" name="" class="form-control checkpadding" value="<?php echo esc_attr($contacts->status); ?>" readonly>
            <!-- <label>Status</label> : <?php //echo $contacts->status; ?> -->
        </div>
    </div>
        <!-- <div style="clear: both;"></div> -->
    </div>
    <div class="clr"></div>
</div>
<!-- User Information End -->
<div class="clr"></div>


<!-- Account Manager Start -->

<div class="card col-md-12" style="border: none">
    <h4 class="" style="border-bottom: none;padding-left: 40px;">Account Manager</h4>

    <div class="card-body ">

       <form role="form" action="" method="post" > 
            <?php 
                wp_nonce_field('updateAccountManager','csrf_token_nonce');
            ?>

            <div class="form-group col-md-6 ">
                <div class="crm-module-list">
                    <label class="control-label" style="margin-right: 20px;">Display ( Name, Email, Phone)</label>
                    <label class="switch control-label">
                        <input type="checkbox" name="permission" <?php if(isset($amprmsnV->permission))echo "checked"; ?>>
                        <span class="slider round"></span>
                    </label>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            </div>



            <!-- <div class="form-group col-md-12">
                <label class="control-label" style="margin-right: 20px;">Display ( Name, Email, Phone)</label>
                <input type="checkbox" name="permission" <?php if(isset($amprmsnV->permission))echo "checked"; ?>>
                <div class="clr"></div>
            </div> -->
            <div class="clr"></div>

            <div class="form-group col-md-12 ">
                <input type="submit" class="btn btn-primary newSubmitBtn btn-padding" name="updateAccountManager" value="Update">
            </div>
            <div class="clr"></div>
        </form>

    </div>
    
    <div class="clr"></div>
</div>
<!-- Account Manager End -->
<div class="clr"></div>


<!-- Permission Level -->
<div class="card col-md-12" style="border: none">
    <h4 class="" style="border-bottom: none;padding-left: 40px;">Permission Level</h4>
    
    <div class="card-body ">

       <form role="form" action="" method="post" style="padding-left: 15px;"> 
            <?php 
                wp_nonce_field('updatePermissionLevel','csrf_token_nonce');
            ?>

            <input type="hidden" name="uid" value="<?php echo esc_attr($crmConId); ?>">

            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="radio" name="permission" <?php if($uPLvlV =="ReadOnly")echo "checked"; ?> value="ReadOnly">
                                <label class="control-label" style="margin-right: 20px;">Read Only</label>
                                <div class="clr"></div>
                            </div>
                        </div>

                         <div class="col-md-3">
                            <div class="form-group">
                               
                                <input type="radio" name="permission" <?php if($uPLvlV =="ReadWrite")echo "checked"; ?> value="ReadWrite">
                                <label class="control-label" style="margin-right: 20px;">Read/Write</label>
                                <div class="clr"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


           
            <div class="clr"></div>

            
            <div class="clr"></div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary newSubmitBtn btn-padding" name="updatePermissionLevel" value="Update" >
            </div>
            <div class="clr"></div>
        </form>

    </div>
    
    <div class="clr"></div>
</div>
<!-- Permission Level End -->
<div class="clr"></div>

<!-- User Quiclink Start -->
<div class="card col-md-12" style="border: none">
    <h4 class="" style="border-bottom: none;padding-left: 40px;">Home Page Quick Links</h4>
    <div class="card-body ">

        <form role="form" action="" method="post" > 
            <?php 
                wp_nonce_field('updateUserLink','csrf_token_nonce');
            ?>
            <input type="hidden" name="ql_user_id" value="<?php echo esc_attr($contacts->id); ?>">

            <div class="form-group">
                <div class="col-md-12" id="linkListCon">                     
                    <?php 
                    if(isset($dbOpqlV['link']) && $dbOpqlV['link'] !=""){
                        $dbOpqll = json_decode($dbOpqlV['link']);
                        $dbOpqlt = json_decode($dbOpqlV['title']);

                        foreach ($dbOpqll as $lk => $qlLst) { 
                            if($qlLst !=""){ ?>
                                <div class="linkCon">   

                                    <div class="form-group col-md-5 pd-l-0">
                                         <label class="control-label pd-l-0" >Web Tab Name</label> 
                                         <input type="text" name="quickLinkT[]" class="form-control quickLinkinput" placeholder="exe: Google" value="<?php echo esc_attr($dbOpqlt[$lk]);  ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                         <label class="control-label pd-l-0" >Web Tab Link</label> 
                                        <input type="url" name="quickLink[]" class="form-control quickLinkinput" placeholder="exe: https://google.com" value="<?php echo esc_url($qlLst);  ?>">
                                    </div>
                                    <div class="form-group col-md-1" style="margin-top: 27px;">
                                        <button type="button" class="btn btn-danger removeQ_Link" ><i class="fas fa-minus"></i></button>
                                    </div>

                                    <div class="clr"></div>
                                </div>   
                                <div class="clr"></div>
                            <?php }
                        }
                    }
                    ?>                    
                    <div class="linkCon">  

                        <div class="form-group col-md-5 pd-l-0">
                             <label class="control-label pd-l-0" >Web Tab Name</label> 
                             <input type="text" name="quickLinkT[]" class="form-control quickLinkinput" placeholder="exe: Google">
                        </div>
                        <div class="form-group col-md-6">
                             <label class="control-label pd-l-0" >Web Tab Link</label> 
                            <input type="url" name="quickLink[]" class="form-control quickLinkinput" placeholder="exe: https://google.com">
                           
                        </div>
                        <div class="form-group col-md-1" style="margin-top: 27px;">
                            <button type="button" id="addQ_Link" class="btn btn-primary" ><i class="fas fa-plus"></i></button>
                        </div>

                        <div class="clr"></div>
                    </div>
                    <div class="clr"></div>

                </div>
                <div class="clr"></div>

            </div>
            <div class="clr"></div>

            <div class="form-group col-md-12">
                <input type="submit" class="btn btn-primary newSubmitBtn btn-padding" name="updateUserLink" value="Update">
            </div>
            <div class="clr"></div>
        </form>
        
        <div class="clr"></div>
    </div>
    
    <div class="clr"></div>
</div>
<!-- User Quiclink End -->
<div class="clr"></div>

<script type="text/javascript">
    jQuery(document).on('click', '.removeQ_Link', function(event) {
        event.preventDefault();
        jQuery(this).closest('.linkCon').remove();
    });
    jQuery(document).on('click', '#addQ_Link', function(event) {
        event.preventDefault();
        var html_ = '<div class="linkCon">\
                        <div class="form-group col-md-5 pd-l-0">\
                             <label class="control-label pd-l-0" >Web Tab Name</label> \
                             <input type="text" name="quickLinkT[]" class="form-control quickLinkinput" placeholder="exe: Google">\
                        </div>\
                        <div class="form-group col-md-6">\
                             <label class="control-label pd-l-0" >Web Tab Link</label> \
                            <input type="url" name="quickLink[]" class="form-control quickLinkinput" placeholder="exe: https://google.com">\
                        </div>\
                        <div class="form-group col-md-1" style="margin-top: 27px;">\
                            <button type="button" class="btn btn-danger removeQ_Link" ><i class="fas fa-minus"></i></button>\
                        </div>\
                        <div class="clr"></div>\
                    </div>';


        jQuery("#linkListCon").append(html_);
    });   
</script>