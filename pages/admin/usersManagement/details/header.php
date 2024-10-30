<?php
if(isset($_GET['sac']))$sac = $_GET['sac']; else $sac = "";

$plugin_dir = CCGP_PLUGIN_URL;
global $wpdb;
global $wp;
$moduleFlds  = array();
$acpMdlV     = array();

$CommonClass = new CCGP_CommonClass();
$SttClass    = new CCGP_SettingsClass();

if(isset($_GET['cm-page'])) $cpage = $_GET['cm-page']; else $cpage = 0;
if(isset($_GET['uid'])) $uid = $_GET['uid'];     

$contacts = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE status = 'active' AND id = $uid ORDER BY id DESC");
$crmConId = $contacts->crmid;
$condtl   = json_decode($contacts->condtl, true);
$condtl   = $condtl['data'][0];
$accId    = $condtl['Account_Name']['id'];
$accName  = $condtl['Account_Name']['name'];

// POST action here start
if(isset($_POST['user_id'])){
    if(isset($_POST['secrets']) && (count($_POST['secrets']) > 0)){
        $SttClass->assigneUserSecrets($_POST);
        $success = true;
    }else{
        $error = true;
    }
}

if(isset($_POST['updateModuleAcp'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'updateModuleAcp')){
        if(isset($_POST['ac_user_id'])){
            $SttClass->userSecretsPermission($_POST);
            $success = true;
        }else{
            $error = true;
        } 
    }else{
        $error = true;
    }

} 

if(isset($_POST['updateUserLink'])){  
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'updateModuleAcp')){
        $SttClass->saveUserQuickLink($_POST);
        $success = true;  
    }else{
        $error = true;
    }      
} 

if(isset($_POST['updateAccountManager'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'updateAccountManager')){
        $SttClass->saveAccountManagerPermission($_POST);
        $success = true;  
    }else{
        $error = true;
    }
}

if(isset($_POST['updatePermissionLevel'])){
    if(isset($_POST['csrf_token_nonce']) && wp_verify_nonce($_POST['csrf_token_nonce'],'updatePermissionLevel')){
        $SttClass->updatePermissionLevel($_POST);
        $success = true;  
    }else{
        $error = true;
    }
}
?>

<style type="text/css">
    input[type=checkbox]{
        margin: 0px 0 0;
    }

    /*******************************
    * Does not work properly if "in" is added after "collapse".
    *******************************/
    #ccgclientportal-content .panel-group .panel-heading+.panel-collapse>.panel-body{
        border: solid 1px #ddd;
    }
    
    .panel-default > .panel-heading {
        padding: 0px;
        border-radius: 0;
        color: #212121;
        background-color: #FAFAFA;
        border-color: #EEEEEE;
    }

    .panel-title{
        font-size: 16px;
        line-height: 19px;
        color: #0084B4;
    }

    .panel-title > a {
        padding: 10px 15px;
        text-decoration: none;
        float: left;
        width: 90%;
    }

    .panel-title > a:focus{
        box-shadow: none;
    }

    .more-less {
        width: 25px;
    }

    .allCk{
        float: right;
        padding: 10px 15px;
        width: 10%;
        text-align: right;
    }

    .ch_desc{
        font-size: 13px;
        color: #666;
        display: block;
        word-wrap: break-word;
    }

    .card{
        max-width: 100%;
        padding: 0;
        border: 0px;
        box-shadow: none;
    }

    .card-body{
        padding: 10px 25px;
    }

    .card-title{
        padding: 10px 25px;
        border-bottom: 0px;
    }

    /*#portal-cotenier .quickLinkinput {
        width: calc(50% - 31px);
        float: left;
        margin-right: 8px;
    }*/

    #portal-cotenier .linkCon {
        margin-bottom: 8px;
    }

    #portal-cotenier .privateFolderForm input[type=checkbox]{
        margin-right: 15px;
    }
    /*new*/
    #portal-cotenier .nav-tabs>li>a {
        /*border: 1px solid #0091d8;*/
        padding: 10px 20px;
    }
    #portal-cotenier .nav-tabs>li>a {
        margin-right: 8px;
    }
    #portal-cotenier .nav-tabs {
        border-bottom: 0px;
        box-shadow: -4px 10px 5px -7px rgba(4, 4, 4, 0.19);
        background: #fff;
        border-radius: 10px 10px 0px 0px;
        position: relative;
        padding-left: 15px;

    }
    .takepadding {
        padding-bottom: 20px;
    }
    #addQ_Link {
        background-color: #7BDA7C !important;
    }
</style>

<div class="ccgclient-portal">
    <h3 class="page-heading">User Details</h3>
    <div class="clr"></div>

    <?php if(isset($success)){ ?>
        <div class="alert alert-success" style="margin: 25px 0px 0px;">Update completed successfully.</div>
    <?php }
    if(isset($error)){ ?>
        <div class="alert alert-danger" style="margin: 25px 0px 0px;">Data not saved.</div>
    <?php } ?>
    <div class="clr"></div>

    <div id="portal-cotenier" class="user-details">

            <?php $um_url = admin_url().'admin.php?page=usersmanagement&action=userdetails&uid='.$uid.'&sac=';?>
            <ul class="nav nav-tabs userDetailsTab" id="userDetailsTab">
                <li class="nav-item <?php if($sac == 'userinfo' || $sac == '')echo 'active';?>">
                    <a class="nav-link <?php if($sac == 'userinfo' || $sac == '')echo 'active';?>" href="<?php echo $um_url;?>userinfo">User Info</a>
                </li>
                <div class="clr"></div>
            </ul>

            <div id="myTabContent" class="tab-content brd-top-off">
                <div class="tab-pane fade active in" id="" style="padding: 15px 0px;">
