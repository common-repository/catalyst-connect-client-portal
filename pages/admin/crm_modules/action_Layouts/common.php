
<script type="text/javascript">
    jQuery(document).ready(function(){ 

        jQuery(document).on('change', '.viewAbleCheckBox', function(event) {

            if (jQuery(this).is(':checked'))  {
                jQuery(this).closest('.switch').find('input.fldView').val("yes");
                jQuery(this).closest('.switch').find('span').addClass('active');
                jQuery(this).closest('.switch').find('i').addClass('fa-eye');
                jQuery(this).closest('.switch').find('i').removeClass('fa-eye-slash');
            }else {
                jQuery(this).closest('.switch').find('input.fldView').val("no"); 
                jQuery(this).closest('.switch').find('span').removeClass('active');
                
                jQuery(this).closest('.switch').find('i').removeClass('fa-eye');
                jQuery(this).closest('.switch').find('i').addClass('fa-eye-slash');
            }           

        }); 

        jQuery(document).on('change', '.addAbleCheckBox', function(event) {

            if (jQuery(this).is(':checked'))  {
                jQuery(this).closest('.switch').find('input.fldAdd').val("yes");
                jQuery(this).closest('.switch').find('span').addClass('active');
            }else {
                jQuery(this).closest('.switch').find('input.fldAdd').val("no");   
                jQuery(this).closest('.switch').find('span').removeClass('active'); 
            }         

        }); 

        jQuery(document).on('change', '.editAbleCheckBox', function(event) {

            if (jQuery(this).is(':checked'))  {
                jQuery(this).closest('.switch').find('input.fldEdit').val("yes");
                jQuery(this).closest('.switch').find('span').addClass('active');
            }else {
                jQuery(this).closest('.switch').find('input.fldEdit').val("no"); 
                jQuery(this).closest('.switch').find('span').removeClass('active');           
            }

        });
    });
</script>


<div id="layoutRow" style="display: none;">
    <div class="row layoutRow">
        <div class="layoutRowHeader col-md-12">
            <input type="hidden" class="rowAdded" name="rowAdded[]">
            <input type="hidden" class="columnAdded" name="columnAdded[]">
            <input type="hidden" class="sectiontype" name="sectiontype[]" value="normal">

            <div class="row">

             <div class="col-md-8">
                <span class="noclickthis movesection" style="margin-right: 0px !important;padding: 0px !important;float: left;">
                    <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                </span>              
                <input type="text" class="form-control sectiontitle" placeholder="Section Title" name="sectiontitle[]">
            </div>

              <div class="col-md-4">
                <button type="button" class="remove removeRow" title="Remove" style="float: right;"><i class="fas fa-times" style="font-size: 18px;"></i></button>
                <button type="button" class="btn btn-primary addLayColumn" style="float: right;    border-radius: 10px;"><i class="fas fa-plus"></i> Add Field</button>
                <?php if(isset($gglintOn)){ ?>
                    <button type="button" class="btn btn-primary addLayAddSrcColumn" style="float: right;""><i class="fas fa-plus"></i> Address Searchbar</button>
                <?php } ?>
              </div>
              
          </div>


        </div>
        <div class="clr"></div>

        <div class="layoutColumnContainer"></div>
        <div class="clr"></div>
    </div>
</div>
<div id="layoutColumn" style="display: none;">
    <div class="Column12">
        <div class="layoutColumn layoutRowHeader col-md-12">     

            <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;float: left;">
                <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
            </span>

            <img class="makeCol12" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-13.png" width="35px">
            <img class="makeCol6" src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-12.png" width="35px">
            
            <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="12">
            <input type="hidden" class="layoutFieldList" name="layoutFieldList[]">
            <select class="form-control update_fieldNameValue" name="fieldName[]" required value="">
                <option value="">--Select One--</option>
                <?php
                    echo $allFld;
                ?>
            </select>
            <button type="button" class="remove removeLColumn"><i class="fas fa-times"></i></button>

            <!-- <div class="clr"></div> -->
            <div class="fieldAcp">
                <?php if(isset($acpMdlV['View'])){ ?>
                <div class="form-group">
                    <label class="switch">
                        <input type="hidden" name="fldView[]" class="fldView" value="yes">
                        <input class="viewAbleCheckBox" type="checkbox" checked>
                        <span class="active"><i class="fas fa-eye"></i></span>
                    </label>
                  <div class="clr"></div>
                </div>
                <?php } ?>
                <?php if(isset($acpMdlV['Add'])){ ?>
                <div class="form-group">
                    <label class="switch">
                        <input type="hidden" name="fldAdd[]" class="fldAdd" value="yes">
                        <input class="addAbleCheckBox" type="checkbox" checked>
                        <span class="active"><i class="fas fa-plus-square"></i> </span>
                    </label>
                  <div class="clr"></div>
                </div>
                <?php } ?>
                <?php if(isset($acpMdlV['Edit'])){ ?>
                <div class="form-group" style="margin-right: 0px;">
                    <label class="switch">
                        <input type="hidden" name="fldEdit[]" class="fldEdit" value="yes">
                        <input class="editAbleCheckBox" type="checkbox" checked>
                        <span class="active"><i class="fas fa-edit"></i></i></span>
                    </label>
                  <div class="clr"></div>
                </div>
                <?php } ?>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var numRowAdded = 0;

    jQuery(document).on('click', '.addRow', function(event) {
        event.preventDefault();
        var numRowAdded = jQuery(this).closest('form').find('.layoutRow').length;

        jQuery("#layoutRow").find('.rowAdded').val(numRowAdded + 1);
        var rowHtml = jQuery("#layoutRow").html();
        jQuery(this).closest('form').find('.layoutFields').append(rowHtml);
    });
    jQuery(document).on('click', '.removeRow', function(event) {
        event.preventDefault();
        jQuery(this).closest('div.layoutRow').remove();        
    });

    jQuery(document).on('click', '.addLayColumn', function(event) {
        event.preventDefault();
        var Column12 = jQuery("#layoutColumn .Column12").html();
        jQuery(this).closest('.row.layoutRow').find('.layoutColumnContainer').append(Column12);     
        var numColAdded = jQuery(this).closest('.layoutRow').find('.layoutColumnContainer').find('.layoutColumn').length;
        jQuery(this).closest('.layoutRow').find('input.columnAdded').val(numColAdded);
    });

    jQuery(document).on('click', '.makeCol12', function(event) {
        jQuery(this).parent('.layoutColumn').removeClass('col-md-6');
        jQuery(this).parent('.layoutColumn').addClass('col-md-12');
        jQuery(this).parent('.layoutColumn').find('.layoutFldColumn').val('12'); 
    });
    jQuery(document).on('click', '.makeCol6', function(event) {
        jQuery(this).parent('.layoutColumn').removeClass('col-md-12');
        jQuery(this).parent('.layoutColumn').addClass('col-md-6'); 
        jQuery(this).parent('.layoutColumn').find('.layoutFldColumn').val('6'); 
    });
    jQuery(document).on('change', '.update_fieldNameValue', function(event) {
        var slctdFld = jQuery(this).val();
        var rowNmbr = jQuery(this).closest('.layoutRow').find('.rowAdded').val();

        jQuery(this).parent('.layoutColumnContainer').find('input.layoutFieldList').val(rowNmbr+'_'+slctdFld);

    });



    jQuery(document).on('click', '.addColumn12', function(event) {
        event.preventDefault();
        var Column12 = jQuery("#layoutColumn .Column12").html();
        jQuery(this).parent('.row.layoutRow').find('.layoutColumnContainer').html(Column12);

        jQuery(this).parent('.row.layoutRow').find('.columnAdded').val(1);
        
    });
    jQuery(document).on('click', '.addColumn6', function(event) {
        event.preventDefault();
        var Column6 = jQuery("#layoutColumn .Column6").html();
        jQuery(this).parent('.row.layoutRow').find('.layoutColumnContainer').html(Column6+Column6);

        jQuery(this).parent('.row.layoutRow').find('.columnAdded').val(2);
    });

    jQuery(document).on('click', '.removeLColumn', function(event) {
        event.preventDefault();
        var numColAdded = jQuery(this).closest('.layoutColumnContainer').find('.layoutColumn').length;
        jQuery(this).closest('.layoutRow').find('input.columnAdded').val(numColAdded - 1);

        jQuery(this).parent('.layoutColumn').remove();         
    });

</script>




<div id="layoutRowSubfrom" style="display: none;">
    <div class="row layoutRow">
        <div class="layoutRowHeader col-md-12">
            <input type="hidden" class="rowAdded" name="rowAdded[]">
            <input type="hidden" class="columnAdded" name="columnAdded[]">
            <input type="hidden" class="sectiontype" name="sectiontype[]" value="subform">


            <div class="row">

                <div class="col-md-8">
                    <span class="noclickthis movesection" style="margin-right: 0px !important;padding: 0px !important;float: left;">
                        <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                     </span>                  
                    <input type="text" class="form-control sectiontitle" placeholder="Section Title" name="sectiontitle[]">
                </div>

                <div class="col-md-4">
                    <button type="button" class="remove removeRow" title="Remove" style="float: right;"><i class="fas fa-times" style="font-size: 18px;"></i></button>
                    <button type="button" class="btn btn-primary addLayColumnSubform" style="float: right;    border-radius: 10px;"><i class="fas fa-plus"></i> Add Field</button>
                </div>
              
            </div>

            <div class="clr"></div>
        </div>
        <div class="clr"></div>

        <div class="layoutColumnContainer"></div>
        <div class="clr"></div>
    </div>
</div>

<div id="layoutColumnSubfrom" style="display: none;">
    <div class="Column12">
        <div class="subform-field">
            <div class="layoutColumn col-md-12" style="width: calc(100% - 150px) !important;">
                <span class="noclickthis movecolumn" style="margin-right: 0px !important;padding: 0px !important;float: left;">
                    <img src="<?php echo $pluginUrl ?>assets/images/catalyst-portal-icon-14.png" width="35px" style="">
                </span> 

                <input type="hidden" class="layoutFldColumn" name="layoutFldColumn[]" value="12">
                <input type="hidden" class="layoutFieldList" name="layoutFieldList[]">
                
                <select class="form-control update_fieldNameValue subfromSelect" name="fieldName[]" required>
                    <option value="">--Select One--</option>
                    <?php
                        echo $subformFld;
                    ?>
                </select>
                <button type="button" class="remove removeSfFiels"><i class="fas fa-times"></i></button>

                <div class="fieldAcp">
                    <?php if(isset($acpMdlV['View'])){ ?>
                    <div class="form-group">
                        <label class="switch">
                            <input type="hidden" name="fldView[]" class="fldView" value="yes">
                            <input class="viewAbleCheckBox" type="checkbox" checked>
                            <span class="active"><i class="fas fa-eye"></i></span>
                        </label>
                        <div class="clr"></div>
                    </div>
                    <?php } ?>
                    <?php if(isset($acpMdlV['Add'])){ ?>
                    <div class="form-group">
                        <label class="switch">
                            <input type="hidden" name="fldAdd[]" class="fldAdd" value="yes">
                            <input class="addAbleCheckBox" type="checkbox" checked>
                            <span class="active"><i class="fas fa-plus-square"></i> </span>
                        </label>
                        <div class="clr"></div>
                    </div>
                    <?php } ?>
                    <?php if(isset($acpMdlV['Edit'])){ ?>
                    <div class="form-group" style="margin-right: 0px;">
                        <label class="switch">
                            <input type="hidden" name="fldEdit[]" class="fldEdit" value="yes">
                            <input class="editAbleCheckBox" type="checkbox" checked>
                            <span class="active"><i class="fas fa-edit"></i></i></span>
                        </label>
                        <div class="clr"></div>
                    </div>
                    <?php } ?>
                    <div class="clr"></div>
                </div>

            </div>
            <button type="button" class="btn btn-primary addFieldSubform" style="margin-top: 30px;"><i class="fas fa-plus"></i> Add Field</button>

            <div class="clr"></div>
            <div class="slctSubfromLayout" style="display: none;">
                <div class="SubfromCRMFields" style="display: none;"></div>
            </div>
            <div class="clr"></div>
        </div>
    </div>
</div>
<div id="SubfromField" style="display: none;">
    <div class="layoutSfTbl col-md-3">          
        <select class="form-control" name="subformfieldName[]" style="width: calc(100% - 40px) !important;float: left;"></select>
        <button type="button" class="remove removeSfColumn"><i class="fas fa-times"></i></button>
        <div class="clr"></div>
    </div>
</div>
<script type="text/javascript">

    jQuery(document).on('click', '.addRowSubform', function(event) {
        event.preventDefault();
        var numRowAdded = jQuery(this).closest('form').find('.layoutRow').length;

        jQuery("#layoutRowSubfrom").find('.rowAdded').val(numRowAdded + 1);
        var rowHtml = jQuery("#layoutRowSubfrom").html();
        jQuery(this).closest('form').find('.layoutFields').append(rowHtml);
    });

    jQuery(document).on('click', '.addLayColumnSubform', function(event) {
        event.preventDefault();
        var Column12 = jQuery("#layoutColumnSubfrom .Column12").html();
        jQuery(this).closest('.row.layoutRow').find('.layoutColumnContainer').append(Column12);     
        var numColAdded = jQuery(this).closest('.layoutRow').find('.layoutColumnContainer').find('.layoutColumn').length;
        jQuery(this).closest('.layoutRow').find('input.columnAdded').val(numColAdded);
    });

    jQuery(document).on('click', '.addFieldSubform', function(event) {
        event.preventDefault();
        // var subfromField = jQuery("#SubfromField").html();
        var subfromField = jQuery(this).closest('.subform-field').find('.slctSubfromLayout').find('.SubfromCRMFields').html();
        jQuery(this).closest('.subform-field').find('.slctSubfromLayout').append(subfromField);
        jQuery(this).closest('.subform-field').find('.slctSubfromLayout').find('.layoutColumn select').attr('required', 'required');
        jQuery(this).closest('.subform-field').find('.slctSubfromLayout').find('.SubfromCRMFields .layoutColumn select').removeAttr('required');
    });

</script>

<script type="text/javascript">
    <?php
        // $module = str_replace("zbooks_", "", str_replace("zcrm_", "", $acTab)) ;
        
        $view_type = "view";
        if($actTab == "Edit") $view_type = "edit";
        if($actTab == "View") $view_type = "view";
        if($actTab == "Add") $view_type = "create";
    ?>
    jQuery(document).ready(function(){ 

        function getmoduleFields(apiName,_this){
            jQuery.ajax({
                type:'POST',
                data:{ module: "<?php echo $module; ?>", subform: apiName, for: "getFieldsSubform", action:'ccgpp_ajaxrequest', view_type: '<?php echo $view_type ?>'},
                url: "<?php echo site_url(); ?>/wp-admin/admin-ajax.php",
                success: function(value) {
                    // console.log(value);    
                    jQuery(_this).closest('.subform-field').find(".slctSubfromLayout .layoutSfTbl").remove();           
                    jQuery("#SubfromField select").html("");               
                    jQuery("#SubfromField select").attr('name', 'subformfieldName_'+apiName+'[]');              
                    jQuery("#SubfromField select").html(value);

                    var fieldsList = jQuery("#SubfromField").html(); 
                    jQuery(_this).closest('.subform-field').find(".slctSubfromLayout .SubfromCRMFields").html(fieldsList);               
                    jQuery(_this).closest('.subform-field').find(".slctSubfromLayout").show();               
                    jQuery(_this).closest('.subform-field').find(".slctSubfromLayout").append(fieldsList);   
                    jQuery(_this).closest('.subform-field').find('.slctSubfromLayout').find('.layoutColumn select').attr('required', 'required'); 
                    jQuery(_this).closest('.subform-field').find('.slctSubfromLayout').find('.SubfromCRMFields .layoutColumn select').removeAttr('required');
                }
            });
        }
        jQuery(document).on('change', '.subfromSelect', function(event) {
            
            var subfromName = jQuery(this).val();
            if(subfromName !=""){
                console.log(subfromName);
                var nameArr = subfromName.split('___');
                var moduleAPiName = nameArr[0];
                getmoduleFields(moduleAPiName, jQuery(this));
            }else{
                jQuery(this).closest('.subform-field').find('.slctSubfromLayout .layoutSfTbl').remove();
                jQuery(this).closest('.subform-field').find('.slctSubfromLayout').hide();
            }
            
        });

        jQuery(document).on('click', '.removeSfColumn', function(event) {
            event.preventDefault();
            jQuery(this).parent('.layoutSfTbl').remove();         
        });
        jQuery(document).on('click', '.removeSfFiels', function(event) {
            event.preventDefault();
            jQuery(this).closest('.subform-field').remove();         
        });

    });
</script>
