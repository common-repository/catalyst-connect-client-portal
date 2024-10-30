<?php
/*
Created By : Mahidul Islam Tamim
*/
include_once 'details/header.php';

global $wpdb;
global $wp;
$cppageUrl = home_url( $wp->request );
$gact      = false;

if(!isset($_GET['sac']) || ($_GET['sac'] == 'userinfo' || $_GET['sac'] == '')){
    include_once 'details/userinfo.php';
}else{
    include_once 'details/userinfo.php';
}

include_once 'details/footer.php';
?>