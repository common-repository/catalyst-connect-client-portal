
<style type="text/css">
    input[type="text"], select{
        border-radius: 10px !important;
    }
    #ccgclientportal-content #portal-cotenier .pd-30 {

        /*padding: 0px !important;*/
        border: none !important;
    }
</style>
<div class="clr"></div>
<!-- <h3>General </h3> -->

<div class="dashboardContent-layout box-shadow pd-30" style="margin: 0px -15px;padding: 15px;padding-top: 0px;">
<form role="form" action="" id="updateSettingls" method="post" style="">

    <?php 
        wp_nonce_field('general-settings','csrf_token_nonce');
    ?>
    <input type="hidden" class="formid" value="updateSettingls">

    <div class="form-group">
        <label class="col-md-12 control-label">Portal Title&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
        <div class="col-md-8">                                                       
            <input type="text" name="portalTitle" placeholder="exe : Client Portal" class="form-control" value="<?php echo (isset($portalTitle)) ? esc_attr(stripslashes($portalTitle)) : 'Client Portal';  ?>" style="height: 40px!important;">
        </div>
        <div class="clr"></div>
    </div>

    <div class="form-group">
        <label class="col-md-12 control-label">Menu Possition&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
        <div class="col-md-8">
            <?php 
                $mmptp = ''; $mmplft = '';
                if(isset($prtlmm->option_value) && $prtlmm->option_value == 'Left')$mmplft = 'selected';
                if(isset($prtlmm->option_value) && $prtlmm->option_value == 'Top')$mmptp = 'selected';
            ?>


            <select class="form-control" name="menupossition" style="height: 40px;">
                <option <?php echo $mmplft; ?>>Left</option>
                <option <?php echo $mmptp; ?>>Top</option>
            </select>
        </div>

        <div class="clr"></div>
    </div>

     <?php if(isset($prtlmm->option_value) && $prtlmm->option_value == 'Top'){ ?>
      <!--   <div class="form-group">
            <label class="col-md-12 control-label">Menu Responsive&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
            <div class="col-md-8">
                <input type="text" name="menuResponsive" placeholder="exe : 768px" class="form-control" value="<?php //echo (isset($menuResponsive)) ? $menuResponsive : '768px';  ?>" >
            </div>
            <div class="clr"></div>
        </div> -->
    <?php } ?>

    <div class="form-group">
        <div class="col-md-12" >
            <div class="row" style="">


            <div class="col-md-8 ">
                <div class="">
                    <div class="crm-module-list ">
                        <label class="control-label" style="">Show Company Logo</label>

                        <label class="switch control-label switch-appearence" style="">
                           <input type="checkbox" class="form-control acinput" name="showAccMngr" <?php echo (isset($showAccMngr)) ?"checked":"";  ?>>
                          <span class="slider round slider-appearence"></span>
                        </label>
                        <div class="clr"></div>

                    </div>
                    <div class="clr"></div>
                </div>

            </div>

<!-- 
            <label class="col-md-4 control-label labelGeneral">Show Company Logo&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                <div class="col-md-4 checkbopxGeneral">
                    <label class="switch control-label switch-appearence" style="float: right;">
                        <input type="checkbox" class="form-control acinput" name="showAccMngr" <?php echo (isset($showAccMngr)) ?"checked":"";  ?>>
                        <span class="slider round slider-appearence"></span>
                    </label>
                </div> -->


            </div>
           
        </div>
        <div class="clr"></div>
    </div>

    <div class="form-group">
        <label class="col-md-12 control-label">Portal Width&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
        <div class="col-md-8">                                                       
            <input type="text" name="portalWidth" placeholder="exe : 100% or 100px" class="form-control" value="<?php echo (isset($portalWidth)) ? esc_attr($portalWidth) : '100%';  ?>" style="height: 38px!important;">
        </div>
        <div class="clr"></div>
    </div>
    <?php 
        if(isset($prcolor) && $prcolor !="") $primarycolor = $prcolor;
        else $primarycolor = '#2871d1'; 
    ?>
    <div class="clr"></div>
    <div class="form-group">
   
        <label class="col-md-12 control-label">Primary Color&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
   

    <div class="col-md-8"> 
        <div class="genralColoSetting">
            <input type="color" name="primarycolor" id="primary-color" class="form-control NewInput" value="<?php echo esc_attr($primarycolor);?>" style="background: <?php echo esc_attr($primarycolor);?>;">
            <input type="text"  id="primary-color-code" class="form-control Newinput2" value="<?php echo esc_attr($primarycolor);?>" style="width: 60%;margin-right: 25%;height: 35px!important">
        </div>
    </div>


        <div class="clr"></div>
    </div>

    <div class="clr"></div>
    <div class="clr"></div>
    <div class="form-group">
        <div class="col-md-8">
            <input type="submit" class="btn btn-primary newSubmitBtnDesign btn-padding" style="float: right;" name="general-settings" value="Update">
        </div>
    </div>
    <div class="clr"></div>

</form> 
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(document).on("change","#primary-color",function(){
            var colorCode = jQuery(this).val();
            jQuery("#primary-color-code").val(colorCode);
        }); 
    });

    jQuery(document).ready(function(){
        jQuery(document).on("change","#primary-color-code",function(){
            var colorCode = jQuery(this).val();
            jQuery("#primary-color").val(colorCode);
        }); 
    });
</script>