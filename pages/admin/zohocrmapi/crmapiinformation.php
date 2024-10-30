<div class="col-md-6 col-lg-6">
    <form role="form" action="https://portal-plugin.thecatalystcloud.com/api/authorize/parse-zoho-oauth-free.php" method="get">

        <input type="hidden" name="zservice" value="crm">
        <input type="hidden" name="redirect" value="<?php echo esc_js(admin_url()).'admin.php?page=ccgpp-zohocrmapi'; ?>">

        <div class="form-group ">
            <label class="control-label">Organization Id</label>
            <div class="">
                <input type="text" required="" name="org_id" class="form-control" value="<?php echo (isset($auth->orgid)) ? esc_attr($auth->orgid) : '';  ?>" >
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr" style=""></div>   
        <div class="form-group ">
            <div class="">
                <button type="submit" class="btn btn-primary  float-right btn-padding">Authorize With Zoho CRM</button>
            </div>
        </div>
    </form>

</div>

<div class="clr" style="height: 35px"></div>
<hr style="margin: 30px 15px 15px">
<!-- <div class="col-md-6 pd-0">
    <form role="form" action="" method="post" class=" " style="">
    
        <div class="form-group " style="">
            <label class="col-md-4 control-label" style="">Deal Stage(s) to Show</label>
            <div class="col-md-8">
                <input type="text" name="dealStages" placeholder="exe : Closed Won;Closed Lost;Qualification" required class="form-control" value="<?php //echo (isset($dealStages)) ? $dealStages : '';  ?>" >
                <span style="font-size: 10px;">If you want show all stages, then type "All". You can also just show certain stages, such as - Qualification;Closed Won;Closed Lost;</span>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>

        <div class="clr" style=""></div>
        <div class="form-group">        
            <div class="col-md-12">
                <input type="submit" class="btn btn-primary float-right  btn-padding" name="update_CRMAdditionalSettings" value="Update">
            </div>
        </div>

    </form>
</div> -->
    <div class="clr"></div>
    <!-- <hr style="margin-top: 30px"> -->



<div class="clr" style=""></div>
<h4 class="instruction_title">Instructions</h4>
<div class="clr" style=""></div>


    <!-- Instauction -->
    <!-- <h4 class="instruction_title">Instructions</h4> -->
    <div class="instruction-content" style="padding: 15px;">
        
        <h4 style="font-size: 15px;">You can then copy and paste the values into the fields below. To get the <font class="cp_bold">Organization ID</font> go to <a href="https://crm.zoho.com" target="_balnk">https://crm.zoho.com</a> and copy the org id from the URL, include the org prefix when pasting the Org ID.</h4>
        <div class="cp_ins_img_main">
            <img class="cp_ins_img" src="https://portal-plugin.thecatalystcloud.com/api/v1/plugin_instructions/crm_org.png" alt="Organization ID">
            <div class="cp_ins_img_modal">
                <span class="ins_close">&times;</span>
                <img class="ins_modal_content" >
                <div class="ins_caption"></div>
            </div>
        </div>


    </div>

    





    <script type="text/javascript">
        jQuery(document).ready( function () {

            jQuery(document).on('click', '.cp_ins_img', function() {
                var cp_ins_img_modal = jQuery(this).closest('.cp_ins_img_main').find('.cp_ins_img_modal').css("display", "block");
                jQuery(this).closest('.cp_ins_img_main').find('.ins_modal_content').attr('src', jQuery(this).attr('src'));
                jQuery(this).closest('.cp_ins_img_main').find('.ins_caption').html(jQuery(this).attr('alt'));
            });

            jQuery(document).on('click', '.ins_close', function() {
                jQuery(this).closest('.cp_ins_img_modal').css("display", "none");
            });


        });
    </script>
