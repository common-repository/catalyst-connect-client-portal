
<?php
    /*
    * Created by Mahidul Islam.
    */

    global $wpdb;
    $succrss = false;
    $error = false;
    $SttClass = new CCGP_SettingsClass();
    
    if(isset($_POST['generalPermission'])){
      if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'generalPermission')){
        $update = $SttClass->generalPermission($_POST);
        if($update)$succrss = true;
        else $error = true;
      }else{
        $error = true;
      }
    }

    $uPrm = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'userpermission'");
    foreach ($uPrm as $value) {
      if($value->option_index == 'generalPermission')$uPgnrl = $value;
    }
?>


<?php
    $pagetitle = "User Permission";
    include_once 'header.php';
?>

<?php 

    $gact = false;
    global $wpdb;

    // $succrss = false;
    // $error = false;
    $pagetitle = "Settings";

    
    if(isset($_POST['um-settings'])){
      if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'um-settings')){
        unset($_POST['um-settings']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'um-settings', 'option_index' => 'sctoCRM' ) );
        $insres = $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'um-settings',
            'option_index' => 'sctoCRM',
            'option_value' => json_encode( $_POST )
        ]);
        if($insres) $succrss = true;
        else $error = true;
      }else{
        $error = true;
      }
    }
    
    
    if(isset($_POST['um-settings-addstt'])){
      if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'um-settings-addstt')){
          unset($_POST['um-settings-addstt']);
          $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'um-settings', 'option_index' => 'additionalstt' ) );
          $insres = $wpdb->insert("ccgclientportal_options", [
              'option_name' => 'um-settings',
              'option_index' => 'additionalstt',
              'option_value' => json_encode( $_POST )
          ]);

          if($insres) $succrss = true;
          else $error = true;
      }else{
        $error = true;
      }
    }

    $umstt = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'um-settings' ");
    if($umstt){
        foreach ($umstt as $ums) {
            if($ums->option_index == 'sctoCRM') $umsetresV = json_decode($ums->option_value, true);
            if($ums->option_index == 'additionalstt') $umatoapv = json_decode($ums->option_value, true);
        }
    }


    $fieldList = $CrmModules->getModuleFieldList("Contacts");
?>

<!-- <div class="ccgclient-portal"> -->
  
    
    <div id="portal-cotenier" >

        <?php if($succrss){ ?>
        <div class="alert alert-dismissible alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          Update completed successfully
        </div>
        <?php } ?>

        <?php if($error){ ?>
        <div class="alert alert-dismissible alert-danger">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          Permission Not Update
        </div>
        <?php } ?>

        <div class="clr"></div>

        <ul class="nav nav-tabs btn-nav-custom nav-umg" style="border-bottom: none;">
          <li class="nav-item " style="padding-left: 0px">
              <a class="nav-link tab-activeUsers" href="<?php echo admin_url(); ?>admin.php?page=usersmanagement&umTab=activeUsers">Active Users</a>
          </li>
          <li class="nav-item">
              <a class="nav-link tab-addnewuser" id="newUserTab" href="<?php echo admin_url(); ?>admin.php?page=usersmanagement&umTab=addnewuser">Add New</a>
          </li>
          <li class="nav-item">
              <a class="nav-link tab-pendinguser" href="<?php echo admin_url(); ?>admin.php?page=usersmanagement&umTab=pendinguser">Pending User</a>
          </li>
          <li class="nav-item active">
              <a class="nav-link active" href="<?php echo admin_url(); ?>admin.php?page=usersmanagement&action=permissions">Settings</a>
          </li>
          <div class="clr"></div>
      </ul>

      <div class="clr"></div>

      <div id="myTabContent" class="tab-content " style="border: none;background: rgb(255, 255, 255);
  box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.16);margin-top: 15px;">

        <div class="col-md-6 box-shadow pd-30 " style="height: 324px;">


          <h4 class="mt-0" style="color: #333;font-weight: 700;">General</h4>
          <div class="clr"></div>
          <form role="form" action="" method="post" >

              <?php 
                  wp_nonce_field('generalPermission','csrf_token_nonce');
              ?>
              
              <?php if(isset($uPgnrl)) $uPgnrlV = json_decode($uPgnrl->option_value, true); ?>
              <?php if(isset($uPgnrlV['userSignUp']))$userSignUp = true; ?>

              <div class="form-group">
                <div class="crm-module-list">
                  <label class="control-label">User Sign Up&nbsp;<span><i class="fas fa-info-circle" title="test"></i></span></label>
                      <label class="switch control-label switch-appearence">
                          <input type="checkbox" name="userSignUp" <?php echo (isset($userSignUp)) ?"checked":"";  ?>>
                          <span class="slider round slider-appearence"></span>
                      </label>
                  
                  <div class="clr"></div>
                </div>
              </div>
              <div class="clr"></div>


              <?php if(isset($uPgnrlV['userForgetPass']))$userForgetPass = true; ?>
              <div class="form-group">
                <div class="crm-module-list">
                  <label class="control-label">User Forget Password&nbsp;<span><i class="fas fa-info-circle" title="test"></i></span></label>
                      <label class="switch control-label switch-appearence">
                          <input type="checkbox" name="userForgetPass" <?php echo (isset($userForgetPass)) ?"checked":"";  ?>>
                          <span class="slider round slider-appearence"></span>
                      </label>
                  <div class="clr"></div>
                </div>
                <div class="clr"></div>
              </div>
              <div class="clr"></div>

              <div class="form-group">       
                  <input type="submit" class="btn btn-primary float-right btn-padding" name="generalPermission" value="Update">
              </div>

            <div class="clr"></div>
          </form>        
        </div>

        <?php
          include_once "settings.php";
        ?>

        <div class="clr"></div>
      </div>

      <div class="clr"></div>
    </div>
    <div class="clr"></div>
<!-- </div> -->
