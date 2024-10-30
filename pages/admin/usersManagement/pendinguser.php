<?php
/**
* Created by Mahidul Islam .
*/

$plugin_dir = CCGP_PLUGIN_PATH;
?>

<?php 

    $gact = false;
    $success = false;
    $exist = false;
    $errorcrm = false;
    global $wpdb;

    $ZCRequest = new ZohoCrmRequest();

    if(isset($_POST['deleteUserA'])){
        $id = $_POST['id'];
        $wpdb->delete( 'ccgclientportal_users', array( 'id' => $id) );
    }
    if(isset($_POST['deleteUser'])){
        $id = sanitize_text_field($_POST['id']);
        $wpdb->delete( 'ccgclientportal_tempusers', array( 'id' => $id) );
    }

    if(isset($_POST['approveUser'])){
        if (sanitize_text_field($_POST['createNewAcc']) == NULL || sanitize_text_field($_POST['ex_accId']) == NULL) {
            $res = $ZCRequest->addUserApproveNewAcc($_POST);
            
            if($res == "added") $success = true;
            elseif($res == "usernotfound") $usernotfound = true;
            elseif($res == "AccNotFound") $erreAccNotFound = true;
        }else{
            $res = $ZCRequest->addUserApprove($_POST);
            if($res == "added") $success = true;
            elseif($res == "usernotfound") $usernotfound = true;
            elseif($res == "AccNotFound") $erreAccNotFound = true;
        }
    }
    if(isset($_POST['activeUser'])){
        if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'activeUser')){
            $tmpU = $wpdb->get_row("SELECT * FROM ccgclientportal_tempusers WHERE id = '".$_POST['id']."' AND status = 'pending'");

            if(isset($tmpU->data)){
                $tmpUd = json_decode($tmpU->data, true);
                $res = $ZCRequest->addUserNew($tmpUd);
    
                if($res['status'] === "ac_exist"){ 
                    $exist = true;
                    $errMsg = "Account name already exit in CRM";
                }elseif($res['status'] === "con_exist"){ 
                    $exist = true;
                    $errMsg = "Contact already exit in CRM by this Email";
                }elseif($res['status'] === "errorcrm"){ 
                    $errorcrm = true;
                }elseif($res['status'] === "success"){ 
                    $success = true;
                    $wpdb->update( "ccgclientportal_tempusers", array( 'status' => 'added'),array('id'=> sanitize_text_field($_POST['id'])));
                }
            }
            
        }else{
            $exist = true;
            $errMsg = "Something went wrong";
        }

    }

    $needAppCon = $wpdb->get_results("SELECT * FROM ccgclientportal_users WHERE status = 'approve' ORDER BY id DESC");
    $newUsers = $wpdb->get_results("SELECT * FROM ccgclientportal_tempusers WHERE status = 'pending' ORDER BY id DESC");
?>



<?php
    $pagetitle = "Pending Users";
    include_once 'header.php';
?>

<div id="portal-cotenier">

    <?php if($success){ ?>
        <div class="alert alert-dismissible alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          User Added successfully.
        </div>
    <?php } ?>

    <?php if($exist){ ?>
        <div class="alert alert-dismissible alert-danger">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <?php echo $errMsg; ?>
        </div>
    <?php } ?>

    <?php if($errorcrm){ ?>
        <div class="alert alert-dismissible alert-danger">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          User does not added to Zoho CRM   <?php echo($errorcrm) ?>
        </div>
    <?php } ?>

    <?php if(isset($erreAccNotFound)){ ?>
        <div class="alert alert-dismissible alert-danger">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          Please associate this Contact with an Account in the CRM before processing
        </div>
    <?php } ?>

    <div class="col-md-12 pd-30">
        <table class="datatable table table-hover contacts-list">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>User Name</th>
                    <!-- <th>Password</th> -->
                    <th>Account</th>
                    <th>Website</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                <?php if($needAppCon){
                    foreach ($needAppCon as $key => $conDtl) {
                        $crmConDtl = json_decode($conDtl->condtl, true);
                        $accId = "";
                        $accName = "";
                        if(isset($crmConDtl["data"][0]['Account_Name']['id'])){
                            $accId = $crmConDtl["data"][0]['Account_Name']['id'];
                            $accName = $crmConDtl["data"][0]['Account_Name']['name'];
                        }
                    ?>
                        <tr>
                            <td><?php echo esc_html($conDtl->fullname); ?></td>
                            <td><?php echo esc_html($conDtl->email); ?></td>
                            <td><?php echo esc_html($conDtl->phone); ?></td>
                            <td><?php echo esc_html($conDtl->username); ?></td>
                            <!-- <td>
                                <font class="decrptpass" style="display: none;"><?php echo $conDtl->password; ?></font>
                                <font class="encptpass"><?php echo str_repeat('*', strlen($conDtl->password)); ?></font>
                                <i class="fas fa-eye showpass"></i>
                                <i class="fas fa-eye-slash hidepass" style="display: none;"></i>
                            </td> -->
                            <td><?php echo (isset($accName))? $accName : ""; ?></td>
                            <td></td>
                            <td class="actionClumn">
                                <form action="" method="post" id="fromid_app_<?php echo esc_attr($conDtl->id); ?>" style="margin-right: 8px;float: left;">
                                    <input type="hidden" name="id" value="<?php echo esc_attr($conDtl->id); ?>">         
                                    <input type="hidden" name="crmConId" value="<?php echo esc_attr($conDtl->crmid); ?>">         
                                    <input type="hidden" name="approveUser" value="Approve">
                                    <?php if($accId !=""){ ?>
	                                    <input type="button" class="showDailogApproveWacc" value="Approve" data-formid="app_<?php echo esc_attr($conDtl->id); ?>" data-accid="<?php echo esc_attr($accId ); ?>">
	                                <?php } else { ?>
	                                    <input type="button" class="showDailogApprove" value="Approve" data-formid="app_<?php echo esc_attr($conDtl->id); ?>" data-accid="">                                  
	                                <?php } ?>
                                </form>
                                <form action="" method="post" style="float: left;">
                                    <input type="hidden" name="id" value="<?php echo esc_attr($conDtl->id); ?>">
                                    <input type="submit" name="deleteUserA" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php }
                } ?>


                <?php 
                $conDtl = "";
                if($newUsers){
                    foreach ($newUsers as $key => $conDtl) {
                        $postData = json_decode($conDtl->data);
                    ?>
                        <tr>
                            <td><?php echo esc_html($postData->first_name.' '.$postData->last_name); ?></td>
                            <td><?php echo esc_html($postData->email); ?></td>
                            <td><?php echo esc_html($postData->phone); ?></td>
                            <td><?php echo esc_html($postData->username); ?></td>
                            <!-- <td>
                                <font class="decrptpass" style="display: none;"><?php echo esc_html($postData->password); ?></font>
                                <font class="encptpass"><?php echo esc_html(str_repeat('*', strlen($postData->password))); ?></font>
                                <i class="fas fa-eye showpass"></i>
                                <i class="fas fa-eye-slash hidepass" style="display: none;"></i>
                            </td> -->
                            <td><?php echo esc_html($postData->Account_Name); ?></td>
                            <td><?php echo esc_html($postData->website); ?></td>
                            <td class="actionClumn">
                                <?php 
                                    if(isset($postData->addForExistAccAccept) && $postData->addForExistAccAccept =="on"){
                                        if(isset($postData->crmAccId)) $crmAccId = $postData->crmAccId;
                                        else $crmAccId = "";
                                    }else $crmAccId = "";
                                ?>
                                <form action="" method="post" id="fromid_add_<?php echo esc_attr($conDtl->id); ?>" style="margin-right: 8px;float: left;">
                                    
                                    <?php 
                                        wp_nonce_field('activeUser','csrf_token_nonce');
                                    ?>
                                    <input type="hidden" name="id" value="<?php echo esc_attr($conDtl->id); ?>">     
                                    <input type="hidden" name="activeUser" value="Add"> 
                                    <input type="button" class="showDailogAdd" value="Add" data-formid="add_<?php echo esc_attr($conDtl->id); ?>">
                                </form>
                                <form action="" method="post" style="float: left;" id="fromid_del_<?php echo esc_attr($conDtl->id); ?>">
                                    <input type="hidden" name="id" value="<?php echo esc_attr($conDtl->id); ?>">
                                    <input type="submit" name="deleteUser" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php }
                } ?>
                
            </tbody>
        </table>
    </div>  
    <div class="clr"></div>

</div>

<div id="confirmationMessageNew" style="display: none;">
    <div class="modal-body">
        <p>Create a new Account and Contact</p>
        <input type="hidden" name="formId" id="formId" value="">
    </div>
    <div class="clr"></div>
</div>

<!-- Modal -->
<a class='inlinecmaApWAcc' href="#confirmationMessageApproveWacc" style="display: none;"></a>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".inlinecmaApWAcc").colorbox({inline:true,  width:"450px", height:"250px"});
    });
</script>
<div style='display:none'>
    <div id='inlinecmaApWAcc_content' style='padding:10px; background:#fff;'>
        <div id="confirmationMessageApproveWacc">
            <div class="modal-body">
                <p>This will be create Wordpress user as a Client. And if user is exist then password will be update</p>
                <input type="hidden" name="formIdApp" id="formIdApp" value="">
            </div>
            <div class="clr"></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-yescreatnewclient">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<a class='inlinecma' href="#confirmationMessageApprove" style="display: none;"></a>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".inlinecma").colorbox({inline:true,  width:"450px", height:"400px"});
    });
</script>
<div style='display:none'>
    <div id='inlinecma_content' style='padding:10px; background:#fff;'>
		<div id="confirmationMessageApprove">
		    <div class="modal-body">
                <input type="hidden" name="formIdApp" id="formIdApp" value="">

                <div class="associateAcc">
                    <div class="form-group " style="margin-bottom: 15px;">
                        <label class="control-label"> Create a new Account</label>
                        <input type="checkbox" class="crtaccAp" name="crtacc_con" style="margin: 0px 0px 0px 20px;">              
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label class="control-label"> Add to an Existing Account </label>
                        <input type="checkbox" class="adwthexaccAp" name="adwthexacc" style="margin: 0px 0px 0px 20px;">              
                    </div>
                    <div class="form-group cmrAccountSearch" style="display: none;margin-bottom: 15px;">
                        <label class="control-label">Search Account</label>  <br>

                        <input type="text" class="searchstr">
                        <button class="searchaccount">Search</button>
                    </div>

                    <div class="form-group searchingtext" style="display: none; margin-bottom: 15px;">Please wait ..... </div>
                    <div class="form-group cmrAccountList" style="display: none; margin-bottom: 15px;">
                        <label class="control-label">Select Account</label>
                        <select class="form-control" name="Account_Name" style="width: 300px">
                            <option value="">--Select One--</option>
                        </select>
                        <div class="clr"></div>
                    </div>
                </div>
		    </div>
		    <div class="clr"></div>

	    	<div class="modal-footer">

                <button type="button" class="btn btn-default btn-app_crtacc" style="display: none;">Add</button>
                <button type="button" class="btn btn-default btn-app_adwthexacc" style="display: none;">Add</button>
		    </div>
		</div>
	</div>
</div>
<script type="text/javascript">
     jQuery(document).ready(function () {

            jQuery('.crtaccAp').click(function () {
                jQuery("#confirmationMessageApprove").find('.adwthexaccAp').prop('checked',false);
                jQuery("#confirmationMessageApprove").find('.cmrAccountSearch').hide();
                jQuery("#confirmationMessageApprove").find('.cmrAccountList').hide();
                if (jQuery(this).is(':checked')) {
                    jQuery("#confirmationMessageApprove").find(".btn-app_crtacc").show();
                    jQuery("#confirmationMessageApprove").find(".btn-app_adwthexacc").hide();
                }else{          
                    jQuery("#confirmationMessageApprove").find(".btn-app_crtacc").hide();
                    jQuery("#confirmationMessageApprove").find(".btn-app_adwthexacc").hide();
                }
            });    
            jQuery('.adwthexaccAp').click(function () {
                jQuery("#confirmationMessageApprove").find('.crtaccAp').prop('checked',false);
                jQuery("#confirmationMessageApprove").find('.cmrAccountSearch').show();
                if (jQuery(this).is(':checked')) {
                    jQuery("#confirmationMessageApprove").find(".btn-app_adwthexacc").show();
                    jQuery("#confirmationMessageApprove").find(".btn-app_crtacc").hide();
                }else{          
                    jQuery("#confirmationMessageApprove").find(".btn-app_crtacc").hide();
                    jQuery("#confirmationMessageApprove").find(".btn-app_adwthexacc").hide();
                }
            });




            jQuery('.showDailogApproveWacc').click(function () {
                var formid = jQuery(this).data('formid');
                var acc_accId = jQuery(this).data('accid');
                console.log(acc_accId);
                
                jQuery("#confirmationMessageApproveWacc #formIdApp").val(formid);
                jQuery('.inlinecmaApWAcc').click();
            });

            jQuery('.btn-yescreatnewclient').click(function () {
                var user_id = jQuery("#confirmationMessageApproveWacc #formIdApp").val();
                console.log(user_id);        
                jQuery("#fromid_"+user_id).submit();
            });


            jQuery('.showDailogApprove').click(function () {
                var formid = jQuery(this).data('formid');
                var acc_accId = jQuery(this).data('accid');
                console.log(acc_accId);

                jQuery("#confirmationMessageApprove .approveUserConMessage").hide();
                jQuery("#confirmationMessageApprove .associateAcc").show();
                
                jQuery("#confirmationMessageApprove #formIdApp").val(formid);
                jQuery('.inlinecma').click();
            });


            jQuery('.btn-app_crtacc').click(function () {
                var user_id = jQuery("#confirmationMessageApprove #formIdApp").val();
                console.log(user_id);        
                jQuery("#fromid_"+user_id).find('input.ex_accId').remove();                
                jQuery("#fromid_"+user_id).find('input.createNewAcc').remove();                
                jQuery("#fromid_"+user_id).append('<input type="hidden" class="createNewAcc" name="createNewAcc" value="Create">');
                jQuery("#fromid_"+user_id).submit();
            });
            jQuery('.btn-app_adwthexacc').click(function () {
                var assoAccId = jQuery("#confirmationMessageApprove .cmrAccountList select").val();
                console.log(assoAccId);
                if(assoAccId ==""){
                    alert("Please select Account");
                }else if(assoAccId.length > 10){
                    var user_id = jQuery("#confirmationMessageApprove #formIdApp").val();
                    console.log(user_id); 
                    jQuery("#fromid_"+user_id).find('input.createNewAcc').remove();  
                    jQuery("#fromid_"+user_id).find('input.ex_accId').remove();  
                    var accinput = '<input type="hidden" class="ex_accId" name="ex_accId" value="'+assoAccId+'">';
                    jQuery("#fromid_"+user_id).append(accinput);
                    jQuery("#fromid_"+user_id).submit();
                }
            });

        });
</script>

<style type="text/css">
	.cmrAccountList .control-label{
		float: left;
	    line-height: 28px;
	    margin-bottom: 0px;
	}
	.cmrAccountList .form-control{
		float: left;
	    width: 350px;	
	}
</style>

<!-- Modal -->
<a class='inlinecmm' href="#confirmationMessageModal" style="display: none;"></a>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery(".inlinecmm").colorbox({inline:true,  width:"450px", height:"400px"});
    });
</script>
<div style='display:none'>
    <div id='inlinecmm_content' style='padding:10px; background:#fff;'>

		<div id="confirmationMessageModal">
		    <!-- Modal content-->
		      <div class="modal-body">
			    <input type="hidden" name="formId" class="formId" value="">

		        <div class="form-group " style="margin-bottom: 15px;">
		            <label class="control-label"> Create a new Account and Contact</label>
		            <input type="checkbox" class="crtacc_con" name="crtacc_con" style="margin: 0px 0px 0px 20px;">	            
		        </div>

		        <div class="form-group" style="margin-bottom: 15px;">
		            <label class="control-label"> Add to an Existing Account </label>
		            <input type="checkbox" class="adwthexacc" name="adwthexacc" style="margin: 0px 0px 0px 20px;">	            
		        </div>
		        <div class="form-group cmrAccountSearch" style="display: none;margin-bottom: 15px;">
		            <label class="control-label">Search Account</label>  <br>

		            <input type="text" class="searchstr">
		            <button class="searchaccount">Search</button>
		        </div>

		        <div class="form-group searchingtext" style="display: none; margin-bottom: 15px;">Please wait ..... </div>
		        <div class="form-group cmrAccountList" style="display: none; margin-bottom: 15px;">
		        	<label class="control-label">Select Account</label>
		            <select class="form-control" name="Account_Name" style="width: 300px">
		                <option value="">--Select One--</option>
		            </select>
		            <div class="clr"></div>
		        </div>

		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default btn-crtacc_con" style="display: none;">Add</button>
		        <button type="button" class="btn btn-default btn-adwthexacc" style="display: none;">Add</button>
		      </div>
		</div>

    </div>
</div>

<script type="text/javascript">
     jQuery(document).ready(function () {

        jQuery('.showDailogAdd').click(function () {
            var formid = jQuery(this).data('formid');
            jQuery("#confirmationMessageModal").find('.formId').val(formid);
            jQuery('.inlinecmm').click();
        });
    });
</script>
<script type="text/javascript">
    
    jQuery(document).ready(function () {
	    
	    jQuery('.crtacc_con').click(function () {
	        jQuery("#confirmationMessageModal").find('.adwthexacc').prop('checked',false);
	        jQuery("#confirmationMessageModal").find('.cmrAccountSearch').hide();
	        jQuery("#confirmationMessageModal").find('.cmrAccountList').hide();
	        if (jQuery(this).is(':checked')) {
	        	jQuery("#confirmationMessageModal").find(".btn-crtacc_con").show();
	        	jQuery("#confirmationMessageModal").find(".btn-adwthexacc").hide();
	        }else{        	
	        	jQuery("#confirmationMessageModal").find(".btn-crtacc_con").hide();
	        	jQuery("#confirmationMessageModal").find(".btn-adwthexacc").hide();
	        }
	    });    
	    jQuery('.adwthexacc').click(function () {
	        jQuery("#confirmationMessageModal").find('.crtacc_con').prop('checked',false);
	        jQuery("#confirmationMessageModal").find('.cmrAccountSearch').show();
	        if (jQuery(this).is(':checked')) {
	        	jQuery("#confirmationMessageModal").find(".btn-adwthexacc").show();
	        	jQuery("#confirmationMessageModal").find(".btn-crtacc_con").hide();
	        }else{        	
	        	jQuery("#confirmationMessageModal").find(".btn-crtacc_con").hide();
	        	jQuery("#confirmationMessageModal").find(".btn-adwthexacc").hide();
	        }
	    });

	    function getmodule(searchstr,_this){
			var criteria = "(Account_Name:starts_with:"+searchstr+")";
            var ajaxurl  = "<?php echo site_url(); ?>/wp-admin/admin-ajax.php";
	        jQuery.ajax({
	            type:'POST',
	            data:{ module: "Accounts", criteria: criteria, for: "searchRecords", action:'ccgpp_ajaxrequest'},
	            url: "<?php echo $ajaxUrl ?>",
	            success: function(response) { 
	        		jQuery(_this).closest('.modal-body').find('.searchingtext').hide();  
	                jQuery(_this).closest('.modal-body').find('.cmrAccountList').show();          
	                jQuery(_this).closest('.modal-body').find('.cmrAccountList select').html(response);          
	            }
	        });
	    }
	    jQuery('.searchaccount').click(function () {
	        jQuery(this).closest('.modal-body').find('.searchingtext').show();  
	        jQuery(this).closest('.modal-body').find('.cmrAccountList').hide();  
	        var searchstr = jQuery(this).closest('.cmrAccountSearch').find('.searchstr').val();
	        getmodule(searchstr, jQuery(this));
	    });


	    jQuery('.btn-crtacc_con').click(function () {
	        var user_id = jQuery("#confirmationMessageModal .formId").val();        
		    jQuery("#fromid_"+user_id).find('input.crmAccIdNew').remove();
	        jQuery("#fromid_"+user_id).submit();
	    });
	    jQuery('.btn-adwthexacc').click(function () {
	        var accountid = jQuery("#confirmationMessageModal .cmrAccountList select").val();
	        if(accountid ==""){
	        	alert("Please select Account");
	        }else if(accountid.length > 10){
		        var user_id = jQuery("#confirmationMessageModal .formId").val();
	        	var accinput = '<input type="hidden" class="crmAccIdNew" name="crmAccIdNew" value="'+accountid+'">';
		        jQuery("#fromid_"+user_id).append(accinput);
		        jQuery("#fromid_"+user_id).submit();
		    }
	    });
    });
</script>
