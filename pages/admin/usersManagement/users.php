<?php
/*
    Created By : Mahidul Islam Tamim
*/
$plugin_dir = CCGP_PLUGIN_URL;
$gact       = false;

global $wpdb;

if(isset($_POST['inactiveUser'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'inactiveUser')){
        $wpdb->update( "ccgclientportal_users", array( 'status' => 'inactive'),array('id'=>$_POST['id']));
    }else{

    }
}

if(isset($_POST['activeUser'])){
    $wpdb->update( "ccgclientportal_users", array( 'status' => 'active'),array('id'=>$_POST['id']));
}

if(isset($_POST['deleteUser'])){
    $wpdb->delete( 'ccgclientportal_users', array( 'id' => $_POST['id']) );
}

$pagetitle = "Users";
include_once 'header.php';
?>

<style type="text/css">
#ccgclientportal-content .nav-tabs.nav-umg > li > a{
    padding: 12px 40px 12px 40px !important; 
}
#ccgclientportal-content .ccgclient-portal #portal-cotenier .tab-content .tab-pane{
    /*padding: 30px 20px;*/
        padding: 0;
}


</style>
<div id="portal-cotenier">

    <ul class="nav nav-tabs btn-nav-custom nav-umg" style="border-bottom: none;">
        <li class="nav-item active" style="padding-left: 0px">
            <a class="nav-link active tab-activeUsers" data-toggle="tab" href="#activeUsers">Active Users</a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#inactiveUsers">Inactive Users</a>
        </li> -->
        <li class="nav-item">
            <a class="nav-link tab-addnewuser" data-toggle="tab" id="newUserTab" href="#addnewuser">Add New</a>
        </li>
        <li class="nav-item">
            <a class="nav-link tab-pendinguser" data-toggle="tab" href="#pendinguser">Pending User</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo admin_url(); ?>admin.php?page=usersmanagement&action=permissions">Settings</a>
        </li>
        <div class="clr"></div>
    </ul>

    <div class="clr"></div>

    <div id="myTabContent" class="tab-content " style="border: none;background: rgb(255, 255, 255);
box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.16);margin-top: 15px;">

        <div class="tab-pane fade active in" id="activeUsers" >

            <ul class="nav nav-tabs nav-internal">
                <li class="nav-item active">
                    <a class="nav-link active" data-toggle="tab" href="#actvUsers">Active Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-addnew" data-toggle="tab" href="#inactvUsers">Inactive Users</a>
                </li>
            </ul>
            <div class="clr"></div>

            <div id="acInacTabContent" class="tab-content pd_30_20">
                <div class="tab-pane fade active in" id="actvUsers">  
                    <?php include 'activeUsers.php';?>
                </div>
                <div class="tab-pane fade " id="inactvUsers">
                    <?php include 'inactiveUsers.php';?>
                </div>
            </div>

        </div>
        <!-- <div class="tab-pane fade " id="inactiveUsers">
            <?php
            // include_once 'inactiveUsers.php';
            ?>
        </div> -->

        <div class="tab-pane fade" id="addnewuser">
            <?php
            include_once 'adduser.php';
            ?>
        </div>

        <div class="tab-pane fade" id="pendinguser">
            <?php
            include_once 'pendinguser.php';
            ?>
        </div>

    </div>
    <div class="clr"></div>
</div>
<div class="clr"></div>
</div>
<div class="clr"></div>
<script type="text/javascript">
    <?php if( isset($_POST['searchContacts']) ){ ?>
    jQuery(document).ready(function($) {
        jQuery("#newUserTab").click();
    });
    <?php } ?>
    <?php if( isset($_GET['umTab']) ){ ?>
    jQuery(document).ready(function($) {
        console.log("<?php echo esc_js($_GET['umTab']);?>");
        jQuery(".tab-<?php echo esc_js($_GET['umTab']);?>").click();
    });
    <?php } ?>
</script>