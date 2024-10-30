	<?php
		include_once 'common/left-menu.php';

		$succrss = false;
		$error = false;
		$errorm = false;
		$contact = false;

		$ZCRequest = new ZohoCrmRequest();
		$CrmModules = new CrmModules();

		$crmMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'layout_zcrm_Accounts'");
		if(isset($crmMdl->option_index))$layViewV = json_decode($crmMdl->option_value, true); 

		$fieldList = $CrmModules->getModuleFieldList("Accounts");

		if(isset($layViewV['rowAdded'])){
			if(isset($_SESSION["ccgcp_loged"]['id'])){
				$conId = $_SESSION["ccgcp_loged"]['id'];
				$accId = $_SESSION["ccgcp_loged"]['Account_Name']['id'];

				if(isset($_POST['updateModule'])){
					unset($_POST['updateModule']);

					// echo "<pre>";var_dump($_POST);echo "</pre>";
					if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'updateModule')){
						foreach ($layViewV['fieldName'] as $flfapinm) {
							$flfapinmArr = explode("___", $flfapinm);
							if(isset($flfapinmArr[1])){
								$fldApiN = $flfapinmArr[0];
							}else{
								$fldApiN = $flfapinm;                   
							}

							if(isset($fieldList[$fldApiN]['data_type'])){
								$fdtype = $fieldList[$fldApiN]['data_type'];

								if(isset($_POST[$fldApiN])){
									$value = $_POST[$fldApiN];
									if($fdtype == 'boolean')$_POST[$fldApiN] = ($value == "on") ? true : false;
									elseif($fdtype == 'integer')$_POST[$fldApiN] = (int) $value;            
									elseif($fdtype == 'currency')$_POST[$fldApiN] = (float) $value;     
									elseif($fdtype == 'lookup'){
										if(isset($_POST[$fldApiN."___id"])){
											if($_POST[$fldApiN."___id"] !=""){
												$record_id = $_POST[$fldApiN."___id"];
												$_POST[$fldApiN] =  array('name' => $value,'id' => $record_id);
											}
											unset($_POST[$fldApiN."___id"]);
										}
									}                
								}else if($fdtype == 'subform'){

									// $fieldListSF = $CrmModules->getModuleFieldList($fldApiN);
									$fieldListSF = $CrmModules->getSubformFieldList('Accounts__'.$fldApiN);

									$_POST[$fldApiN] = array();
									foreach ($layViewV['subformfieldName_'.$fldApiN] as $sfexf) {
										if($sfexf !=""){

										$sfexfArr  = explode("___", $sfexf);
										$sffldApiN = $sfexfArr[0];

										if(isset($_POST[$fldApiN.'---'.$sffldApiN])){

											$sfRcArr = $_POST[$fldApiN.'---'.$sffldApiN];                                    
											foreach ($sfRcArr as $key => $sfvalue) {

											if ($sfvalue !="") {

												if(isset($fieldListSF[$sffldApiN]['data_type'])){
													$sffdtype = $fieldListSF[$sffldApiN]['data_type'];

													if($sffdtype == 'boolean')$_POST[$fldApiN][$key][$sffldApiN] = ($sfvalue == "on") ? true : false;
													else if($sffdtype == 'integer')$_POST[$fldApiN][$key][$sffldApiN] = (int) $sfvalue;
													elseif($sffdtype == 'lookup'){
														$record_id = $_POST[$fldApiN.'---'.$sffldApiN."___id"][$key];
														$_POST[$fldApiN][$key][$sffldApiN] =  array('name' => $sfvalue,'id' => $record_id);

													}else{
														$_POST[$fldApiN][$key][$sffldApiN] =  $sfvalue;
													}

												}
												} 
											}//foreach ($sfRcArr as $key => $sfvalue)

											unset($_POST[$fldApiN.'---'.$sffldApiN]);
											unset($_POST[$fldApiN.'---'.$sffldApiN."___id"]);

										}
										} //if($sfexf !="")
									}
									unset($_POST[$fldApiN.'---'.$sffldApiN]);                           

								}else{

									if($fdtype == 'boolean')$_POST[$fldApiN] = ($value == "on") ? true : false;                        
								}
							}
						}
						// echo "<pre>";var_dump($_POST);echo "</pre>"; //exit();

						$zohoArr = array($_POST);
						$update = $ZCRequest->updateRecordsN($accId, $zohoArr, 'Accounts', 'user', 'yes');
						
						if((isset($update->data[0]->code)) && ($update->data[0]->code == "SUCCESS")){
							$succrss = "SUCCESS";
						}else $error = true;
						// echo "<pre>";var_dump($update);
					}else{
						$errorm = "Somethings went wrong";

					}
				}

				$moduleData = $ZCRequest->getRecordsByIdN($accId, "Accounts", 'user', 'yes');
				if(isset($moduleData['data'][0])){
					$recordDtl = $moduleData['data'][0];
				}elseif(isset($moduleData['status']) && ($moduleData['status'] == 'failed')){
					$errorm = "Your API Limit exceed. Please contact your administrator.";
				}

			}

			
		}

	?>

	<div id="portal-cotenier">
		<div class="col-md-12">
			<div class="profile-edit-page page-body" style="padding: 10px;">

				<?php if($succrss == "SUCCESS"){ ?>
					<div class="alert alert-dismissible alert-success">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <strong>Well done!</strong> Update successfully </a>.
					</div>
				<?php } ?>

				<?php if($error){ ?>
					<div class="alert alert-dismissible alert-danger">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <strong>Oh snap!</strong> Information does not update. Please try again leater !!
					</div>
				<?php } ?>


			<?php if($errorm){ ?>
				<div class="alert alert-dismissible alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?php echo $errorm;?>
				</div>
			<?php }else{ ?>


				<?php if(isset($layViewV['rowAdded'], $layViewV['layoutFldColumn'])){ ?>

				<?php if($recordDtl){ ?>
					<h4 class="section-title"><?php echo ($layViewV['sectiontitle'][0]) ? $layViewV['sectiontitle'][0] : "Account Information";?></h4>
					<div class="newtabSection">
					<form role="form" action="" method="post">
							
						<?php 
							wp_nonce_field('updateModule','csrf_token_nonce');
						?>

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

							$fldEdit="";                            
							if(isset($layViewV['fldEdit'][$fldP]))$fldEdit = $layViewV['fldEdit'][$fldP];
							if(isset($exFldHstry[$exfldN]['edit'])) $fldEdit = 'yes';
							$fldP++;


							$fValue = $recordDtl[$fieldApiName];
							$fValueId ="";
							$type = $fieldList[$fieldApiName]['data_type']; 

							if($fieldList[$fieldApiName]['system_mandatory']) $mandatory = 'required'; else  $mandatory ="";
							if(($type == 'subform') && (is_array($fValue))){
								$subformData = $fValue;
								$fValue = "";
							}else if(($type == 'multiselectpicklist') && is_array($fValue)){
								$fValue = implode(';', $fValue);
							}else if( ($type == 'lookup') ){

								if(isset($fValue['id']))$fValueId = $fValue['id'];
								if(isset($fValue['name']))$fValue = $fValue['name'];
								$lookupModule = $fieldList[$fieldApiName]['lookup']['module'];
							}

							if(is_array($fValue)) $fValue = "";

							$fValue = stripslashes($fValue);
							if($fldEdit == 'yes'){
							?>

							<div class="form-group pd-0 col-md-<?php echo $col; ?>">
								<label class="col-md-12 control-label">
									<?php echo $fieldList[$fieldApiName]['field_label'] ?>                                    
								</label>
								<div class="col-md-12">
								<?php 
									if($type == 'subform')
									{ 
										
								?>

							<?php 

							// echo "<pre>";var_dump($layViewV);echo "</pre>";
							if(isset($layViewV['subformfieldName_'.$fieldApiName])) $isSubfrom = true;
							else $isSubfrom = false;

							if($isSubfrom){
								$sfclumn = $layViewV['subformfieldName_'.$fieldApiName];

								// $fieldListSF = $CrmModules->getModuleFieldList($fieldApiName);
								$fieldListSF = $CrmModules->getSubformFieldList('Accounts__'.$fieldApiName);
								// echo "<pre>".$fieldApiName;var_dump($fieldListSF);echo "</pre>";

								?>
								<table class="table table-hover table-striped subform-record-list" style="margin-top: 15px;">
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
													<th><?php echo $sfexfDValue; ?></th>
												<?php }
											} ?>
											<th style="width: 100px;">Action</th>
										
										</tr>
									</thead>
									<tbody>
										<?php 

										if(isset($subformData[0]) && (is_array($subformData[0]))){
											foreach ($subformData as $sfdtl) { ?>

											<tr class="subform-recordRow">
												<?php 
													foreach ($sfclumn as $sfexf) {

														if($sfexf !=""){ ?>

															<?php $fieldDValueArr = explode("___", $sfexf);
															if(isset($fieldDValueArr[1])) $sfexfApiName = $fieldDValueArr[0];
															else $sfexfApiName = $exfldN; 

															$fValue = $sfvalue = $sfdtl[$sfexfApiName];
															?>
															<td> <?php 
																$type = $fieldListSF[$sfexfApiName]['data_type'];

																if(($type == 'multiselectpicklist') && is_array($fValue)){
																	$fValue = implode(';', $fValue);
																}else if($type == 'lookup'){

																	if(isset($fValue['id']))$fValueId = $fValue['id'];
																	if(isset($fValue['name']))$fValue = $fValue['name'];
																	$lookupModule = $fieldListSF[$sfexfApiName]['lookup']['module'];
																}

																if(is_array($fValue)) $fValue = "";
																$fValue = stripslashes($fValue);
																include 'crm_modules/defineSFInputTypeEdit.php';
															 ?>
															</td>
														<?php
														} 
													} ?>
													<td>
														<button type="button" class="remove removeTd"><i class="fas fa-times"></i></button>
													</td>
												</tr>

											<?php }
											} ?>

											<tr class="subform-recordRow subform-recordRowN ">
											<?php 
												$fValueId = "";$fValue = "";
												foreach ($sfclumn as $sfexf) {

													if($sfexf !=""){ ?>

														<?php $fieldDValueArr = explode("___", $sfexf);
														if(isset($fieldDValueArr[1])) $sfexfApiName = $fieldDValueArr[0];
														else $sfexfApiName = $exfldN;

														
														

														?>
														<td> <?php 
															$type = $fieldListSF[$sfexfApiName]['data_type'];
															if($type == 'lookup')$lookupModule = $fieldListSF[$sfexfApiName]['lookup']['module'];
															$fValue = "";
															include 'crm_modules/defineSFInputTypeEdit.php';
														 ?>
														</td>
													<?php
													} 
												} ?>
												<td>
													<button type="button" class="addSfItm"><i class="fas fa-plus"></i></button>
												</td>
											</tr>
									</tbody>
								</table>

							<?php } ?>
									<?php }elseif($type == 'textarea'){?>
										<textarea name="<?php echo $fieldApiName;?>" class="form-control" <?php echo $mandatory;?>><?php echo $fValue; ?></textarea>
									<?php }else if($type == 'text'){?>
										<input type="text" name="<?php echo $fieldApiName; ?>" class="form-control" <?php echo $mandatory;?> value="<?php echo $fValue; ?>">
									<?php }elseif($type == 'date'){?>
										<input type="date" name="<?php echo $fieldApiName; ?>" class="form-control" <?php echo $mandatory;?> value="<?php echo $fValue; ?>">
									<?php }elseif($type == 'datetime'){?>
										<input type="datetime-local" name="<?php echo $fieldApiName; ?>" class="form-control" <?php echo $mandatory;?> value="<?php echo $fValue; ?>">                                    
									<?php }elseif($type == 'picklist'){?>
										<select  name="<?php echo $fieldApiName; ?>" class="form-control ccg-basic-single"  <?php echo $mandatory;?>>
											
											<?php 
											$pick_list_values = $fieldList[$fieldApiName]['pick_list_values'];
											foreach ($pick_list_values as $option) {
												$ac_value = $option["actual_value"];
												$d_value = $option["display_value"];
												if($ac_value == $fValue || $d_value == $fValue)$slctd = "selected"; else $slctd = "";
												
												echo '<option value="'.$d_value.'" '.$slctd.'>'.$d_value.'</option>';

											} ?>
										</select>
									<?php }elseif($type == 'multiselectpicklist'){?>
										<select multiple name="<?php echo $fieldApiName; ?>[]" class="form-control ccg-basic-multiple"  <?php echo $mandatory;?>>
											
											<?php 
											$pick_list_values = $fieldList[$fieldApiName]['pick_list_values'];
											foreach ($pick_list_values as $option) {
												$ac_value = $option["actual_value"];
												$d_value = $option["display_value"];
												if((strpos($fValue, $ac_value) !== false) || (strpos($fValue, $d_value) !== false))$slctd = "selected"; else $slctd = "";
												
												echo '<option value="'.$d_value.'" '.$slctd.'>'.$d_value.'</option>';

											} ?>
										</select>
									<?php }elseif($type == 'boolean'){?>
										<input type="checkbox" class="form-control"  name="<?php echo $fieldApiName; ?>" <?php echo ($fValue == 1) ? "checked" : "";?>>
									<?php }else if($type == 'integer'){ ?>
										<input type="number" name="<?php echo $fieldApiName; ?>" class="form-control" <?php echo $mandatory;?> value="<?php echo $fValue; ?>" >
									<?php }else if($type == 'lookup'){ ?>

										<div class="input-group" id="<?php echo $fieldApiName; ?>_lookup">
											<input type="text" readonly name="<?php echo $fieldApiName; ?>" class="form-control showDialogLookup record_name" <?php echo $mandatory;?> value="<?php echo $fValue; ?>">
											<input type="hidden" name="<?php echo $fieldApiName; ?>___id" class="record_id" value="<?php echo $fValueId; ?>" >
											<input type="hidden" class="modulename" value="<?php echo $lookupModule; ?>" >
											<input type="hidden" class="fieldname" value="<?php echo $fieldApiName; ?>" >
										</div>

									<?php  }else{ ?>
										<input type="text" name="<?php echo $fieldApiName; ?>" class="form-control" <?php echo $mandatory;?> value="<?php echo $fValue; ?>">
									<?php } ?>
								</div>
							</div>
						<?php } ?>
						   
							<?php if($ttlcol == 12) {
								$ttlcol = 0;
							 ?>
								<div class="clr"></div>
							<?php }  ?>
							
							<?php if($colN == $layViewV['columnAdded'][$rowN]) {
								$colN = 0; $ttlcol = 0; $rowN = $rowN + 1; ?>
								<div class="clr"></div>
								</div> <!-- newtabSection -->
								<div style="clear: both;height: 40px;"></div>
								<?php if(isset($layViewV['sectiontitle'][$rowN])){ ?>
									<h4 class="section-title"><?php echo $layViewV['sectiontitle'][$rowN];?></h4>
								<?php }  ?>
								<?php if(isset($layViewV['sectiontitle'][$rowN])){ ?>
	                                <div class="newtabSection">
	                            <?php }  ?>
							<?php }  ?>

						<?php } ?>


						<div class="clr"></div>
						<div class="form-group">
							<div class="col-md-12">
								<input type="submit" class="btn btn-primary saveButton" style="float: right;" name="updateModule" value="Update">
							</div>
						</div>
						<div class="clr"></div>

					</form>


				<?php } else { ?>
					<p style="text-align: center;margin: 0px;">No record found</p></td>                   

				<?php } 
				} else { ?>
					<p style="text-align: center;margin: 0px;">Customization is not complete. Please Contact your administrator.</p></td>
				<?php } ?>

			<?php } ?>

			</div>  
		</div>  

		</div>
	</div>


	<style type="text/css">
		.showDialogLookup{cursor: pointer;}
		#showDialogLookup .modal-body .searchstr{width: calc(100% - 75px);float: left;}
		.ccgclientportal-content button.remove {
			background: #FF7D7E !important;
			color: #fff !important;
			border: solid 2px #FF7D7E !important;
			border-radius: 4px !important;
			padding: 5px 10px !important;
		}
		.ccgclientportal-content button.addSfItm {
			background: #7BDA7C !important;
			color: #fff !important;
			border: solid 2px #7BDA7C !important;
			border-radius: 4px !important;
			padding: 5px 10px !important;
		}
		#ccgclient-portal #portal-cotenier, #ccgclient-portal p, #ccgclient-portal strong, #ccgclient-portal b{
			color: #000000;
		}

	</style>
	<div style='display:none'>
		<div id='showDialogLookup' style='padding:10px; background:#fff;height: 200px;'>
			<div class="modal-body">
			<input type="hidden" class="moduleName" value="">
			<input type="hidden" class="fieldname" value="">

			<div class="form-group" style="margin-bottom: 15px;">
				<!-- <label class="control-label" style="float: left;line-height: 26px;">Name &nbsp;: &nbsp;</label> -->
				<input class="form-control searchstr" type="text">
				<button class="btn btn-primary saveButton searchrecord" style="float: left;">Search</button>
				<div class="clr"></div>
			</div>

			<div class="form-group searchingtext" style="display: none; margin-bottom: 15px;">Please wait ..... </div>
			<div class="form-group cmrModuleList" style="display: none; margin-bottom: 15px;">
				<!-- <label class="control-label">Select Account &nbsp;: &nbsp; </label> -->
				<select class="form-control selectrecord" style="width: 100%">
					<option value="">--Select One--</option>
				</select>
				<div class="clr"></div>
			</div>

		  </div>
		  <div class="modal-footer" style="display: none;">
			<button type="button" class="btn btn-default btn-selectrecord" style="">Add</button>
		  </div>

		</div>
	</div>
	<script type="text/javascript">

		function getmodule(searchstr,moduleName, fieldname, _this){
			// var criteria = "("+fieldname+":starts_with:"+searchstr+")";
			jQuery.ajax({
				type:'POST',
				data:{ module: moduleName, string: searchstr, for: "searchModule", action:'ccgpp_ajaxrequest'},
				url: "<?php echo site_url(); ?>/wp-admin/admin-ajax.php",
				success: function(response) {
					// console.log(response); 
					jQuery(_this).closest('.modal-body').find('.searchingtext').hide(); 
					if(response !='<option value="">--Select One--</option>'){        
						jQuery('#showDialogLookup').find('.modal-footer').show();   
						jQuery(_this).closest('.modal-body').find('.cmrModuleList').show(); 
					}else{
						jQuery('#showDialogLookup').find('.modal-footer').hide();  
						alert("No record found !");
					}
					jQuery(_this).closest('.modal-body').find('.cmrModuleList select').html(response);          

				}
			});
		}

		jQuery('.btn-selectrecord').click(function () {
			var record_id = jQuery("#showDialogLookup .selectrecord").val();
			var record_name = jQuery("#showDialogLookup .selectrecord option:selected").text();
			if(record_id == ""){
				jQuery('#showDialogLookup .selectrecord').focus();
				console.log("focus");
				return false;
			}
			var fieldname = jQuery('#showDialogLookup').find('.fieldname').val();
			jQuery('#'+fieldname+"_lookup.sleectLookupFld").find('.record_name').val(record_name);
			jQuery('#'+fieldname+"_lookup.sleectLookupFld").find('.record_id').val(record_id);     
			jQuery('#'+fieldname+"_lookup.sleectLookupFld").removeClass('sleectLookupFld');     
			jQuery.colorbox.close();
		});
	</script>



	<a style="display: none;" class='showDialog' href="#showDialogLookup"></a>
	<script type="text/javascript">
		jQuery(document).ready(function(){     

			jQuery(document).on('click', '.removeTd', function(event) {
				jQuery(this).closest('.subform-recordRow').remove();
			});

			jQuery(document).on('click', '.addSfItm', function(event) {
				var itemHtml = jQuery(this).closest('tbody').find('.subform-recordRowN').html();
				jQuery(this).closest('tbody').append('<tr class="subform-recordRow adddrw">'+itemHtml+'</tr>');
				jQuery(this).closest('tbody').find('.adddrw .addSfItm').addClass('remove removeTd');
				jQuery(this).closest('tbody').find('.adddrw .addSfItm').removeClass('addSfItm');
				jQuery(this).closest('tbody').find('.adddrw .fa-plus').addClass('fa-times');
				jQuery(this).closest('tbody').find('.adddrw .fa-plus').removeClass('fa-plus');

			});

		});
	</script>
	<script type="text/javascript">
		jQuery(document).ready(function(){

			jQuery(document).on('click', '.showDialogLookup', function(event) {
				event.preventDefault();
				/* Act on the event */
				
				jQuery('#showDialogLookup').find('.moduleName').val("");
				jQuery('#showDialogLookup').find('.fieldname').val("");
				jQuery('#showDialogLookup').find('.searchstr').val("");
				jQuery('#showDialogLookup').find('.cmrModuleList').hide(); 
				jQuery('#showDialogLookup').find('.modal-footer').hide();
				jQuery('#showDialogLookup').find('.cmrModuleList select').html('<option value="">--Select One--</option>'); 

				jQuery(".sleectLookupFld").removeClass('sleectLookupFld');   
				jQuery(this).closest('.input-group').addClass('sleectLookupFld');
				var moduleName = jQuery(this).closest('.input-group').find('.modulename').val();
				var fieldname = jQuery(this).closest('.input-group').find('.fieldname').val();
				jQuery("#showDialogLookup").find('.moduleName').val(moduleName);
				jQuery("#showDialogLookup").find('.fieldname').val(fieldname);

				jQuery(".showDialog").click();
			});
			jQuery(".showDialog").colorbox({inline:true, width:"450px", height:"auto"});

			
			jQuery('.searchrecord').click(function () {
				// console.log("Click");
				var searchstr = jQuery(this).closest('#showDialogLookup').find('.searchstr').val();
				if(searchstr == ""){
					jQuery(this).closest('#showDialogLookup').find('.searchstr').focus();
					return false;
				}

				jQuery(this).closest('.modal-body').find('.searchingtext').show();  
				jQuery(this).closest('.modal-body').find('.cmrModuleList').hide();
				jQuery('#showDialogLookup').find('.modal-footer').hide();

				var moduleName = jQuery(this).closest('#showDialogLookup').find('.moduleName').val();
				var fieldname = jQuery(this).closest('#showDialogLookup').find('.fieldname').val();
				getmodule(searchstr, moduleName, fieldname, jQuery(this));
			});
		});
	</script>

	<?php
		include_once 'common/footer.php';
	?>