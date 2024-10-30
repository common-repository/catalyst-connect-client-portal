    <?php if(isset($activesubTab) && $activesubTab === "tab-addnew"){ ?>
        <script type="text/javascript">
            jQuery(document).ready( function () {
                jQuery('.nav-item > #newUserTab').click();
                jQuery('.nav-item > #tab-addnew').click();
            } );
        </script>
    <?php } ?>


    <form action="" method="post" id="SignUp" >        
            <?php 
                wp_nonce_field('sign_up_page','csrf_token_nonce');
            ?>            
            <div class="contactSection" style="display: <?php if($showAcc)echo "none";?>;">
                <h4 class="col-md-12">Contact Information</h4>
                <div class="clr"></div>
                <div class="form-group col-md-6">
                    <label class="col-md-12 pd-0 control-label">First Name&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                    <div class="col-md-12 pd-0">                                                        
                        <input type="text" name="first_name" class="form-control" required value="<?php if(isset($_POST['email']))echo esc_attr(sanitize_text_field($_POST['first_name'])); ?>">
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12 pd-0 control-label">Last Name&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                    <div class="col-md-12 pd-0">                                                        
                        <input type="text" name="last_name" class="form-control" required value="<?php if(isset($_POST['email']))echo esc_attr(sanitize_text_field($_POST['last_name'])); ?>">
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12 pd-0 control-label">Email&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                    <div class="col-md-12 pd-0">                                                        
                        <input type="email" name="email" placeholder="" class="form-control primaryEmail" required value="<?php if(isset($_POST['email'])) echo esc_attr(sanitize_email($_POST['email'])); ?>">
                    </div>
                    <div class="clr"></div>
                </div>        
                <script>
                    jQuery(function () {
                        jQuery(document).on("focusout","input.primaryEmail",function(){
                            var usernameInput = jQuery(this).val();
                            jQuery('input.usernameInput').val(usernameInput);
                        });
                    });
                </script>
                <div class="form-group col-md-6">
                    <label class="col-md-12 pd-0 control-label">Phone&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                    <div class="col-md-12 pd-0">                                                        
                        <input type="text" name="phone" class="form-control" value="<?php if(isset($_POST['email']) != NULL)echo esc_attr(sanitize_text_field($_POST['phone'])); ?>">
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12 pd-0 control-label">User Name&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                    <div class="col-md-712 pd-0">                                                        
                        <input type="email" name="username" class="form-control usernameInput" required value="<?php if(isset($_POST['email']))echo esc_attr(sanitize_text_field($_POST['username'])); ?>">
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-md-12 pd-0 control-label">Password&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                    <div class="col-md-12 pd-0">                                                        
                        <input type="text" name="password" class="form-control" required value="<?php if(isset($_POST['email']))echo esc_attr(sanitize_text_field($_POST['password'])); ?>">
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr" style=""></div>
            </div>
            

            <?php if($showAcc){ ?>

            <div class="accountSection">
                <h4>Account Information</h4>

                <div class="adwthexaccsection col-md-12">
                    <div class="form-group">
                        <label class="control-label"> Create a new Account&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                        <input type="checkbox" class="crtacc_con" name="crtacc_con" style="margin: 0px 0px 0px 20px;">              
                    </div>
                    <div class="clr"></div>
                    <div class="accountInfo" style="display: none;">
                        <div class="form-group col-md-6">
                            <label class="col-md-5 control-label">Account Name&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                            <div class="col-md-7">                                                        
                                <input type="text" name="Account_Name" id="account_Name" class="form-control" value="<?php if(isset($_POST['website']))echo esc_attr(sanitize_text_field($_POST['Account_Name'])); ?>">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-5 control-label">Website&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                            <div class="col-md-7">                                                        
                                <input type="text" name="website" class="form-control" value="<?php if(isset($_POST['website']))echo esc_url(sanitize_text_field($_POST['website'])); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>

                <div class="adwthexaccsection  col-md-6">
                    <div class="form-group">
                        <label class="control-label"> Add to an Existing Account &nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>
                        <input type="checkbox" class="adwthexacc" name="adwthexacc" style="margin: 0px 0px 0px 20px;">              
                    </div>
                    <div class="form-group cmrAccountSearch" style="display: none;">
                        <label class="control-label">Search Account&nbsp;<span><i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="test"></i></span></label>  <br>
                        <input type="text" class="searchstr">
                        <button class="searchaccount">Search</button>
                    </div>

                    <div class="form-group searchingtext" style="display: none;">Please wait ..... </div>
                    <div class="form-group cmrAccountList" style="display: none;">
                        <label class="control-label">Select Account</label>
                        <select class="form-control crmAccIdNew" name="crmAccIdNew">
                            <option value="">--Select One--</option>
                        </select>
                        <div class="clr"></div>
                    </div>
                    <div class="clr"></div>
                </div>


                <div class="clr"></div>
            </div>

            <?php } ?>

            <div class="clr"></div>
            <div class="form-group col-md-12">
                <div class="col-md-12 pd-0">
                    <?php if(!$showAcc){ ?>
                        <input type="submit" class="btn btn-primary btn-padding checckExist" style="float: right;" name="checckExist" value="Next">
                    <?php } ?>                
                    <?php if($showAcc){ ?>
                        <input type="submit" class="btn btn-primary btn-padding addnewuser" style="float: right; display: none;" name="addnewuser" value="Save">
                    <?php } ?>
                </div>
            </div>
            <div class="clr"></div>

        </form>

    <script type="text/javascript">
        
        jQuery('.crtacc_con').click(function () {
            jQuery(".accountSection").find('.adwthexacc').prop('checked',false);
            jQuery(".accountSection").find('#account_Name').prop('required',true);
            jQuery(".accountSection").find('.crmAccIdNew').removeAttr('required');
            jQuery(".accountSection").find('.cmrAccountSearch').hide();
            jQuery(".accountSection").find('.cmrAccountList').hide();  
            if (jQuery(this).is(':checked')) {
                jQuery("form").find(".addnewuser").show();      
                jQuery(".accountSection").find('.accountInfo').show();   
            }else{          
                jQuery("form").find(".addnewuser").hide();      
                jQuery(".accountSection").find('.accountInfo').hide();   
            }     
        });

        jQuery('.adwthexacc').click(function () {
            jQuery(".accountSection").find('.crtacc_con').prop('checked',false);
            jQuery(".accountSection").find('#account_Name').removeAttr('required');
            jQuery(".accountSection").find('.crmAccIdNew').prop('required',true);     
            jQuery(".accountSection").find('.accountInfo').hide();
            if (jQuery(this).is(':checked')) {
                jQuery("form").find(".addnewuser").show();
                jQuery(".accountSection").find('.cmrAccountSearch').show();   
            }else{          
                jQuery("form").find(".addnewuser").hide();
                jQuery(".accountSection").find('.cmrAccountSearch').hide();   
            }  
        });

        function getmodule(searchstr,_this){
            var criteria = "(Account_Name:starts_with:"+searchstr+")";
            jQuery.ajax({
                type:'POST',
                data:{ module: "Accounts", criteria: criteria, for: "searchRecords", action:'ccgpp_ajaxrequest'},
                url: "<?php echo $ajaxUrl ?>",
                success: function(response) { 
                    jQuery(_this).closest('.accountSection').find('.searchingtext').hide();  
                    jQuery(_this).closest('.accountSection').find('.cmrAccountList').show();          
                    jQuery(_this).closest('.accountSection').find('.cmrAccountList select').html(response);          
                }
            });
        }

        jQuery('.searchaccount').click(function () {
            jQuery(this).closest('.accountSection').find('.searchingtext').show();  
            jQuery(this).closest('.accountSection').find('.cmrAccountList').hide();  
            var searchstr = jQuery(this).closest('.cmrAccountSearch').find('.searchstr').val();
            getmodule(searchstr, jQuery(this));
        });

    </script>