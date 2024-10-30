
<style type="text/css">
    #addQ_Link{
        background-color: #7BDA7C !important;
    }
    .removeQ_Link{
        background-color: #FF7D7E !important;
    }
  .dcsectitle {
    float: left !important;
   }
</style>

<div class="clr"></div>

<div class="dashboardContent-layout box-shadow pd-30 pd-t-0" style="margin: 0px -15px 30px;">
<form role="form" action="" method="post" id="updateOp_dashboardContent" class="dashboardContentForm">
    <input type="hidden" class="formid" value="updateOp_dashboardContent">

    
    <?php 
        wp_nonce_field('dashboardContent','csrf_token_nonce');
    ?>

    <div class="row">
        
        <div class="col-md-6">
            
            <div class="form-group">

                <label class="col-md-12 control-label">           

                    <input type="checkbox" class="form-control brd-checkbox" style="margin-top: 2px;" name="BannerPermission" <?php echo (isset($dbBnr['permission']) && ($dbBnr['permission'] == 'on')) ? 'checked' : '';  ?>> &nbsp; Banner <span style="font-size: 8px;">(1400 X 355)</span>&nbsp;<span><i class="fas fa-info-circle"   data-toggle="tooltip" data-placement="top" title="test"></i></span> </label>
                    
                </label>

                <div class="col-md-12">

                    <?php if(isset($dbBnr['content'])){
                        $dbIsiV = json_decode($dbBnr['content'], true);

                        foreach ($dbIsiV as $slim) {  ?>
                            <div class="sImageCon"> 
                                <input type="text" name="sliderimages[]" class="form-control regular-text image_url" value="<?php echo esc_attr($slim); ?>" style="padding:20px;width: 100% " placeholder="No file Chosen">
                                <input type="button" name="upload-btn" class="button-secondary upload-btn choosefileButton" value="Choose File" style="height: 33px !important;">
                            </div>
                            <div class="clr"></div>
                        <?php } 
                    }else { ?>     
                        <div class="sImageCon"> 
                            <input type="text" name="sliderimages[]" class="form-control regular-text image_url" style="padding:20px;width: 100% ">
                            <input type="button" name="upload-btn" class="button-secondary upload-btn choosefileButton" value="Choose File" style="height: 33px !important;">
                        </div>
                        <div class="clr"></div>

                    <?php } ?>            
                </div>
                <div class="clr"></div>
            </div>

            <div class="form-group">
                <label class="col-md-12 control-label">&nbsp;
                    <span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i>
                    </span>
                    <input type="checkbox" class="form-control brd-checkbox" name="aboutUsPermission" <?php echo (isset($dbOat['permission']) && ($dbOat['permission'] == 'on')) ? 'checked' : '';  ?> style="margin-top: 8px;">
                    <input type="text" class="form-control" name="aboutUsLabel" value="<?php echo (isset($dbOat['label'])) ? esc_attr(stripslashes($dbOat['label'])) : 'About Us';  ?>" style="border:none;margin-top: -3px;margin-left: 0px;">
                </label>

                <div class="col-md-12">
                    <textarea name="aboutUstext" style="height: 100px;border-radius: 10px;" class="form-control"><?php echo (isset($dbOat['content'])) ? esc_textarea(stripslashes($dbOat['content'])) : '';  ?></textarea> 
                </div>
                <div class="clr"></div>
            </div>


    <div class="form-group">
        <label class="col-md-12 control-label">&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span>
            <input type="checkbox" class="form-control brd-checkbox" style="margin-top: 8px" name="dashboardIframePermission" <?php echo (isset($dsbif['permission']) && ($dsbif['permission'] == 'on')) ? 'checked' : '';  ?>>
            <input type="text" class="form-control" name="dashboardIframeLabel" value="<?php echo (isset($dsbif['label'])) ?  esc_attr(stripslashes($dsbif['label'])) : 'Video Link';  ?>" style="border:none;margin-top: -3px;margin-left: 0px;">
        </label>
        <div class="col-md-12">                                                        
            <input type="text" class="form-control" name="dashboardiframe" placeholder="exe: https://www.youtube.com/embed/wwYXFfDqwGk" class="form-control" value="<?php echo (isset($dsbif['content'])) ?  esc_attr($dsbif['content']) : '';  ?>" style="height: 40px !important;">
        </div>
        <div class="clr"></div>
    </div>




    <div class="form-group">
        <label class="col-md-12 control-label">&nbsp;<span><i class="fas fa-info-circle"  data-toggle="tooltip" data-placement="top" title="test"></i></span>
            <input type="checkbox" class="form-control brd-checkbox" style="margin-top: 8px" name="iframeEmbedpermission" <?php echo (isset($dsbifem['permission']) && ($dsbifem['permission'] == 'on')) ? 'checked' : '';  ?>>
            <input type="text" class="form-control" name="iframeEmbedLabel" value="<?php echo (isset($dsbifem['label'])) ?  esc_attr(stripslashes($dsbifem['label'])) : 'Iframe Embed';  ?>" style="border:none;margin-top: -3px;margin-left: 0px;">
        </label>
        <div class="col-md-12 pd-0">   

            <?php 
                $exifembacf = "";
                if(isset($dsbifem['content'])) $dsbifemc = json_decode($dsbifem['content'], true);
                if(isset($dsbifemc['AcFld'])){
                    $ifembAcfArr = explode("___", $dsbifemc['AcFld']);
                    if(isset($ifembAcfArr[1])){
                        $exifembacf .='<option selected value="'. $ifembAcfArr[0] .'___'. $ifembAcfArr[1] .'">'. $ifembAcfArr[1] .'</option>';
                    }
                }
            ?>  
            <div class="col-md-6">  
                <!-- <label class="control-label">Enter Url</label>                                                  -->
                <input type="text" name="iframeEmbedUrl" class="form-control" value='<?php echo (isset($dsbifemc['url'])) ?  esc_url($dsbifemc['url']) : '';  ?>' style="height: 38px!important" >
            </div>
            <div class="col-md-1" style="text-align: center"> <label class="control-label" >Or</label></div>
            <div class="col-md-5">       
                <select class="form-control" name="AccountsFld" style="height: 38px!important">
                    <option value="">--None--</option>
                    <?php echo $exifembacf; ?>
                    <?php echo str_replace($exifembacf, "", $viewAbleFld); ?>
                </select>
            </div>

        </div>
        <div class="clr"></div>
    </div>

    <?php 
    if(isset($dbColor['headbckcolor']) && $dbColor['headbckcolor'] !="") $headbckcolor = $dbColor['headbckcolor'];
    else $headbckcolor = '#2871d1'; 
    ?>

    <div class="clr"></div>


    <div class="form-group">
       
        <div class="col-md-12 pd-0">

            <div class="col-md-6">
                 <label class="control-label">Headings Background&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                <div class="row">
                    <div class="col-md-12">

                        <div class="colobox">
                            <input type="color" name="headbckcolor" id="headbckcolor" class="form-control NewInput12" value="<?php echo  esc_attr($headbckcolor);?>" style="background: <?php echo  esc_attr($headbckcolor);?>;">
                            <input type="text" id="headbckcolor-code" class="form-control Newinput212" value="<?php echo  esc_attr($headbckcolor);?>" style="">
                         
                        </div>


                    <!--     <input type="text" class="form-control" id="headbckcolor-code" value="<?php echo $headbckcolor;?>">
                  
                        <input type="color" name="headbckcolor" id="headbckcolor" class="form-control" value="<?php echo $headbckcolor;?>" > -->
                    </div>
                </div>                
            </div>  

            <div class="col-md-6">
                <label class="control-label">Font</label>
                <?php 
                    if(isset($dbColor['headfontcolor']) && $dbColor['headfontcolor'] !="") $headfontcolor = $dbColor['headfontcolor'];
                    else $headfontcolor = '#FFFFFF'; 
                ?>     
                <div class="row">
                    <div class="col-md-12">   

                        <div class="colobox">
                            <input type="color" name="headfontcolor" id="headfontcolor" class="form-control NewInput12" value="<?php echo  esc_attr($headfontcolor);?>" style="background: <?php echo  esc_attr($headfontcolor);?>;">
                            <input type="text"  class="form-control Newinput212" value="<?php echo  esc_attr($headfontcolor);?>" style="">
                         
                        </div>

                        <!-- <input type="text" name="headfontcolor" class="form-control colorpicker" value="<?php //echo $headfontcolor;?>" style="background: <?php //echo $headfontcolor;?>" > -->
                    </div>
                </div>
            </div>
            
            <div class="clr"></div>
        
        </div>
        <div class="clr"></div>
    </div>




</div>


<div class="col-md-6">
            
            
    <div class="form-group">
        <label class="col-md-12 control-label">&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span>
            <input type="checkbox" class="form-control brd-checkbox" name="quickLinkPermission" <?php echo (isset($dbOql['permission']) && ($dbOql['permission'] == 'on')) ? 'checked' : '';  ?> style="margin-top: 8px;">
            <input type="text" class="form-control" name="quickLinkTLabel" value="<?php echo (isset($dbOql['label'])) ?  esc_attr(stripslashes($dbOql['label'])) : 'Quick Link';  ?>"  style="border:none;margin-top: -3px;margin-left: 0px;">
            <div class="clr"></div>
        </label>
        <div class="clr"></div>
        <div class="col-md-12" id="linkListCon">                     
            <?php if(isset($dbOql['content'])){
                $dbOpqlV = json_decode($dbOql['content'], true);
                if(isset($dbOpqlV['link']) && $dbOpqlV['link'] !=""){
                    $dbOpqlt = json_decode($dbOpqlV['title']);
                    $dbOpqll = json_decode($dbOpqlV['link']);
                    if(isset($dbOpqlV['linkaf']))$linkaf = json_decode($dbOpqlV['linkaf']);

                    foreach ($dbOpqll as $lk => $qlLst) { 
                        $exlinkaf = "";
                        if(isset($linkaf[$lk])){
                            $linkafarr = explode("___", $linkaf[$lk]);
                            if(isset($linkafarr[1])){
                                $exlinkaf .='<option selected value="'. $linkafarr[0] .'___'. $linkafarr[1] .'">'. $linkafarr[1] .'</option>';
                            }
                        }
                        if($qlLst !="" || $exlinkaf !=""){

                            ?>
                            <div class="linkCon">                         
                                <input type="text" name="quickLinkT[]" class="form-control quickLinkinput" value="<?php echo  esc_attr($dbOpqlt[$lk]);  ?>" >
                                <input type="text" name="quickLink[]" class="form-control quickLinkinput" value="<?php echo  esc_url($qlLst);  ?>" >
                                <select class="form-control quickLinkinput" name="quickLinkAF[]">
                                    <option value="">--None--</option>
                                    <?php echo $exlinkaf; ?>
                                    <?php echo str_replace($exlinkaf, "", $viewAbleFld); ?>
                                </select>
                                <button type="button" class="btn btn-danger removeQ_Link" ><i class="fas fa-minus"></i></button>
                                <div class="clr"></div>
                            </div>   
                            <div class="clr"></div>
                        <?php }
                    }
                }
            } ?>                    
            <div class="linkCon">                          
                <input type="text" name="quickLinkT[]" class="form-control quickLinkinput" placeholder="exe: Google">
                <input type="text" name="quickLink[]" class="form-control quickLinkinput" placeholder="exe: https://google.com">
                <select class="form-control quickLinkinput" name="quickLinkAF[]">
                    <option value="">--None--</option>
                    <?php echo $viewAbleFld; ?>
                </select>
                <button type="button" id="addQ_Link" class="btn btn-primary" ><i class="fas fa-plus"></i></button>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    <script type="text/javascript">
        jQuery(document).on('click', '.removeQ_Link', function(event) {
            event.preventDefault();
            jQuery(this).closest('.linkCon').remove();
        });
        jQuery(document).on('click', '#addQ_Link', function(event) {
            event.preventDefault();
            var html_ = '<div class="linkCon">\
                <input type="text" name="quickLinkT[]" class="form-control quickLinkinput">\
                <input type="text" name="quickLink[]" class="form-control quickLinkinput">\
                <select class="form-control quickLinkinput" name="quickLinkAF[]">\
                    <option value="">--None--</option>\
                    <?php echo $viewAbleFld; ?>\
                </select>\
                <button type="button" class="btn btn-danger removeQ_Link" ><i class="fas fa-minus"></i></button>\
                <div class="clr"></div>\
            </div>';
            jQuery("#linkListCon").append(html_);
        });   
    </script>


        </div>



    </div>

  



    <div class="clr"></div>
    <div class="form-group">
        <div class="col-md-6">
            <input type="submit" class="btn btn-primary newSubmitBtnDesign btn-padding" style="float: right;margin-right: 15px;" name="dashboardContent" value="Update">
        </div>
    </div>
    <div class="clr"></div>

</form>
</div>


<div class="clr"></div>
<style type="text/css">
    .dcsectitle{
        float: right;
        font-size: 16px;
        line-height: 33px;
    }
    
</style>
<div class="dashboardContent-layout box-shadow pd-30 pd-t-0" style="margin: 0px -15px;">
    <h4 style="color: #343434;font-weight: 700">Additional Settings</h4>
    <!-- <hr style="width: 100%;margin-top: 0px;"> -->

    <form role="form" action="" method="post" id="layout_View>">   
        <?php 
            wp_nonce_field('dcLayoutupdate','csrf_token_nonce');
        ?>
        <div class="layoutFields">
            <div class="row" style="padding: 6px">

        <?php
            $dbcLayoutC = array();
            $dbcLayoutC['section'] = array('dashboardbanner','accountmanager','aboutus','quicklink','videolink','iframeembed');
            $dbcLayoutC['layoutFldColumn'] = array('12','6','6','6','6','12');
            if(!isset($dbcLayout['section'])){
                $dbcLayout = $dbcLayoutC;
            }

            $dbcLayoutN['section'] = array_unique( array_merge($dbcLayout['section'], $dbcLayoutC['section']));
            $dbcLayoutN['layoutFldColumn'] =$dbcLayout['layoutFldColumn'];
            
        ?>
                
        <div class="layoutColumnContainer" id="layoutColumnContainer">
            <?php foreach ($dbcLayoutN['section'] as $key => $dcs) {
                if(isset($dbcLayoutN['layoutFldColumn'][$key])){
                    $columnSize = $dbcLayoutN['layoutFldColumn'][$key];
                }else $columnSize = "12";
                ?>
                <?php if($dcs == 'dashboardbanner'){ ?>
                    <?php if(isset($dbBnr['permission']) && ($dbBnr['permission'] == 'on')){ ?>
                        <div class="layoutColumn col-md-<?php echo $columnSize; ?>" id="layoutColumn">



                        <div class="row">
                            <div class="" style="width: 100px;display: inline-flex;">
                               
                                <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;">
                                    <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                                </span>

                                
                              <?php if($columnSize == "12"){?>
                                   
                                     <img class="makeCol6" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                      <img class="show12col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                    
                                <?php } else {?>


                                    <img class="makeCol12" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                    <img class="show6col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                  

                                <?php } ?>

                       
                                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo  esc_attr($columnSize); ?>">

                            </div>
                            <div class="moveBar" style="width:calc(100% - 120px);display: inline-flex;">
                                
                                <input type="hidden" name="section[]" value="dashboardbanner">
                                <font class="dcsectitle" style="padding-left: 10px;">Banner</font>

                            </div>
                        </div> 





                         <!--    <div style="display: inline-flex;width: 20%">
                                <button type="button" class="movecolumn"><i class="fas fa-arrows-alt"></i></button>
                                <button type="button" class="makeCol12"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                                <button type="button" class="makeCol6"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php //echo $columnSize; ?>">
                            </div>

                            <div style="width: 80%">
                                <input type="hidden" name="section[]" value="dashboardbanner">
                                <font class="dcsectitle">Banner </font>
                            </div> -->
                           
                           

                            <div class="clr"></div>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if($dcs == 'accountmanager'){ ?>
                    <div class="layoutColumn col-md-<?php echo $columnSize; ?>" id="layoutColumn">
                        <!-- 
                           <i class="fas fa-bars"></i><i class="fas fa-bars"></i></button> 
                            <button type="button" class="makeCol6 barsImg showaccountmanager"style="display: none;margin-left: 20px;"><i class="fas fa-bars"></i><i class="fas fa-bars"></i>
                        -->
                        <div class="row">
                            <div class="" style="width: 100px;display: inline-flex;">
                               
                                <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;">
                                    <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                                </span>

                                
                                <?php if($columnSize == "12"){?>
                                   
                                     <img class="makeCol6" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                      <img class="show12col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                    
                                <?php } else {?>


                                    <img class="makeCol12" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                    <img class="show6col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                  

                                <?php } ?>

                       
                                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo  esc_attr($columnSize); ?>">

                            </div>
                            <div class="moveBar" style="width:calc(100% - 120px);display: inline-flex;">
                                
                                <input type="hidden" name="section[]" value="accountmanager">
                                <font class="dcsectitle" style="padding-left: 10px;">Account Manager</font>

                            </div>
                        </div>


                      
                   
                        <div class="clr"></div>
                    </div>
                <?php } ?>

                <?php if($dcs == 'quicklink'){ ?>
                    <?php if(isset($dbOql['permission']) && ($dbOql['permission'] == 'on')){ ?>
                        <div class="layoutColumn col-md-<?php echo $columnSize; ?>"> 

                            <div class="row">
                            <div class="" style="width: 100px;display: inline-flex;">
                               
                                <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;">
                                    <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                                </span>

                                
                              <?php if($columnSize == "12"){?>
                                   
                                     <img class="makeCol6" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                      <img class="show12col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                    
                                <?php } else {?>


                                    <img class="makeCol12" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                    <img class="show6col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                  

                                <?php } ?>

                       
                                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo  esc_attr($columnSize); ?>">

                            </div>
                            <div class="moveBar" style="width:calc(100% - 120px);display: inline-flex;">
                                
                                <input type="hidden" name="section[]" value="quicklink">
                                <font class="dcsectitle" style="padding-left: 10px;"><?php echo (isset($dbOql['label'])) ?  esc_attr(stripslashes($dbOql['label'])) : 'Quick Link';  ?></font>

                            </div>
                        </div>


                         <!--    <button type="button" class="movecolumn"><i class="fas fa-arrows-alt"></i></button>
                            <button type="button" class="makeCol12"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                            <button type="button" class="makeCol6"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                            <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php //echo $columnSize; ?>">
                            <input type="hidden" name="section[]" value="quicklink">
                            <font class="dcsectitle"><?php //echo (isset($dbOql['label'])) ? stripslashes($dbOql['label']) : 'Quick Link';  ?></font>
 -->



                            <div class="clr"></div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if($dcs == 'aboutus'){ ?>
                    <?php if(isset($dbOat['permission']) && ($dbOat['permission'] == 'on')){ ?>
                        <div class="layoutColumn col-md-<?php echo $columnSize; ?>">  


                        <div class="row">
                            <div class="" style="width: 100px;display: inline-flex;">
                               
                                <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;">
                                    <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                                </span>

                                
                              <?php if($columnSize == "12"){?>
                                   
                                     <img class="makeCol6" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                      <img class="show12col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                    
                                <?php } else {?>


                                    <img class="makeCol12" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                    <img class="show6col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                  

                                <?php } ?>

                       
                                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo  esc_attr($columnSize); ?>">

                            </div>
                            <div class="moveBar" style="width: calc(100% - 120px);display: inline-flex;">
                                
                                <input type="hidden" name="section[]" value="aboutus">
                                <font class="dcsectitle" style="padding-left: 10px;"><?php echo (isset($dbOat['label'])) ?  esc_attr(stripslashes($dbOat['label'])) : 'About Us';  ?></font>

                            </div>
                        </div>





                     <!--        <button type="button" class="movecolumn"><i class="fas fa-arrows-alt"></i></button>
                            <button type="button" class="makeCol12"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                            <button type="button" class="makeCol6"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                            <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php //echo $columnSize; ?>">
                            <input type="hidden" name="section[]" value="aboutus">
                            <font class="dcsectitle"><?php //echo (isset($dbOat['label'])) ? stripslashes($dbOat['label']) : 'About Us';  ?></font> -->

                            <div class="clr"></div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if($dcs == 'videolink'){ ?>
                    <?php if(isset($dsbif['permission']) && ($dsbif['permission'] == 'on')){ ?>
                        <div class="layoutColumn col-md-<?php echo $columnSize; ?>">  


                        <div class="row">
                            <div class="" style="width: 100px;display: inline-flex;">
                               
                                <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;">
                                    <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                                </span>

                                
                             <?php if($columnSize == "12"){?>
                                   
                                     <img class="makeCol6" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                      <img class="show12col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                    
                                <?php } else {?>


                                    <img class="makeCol12" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                    <img class="show6col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                  

                                <?php } ?>

                       
                                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo  esc_attr($columnSize); ?>">

                            </div>
                            <div class="moveBar" style="width:calc(100% - 120px);display: inline-flex;">
                                
                                <input type="hidden" name="section[]" value="videolink">
                                <font class="dcsectitle" style="padding-left: 10px;"><?php echo (isset($dsbif['label'])) ?  esc_html(stripslashes($dsbif['label'])) : 'Video Link';  ?></font>

                            </div>
                        </div> 



                          <!--   <button type="button" class="movecolumn"><i class="fas fa-arrows-alt"></i></button>
                            <button type="button" class="makeCol12"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                            <button type="button" class="makeCol6"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                            <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php //echo $columnSize; ?>">
                            <input type="hidden" name="section[]" value="videolink">
                            <font class="dcsectitle"><?php //echo (isset($dsbif['label'])) ? stripslashes($dsbif['label']) : 'Video Link';  ?></font> -->




                            <div class="clr"></div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if($dcs == 'iframeembed'){ ?>
                    <?php if(isset($dsbifem['permission']) && ($dsbifem['permission'] == 'on')){ ?>
                        <div class="layoutColumn col-md-<?php echo $columnSize; ?>">   


                          <div class="row">
                            <div class="" style="width: 100px;display: inline-flex;">
                               
                                <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;">
                                    <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                                </span>

                                
                             <?php if($columnSize == "12"){?>
                                   
                                     <img class="makeCol6" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                      <img class="show12col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                    
                                <?php } else {?>


                                    <img class="makeCol12" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;">

                                    <img class="show6col" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px" style="margin-right: 0px !important;padding: 0px !important;display: none">
                                  

                                <?php } ?>

                       
                                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php echo  esc_attr($columnSize); ?>">

                            </div>
                            <div class="moveBar" style="width:calc(100% - 120px);display: inline-flex;">
                                
                                <input type="hidden" name="section[]" value="iframeembed">
                                <font class="dcsectitle" style="padding-left: 10px;"><?php echo (isset($dsbifem['label'])) ?  esc_attr(stripslashes($dsbifem['label'])) : 'Iframe Embed';  ?></font>

                            </div>
                        </div> 





                   <!--          <button type="button" class="movecolumn"><i class="fas fa-arrows-alt"></i></button>
                            <button type="button" class="makeCol12"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                            <button type="button" class="makeCol6"><i class="fas fa-bars"></i><i class="fas fa-bars"></i></button>
                            <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="<?php //echo $columnSize; ?>">
                            <input type="hidden" name="section[]" value="iframeembed">
                            <font class="dcsectitle"><?php //echo (isset($dsbifem['label'])) ? stripslashes($dsbifem['label']) : 'Iframe Embed';  ?></font>
 -->
                            <div class="clr"></div>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>


            </div>
            <div class="clr"></div>
        </div>

        <div class="clr" style="height: 20px"></div>
        <input type="submit" class="btn btn-primary fl-r dcLayoutupdate btn-padding"  name="dcLayoutupdate" value="Update" style="background: #004887 !important;border-radius: 8px !important;">
        <div class="clr"></div>
    </form>

</div>
<script type="text/javascript">
    // function showBars(ids){
    //     jQuery('.'+ids).show();
    // }
    jQuery(document).ready(function(){
            jQuery(document).on("change","#headbckcolor",function(){
                var colorCode = jQuery(this).val();
                jQuery("#headbckcolor-code").val(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#headbckcolor-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#headbckcolor").val(colorCode);
            }); 


        // jQuery(document).on('click', function (event) {
        //    jQuery('.barsImg').hide();
        // });
        // jQuery('.noclickthis').on('click', function (event) {
        //   event.stopPropagation();
        // });


           
        });
</script>