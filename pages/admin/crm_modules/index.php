<?php
    global $wpdb;
    global $wp;
    

    $CPNzoho    = new ZohoCrmRequest();
    $SttClass   = new CCGP_SettingsClass();

    $urlFlds ="";
    $useModules = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index = 'useModules'");

    if(isset($useModules->option_value)) $useMdlV = json_decode($useModules->option_value, true);

    if( isset($accRldM->related_lists[0]) ){
        $accRldMl = $accRldM->related_lists;
    }

    $moduleList = $CPNzoho->getModuleN();

    $ignoreArr = array('Home','Desk','Calls','Events','Tasks','Accounts','Activities','Actions Performed','Zoho Desk','Zoho Finance','Checklists', 'Notes','Zoho Projects','Attachments','Emails','Products','Open Activities','Closed Activities','Member Accounts');
?>

<style type="text/css">
	form .form-group label.control-label{
		min-width: 125px;
		margin: 0px;
	}
    #ccgclientportal-content #portal-cotenier .tabCon .col-md-1,
    #ccgclientportal-content #portal-cotenier .tabCon .col-md-2,
    #ccgclientportal-content #portal-cotenier .tabCon .col-md-3,
    #ccgclientportal-content #portal-cotenier .tabCon .col-md-4{
        padding-right: 0px; 
    }
    #ccgclientportal-content #portal-cotenier .webTabinput {
        width: calc(50% - 231px);
        /*float: left;*/
        margin-right: 8px;
    }
    #ccgclientportal-content #portal-cotenier .tabCon .hw{
        width: 50%;
        float: left;
        margin-right: 0px;
        /*padding: 8px 2px;*/
    }
    #ccgclientportal-content #portal-cotenier .tabCon {
        margin-bottom: 12px;
    }

    #addLink{
        background-color: #7BDA7C !important;
    }
    .removeTab{
        background-color: #FF7D7E !important;
    }
    .webtabtgrid{
    	width: 280px;
    	box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.16);
    	padding: 20px;
    	float: left;
	    height: 315px;
	    margin: 0px 20px 20px 0px;
	    text-align: center;
	    border-radius: 3px;
    }

    .webtabtgrid .edit{
	    width: 26px;
	    position: absolute;
	    margin-left: 212px;
	    background: #f1f1f1;
	    padding: 3px 5px;
	    border-radius: 3px;
	    cursor: pointer;
	}
    .webtabtgrid .icon{
	    width: 120px;
	    height: 120px;
	    font-size: 50px;
	    padding: 35px;
	    background: #f1f1f1;
	    margin: 0 auto;
	    border-radius: 100px;
	    text-align: center;
	    margin-bottom: 20px;
	}
    .webtabtgrid .title{
    	font-weight: bold;
    	color: #0073aa;
    	font-size: 16px;
	    margin-bottom: 10px;

    }
    .webtabtgrid .link{
    	font-weight: bold;
    	color: #6ebeec;
    	font-size: 14px;
	    margin-bottom: 10px;
	    overflow: hidden;
	    min-height: 25px;
    }
    .webtabtgrid .height,
    .webtabtgrid .width{
	    width: calc( 50% - 7px);
	    float: left;
	    background: #f1f1f1;
	    padding: 6px;
	    border-radius: 5px;
	}
    .webtabtgrid .openin{
	    width: 100%;
	    float: left;
	    background: #f1f1f1;
	    padding: 6px;
	    border-radius: 5px;
	    margin-top: 10px;
	}
    #ccgclientportal-content .tabCon .btn.removeTab{
        padding: 9px 12px;
    }

    #portal-cotenier h4.instruction_title {
        padding: 12px 15px;
        color: rgb(0, 145, 216);
        margin-top: 0px;
        font-size: 22px;
    }
</style>

<form role="form" action="" method="post" class="box-shadow" style="margin: 0px -15px;padding: 30px 15px;">
    <?php 
        wp_nonce_field('useModules','csrf_token_nonce');
    ?>
    <?php 
    $crmModulesList = array();
    if(isset($moduleList->modules) && (count($moduleList->modules) > 0)){
        foreach ($moduleList->modules as $key => $rldMdtl) {
            $egnore = false;
            $is_custom_module = false;
            $mdlApiNm = $rldMdtl->api_name;
            if (strpos($mdlApiNm, 'zohosign__') !== false) $egnore = true; 
            if (strpos($mdlApiNm, 'CustomModule') !== false) {
                $dataArr = explode('CustomModule', $mdlApiNm);                
                if(isset($dataArr[1]) && (strlen($dataArr[1]) == 4)) $egnore = true;                     
            }
            $gnrtType = $rldMdtl->generated_type;            
            if(($gnrtType == "subform") ||($gnrtType == "web") ||($gnrtType == "linking")) $egnore = true;

            if( $gnrtType == "custom" ){
                $is_custom_module = true;
                $custom_module_api = $rldMdtl->api_name;
                $rldMdtl->api_name = str_replace(" ","_",$rldMdtl->plural_label);
            }  

            if(!$egnore){
                $crmModulesList[$rldMdtl->api_name] = json_encode($rldMdtl);
                if(!in_array($rldMdtl->plural_label, $ignoreArr)){ ?>
                    <div class="form-group col-md-4 ">
                        <div class="crm-module-list">
                            <label class="control-label" style="margin-right: 20px;"><?php echo $rldMdtl->plural_label; ?></label>
                            <label class="switch control-label">
                                <?php if( $is_custom_module ){ ?>
                                <input type="checkbox" <?php echo (isset($useMdlV['zcrm_'.$custom_module_api])) ? 'checked' : '';?> name="data[zcrm_<?php echo $custom_module_api; ?>]" value="on_<?php echo esc_attr($rldMdtl->generated_type.'_'.$rldMdtl->api_name);?>">
                                <?php }else{ ?>
                                <input type="checkbox" <?php echo (isset($useMdlV['zcrm_'.$rldMdtl->api_name])) ? 'checked' : '';?> name="zcrm_<?php echo esc_attr($rldMdtl->api_name); ?>" value="on_<?php echo esc_attr($rldMdtl->generated_type.'_'.$rldMdtl->api_name);?>">
                                <?php } ?>
                                <span class="slider round"></span>
                            </label>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                <?php }
            }
        }
    } ?>    
    <input type="hidden" name="moduleList" value=''>    

    <div class="clr"></div>
    <div class="form-group col-md-12">
        <input type="submit" class="btn btn-primary float-right btn-padding" name="useModules" value="Update">
    </div>
    <div class="clr"></div>

</form>

<style type="text/css">
</style>

<hr style="margin: 15px;"></hr>
<h4 class="instruction_title" >Web Tab</h4>

<div class="extraBarAdd"></div>

<div class="col-md-12">
    <?php if(isset($webtab['content'])){
        $webtabV = json_decode($webtab['content'], true);
        if(isset($webtabV['link']) && $webtabV['link'] !=""){
            $webtabl = ($webtabV['link'] != null)?json_decode($webtabV['link']):[];
            $webtabt = ($webtabV['title'] != null)?json_decode($webtabV['title']):[];
            $width = (isset($webtabV['width'])) ? json_decode($webtabV['width']) : [];
            $icon = (isset($webtabV['Icon'])) ? json_decode($webtabV['Icon']) : [];
            $height = (isset($webtabV['height'])) ? json_decode($webtabV['height']) : [];
            $openin = (isset($webtabV['openin'])) ? json_decode($webtabV['openin']) : [];
            $webacclink = (isset($webtabV['quickLinkAF'])) ? json_decode($webtabV['quickLinkAF']) : [];

            if(!empty($webtabl)){
                foreach ($webtabl as $wt => $wtt) { 
                    if($webtabt[$wt] !=""){
    
                        $laccfld = "";
                        if(isset($websiteFields)) {
                            $urlFldshave = '';
                            foreach ($websiteFields as $key => $fldDtl) {
                              if(!empty($webacclink) && $webacclink[$wt] == $fldDtl->api_name) $laccfld = $fldDtl->field_label  ;
                            }
                        }
                        ?>
    
                        <div class="webtabtgrid" id="webtabtgrid_<?php echo $wt; ?>">
                            <div class="edit" data-id="<?php echo $wt; ?>"> <i class="fas fa-pencil-alt"></i> </div>
                            <div class="icon"><i class="<?php echo (isset($icon[$wt]) && $icon[$wt] != "") ? stripslashes($icon[$wt]) : "fas fa-link"; ?>"></i> </div>
                            <div class="content">
                                <div class="title"><?php echo stripslashes($webtabt[$wt]); ?></div>
                                <div class="link"><?php echo ($wtt == "")? $laccfld : stripslashes($wtt); ?></div>
                                <div class="height"><?php echo (isset($height[$wt])) ? stripslashes($height[$wt]) : "100%";?></div>
                                <div style="width: 14px;float: left;height: 30px;"></div>
                                <div class="width"><?php echo (isset($width[$wt])) ? stripslashes($width[$wt]) : "100%";?></div>
                                <div class="openin">Open in : <?php echo  (isset($openin[$wt]) && $openin[$wt]) ? ucfirst($openin[$wt]) : "Iframe"; ?></div>
                                <div class="clr"></div>
                            </div>
                            <div class="clr"></div>
                        </div>
    
                    <?php
                    }
                }
            }
        }
    } ?>


	<div class="webtabtgrid addLink" id="webtabtgrid_<?php echo $wt; ?>" style="cursor: pointer;">
		<div class="icon" style="margin-top: 50px;"><i class="fas fa-plus"></i> </div>
		<div class="content" style="font-size: 18px;">
			Add Web Tab
			<div class="clr"></div>
		</div>
		<div class="clr"></div>
	</div>
    <div class="clr"></div>
</div>
<div class="clr" style="height: 30px"></div>

<form role="form" action="" id="updatewebtab" method="post" class="box-shadow" style="margin: 0px -15px;padding: 30px 30px; border: none">
    <input type="hidden" class="formid" value="updatewebtab">

    <?php 
        wp_nonce_field('savewebtab','csrf_token_nonce');
    ?>


    <div class="form-group">
        <div class="" id="tabListCon">                     
            <?php if(isset($webtab['content'])){
                $webtabV = json_decode($webtab['content'], true);
                if(isset($webtabV['link']) && $webtabV['link'] !=""){
                    $webtabl = ($webtabV['link'] != null)?json_decode($webtabV['link']):[];
                    $webtabt = ($webtabV['title'] != null)?json_decode($webtabV['title']):[];
                    $width = (isset($webtabV['width'])) ? json_decode($webtabV['width']) : [];
                    $icon = (isset($webtabV['Icon'])) ? json_decode($webtabV['Icon']) : [];
                    $height = (isset($webtabV['height'])) ? json_decode($webtabV['height']) : [];
                    $openin = (isset($webtabV['openin'])) ? json_decode($webtabV['openin']) : [];
                    $webacclink = (isset($webtabV['quickLinkAF'])) ? json_decode($webtabV['quickLinkAF']) : [];

                    if(!empty($webtabl)){
                        foreach ($webtabl as $wt => $wtt) { 
                            if($webtabt[$wt] !=""){ ?>
                                <div class="tabCon" id="tabCon_<?php echo $wt; ?>" style="display: none;">
                                    <?php
                                    unset($header);
                                    if(!empty($wtt)){
                                        $header = get_headers($wtt, 1);
                                        if(isset($header["X-Frame-Options"][0])) {
                                            if ($header["X-Frame-Options"][0] == 'DENY' || $header["X-Frame-Options"][0] == 'SAMEORIGIN' || $header["X-Frame-Options"][0] == 'ALLOW-FROM') {
                                                echo "<span style='color:red;'>This URL is invalid for Iframe.</span></br>";
                                            }
                                        }                            
                                        if(isset($header["X-Frame-Options"])) {
                                            if ($header["X-Frame-Options"] == 'DENY' || $header["X-Frame-Options"] == 'SAMEORIGIN' || $header["X-Frame-Options"] == 'ALLOW-FROM') {
                                                echo "<span style='color:red;'>This URL is invalid for Iframe.</span></br>";
                                            }
                                        }
    
                                    }
                                    ?>
    
                               
                            <div class="row"> 
                                <div class="col-md-3">
                                    <label class="control-label">Name</label>
                                    <input type="text" name="webTabT[]" class="form-control webTabinput" value="<?php echo esc_attr(stripslashes($webtabt[$wt])); ?>" style="width: 100%" >
                                </div>
    
                                <div class="col-md-3">
                                    <label class="control-label">Icon</label>
                                    <input type="text" name="Icon[]" class="form-control set-icon webTabinput"  value="<?php echo (isset($icon[$wt])) ? esc_attr(stripslashes($icon[$wt])) : ""; ?>" placeholder="" style="width: 100%">
                                </div>
    
                                <div class="col-md-3">
                                    <label class="control-label">Link</label>
                                    <input type="text" name="webLink[]" class="form-control webTabinput" value="<?php echo esc_url(stripslashes($wtt)); ?>" style="width: 100%">
                                </div>
    
                                <?php       
                                    if(isset($websiteFields)) {
                                        $urlFldshave = '';
                                        foreach ($websiteFields as $key => $fldDtl) {
                                          $seleee = '';
                                          if(!empty($webacclink) && $webacclink[$wt] == $fldDtl->api_name) $seleee = 'selected'  ;
                                            $urlFldshave .='<option value="'.$fldDtl->api_name.'"'.$seleee .'>'. $fldDtl->field_label .'</option>';
                                        }
                                    }
                                ?>
    
                                <div class="col-md-3">
                                    <label class="control-label">Accounts Link</label>
                                    <select class="form-control quickLinkinput" name="quickLinkAF[]">
                                        <option value="">--None--</option>
                                        <?php echo $urlFldshave; ?>
                                    </select>
                                </div>
    
                                <div class="col-md-3">
                                    <label class="control-label">Height</label>
                                    <input type="text" name="height[]" class="form-control hw" value="<?php echo (isset($height[$wt])) ? esc_attr(stripslashes($height[$wt])) : "100%";?>" placeholder="height" style="width: 100%">
                                </div>
    
                                <div class="col-md-3">
                                    <label class="control-label">Width</label>
                                    <input type="text" name="width[]" class="form-control hw" value="<?php echo (isset($width[$wt])) ? esc_attr(stripslashes($width[$wt])) : "100%";?>" placeholder="width" style="width: 100%">
                                </div>
    
                                <div class="col-md-3">
                                    <?php $openinv = (isset($openin[$wt])) ? $openin[$wt] : "iframe";?>
                                    <label class="control-label">Open In</label>                                
                                    <select class="form-control quickLinkinput" name="openin[]">
                                        <option <?php echo ($openinv == "iframe") ? "selected" : ""; ?> value="iframe">Iframe</option>
                                        <option <?php echo ($openinv == "newtab") ? "selected" : ""; ?> value="newtab">New Tab</option>
                                    </select>
                                </div>
    
                                <div class="col-md-1">
                                    <label class="control-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger removeTab" data-id="<?php echo $wt; ?>"><i class="fas fa-trash-alt"></i></button>
                                </div>
    
                                <div class="clr"></div>
    
                            </div>
    
                                    <div class="clr"></div>
                                </div>   
                                <div class="clr"></div>
                            <?php }
                        }
                    }
                }
            } ?>                    
            <!--div class="tabCon" style="display: none;"> 

                <div class="row"> 
                    <div class="col-md-2">
                        <label class="control-label">Name</label>
                        <input type="text" name="webTabT[]" class="form-control webTabinput col-md-12" placeholder="exe: Google" style="width: 100%">                                
                    </div>
                    <div class="col-md-2">
                        <label class="control-label">Icon</label>
                        <input type="text" name="Icon[]" class="form-control set-icon webTabinput col-md-12" placeholder="" style="width: 100%">
                    </div>

                    <div class="col-md-3">
                        <label class="control-label">Link</label>
                        <input type="text" name="webLink[]" class="form-control webTabinput col-md-12" placeholder="exe: https://google.com" style="width: 100%">
                    </div>

                    <div class="col-md-2">
                        <label class="control-label">Accounts Link</label>
                        <select class="form-control quickLinkinput" name="quickLinkAF[]">
                            <option value="">--None--</option>
                            <?php echo $urlFlds; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="control-label">Height</label>
                         <input type="text" name="height[]" class="form-control hw" value="100%" placeholder="height" style="width: 100%">
                    </div>

                    <div class="col-md-2">
                        <label class="control-label">Width</label>
                         <input type="text" name="width[]" class="form-control hw" value="100%" placeholder="width" style="width: 100%">
                    </div>

                    <div class="col-md-2">
                        <label class="control-label">Open In</label>                                
                        <select class="form-control quickLinkinput" name="openin[]">
                            <option value="iframe">Iframe</option>
                            <option value="newtab">New Tab</option>
                        </select>
                    </div>

                    <div class="col-md-1 fl-r">
                        <button type="button" id="addLink" class="btn btn-primary addLink" ><i class="fas fa-plus" ></i></button>
                    </div>
                    <div class="clr"></div>
                </div>
               
               
                <div class="clr"></div>
            </div-->

            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>

    <div class="clr"></div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary newSubmitBtn" name="savewebtab" value="Update">
    </div>
    <div class="clr"></div>

</form>

<script type="text/javascript">
    jQuery(document).on('click', '.removeTab', function(event) {
        event.preventDefault();
        jQuery(this).closest('.tabCon').remove();
    });
    jQuery(document).on('click', '.addLink', function(event) {
        event.preventDefault();

        jQuery(".webtabtgrid .edit").hide();

        var html_ = '<div class="tabCon">\
        <div class="row">\
            <div class="col-md-3">\
            <label class="control-label">Name</label>\
                <input type="text" name="webTabT[]" class="form-control webTabinput" placeholder="exe: Google" style="width: 100%">\
            </div>\
            <div class="col-md-3">\
                <label class="control-label">Icon</label>\
                <input type="text" name="Icon[]" class="form-control set-icon webTabinput col-md-12" placeholder="" style="width: 100%">\
            </div>\
            <div class="col-md-3">\
                <label class="control-label">Link</label>\
                <input type="text" name="webLink[]" placeholder="exe: https://google.com" class="form-control webTabinput" style="width: 100%">\
            </div>\
            <div class="col-md-3">\
                <label class="control-label">Account Link</label>\
                <select class="form-control quickLinkinput" name="quickLinkAF[]">\
                    <option value="">--None--</option>\
                    <?php echo $urlFlds; ?>\
                </select>\
            </div>\
            <div class="col-md-3">\
                <label class="control-label">Height</label>\
                <input type="text" name="height[]" class="form-control hw" value="100%" placeholder="height" style="width: 100%">\
            </div>\
            <div class="col-md-3">\
                <label class="control-label">Width</label>\
                <input type="text" name="width[]" class="form-control hw" value="100%" placeholder="width" style="width: 100%">\
            </div>\
            <div class="col-md-3">\
                <label class="control-label">Open In</label>\
                <select class="form-control quickLinkinput" name="openin[]">\
                    <option value="iframe">Iframe</option>\
                    <option value="newtab">New Tab</option>\
                </select>\
            </div>\
            <div class="col-md-1">\
                <label class="control-label">&nbsp;</label>\
                <button type="button" class="btn btn-danger removeTab"><i class="fas fa-trash-alt"></i></button></div>\
            </div>\
            <div class="clr"></div>\
        </div>';
        jQuery("#tabListCon").append(html_);
    });   
</script>

<script type="text/javascript">
    var icinput = "";
    jQuery(document).on('click', '.set-icon', function(event) {
        event.preventDefault();
        icinput = jQuery(this);
        var exIcon = jQuery(this).val();
        jQuery('.inlinecmm').click();
        if(exIcon != ""){
            exIcon = exIcon.replace(" ", '.');
            console.log(exIcon);
            jQuery('.hover-bg-blue4').removeClass("active");
            jQuery('.'+exIcon).closest("a.hover-bg-blue4").addClass("active");
        }
    });

    jQuery(function () {
        jQuery('ul.list a').on("click", function (e) {
            e.preventDefault();
            var iclass = jQuery(this).find("i").attr("class");
            // console.log(icinput);
            // console.log(iclass);
            jQuery(icinput).val(iclass);
            jQuery(icinput).focus();
            jQuery("#cboxClose").click();
        });
    });
    jQuery('.webtabtgrid .edit').on("click", function (e) {
    	var id = jQuery(this).data('id');
        jQuery(".tabCon").hide();
        jQuery("#tabCon_"+id).show();
    });
    jQuery('.removeTab').on("click", function (e) {
        var id = jQuery(this).data("id");
        jQuery("#webtabtgrid_"+id).remove();
    });
</script>


<!-- Modal -->
<a class='inlinecmm' href="#confirmationMessageModal" style="display: none;">Icon List</a>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".inlinecmm").colorbox({inline:true,  width:"90%", height:"90%"});
    });
</script>
<div style='display:none'>
    <div id='inlinecmm_content' style='padding:10px; background:#fff;'>

        <div id="confirmationMessageModal">
            <!-- Modal content-->
            <div class="modal-body">
                <?php include 'icon-list.php'; ?>
                
            </div>
        </div>

    </div>
</div>

<!-- </div>
</div> -->