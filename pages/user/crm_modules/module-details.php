<?php
/*
    Created By : Mahidul Islam Tamim
*/
    
    global $wpdb;
    $fusuccrss = false;
    $fuerror = false;
    $errorm = false;
    $recordDtl = false;
    $attchment = false;
    $actionAdd = false;
    $actionView = false;
    $actionEdit = false;
    $actionDelete = false;
    $actionAttcAdd = false;
    $actionAttcDelete = false;

    $module = ucfirst($_GET['module']) ;
    $crmId  = $_GET['id'];
    $page   = $_GET['ppage'];
    $rl     = 'zcrm_';

    if(isset($_POST["deleteAttachment"])){

        $module = $_POST['modulename'];
        $crmId = $_POST['crmId'];
        $attachment_id = $_POST['attId'];
            
        $dltAtt = $CPNzoho->deleteFileN($module, $crmId, $attachment_id, 'user', 'yes');

        if(isset($dltAtt->data[0]->code) && $dltAtt->data[0]->code == "SUCCESS"){
            $fusuccrss = "SUCCESS";
            $fusuccrssM = "File delete successfully";
            
        }else {
            $fuerror = true;
            $fuerrorM = "File does not delete. Please try again leater";
        }
    }

    $layViewV = array();
    $crmMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'layout_".$rl.$module."'");
    if(isset($crmMdl->option_index))$layViewV = json_decode($crmMdl->option_value, true); 
    if(isset($layViewV['fieldName'])) $allLayout = true;
    else $layViewV = array();

    $exFldHstry = array();
    if(!isset($allLayout)){
        $exView = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'lay_View_".$rl.$module."'");
        if(isset($exView->option_index)){
            $ExlayView = json_decode($exView->option_value, true);
            if(isset($ExlayView['fieldName'])){
                foreach ($ExlayView['fieldName'] as $key => $fname) {
                    $exFldHstry[$fname]['view'] = "yes";
                }
                $layViewV = $ExlayView;        
            }

        }
    } 


    if(isset($layViewV['rowAdded'])){
		$moduleData = $CPNzoho->getRecordsByIdN($crmId, $module, 'user', 'yes');
		if(isset($moduleData['data'][0])){
			$recordDtl = $moduleData['data'][0];
            $attchmentL = $CPNzoho->getFilesN($module, $crmId, 'user', 'yes');
            if(isset($attchmentL->data))$attchment = $attchmentL->data;
		}elseif(isset($moduleData['status']) && ($moduleData['status'] == 'failed')){
          $errorm = "Your API Limit exceed. Please contact your administrator.";
        }
    }


    // Get User Permissions
    $crmMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'acp_".$rl.$module."'");
    $acpMdlV = array();
    if($crmMdl->option_index == 'acp_'.$rl.$module)$acpMdlV = json_decode($crmMdl->option_value, true);

    foreach ($acpMdlV as $actionKey => $action) {
      if($actionKey == "Add")$actionAdd = true;
      if($actionKey == "View")$actionView = true;
      if($actionKey == "Edit")$actionEdit = true;
      if($actionKey == "Delete")$actionDelete = true;

      if($actionKey == "AttcAdd")$actionAttcAdd = true;
      if($actionKey == "AttcDelete")$actionAttcDelete = true;
    }
    
    $fieldList = $CrmModules->getModuleFieldList($module);


    $uPLvlV = "ReadWrite";
    $usrPLvl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'userdata-pl' AND option_index =  '$crmId'");
    if(isset($usrPLvl->option_value)){
      $uPLvlV = $usrPLvl->option_value;
    }
?>

<style type="text/css">
    .showmore, .hidemore{cursor: pointer;border: solid 1px #ccc;padding: 3px 5px;border-radius: 5px;}
</style>

<div id="portal-cotenier">
    <div class="col-md-12">
		<div class="<?php echo $module;?>-page page-body" style="padding: 20px 10px;">

            <?php if($fusuccrss == "SUCCESS"){ ?>
                <div class="alert alert-dismissible alert-success">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Well done!</strong> <?php echo esc_html($fusuccrssM); ?>.
                </div>
            <?php } ?>

            <?php if($fuerror){ ?>
                <div class="alert alert-dismissible alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <strong>Oh snap!</strong> <?php echo esc_html($fuerrorM); ?> !!
                </div>
            <?php } ?>
            <?php if($errorm){ ?>
              <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo esc_html($errorm);?>
              </div>
            <?php }else{ ?> 


                <?php if(isset($layViewV['rowAdded'], $layViewV['layoutFldColumn'])){ ?>

                    <?php if(isset($recordDtl['id'])){ ?>

                        <div class="portal-header section-title" >

                            <div class="row">
                                
                                <div class="col-md-6">
                                    <h4>
                                        <?php echo ($layViewV['sectiontitle'][0]) ? $layViewV['sectiontitle'][0] :  str_replace("_", " ", $module)."&nbsp; Details";?>                            
                                    </h4>
                                </div>

                                 <div class="col-md-6">
                                    
                                    <div class="portal-header-left" style="float: right;">
                                        <?php if(($actionEdit) && ($uPLvlV == "ReadWrite")){ ?>
                                          <a href="<?php echo esc_url($cppageUrl).'?ppage=module-edit&module='. esc_attr($module).'&id='.  esc_attr($recordDtl['id']).'&origin=CRM'. esc_attr($page_id); ?>" class="editButton" style="padding-left: 20px;" ><img src="<?php echo  esc_attr($pluginUrl) ?>assets/images/nicon/portal-frontend-icons-17.png" style="width: 25px;float: right;"></a>
                                          <div class="clr"></div>
                                        <?php } ?>
                                    </div>
                                </div>


                            </div>
                          

                            <div class="clr"></div>

                        </div>
                        <div class="clr"></div>
                    
                        <div class="newtabSection"> 
                        <?php 
                        $fldP = 0;
                        $rowN = 0;
                        $colN = 0;
                        $ttlcol = 0;
                        foreach ($layViewV['layoutFldColumn'] as $rowP => $col) {

                            $ttlcol = $ttlcol + (int)$col;
                            $colN++;

                            $exfldN = $layViewV['fieldName'][$rowP]; 
                            $fieldnameArr = explode("___", $exfldN);
                            if(isset($fieldnameArr[1])){
                                $fieldApiName = $fieldnameArr[0];
                                $fieldDValue = $fieldnameArr[1];
                            }else{
                                $fieldApiName = $exfldN;
                                $fieldDValue = $exfldN;                        
                            }

                            $fldView="";                            
                            if(isset($layViewV['fldView']))$fldView = $layViewV['fldView'][$fldP];
                            if(isset($exFldHstry[$exfldN]['view'])) $fldView = 'yes';
                            $fldP++;

                            if($fldView == "yes"){

                                $type = $fieldList[$fieldApiName]['data_type']; 
                                $field_label = $fieldList[$fieldApiName]['field_label'];
                                
                                if(isset($recordDtl[$fieldApiName]))$fValue = $recordDtl[$fieldApiName]; else $fValue = "";
                                $picklist = "";
                                if(($type == 'subform') && (is_array($fValue))){
                                    $subformData = $fValue;
                                    $fValue = "";
                                }elseif(($type == 'date') && (strtotime($fValue))){
                                    $fValue = date("$date_format", strtotime($fValue));
                                }elseif(($type == 'datetime') && (strtotime($fValue))){
                                    $fValue = date("$date_format $time_format", strtotime($fValue));
                                }
                                else if($type == 'picklist'){

                                    if((count($fieldList[$fieldApiName]['pick_list_values']) > 0)){
                                        $picklist = $fieldList[$fieldApiName]['pick_list_values'];
                                        $fValue = $CrmModules->getPicListDisplayValue($picklist, $fValue);
                                    }else $fValue = "";

                                }else if(($type == 'multiselectpicklist') && is_array($fValue)){
                                    $fdValue = "";                                    
                                    if((count($fieldList[$fieldApiName]['pick_list_values']) > 0)){
                                        $picklist = $fieldList[$fieldApiName]['pick_list_values'];
                                        foreach ($fValue as $msplv) {
                                            $fdValue .=  $CrmModules->getPicListDisplayValue($picklist, $msplv).";";
                                        }
                                    }
                                    $fValue = $fdValue;

                                }else if(($type == 'lookup') && (is_array($fValue)) && (isset($fValue['name']))){
                                    $fValue = $fValue['name'];
                                }

                                if(is_array($fValue)) {
                                    $fValue = json_encode($fValue);
                                }
                                 
                                ?>        

                                <div class="col-md-<?php echo $col; ?> detail-con">
                                    <label class="fieldLabel"><?php echo $field_label; ?> : </label>
                                    <div class="fieldData">
                                        <?php if($type == "website"){ ?>
                                            <a target="_blank" href="<?php echo $fValue;?>"><?php echo  esc_html($fValue);?></a>
                                        <?php }else if($type == "boolean"){ ?>
                                            <input class="click-dis" type="checkbox" <?php echo ($fValue == 1) ? "checked" : "";?>>
                                        <?php }else{ ?>

                                            <?php if (strlen($fValue) < 50) { ?>
                                                <span><?php echo esc_html($fValue);?></span>
                                            <?php }else{ ?>
                                                <span class="sortdetails"><?php echo esc_html(substr($fValue, 0, 47));?> ... <span class="showmore">more</span></span>
                                                <span class="fulldetails" style="display: none;"><?php echo esc_html($fValue);?> <span class="hidemore">less</span></span>
                                                
                                            <?php } ?>

                                        <?php } ?>
                                        <div class="clr"></div>
                                    </div>
                                    <div class="clr"></div>
                                </div>


                                <?php 
                                if(isset($layViewV['subformfieldName_'.$fieldApiName])) $isSubfrom = true;
                                else $isSubfrom = false;

                                if($isSubfrom){
                                    $sfclumn = $layViewV['subformfieldName_'.$fieldApiName];

                                    if(isset($subformData[0]) && (is_array($subformData[0]))){
                                        ?>
                                        <div class="col-md-12 detail-con">
                                        <table class="table table-hover col-md-12 subform-record-list mr-t-0">
                                            <thead class="headtable">
                                                <tr>
                                                <?php 
                                                foreach ($sfclumn as $sfexf) {
                                                    if($sfexf !=""){
                                                        $fieldDValueArr = explode("___", $sfexf);
                                                        if(isset($fieldDValueArr[1])){
                                                            $sfexfDValue = $fieldDValueArr[1];
                                                        }else{
                                                            $sfexfDValue = $exfldN;                        
                                                        }
                                                        ?>
                                                        <th><?php echo esc_html($sfexfDValue); ?></th>
                                                    <?php }
                                                } ?>
                                                
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($subformData as $sfdtl) { ?>

                                                    <tr>
                                                        <?php 
                                                            foreach ($sfclumn as $sfexf) {

                                                                if($sfexf !=""){ ?>

                                                                    <?php $fieldDValueArr = explode("___", $sfexf);
                                                                    if(isset($fieldDValueArr[1])){
                                                                        $sfexfApiName = $fieldDValueArr[0];
                                                                    }else{
                                                                        $sfexfApiName = $exfldN;                     
                                                                    }
                                                                    $sfvalue = $sfdtl[$sfexfApiName];
                                                                    if(is_array($sfvalue) && (isset($sfvalue['name']))) $sfvalue = $sfvalue['name'];
                                                                    else if(is_array($sfvalue)) $sfvalue = "";
                                                                    ?>
                                                                    <td><?php echo esc_html($sfvalue); ?></td>
                                                                <?php
                                                                } 
                                                            } ?>
                                                        </tr>
                                                    <?php } ?>
                                            </tbody>
                                        </table>
                                        </div>
                                    <?php }
                                } ?>


                                <?php if($ttlcol == 12) {
                                    $ttlcol = 0;
                                 ?>
                                    <div class="clr"></div>
                                <?php }  ?>
                                
                                <?php if($colN == $layViewV['columnAdded'][$rowN]) {
                                    $colN = 0; $rowN = $rowN + 1; ?>
                                    <div class="clr"></div>
                                    </div> <!-- newtabSection -->
                                    <div style="clear: both;height: 20px;"></div>
                                    <?php if(isset($layViewV['sectiontitle'][$rowN])){ ?>
                                        <h4 class="section-title"><?php echo esc_html($layViewV['sectiontitle'][$rowN]);?></h4>
                                    <?php }  ?>
                                    <?php if(isset($layViewV['sectiontitle'][$rowN])){ ?>
                                        <div class="newtabSection">
                                    <?php }  ?>
                                <?php }  ?>

                            <?php }  ?>

                        <?php } ?>
                    
                        <div class="clr" style="height: 20px;"></div>
                        <h4 class="section-title">Attachment</h4>

                        <table class="table table-hover attachment-list mr-t-0">
                            <thead class="headtable">
                                <tr>
                                    <th>File Name</th>
                                    <th>Date Added</th>
                                    <th>Size</th>
                                    <th style="width: 260px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($attchment){
                                    foreach ($attchment as $key => $attDtl) {
                                        $link_url = '$link_url';
                                        $type = '$type';
                                        ?>
                                        <tr>
                                            <td><?php echo esc_html($attDtl->File_Name); ?></td>
                                            <td><?php echo esc_html(date("F j, Y", strtotime($attDtl->Created_Time))); ?></td>
                                            <td><?php echo esc_html(round($attDtl->Size / 1024, 2)); ?> KB</td>
                                            <td>
                                                <?php if($attDtl->$type == 'Attachment'){ ?>
                                                    <a href="<?php echo esc_url($cppageUrl)."?ppage=viewatt&module=".esc_attr($module)."&id=".esc_attr($crmId)."&attid=".esc_attr($attDtl->id)."&fn=".esc_attr($attDtl->File_Name.$page_id);?>" target="_blank" class="btn btn-primary viewButton">View</a>
                                                    <a href="<?php echo esc_url($cppageUrl)."?ppage=downloadatt&module=".esc_attr($module)."&id=".esc_attr($crmId)."&attid=".esc_attr($attDtl->id)."&fn=".esc_attr($attDtl->File_Name.$page_id);?>" target="_blank" class="btn btn-primary viewButton">Download</a>

                                                <?php }else if($attDtl->$type == 'Link URL'){ ?>
                                                    <a href="<?php echo esc_url($attDtl->$link_url) ?>" target="_blank" class="btn btn-primary viewButton">Go to Link</a>
                                                <?php } ?>
                                                    
                                                <?php if(($actionAttcDelete == true) && ($uPLvlV == "ReadWrite")){ ?>
                                                    <button class="btn btn-primary deleteButton deleteAttachment">Delete</button>
                                                    <form action="" method="post" class="deleteattForm">
                                                        <input type="hidden" name="modulename" value="<?php echo esc_attr($module); ?>">
                                                        <input type="hidden" name="crmId" value="<?php echo esc_attr($crmId); ?>">
                                                        <input type="hidden" name="attId" value="<?php echo esc_attr($attDtl->id); ?>">
                                                        <input type="hidden" name="deleteAttachment">
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>

                                    <tr>
                                        <td colspan="4"><p style="text-align: center;margin: 0px;">No records found</p></td>
                                    </tr>                    
                                <?php } ?>
                            </tbody>
                        </table>
                        
                    <?php } else { ?>
        				<p style="text-align: center;margin: 0px;">No records found</p></td>
                    <?php }
                } else { ?>
                    <p style="text-align: center;margin: 0px;">Customization for <?php echo esc_attr(str_replace("_", " ", $module));?> is not complete. Please Contact your administrator. </p></td> 
                <?php } ?>

            <?php } ?>
		    </div>  
        </div>  
	</div>  

</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(".deleteAttachment").click(function(event) {
            jQuery(this).closest('td').find('form.deleteattForm').submit();
        });
    });

    jQuery(document).ready(function() {
        jQuery(".showmore").click(function(event) {
            jQuery(this).closest('div.detail-con').find('.fulldetails').show();
            jQuery(this).closest('div.detail-con').find('.sortdetails').hide();
        });
        jQuery(".hidemore").click(function(event) {
            jQuery(this).closest('div.detail-con').find('.fulldetails').hide();
            jQuery(this).closest('div.detail-con').find('.sortdetails').show();
        });
    });
</script>