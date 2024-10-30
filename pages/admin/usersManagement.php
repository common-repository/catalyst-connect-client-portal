<?php
/*
    Created By : Mahidul Islam Tamim
*/ 
include_once 'common/header.php';

global $wpdb;
global $wp;
$cppageUrl  = home_url( $wp->request );
$gact       = false;
$CrmModules = new CrmModules();

if(!isset($_GET['action']) || ($_GET['action'] == 'users' || $_GET['action'] == '')){
    include_once 'usersManagement/users.php';
}elseif(isset($_GET['action'], $_GET['uid']) &&  $_GET['action'] == 'userdetails'){
    include_once 'usersManagement/userdetails.php';
}elseif(isset($_GET['action']) &&  $_GET['action'] == 'adduser'){
    include_once 'usersManagement/adduser.php';
}elseif(isset($_GET['action']) &&  $_GET['action'] == 'pendinguser'){
    include_once 'usersManagement/pendinguser.php';
}elseif(isset($_GET['action']) &&  $_GET['action'] == 'permissions'){
    include_once 'usersManagement/userpermission.php';
}elseif(isset($_GET['action']) &&  $_GET['action'] == 'settings'){
    include_once 'usersManagement/settings.php';
}else{
    include_once 'usersManagement/users.php';
}

include_once 'common/footer.php';
?>