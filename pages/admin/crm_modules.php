<?php
/*
    Created By : Mahidul Islam Tamim
*/
    
include_once 'common/header.php';
include_once 'crm_modules/header.php'; 

global $wpdb;
global $wp;
$cppageUrl = home_url( $wp->request );
$gact      = false;

if(!isset($_GET['module']) || ($_GET['module'] == 'selectModule' || $_GET['module'] == '')){
    include_once 'crm_modules/index.php';
}elseif(isset($_GET['module']) &&  $_GET['module'] == 'zcrm_Accounts'){
    include_once 'crm_modules/profileSettings.php';
}elseif(isset($_GET['module']) &&  $_GET['module'] != ''){
    include_once 'crm_modules/moduleSettings.php';
}else{
    echo "Module Not Found";
}

include_once 'crm_modules/footer.php'; 
include_once 'common/footer.php';
?>