<?php
/*
    Created By : Mahidul Islam Tamim
*/
  global $wpdb;

  $moduleList = false;
  $ltcMdlV = array();
  $acpMdlV = array();
  $succrss = false;
  $errorm = false;

  $actionAdd = false;
  $actionView = false;
  $actionEdit = false;
  $actionDelete = false;
  $totalModuleRecord = 0;

  $module = ucfirst($_GET['module']);
  $page = $_GET['ppage'];
  $pageindex = (isset($_GET['pageindex'])) ? $_GET['pageindex'] : 1;
  $rl = 'zcrm_';

  

  if( !isset($useMdlV['zcrm_'.$module]) && !isset($useMdlV['zcrm_'.$_GET['module']]) ){
    header("Location: ".$cppageUrl);
  }

  if( isset($useMdlV['zcrm_'.$_GET['module']]) ){
    $module = $_GET['module'];
  }

  $mdlType = $useMdlV['zcrm_'.$module];
  $mdlType = str_replace("on_", '', str_replace("_".$module, '', $mdlType));


  $conId = $_SESSION["ccgcp_loged"]['id'];

	// Delete Module From CRM
  if(isset($_POST['delRecCrmId'])){
      if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'delRecCrmId')){
        $update = $CPNzoho->deleteRecordsN($_POST['delRecCrmId'], $module, 'user', 'yes'); 
        
        if(isset($update->data[0]->code) && $update->data[0]->code == "SUCCESS"){
            $succrss = "SUCCESS";
        }else{
          $errorm = "Your API Limit exceed. Please contact your administrator.";
        }
    }else{
      $errorm = "Something went wrong.";
    }
  }

  // Get Module List From CRM
	$accId = $_SESSION["ccgcp_loged"]['Account_Name']['id'];
  $accName = $_SESSION["ccgcp_loged"]['Account_Name']['name'];


  $spMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'sp_zcrm_$module'");        
  if(isset($spMdl->option_value)) $spMdlV = json_decode($spMdl->option_value, true); else $spMdlV = array();


  if(isset($spMdlV['modulerecords_rltdto'])){
    if($spMdlV['modulerecords_rltdto'] == "Custom-Sharing"){
      $customviewR = true;
      $cvidArr = explode("___", $spMdlV['custom_view']);

      $moduleData = $CPNzoho->getRecordsByViewId($module, $cvidArr[0], $pageindex, 'user', 'yes');
    }
  }
  if(!isset($customviewR)){

    // Get Child Account
    $spAcc = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'sp_zcrm_Accounts'");        
    if(isset($spAcc->option_value)) $spAccV = json_decode($spAcc->option_value, true); else $spAccV = array();

    if(isset($spAccV['parentaccountField'])){
      $paccfld = $spAccV['parentaccountField'];
      $paccfldArr = explode("___", $paccfld);
      $accDtl = $_SESSION["ccgcp_loged"]['accDtl'];

      if((isset($paccfldArr[0])) && (!isset($accDtl[$paccfldArr[0]]['id']))){
        $criteriacacc = "(".$paccfldArr[0].":equals:".$accName.")";
        $zohores = $CPNzoho->getSearchRecordsN("Accounts", $criteriacacc, 'user', 'yes');
        if(isset($zohores['data'][0]['id'])) $childAcc = $zohores['data'];  
      }

    }

    // Get records from CRM
    $moduleDataArr = array();      
    if(isset($childAcc) && count($childAcc) > 0){
      foreach ($childAcc as $childAccDtl) {
        $caccName = $childAccDtl['Account_Name'];
        $caccId = $childAccDtl['id'];
        if(($caccId != $accId)){

          if((isset($spMdlV['accountField'])) && ($spMdlV['accountField'] !="")){
            $accountField = $spMdlV['accountField'];
            $fieldAcArr = explode("___", $accountField);
            if(isset($fieldAcArr[0])) $fieldAcApiN = $fieldAcArr[0];
            else $fieldAcApiN = "Account";                        
            
            $criteria = "(".$fieldAcApiN.":equals:".$caccId.")";
          }else{
            $criteria = "(Account:equals:".$caccId.")";
          }

          if($mdlType == 'default') $moduleData = $CPNzoho->getRelatedRecordsN($module ,$caccId, "Accounts", 'user', 'yes');
          else if($mdlType == 'custom') $moduleData = $CPNzoho->getSearchRecordsN($module, $criteria, 'user', 'yes');
          if(isset($moduleData['data']) && count($moduleData['data']) > 0){
            $moduleDataArr = array_merge($moduleDataArr, $moduleData['data']);                         
          }
        }
        
      }

    }

    $moduleData = array();
    if(isset($spMdlV['accountField']) && ($spMdlV['accountField'] !="")){
      $accountField = $spMdlV['accountField'];
      $fieldAcArr = explode("___", $accountField);
      if(isset($fieldAcArr[0])) $fieldAcApiN = $fieldAcArr[0];
      else $fieldAcApiN = "Account";                        
      
      $criteria = "(".$fieldAcApiN.":equals:".$accId.")";
    }else{
      $criteria = "(Account:equals:".$accId.")";
    }

    if($mdlType == 'default') $moduleData = $CPNzoho->getRelatedRecordsN($module ,$accId, "Accounts", 'user', 'yes');
    else if($mdlType == 'custom') $moduleData = $CPNzoho->getSearchRecordsN($module, $criteria, 'user', 'yes');
         
  }

  if(isset($moduleData['data'][0]['id'])){
    $moduleDataArr = array_merge($moduleDataArr, $moduleData['data']);
    $totalModuleRecord = $moduleData['count'];

  }elseif(isset($moduleData['status']) && ($moduleData['status'] == 'failed')){
    $errorm = "Your API Limit exceed. Please contact your administrator.";
  }
  $moduleList = $moduleDataArr;
	
	// Get User Permissions
  $crmMdl = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE '%".$rl.$module."'");
  
  if(count($crmMdl) > 0){
      foreach ($crmMdl as $crmMdlV) {
          if($crmMdlV->option_index == 'ltc_'.$rl.$module)$ltcMdlV = json_decode($crmMdlV->option_value, true);
          else if($crmMdlV->option_index == 'acp_'.$rl.$module)$acpMdlV = json_decode($crmMdlV->option_value, true);
          else if($crmMdlV->option_index == 'mtd_'.$rl.$module)$mtdV = json_decode($crmMdlV->option_value, true);
      }
  }

  if($module == 'Deals'){
    $dealStages = "All";

    $crmAddS = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index = 'crmAddSettings'");    
    if(isset($crmAddS->option_value)){
        $crmAddSV = json_decode($crmAddS->option_value, true);
        $dealStages = $crmAddSV['dealStages'];
    }
    else{ 
        $dealSRes = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'general-settings' AND option_index = 'dealStages'");
        if(isset($dealSRes->option_value)){
            $dealStages = $dealSRes->option_value;
        }
    }

  }

  foreach ($acpMdlV as $actionKey => $action) {
    if($actionKey == "Add")$actionAdd = true;
    if($actionKey == "View")$actionView = true;
    if($actionKey == "Edit")$actionEdit = true;
    if($actionKey == "Delete")$actionDelete = true;
  }

  
  $fieldList = $CrmModules->getModuleFieldList($module);
  
  $uPLvlV = "ReadWrite";
  $usrPLvl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'userdata-pl' AND option_index =  '$conId'");
  if(isset($usrPLvl->option_value)){
      $uPLvlV = $usrPLvl->option_value;
  }

?>

<style type="text/css">
 

   .addButton{
    float: right;
    padding: 0px 15px;
    margin-top: -3px;
   }




</style>

<div id="portal-cotenier">
  <?php if((isset($mtdV['Title']) && ($mtdV['TitleContent'] !="")) || (isset($mtdV['Description']) && ($mtdV['DescriptionContent'] !=""))){ ?>
    <div class="col-md-12">
  		<div class="page-body">
        <?php if(isset($mtdV['Title'])){ ?> <h4 style="margin-top: 0px;"><?php echo esc_attr(stripslashes($mtdV['TitleContent']));?></h4><?php } ?>
        <div class="clr"></div>
        <?php if(isset($mtdV['Description'])){ ?> <p style="margin: 0px;"><?php echo esc_attr(stripslashes($mtdV['DescriptionContent']));?></p><?php } ?>
      </div>
    </div>
    <div class="clr" style="height: 30px;"></div>
  <?php } ?>

    <div class="col-md-12">
    <div class="<?php echo esc_attr(strtolower($module));?>-page page-body">
      
      <?php if( isset($_GET['msg']) && ($_GET['msg'] == "SUCCESS")){ ?>
          <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Well done!</strong> Added successfully </a>.
          </div>
      <?php } ?>

      <?php if($succrss == "SUCCESS"){ ?>
          <div class="alert alert-dismissible alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Well done!</strong> Contact Delete Successfully </a>.
          </div>
      <?php } ?> 

      <?php if($errorm){ ?>
          <div class="alert alert-dismissible alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $errorm;?>
          </div>
      <?php }else{ ?>   
            
        <?php if(isset($ltcMdlV['column'])){ ?>

          <div class="deleteRecordsCon" style="display: none;">
            <label style="float: left;margin-right: 15px;color: red;">Are you sure to delete this <?php echo esc_attr(str_replace("_", " ", $module));?> ?</label> 
            <form action="" method="post">
 
              <?php 
                  wp_nonce_field('delRecCrmId','csrf_token_nonce');
              ?>             
              <input type="hidden" name="module"  value="<?php echo esc_attr($module);?>">
              <input type="hidden" name="delRecCrmId" class="delRecCrmId" required>
              <button type="submit" class="btn btn-primary" style="color: #fff;float: left;padding: 2px 12px;margin-right: 15px;">Yes</button>
              <button type="button" class="btn btn-primary deleteCancel" style="color: #fff;float: left;padding: 2px 12px;">No</button>
            </form>
            <div class="clr"></div>
          </div>

          <div class="clr"></div>

          <div class="portal-header section-title">
            <?php if (strpos($mdlType, 'custom_') === 0) { ?>
            <?php echo esc_attr(str_replace( "_", " ", str_replace("custom_", "", $mdlType)) );?> List
            <?php }else{ ?>
            <h4><?php echo esc_attr(str_replace("_", " ", $module));?> List</h4>
            <?php } ?>

            <div class="portal-header-left">
                <?php if(($actionAdd == true) && ($uPLvlV == "ReadWrite")){ ?>
                  <a class="addButton" style="" href="<?php echo esc_url($cppageUrl).'?ppage=module-add&module='. esc_attr($module).'&origin=CRM'.esc_attr($page_id); ?>">
                    <i class="fa fa-plus-circle" style="color: #287cdb;"></i> <?php //echo str_replace("_", " ", $module);?>              
                  </a>
                <?php } ?>
            </div>
            <div class="clr"></div>

          </div>
          <div class="clr"></div>

          <?php if(count($ltcMdlV['column']) < 4) $tableW = "min-width:auto;"; else $tableW = ""; ?>
          <table class="datatable table table-hover module-list <?php echo strtolower($module);?>-list" style="<?php echo esc_attr($tableW); ?>">
    				<thead class="headtable">
    					<tr>
                <?php
                  $numColumn = 0; 
                  if(isset($ltcMdlV['column'])){
                  foreach ($ltcMdlV['column'] as $column) {

                      $fieldnameArr = explode("___", $column);
                      if(isset($fieldnameArr[1])){
                          $fieldDValue = $fieldnameArr[1];
                      }else{
                          $fieldDValue = $column;                        
                      }
                  $numColumn++; ?>

                    <th><?php echo esc_html(str_replace("_", " ", $fieldDValue));?></th>

                  <?php }
                } ?>
                <?php if(count($acpMdlV) > 0){ ?>
      						<th class="action">Action</th>
                <?php } ?>
    					</tr>
    				</thead>
    				<tbody>

    					<?php 
              $i = 0;
              if($moduleList){
    						foreach ($moduleList as $key => $modDtl) {

                  if (($module !="Deals") || ($dealStages == "All" || strpos($dealStages, $modDtl['Stage']) !== false)){              
                    $i++; 
                    
                    ?>
      							<tr>
                      <?php if(isset($ltcMdlV['column'])){
                        foreach ($ltcMdlV['column'] as $column) {

                          $fieldnameArr = explode("___", $column);
                          if(isset($fieldnameArr[1])){
                              $fieldApiName = $fieldnameArr[0];
                          }else{
                              $fieldApiName = $column;                        
                          }
                          
                          $type = $fieldList[$fieldApiName]['data_type'];

                          if(isset($modDtl[$fieldApiName]))$fValue = $modDtl[$fieldApiName]; else $fValue ="";
                          if(($type == 'subform') && (is_array($fValue))){
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
                              $fValue = implode(';', $fValue);
                          }else if(($type == 'lookup') && (is_array($fValue)) && (isset($fValue['name']))){
                              $fValue = $fValue['name'];
                          }
                          if(is_array($fValue)) $fValue = "";
                          
                          ?>
            				    	<td>
                              <?php if($actionView == true){ ?>
                                <a href="<?php echo esc_url($cppageUrl).'?ppage=module-details&module='.esc_attr($module).'&id='.esc_attr($modDtl['id']).'&origin=CRM'.esc_attr($page_id); ?>">
                                  <?php echo esc_html($fValue); ?>
                                </a>
                              <?php }else { echo esc_html($fValue); }?> 
                           </td>
                        <?php }
                      } ?>

                        <?php if(count($acpMdlV) > 0){ ?>

        				    			<td>
                              <?php if($actionView == true){ ?>
          				    					<a href="<?php echo esc_url($cppageUrl).'?ppage=module-details&module='.esc_attr($module).'&id='.esc_attr($modDtl['id']).'&origin=CRM'.esc_attr($page_id); ?>" class="viewButton"><img src="<?php echo CCGP_PLUGIN_URL; ?>assets/images/nicon/icons_activities copy 10.png" class="iconisImg"></a>
          					    			<?php } ?>                   
                              <?php if(($actionEdit == true)  && ($uPLvlV == "ReadWrite")){ ?>
          					    				<a href="<?php echo esc_url($cppageUrl).'?ppage=module-edit&module='. esc_attr($module).'&id='. esc_attr($modDtl['id']).'&origin=CRM'.esc_attr($page_id); ?>" class="editButton"><img src="<?php echo CCGP_PLUGIN_URL; ?>assets/images/nicon/icons_activities copy 9.png" class="iconisImg"></a>
          					    			<?php } ?>               
                              <?php if(($actionDelete  == true)  && ($uPLvlV == "ReadWrite") && ($modDtl['id'] != $conId)){ ?>
          					    				<span class="deleteButton dltModuleModal" data-crmid="<?php echo esc_attr($modDtl['id']); ?>" style="cursor: pointer;"><img src="<?php echo esc_url(CCGP_PLUGIN_URL); ?>assets/images/nicon/delicon.png" class="iconisImg"></button>
          					    			<?php } ?>
        				    			</td>
                        <?php } ?>
      							</tr>
    						<?php }
                }
                
    					}
              if($i == 0){ ?>

    					<?php } ?>
    					
    				</tbody>
    			</table>
          <?php if($totalModuleRecord == 200){ ?>
            <a href="<?php echo esc_url($cppageUrl).'?ppage=list&module='.esc_attr($module).'&pageindex='.($pageindex + 1).'&origin=CRM'.esc_attr($page_id); ?>" class="f-r btn btn-primary editButton" style="margin-top: 20px;" >Next Page</a>
          <?php } ?>


          <?php } else { ?>
            <p style="text-align: center;margin: 0px;">Customization for <?php echo esc_attr(str_replace("_", " ", $module));?> is not complete. Please Contact your administrator. </p></td>
          <?php } ?>


          <script type="text/javascript">
              jQuery(document).on('click', '.dltModuleModal', function(event) {
                event.preventDefault();      
                var crmId = jQuery(this).data('crmid');
                  jQuery(".deleteRecordsCon").show();
                  jQuery(".deleteRecordsCon input.delRecCrmId").val(crmId);
              });
              jQuery(document).on('click', '.deleteCancel', function(event) {
                event.preventDefault();      
                  jQuery(".deleteRecordsCon").hide();
                  jQuery(".deleteRecordsCon input.delRecCrmId").val("");
              });  
          </script>

        <?php } ?>

      <div class="clr"></div>

		</div>  
	</div>  

</div>

