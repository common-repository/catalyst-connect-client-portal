        <style type="text/css">
            form .col-md-9 .control-label{font-weight: normal;}
            form .col-md-9 hr{margin-bottom: 0px;}
            .disabledsection {pointer-events: none;opacity: 0.4;}
            input[type="color"]::-webkit-color-swatch-wrapper {
                padding: 0;
            }
            input[type="color"]::-webkit-color-swatch {
                border: none;
                border-radius: 10px;
            }

            /*input[type="submit"]{
               width: 16%
            }*/

        </style>

        <div class="clr"></div>
    
            <?php 
                if(isset($apfontC['bodycolor']) || isset($apMenC['menuhvBgcolor']) || isset($apbtnC['suBtnBgcolor'])) { 
                    $checkbox = ''; $disabledsection = '';$updateChecked = 'updatedefaultApperance';
                }
                else {
                    $checkbox = 'checked';$disabledsection='disabledsection';
                }
            ?>

        <div class="form-group">
            <div class="default-switch">
                <!-- updatedefaultApperance -->
                <span style="" class="Setting-apprerance">Default</span>
                <label class="switch control-label switch-appearence">
                    <input type="checkbox" class="form-control acinput <?php if(isset($updateChecked)) echo $updateChecked?>" id="updateDefault" name="showAccMngr" <?php echo $checkbox ?>>
                    <span class="slider round slider-appearence"></span>
                </label>
            </div>
            <div class="clr"></div>
        </div>


    <div class="clr">
        
    </div>

    <div class="row">

<div class="col-md-6">

    <div class="dashboardContent-layout pd-30 <?php echo $disabledsection?>" style="margin: 0px -15px 30px;">

        <?php 
            if(isset($apfontC['bodycolor']) && $apfontC['bodycolor'] !="") $primarycolor = $apfontC['bodycolor'];
            else $primarycolor = '#2871d1'; 
        ?>

        <h4>Font Color</h4>

        <form role="form" action="" id="updatefontcolor" method="post" >
            <input type="hidden" class="formid" value="updatefontcolor">
                
            <?php 
                wp_nonce_field('ap_fontColor','csrf_token_nonce');
            ?>

            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="control-label">Body &nbsp;<span><i class="fas fa-info-circle"  data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                            </div>

                            <div class="col-md-12">
                                <div class="colobox">
                                    <input type="color" name="bodycolor" id="body-color" class="form-control NewInput" value="<?php echo esc_attr($primarycolor);?>" style="background: <?php echo $primarycolor;?>;">
                                    <input type="text" id="body-color-code" class="form-control Newinput2" value="<?php echo esc_attr($primarycolor);?>" style="">
                                 
                                </div>
                            </div>
                       
                       </div>
                    </div>
                </div>


                 <div class="col-md-12 topPadding">
                    <div class="form-group">
                      

                    <?php 
                        if(isset($apfontC['fontcolor'])) $heddingFc = $apfontC['fontcolor'];
                        else $heddingFc = '#228be6'; 
                    ?> 

                    <div class="row">

                      <!--   <div class="col-md-12">
                            
                        </div> -->

                        <div class="col-md-6">
                             <label class="control-label">Headings Font Color &nbsp;<span><i class="fas fa-info-circle"  data-toggle="tooltip" data-placement="top" title="test"></i></span></label>

                            <div class="colobox">
                            
                                <input type="color" name="fontcolor" id="font-color" class="form-control NewInputcol6" value="<?php echo esc_attr($heddingFc);?>" style="background: <?php echo esc_attr($heddingFc);?>;">
                              
                                <input type="text" id="font-color-code" class="form-control Newinput2col6" value="<?php echo esc_attr($heddingFc);?>" style="">
       
                             
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="control-label">Headings Font Weight</label>
                            <?php 
                                if(isset($apfontC['heddingFw'])) $heddingFw = $apfontC['heddingFw'];
                                else $heddingFw = 'normal'; 
                            ?>
                            <select name="heddingFw" class="form-control newselect" >
                                <option value="normal" <?php if($heddingFw == "normal")echo "selected"; ?>>Normal</option>
                                <option value="bold" <?php if($heddingFw == "bold")echo "selected"; ?>>Bold</option>
                            </select>

                        </div>

                    </div>
                       
                       
                    </div>
                </div>



                
            </div>
           

            <!-- <div class="form-group">
                <label class="col-md-3 control-label">Body</label>
                <div class="col-md-9">   
                    <?php 
                        //if(isset($apfontC['bodyFc'])) $bodyFc = $apfontC['bodyFc'];
                       // else $bodyFc = '#5a5a5a'; 
                    ?>
                    <input type="text" name="bodyFc" class="form-control colorpicker" value="<?php //echo $bodyFc;?>" style="background: <?php //echo $bodyFc;?>" >                    
                </div>
                <div class="clr"></div>
            </div> -->              
            

            <div class="clr"></div>
            <div class="form-group">
                <div class="">
                    <input type="submit" class="btn btn-primary newSubmitBtnDesign btn-padding" name="ap_fontColor" value="Update" style="float: right;margin-top: 15px;">
                    <!-- <button type="button" class="btn btn-primary" style="float: right;" onclick="fontColorSetDefult()">Default</button>  -->
                </div>
            </div>
            <div class="clr"></div>

        </form>
        <form action="" method="post" id="fontColorSetDefultFrom">
            <input type="hidden" name="ap_fontColor">
        </form>
        <script type="text/javascript">
            function fontColorSetDefult() {
                jQuery("#fontColorSetDefultFrom").submit();
            }
        </script>
    </div>

    </div>



<div class="col-md-6">

    <div class="dashboardContent-layout pd-30 <?php echo $disabledsection?>" style="margin: 0px -15px 30px;">
    <h4>Menu Color </h4>
    <form role="form" action="" id="updatemenucolor" method="post" >
        <input type="hidden" class="formid" value="updatemenucolor">
            
        <?php 
            wp_nonce_field('ap_menuColor','csrf_token_nonce');
        ?>

        <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            

                            <?php
                                if(isset($apMenC['menuBgcolor'])) $menubckcolor = $apMenC['menuBgcolor'];
                                else $menubckcolor = '#FFFFFF'; 
                            ?>

                            <div class="col-md-6">
                                <label class="control-label">Menu Background Color</label>
                               

                                <div class="colobox">
                                    <input type="color" name="menuBgcolor" id="menuBg-color" class="form-control NewInput12" value="<?php echo esc_attr($menubckcolor);?>" style="background: <?php echo esc_attr($menubckcolor);?>;">
                                    <input type="text" id="menuBg-color-code" class="form-control Newinput212" value="<?php echo esc_attr($menubckcolor);?>" style="">
                                 
                                </div>
                            </div>

                            <?php 
                                if(isset($apMenC['menufontcolor'])) $menufontcolor = $apMenC['menufontcolor'];
                                else $menufontcolor = '#000000'; 
                            ?> 

                             <div class="col-md-6">
                                <label class="control-label">Menu Font Color</label>
                               

                               
                                <div class="colobox">
                                    <input type="color" name="menufontcolor" id="menufont-color" class="form-control NewInput12" value="<?php echo esc_attr($menufontcolor);?>" style="background: <?php echo esc_attr($menufontcolor);?>;">
                                    <input type="text" id="menufont-color-code" class="form-control Newinput212" value="<?php echo esc_attr($menufontcolor);?>" style="">
                                 
                                </div>
                            </div>

                    

                   <?php 
                        if(isset($apMenC['menuAcBgcolor'])) $menuactbgcolor = $apMenC['menuAcBgcolor'];
                        else $menuactbgcolor = '#228be6'; 
                    ?>    

                            <div class="col-md-6 topPadding">
                                <label class="control-label">Menu Active Background Color</label>
                               
                          
                                <div class="colobox">
                                    <input type="color" name="menuAcBgcolor" id="menuAcBg-color" class="form-control NewInput12" value="<?php echo esc_attr($menuactbgcolor);?>" style="background: <?php echo esc_attr($menuactbgcolor);?>;">
                                    <input type="text" id="menuAcBg-color-code" class="form-control Newinput212" value="<?php echo esc_attr($menuactbgcolor);?>" style="">
                                 
                                </div>
                            </div>

                    <?php 
                        if(isset($apMenC['menuacfontcolor'])) $menuactfc = $apMenC['menuacfontcolor'];
                        else $menuactfc = '#FFFFFF'; 
                    ?>    

                            <div class="col-md-6 topPadding">
                                <label class="control-label">Menu Active Font Color</label>
                               
                               
                                <div class="colobox">
                                    <input type="color" name="menuacfontcolor" id="menuacfont-color" class="form-control NewInput12" value="<?php echo esc_attr($menuactfc);?>" style="background: <?php echo esc_attr($menuactfc);?>;">
                                    <input type="text" id="menuacfont-color-code" class="form-control Newinput212" value="<?php echo esc_attr($menuactfc);?>" style="">
                                 
                                </div>
                            </div>





                   <?php 
                       if(isset($apMenC['menuhvBgcolor'])) $menuhovbg = $apMenC['menuhvBgcolor'];
                        else $menuhovbg = '#3ea1f5'; 
                    ?>    
                            <div class="col-md-6 topPadding">
                                <label class="control-label">Menu Hover Background Color</label>
                               
                          
                                <div class="colobox">
                                    <input type="color" name="menuhvBgcolor" id="menuhvBg-color" class="form-control NewInput12" value="<?php echo esc_attr($menuhovbg);?>" style="background: <?php echo esc_attr($menuhovbg);?>;">
                                    <input type="text" id="menuhvBg-color-code" class="form-control Newinput212" value="<?php echo esc_attr($menuhovbg);?>" style="">
                                 
                                </div>
                            </div>

                    <?php 
                        if(isset($apMenC['menuhvfontcolor'])) $menuhovf = $apMenC['menuhvfontcolor'];
                        else $menuhovf = '#FFFFFF'; 
                    ?>    

                            <div class="col-md-6 topPadding">
                                <label class="control-label">Menu Active Background Color</label>
                               
                               
                                <div class="colobox">
                                    <input type="color" name="menuhvfontcolor" id="menuhvfont-color" class="form-control NewInput12" value="<?php echo esc_attr($menuhovf);?>" style="background: <?php echo esc_attr($menuhovf);?>;">
                                    <input type="text" id="menuhvfont-color-code" class="form-control Newinput212" value="<?php echo esc_attr($menuhovf);?>" style="">
                                </div>
                            </div>


                            <div class="col-md-12">            
                                <input type="submit" class="btn btn-primary newSubmitBtnDesign btn-padding" name="ap_menuColor" value="Update" style="float: right;margin-top: 15px;">
                                <!-- <button type="button" class="btn btn-primary" style="float: right;" onclick="menuColorSetDefult()">Default</button>  -->
                            </div>

                       
                       </div>

                    </div>
                </div>

            </div>

       



    </form>

</div>

</div>
 <div class="clr"></div>

    <form action="" method="post" id="menuColorSetDefultFrom">
        <input type="hidden" name="ap_menuColor">
    </form>

    <script type="text/javascript">
        function menuColorSetDefult() {
            jQuery("#menuColorSetDefultFrom").submit();
        }
    </script>



<div class="col-md-7">
<div class="dashboardContent-layout box-shadow pd-30 <?php echo $disabledsection?>" style="margin: 0px -15px;">
    <h4>Button Color</h4>
    <form role="form" action="" id="updateap_btnColor" method="post" >
        <input type="hidden" class="formid" value="updateap_btnColor">
        
        <?php 
            wp_nonce_field('ap_btnColor','csrf_token_nonce');
        ?>


<!-- Last Form -->

            <div class="row">

                <div class="col-md-12">
                    <div class="form-group">
                        <div class="row">
                            

                    <?php 
                        if(isset($apbtnC['btnBgcolor'])) $addButtonBg = $apbtnC['btnBgcolor'];
                        else $addButtonBg = '#337ab7'; 
                    ?> 

                            <div class="col-md-6">
                                <label class="control-label">Add Button Background Color</label>
                              

                                <div class="colobox">
                                    <input type="color" name="btnBgcolor" id="btnBg-color" class="form-control NewInput12" value="<?php echo $addButtonBg;?>" style="background: <?php echo $addButtonBg;?>;">
                                    <input type="text" id="btnBg-color-code" class="form-control Newinput212" value="<?php echo $addButtonBg;?>" style="">
                                 
                                </div>
                            </div>


                             <div class="col-md-6">
                                <label class="control-label">Add Button Font Color</label>
             
                               <?php
                                    if(isset($apbtnC['btnfontcolor'])) $btnFont = $apbtnC['btnfontcolor'];
                                    else $btnFont = '#FFFFFF'; 
                                ?>  
                                <div class="colobox">
                                    <input type="color" name="btnfontcolor" id="btnfont-color" class="form-control NewInput12" value="<?php echo $btnFont;?>" style="background: <?php echo $btnFont;?>;">
                                    <input type="text" id="btnfont-color-code" class="form-control Newinput212" value="<?php echo $btnFont;?>" style="">
                                 
                                </div>
                            </div>

  

                    <div class="col-md-6 topPadding">
                                <label class="control-label">Edit Button Background Color</label>
                               
                           <?php
                                if(isset($apbtnC['editBtnBgcolor'])) $editButtonBg = $apbtnC['editBtnBgcolor'];
                                else $editButtonBg = '#337ab7'; 
                            ?>  
                                <div class="colobox">
                                    <input type="color" name="editBtnBgcolor" id="editBtnBg-color" class="form-control NewInput12" value="<?php echo $editButtonBg;?>" style="background: <?php echo $editButtonBg;?>;">
                                    <input type="text" id="editBtnBg-color-code" class="form-control Newinput212" value="<?php echo $editButtonBg;?>" style="">
                                 
                                </div>
                            </div>

                    <?php 
                        if(isset($apbtnC['editBtnfontcolor'])) $editButtonFnt = $apbtnC['editBtnfontcolor'];
                        else $editButtonFnt = '#FFFFFF'; 
                    ?>    

                            <div class="col-md-6 topPadding">
                                <label class="control-label">Edit Button Font Color</label>
                               
                               
                                <div class="colobox">
                                    <input type="color" name="menuacfontcolor" name="editBtnfontcolor" id="editBtnfont-color"  class="form-control NewInput12" value="<?php echo $editButtonFnt;?>" style="background: <?php echo $editButtonFnt;?>;">
                                    <input type="text" id="menuacfont-color-code" class="form-control Newinput212" value="<?php echo $editButtonFnt;?>" style="">
                                 
                                </div>
                            </div>





                   <?php 
                       if(isset($apbtnC['deleteBtnBgcolor'])) $deleteButtonBg = $apbtnC['deleteBtnBgcolor'];
                        else $deleteButtonBg = '#f0ad4e';
                    ?>    
                            <div class="col-md-6 topPadding">
                                <label class="control-label">Delete Button Background Color</label>
                               
                          
                                <div class="colobox">
                                    <input type="color" name="deleteBtnBgcolor" id="deleteBtnBg-color" class="form-control NewInput12" value="<?php echo $deleteButtonBg;?>" style="background: <?php echo $deleteButtonBg;?>;">
                                    <input type="text"  id="deleteBtnBg-color-code"  class="form-control Newinput212" value="<?php echo $deleteButtonBg;?>" style="">
                                 
                                </div>
                            </div>

                    <?php 
                         if(isset($apbtnC['deleteBtnfontcolor'])) $deleteButtonFnt = $apbtnC['deleteBtnfontcolor'];
                        else $deleteButtonFnt = '#FFFFFF'; 
                    ?>    

                            <div class="col-md-6 topPadding">
                                <label class="control-label">Delete Button Font Color</label>
                               
                               
                                <div class="colobox">
                                    <input type="color" name="deleteBtnfontcolor" id="deleteBtnfont-color" class="form-control NewInput12" value="<?php echo $deleteButtonFnt;?>" style="background: <?php echo $deleteButtonFnt;?>;">
                                    <input type="text" id="deleteBtnfont-color-code" class="form-control Newinput212" value="<?php echo $deleteButtonFnt;?>" style="">
                                </div>
                            </div>





                   <?php 
                     if(isset($apbtnC['suBtnBgcolor'])) $saveButtonBG = $apbtnC['suBtnBgcolor'];
                        else $saveButtonBG = '#337ab7'; 
                    ?>    
                            <div class="col-md-6 topPadding">
                                <label class="control-label">Save/Upload Button Background Color</label>
                               
                          
                                <div class="colobox">
                                    <input type="color" name="suBtnBgcolor" id="suBtnBg-color" class="form-control NewInput12" value="<?php echo $saveButtonBG;?>" style="background: <?php echo $saveButtonBG;?>;">
                                    <input type="text" id="suBtnBg-color-code" class="form-control Newinput212" value="<?php echo $saveButtonBG;?>" style="">
                                 
                                </div>
                            </div>  


                    <?php 
                        if(isset($apbtnC['suBtnfontcolor'])) $saveButtonFnt = $apbtnC['suBtnfontcolor'];
                        else $saveButtonFnt = '#FFFFFF'; 
                    ?>                                                 
                 
                            <div class="col-md-6 topPadding">

                                <label class="control-label">Save/Upload Button Font Color</label>
                               
                               
                                <div class="colobox">
                                    <input type="color" name="suBtnfontcolor" id="suBtnfont-color" class="form-control NewInput12" value="<?php echo $saveButtonFnt;?>" style="background: <?php echo $saveButtonFnt;?>;">
                                    <input type="text"  id="suBtnfont-color-code" class="form-control Newinput212" value="<?php echo $saveButtonFnt;?>" style="">
                                </div>
                            </div>
                            

                    <?php 
                      if(isset($apbtnC['viewBtnBgcolor'])) $viewButtonBg = $apbtnC['viewBtnBgcolor'];
                        else $viewButtonBg = '#337ab7';  
                    ?>    
                            <div class="col-md-6 topPadding">
                                <label class="control-label">View Button Background Color</label>
                               
                          
                                <div class="colobox">
                                    <input type="color" name="viewBtnBgcolor" id="viewBtnBg-color" class="form-control NewInput12" value="<?php echo $viewButtonBg;?>" style="background: <?php echo $viewButtonBg;?>;">
                                    <input type="text" id="viewBtnBg-color-code"  class="form-control Newinput212" value="<?php echo $viewButtonBg;?>" style="">
                                 
                                </div>
                            </div>


                      


                        <?php 
                           if(isset($apbtnC['viewBtnfontcolor'])) $viewButtonFnt = $apbtnC['viewBtnfontcolor'];
                          else $viewButtonFnt = '#FFFFFF'; 
                        ?>                                                 
                 
                            <div class="col-md-6 topPadding">
                                <label class="control-label">View Button Font Color</label>
                               
                               
                                <div class="colobox">
                                    <input type="color" name="viewBtnfontcolor" id="viewBtnfont-color"  class="form-control NewInput12" value="<?php echo $viewButtonFnt;?>" style="background: <?php echo $viewButtonFnt;?>;">
                                    <input type="text"  name="viewBtnfontcolor" id="viewBtnfont-color"  class="form-control Newinput212" value="<?php echo $viewButtonFnt;?>" style="">
                                </div>
                            </div>




                         
                       
                       </div>

                    </div>
                </div>

            </div>

            <!-- End Last Form -->






        <div class="clr"></div>
        <div class="form-group">
            <div class="">
                <input type="submit" class="btn btn-primary newSubmitBtnDesign btn-padding" name="ap_btnColor" value="Update" style="margin-left: 12px;float: right;margin-top: 20px;">
            </div>
        </div>
        <div class="clr"></div>

    </form>
    <form action="" method="post" id="btnColorSetDefultFrom">
        <input type="hidden" name="ap_btnColor">
    </form>


    
    </div>

    </div>

    </div>
 <div class="clr"></div>

    <script type="text/javascript">
        function btnColorSetDefult() {
            jQuery("#btnColorSetDefultFrom").submit();
        }
    </script>
    <script type="text/javascript">
        
        jQuery(document).ready(function(){
            jQuery(document).on("change","#body-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#body-color-code").val(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#body-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#body-color").val(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#font-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#font-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#font-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#font-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#btnfont-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#btnfont-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#btnfont-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#btnfont-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#btnBg-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#btnBg-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#btnBg-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#btnBg-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#editBtnfont-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#editBtnfont-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#editBtnfont-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#editBtnfont-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#editBtnBg-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#editBtnBg-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#editBtnBg-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#editBtnBg-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#deleteBtnfont-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#deleteBtnfont-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#deleteBtnfont-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#deleteBtnfont-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#deleteBtnBg-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#deleteBtnBg-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#deleteBtnBg-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#deleteBtnBg-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#suBtnfont-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#suBtnfont-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#suBtnfont-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#suBtnfont-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#suBtnBg-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#suBtnBg-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#suBtnBg-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#suBtnBg-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#viewBtnfont-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#viewBtnfont-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#viewBtnfont-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#viewBtnfont-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#viewBtnBg-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#viewBtnBg-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#viewBtnBg-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#viewBtnBg-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });


        jQuery(document).ready(function(){
            jQuery(document).on("change","#menufont-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menufont-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menufont-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menufont-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuBg-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuBg-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuBg-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuBg-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuacfont-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuacfont-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuacfont-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuacfont-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuAcBg-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuAcBg-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuAcBg-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuAcBg-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuhvfont-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuhvfont-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuhvfont-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuhvfont-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuhvBg-color",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuhvBg-color-code").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("change","#menuhvBg-color-code",function(){
                var colorCode = jQuery(this).val();
                jQuery("#menuhvBg-color").val(colorCode);
                // jQuery("#primary-color-code").text(colorCode);
            }); 
        });

        jQuery(document).ready(function(){
            jQuery(document).on("input","#updateDefault",function(){
               if(jQuery('#updateDefault').is(':checked')){
                  jQuery('.pd-30').addClass('disabledsection');
                  jQuery('#updateDefault').removeClass('updatedefaultApperance');
               }

               else {
                  jQuery('.pd-30').removeClass('disabledsection');
                  jQuery('#updateDefault').addClass('updatedefaultApperance');

            }
            }); 
        });
        
    </script>

