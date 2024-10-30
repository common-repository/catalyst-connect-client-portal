<?php
	include_once 'common/left-menu.php';

	$module = "Accounts" ;
	$contacts = false;
	$recordDtl = false;
	$error = false;
	$errorm = false;
	$succrss = false;
	$activeTab = "Account-Information";

	$CPNzoho = new ZohoCrmRequest();
	$CrmModules = new CrmModules();

	$pact = (isset($_GET["action"])) ? $_GET["action"] : "accinfo";
	$conInfo = $_SESSION["ccgcp_loged"];
	$accId = $_SESSION["ccgcp_loged"]['Account_Name']['id'];
	$conId = $_SESSION["ccgcp_loged"]['id'];

	if(isset($_POST['changePassword'])){
		$activeTab = "Change-Password";
		if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'changePassword')){
			if($_POST['New_Password'] == $_POST['Confirm_Password']){
				$pass = sanitize_text_field($_POST['Old_Password']);
				$newpass = sanitize_text_field($_POST['New_Password']);


				$userDtl = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE crmid = '$conId' AND password = '$pass'");
				if(isset($userDtl->crmid)){
					$wpdb->update( "ccgclientportal_users",  array( 'password' => $newpass), array('id'=>$userDtl->id));

					$email = $_SESSION["ccgcp_loged"]['Email'];
					$exists = email_exists($email);
					if ( $exists ){
						$user_id = (int) $exists; // correct ID
						$res = wp_update_user( array( 'ID' => $user_id, 'user_pass' => $newpass ) );
					}
					$succrss = true;
					$succrssMessage ="Password updateed successfully";
				}else{
					$error = true;
					$errorMessage = "Old Password is wrong !";
				}

			}else{
				$error = true;
				$errorMessage = "New Password and Confirm Password are not same !";
			}
		}else{
			$error = true;
			$errorMessage = "Something went wrong";		
		}
	}

	if(isset($_POST["deleteAttachment"])){

		$module = $_POST['modulename'];
		$crmId = $_POST['crmId'];
		$attachment_id = $_POST['attId'];
			
		$dltAtt = $CPNzoho->deleteFileN($module, $crmId, $attachment_id, 'user', 'yes');

		if(isset($dltAtt->data[0]->code) && $dltAtt->data[0]->code == "SUCCESS"){
			$fusuccrss = "SUCCESS";
			$succrssMessage = "File delete successfully";
			
		}else {
			$fuerror = true;
			$errorMessage = "File does not delete. Please try again leater";
		}
	}

	$crmMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'layout_zcrm_Accounts'");
	if(isset($crmMdl->option_index))$layViewV = json_decode($crmMdl->option_value, true); 

    // Get User Permissions
    $crmMdl = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE '%zcrm_Accounts'");
    if(count($crmMdl) > 0){
      foreach ($crmMdl as $crmMdlV) {
          if($crmMdlV->option_index == 'layout_zcrm_Accounts')$layViewV = json_decode($crmMdlV->option_value, true);
          else if($crmMdlV->option_index == 'acp_zcrm_Accounts')$apMdlV = json_decode($crmMdlV->option_value, true);
          else if($crmMdlV->option_index == 'mtd_zcrm_Accounts')$mtdV = json_decode($crmMdlV->option_value, true);
      }
    }
	

	if(isset($layViewV['rowAdded'])){

		$acc = $CPNzoho->getRecordsByIdN($accId, 'Accounts','user','yes');
		// echo "<pre>";var_dump($acc);echo "</pre>";
		
		if(isset($acc['data'][0]['id'])){
			$recordDtl = $acc['data'][0];

			$attchmentL = $CPNzoho->getFilesN($module, $accId, 'user', 'yes');
			
			if(isset($attchmentL->data))$attchment = $attchmentL->data;

		}elseif(isset($acc['status']) && ($acc['status'] == 'failed')){
			$errorm = "Your API Limit exceed. Please contact your administrator.";
		}
		
	}
	
	$fieldList = $CrmModules->getModuleFieldList($module);


	$uPLvlV = "ReadWrite";
	$usrPLvl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'userdata-pl' AND option_index =  '$conId'");
	if(isset($usrPLvl->option_value)){
		$uPLvlV = $usrPLvl->option_value;
	}
?>

<style type="text/css">
	#ccgclient-portal #portal-cotenier, #ccgclient-portal p, #ccgclient-portal strong, #ccgclient-portal b{
		color: #000000;
	}
	.showmore, .hidemore{cursor: pointer;border: solid 1px #ccc;padding: 3px 5px;border-radius: 5px;}

	.ccgclientportal-content .ccg-nav-tabs > li.active > a {
		color: #555;
		cursor: default;
		background-color: rgb(255, 255, 255);
		border: none;
		border-bottom: 6px solid rgb(40, 113, 209);
		pointer-events: none;
		color: rgb(40, 113, 209);
		font-size: 16px;
		margin-right: 15px;
		padding: 10px 0px;
	}

	.nav-link{
        color: rgb(102, 102, 102);
    }

    .ccgclientportal-content .ccg-nav-tabs>li>a:focus, .ccgclientportal-content .ccg-nav-tabs>li>a:hover {
        text-decoration: none;
         color: rgb(40, 113, 209);
        background-color: #fff;
    }
    .fieldData{
        color: rgb(102, 102, 102);
        font-size: 14px !important;
    }
    .ccg-nav-tabs {
	    border-bottom: none;
	}
	.ccgclientportal-content ul.ccg-nav-tabs>li {
	    margin-left: 0px;
	}
</style>

<div id="portal-cotenier">

    <?php if((isset($mtdV['Title']) && ($mtdV['TitleContent'] !="")) || (isset($mtdV['Description']) && ($mtdV['DescriptionContent'] !=""))){ ?>
	    <div class="col-md-12">
	        <div class="page-body">
	        <?php if(isset($mtdV['Title'])){ ?> <h4 style="margin-top: 0px;"><?php echo stripslashes($mtdV['TitleContent']);?></h4><?php } ?>
	        <div class="clr"></div>
	        <?php if(isset($mtdV['Description'])){ ?> <p style="margin: 0px;"><?php echo stripslashes($mtdV['DescriptionContent']);?></p><?php } ?>
	      </div>
	    </div>
	    <div class="clr" style="height: 30px;"></div>
	<?php } ?>

	<div class="col-md-12">
		<div class="profile-page page-body">


		<?php if($errorm){ ?>
			<div class="alert alert-dismissible alert-danger">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<?php echo $errorm;?>
			</div>
		<?php }else{ ?>

			<ul class="ccg-nav-tabs" role="tablist">
				<li class="nav-item <?php echo ($pact == "accinfo") ? "active" : ""; ?> " >
				  <a class="nav-link" href="<?php echo esc_url($cppageUrl); ?>?ppage=profile&action=accinfo<?php echo esc_attr($page_id); ?>">Account Information</a>
				</li>
				<li class="nav-item <?php echo ($pact == "cngpass") ? "active" : ""; ?>">
				  <a class="nav-link"  href="<?php echo esc_url($cppageUrl); ?>?ppage=profile&action=cngpass<?php echo esc_attr($page_id); ?>">Change Password</a>
				</li>
				<div class="clr"></div>
			</ul>   
			<div class="tab-content tab-content-solid" style="border: none;border-top: none;padding: 30px 10px;">

				<?php if($succrss == "SUCCESS"){ ?>
					<div class="alert alert-dismissible alert-success">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <strong>Well done!</strong> <?php echo $succrssMessage;?>
					</div>
				<?php } ?>

				<?php if($error){ ?>
					<div class="alert alert-dismissible alert-danger">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <strong>Oh snap!</strong> <?php echo $errorMessage; ?>
					</div>
				<?php } ?>

				<?php if ($pact == "accinfo") {?>
				<div class="tab-pane fade active in">
					<?php if(isset($layViewV['rowAdded'], $layViewV['layoutFldColumn'])){ ?>
						<?php if($recordDtl){ ?>
							<h4 class="section-title"><?php echo ($layViewV['sectiontitle'][0]) ? $layViewV['sectiontitle'][0] : "Account Information";?>
								<?php if(isset($apMdlV['Edit']) && ($uPLvlV == "ReadWrite")){ ?>
									<a class=" addButton" href="<?php echo esc_url($cppageUrl).'?ppage=profile-edit'.esc_attr($page_id); ?>" style="padding-left: 20px;margin-top: -4px;" ><img src="<?php echo $pluginUrl ?>assets/images/nicon/portal-frontend-icons-17.png" style="width: 25px;float: right;"></a>
								<?php } ?>
							</h4>

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
								if(isset($layViewV['fldView'][$fldP]))$fldView = $layViewV['fldView'][$fldP];
								if(isset($exFldHstry[$exfldN]['view'])) $fldView = 'yes';
								$fldP++;

								$type = $fieldList[$fieldApiName]['data_type']; 
								$field_label = $fieldList[$fieldApiName]['field_label'];

								if(isset($recordDtl[$fieldApiName]))$fValue = $recordDtl[$fieldApiName]; else $fValue = "";
								if(($type == 'subform') && (is_array($fValue))){
									$subformData = $fValue;
									$fValue = "";
								}elseif(($type == 'date') && (strtotime($fValue))){
									$fValue = date("F j, Y", strtotime($fValue));
								}elseif(($type == 'datetime') && (strtotime($fValue))){
									$fValue = date("F j, Y H:i:s", strtotime($fValue));
								}
								else if($type == 'picklist'){

									if((count($fieldList[$fieldApiName]['pick_list_values']) > 0)){
										$picklist = $fieldList[$fieldApiName]['pick_list_values'];
										$fValue = $CrmModules->getPicListDisplayValue($picklist, $fValue);
									}else $fValue = "";

								}else if(($type == 'multiselectpicklist') && is_array($fValue)){                                    
									// $fValue = implode('; ', $fValue);
									$fdValue = "";                                    
									if((count($fieldList[$fieldApiName]['pick_list_values']) > 0)){
										$picklist = $fieldList[$fieldApiName]['pick_list_values'];
										foreach ($fValue as $msplv) {
											$fdValue .=  $CrmModules->getPicListDisplayValue($picklist, $msplv)."; ";
										}
									}
									$fValue = $fdValue;
								}
								else if(($type == 'lookup') && (is_array($fValue)) && (isset($fValue['name']))){
									$fValue = $fValue['name'];
								}

								if(is_array($fValue)) {
									$fValue = json_encode($fValue);
								}

								$fValue = stripslashes($fValue);
								if($fldView == 'yes'){
								?>        

								<div class="col-md-<?php echo $col; ?> detail-con">
									<label class="fieldLabel"><?php echo $field_label ?> : </label>
									<div class="fieldData"> 
										<?php if($type == "website"){ ?>
											<a target="_blank" href="<?php echo esc_url($fValue);?>"><?php echo $fValue;?></a>
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
										<table class="table table-hover table-striped  subform-record-list mr-t-0">
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
											<tbody class="tbodyBorder">
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
																	else if(is_array($sfvalue)) $sfvalue = "";//json_encode($sfvalue);

																	$sfvalue = stripslashes($sfvalue);
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
									$colN = 0; $ttlcol = 0; $rowN = $rowN + 1; ?>
									<div class="clr"></div>
                                    </div> <!-- newtabSection -->
									<div style="clear: both;height: 20px;"></div>
									<?php if(isset($layViewV['sectiontitle'][$rowN])){ ?>
										<h4 class="section-title"><?php echo $layViewV['sectiontitle'][$rowN];?></h4>
									<?php }  ?>
									<?php if(isset($layViewV['sectiontitle'][$rowN])){ ?>
                                        <div class="newtabSection">
                                    <?php }  ?>
								<?php }  ?>

							<?php } ?>
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
							<tbody class="tbodyBorder">
								<?php if(isset($attchment)){
									foreach ($attchment as $key => $attDtl) {
										$link_url = '$link_url';
										$type = '$type';
										?>
										<tr>
											<td><?php echo $attDtl->File_Name; ?></td>
											<td><?php echo date("F j, Y", strtotime($attDtl->Created_Time)); ?></td>
											<td><?php echo round($attDtl->Size / 1024, 2); ?> KB</td>
											<td>
												<?php if($attDtl->$type == 'Attachment'){ ?>
													<a href="<?php echo esc_url($cppageUrl)."?ppage=viewatt&module=".esc_html($module)."&id=".$accId."&attid=".esc_html($attDtl->id)."&fn=".esc_html($attDtl->File_Name).esc_html($page_id);?>" target="_blank" class="btn btn-primary viewButton">View</a>
													<a href="<?php echo esc_url($cppageUrl)."?ppage=downloadatt&module=".esc_html($module)."&id=".$accId."&attid=".esc_html($attDtl->id)."&fn=".esc_html($attDtl->File_Name).esc_html($page_id);?>" target="_blank" class="btn btn-primary viewButton">Download</a>
												<?php }else if($attDtl->$type == 'Link URL'){ ?>
													<a href="<?php echo $attDtl->$link_url ?>" target="_blank" class="btn btn-primary viewButton">Go to Link</a>
												<?php } ?>

												<?php if(isset($apMdlV['AttcDelete']) && ($uPLvlV == "ReadWrite")){ ?>
													<button class="btn btn-primary deleteButton deleteAttachment">Delete</button>
													<form action="" method="post" class="deleteattForm">
														<input type="hidden" name="modulename" value="<?php echo esc_attr($module); ?>">
														<input type="hidden" name="crmId" value="<?php echo esc_attr($accId); ?>">
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
								<p style="text-align: center;margin: 0px;">Account not found</p></td>
							<?php } ?>

						<?php } else { ?>
							<p style="text-align: center;margin: 0px;">Customization is not complete. Please Contact your administrator.</p></td>
						<?php } ?>
					</div>
				</div>
				<?php } ?>

				<?php if ($pact == "cngpass") {?>
				<div class="tab-pane fade active in">
					
				  
					<form role="form" action="" method="post">   
						
						<?php 
							wp_nonce_field('changePassword','csrf_token_nonce');
						?>

						<div class="form-group brd-radius-with-shdw pd-b-10 col-md-6">
							<label class="col-md-12 control-label fieldLabel"> Old Password </label>
							<div class="col-md-12">
								<input type="password" name="Old_Password" class="form-control" required="">
							</div>
						</div>
						<div class="clr"></div>  
						
						<div class="form-group brd-radius-with-shdw pd-b-10 col-md-6">
							<label class="col-md-12 control-label fieldLabel"> New Password </label>
							<div class="col-md-12">
								<input type="password" name="New_Password" class="form-control" required="">
							</div>
						</div>
						<div class="clr"></div>  
						<div class="form-group brd-radius-with-shdw pd-b-10 col-md-6">
							<label class="col-md-12 control-label fieldLabel"> Confirm Password </label>
							<div class="col-md-12">
								<input type="password" name="Confirm_Password" class="form-control" required="">
							</div>
						</div>
						<div class="clr"></div>                        
					

						<div class="clr"></div>
						<div class="form-group col-md-6 pd-r-0">
							<input type="submit" class="btn btn-primary saveButton f-r" name="changePassword" value="Change Password">
							
							<div class="clr"></div>
						</div>
						<div class="clr"></div>

					</form>

				</div>
				<?php } ?>
			</div>

		<?php } ?>

		</div>  
	</div>  

</div>



<script type="text/javascript">
	jQuery(document).ready(function(){ 
		jQuery('#tab-<?php echo esc_js($activeTab); ?>').click();
	});

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

<?php
	include_once 'common/footer.php';
?>