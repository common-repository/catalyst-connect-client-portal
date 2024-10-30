
<?php
    /*
    * Created by Mahidul Islam.
    */
    include_once 'common/header.php';
?>
<?php

    global $wpdb;
    $succrss = false;
    $error = false;
    $SttClass = new CCGP_SettingsClass();
    
    if(isset($_POST['generalPermission'])){
      $update = $SttClass->generalPermission($_POST);
      if($update)$succrss = true;
      else $error = true;
    }

    $uPrm = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'userpermission'");
    foreach ($uPrm as $value) {
      if($value->option_index == 'generalPermission')$uPgnrl = $value;
    }
?>

<div class="ccgclient-portal">
    <h3 class="page-heading">User Permission</h3>
    <div class="clr"></div>
    <style type="text/css">
      form .form-group label.control-label{
        min-width: 125px;
        margin: 0px;
      }
    </style>

    <div id="portal-cotenier" style="max-width: 980px;">


        <?php if($succrss){ ?>
            <div class="alert alert-dismissible alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Well done!</strong> Permission Update successfully </a>.
            </div>
        <?php } ?>

        <?php if($error){ ?>
            <div class="alert alert-dismissible alert-danger">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Oh snap!</strong> Permission Not Update !!
            </div>
        <?php } ?>

        <div class="clr"></div>

        <h4>General</h4>
        <form role="form" action="" method="post" >
            
            <?php if(isset($uPgnrl)) $uPgnrlV = json_decode($uPgnrl->option_value, true); ?>
            <?php if(isset($uPgnrlV['userSignUp']))$userSignUp = true; ?>
            <div class="form-group">
                <label class="control-label" style="margin-right: 20px;">User Sign Up</label>
                <input type="checkbox" <?php echo (isset($userSignUp)) ? 'checked' : '';?> name="userSignUp">
              <div class="clr"></div>
            </div>
            <div class="clr"></div>

            <div class="clr"></div>
            <div class="form-group">       
              <input type="submit" class="btn btn-primary" name="generalPermission" value="Update">                        
              
            </div>

        </form>        

        <div class="clr"></div>


    </div>
</div>


<?php 
    include_once 'common/footer.php';
?>