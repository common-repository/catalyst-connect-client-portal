<?php 
    /*
        Created by : Mahidul Islam
    */
    include_once 'common/header.php';

    global $wpdb;
    global $wp;
    $cppageUrl = home_url( $wp->request );

    $gact           = false;    
    $success        = false;
    $successMessage = "";
    $error          = false;
    $errorMessage   = "";

    $SttClass = new CCGP_SettingsClass();
    $AdClass  = new AdminClass();

    if(!isset($_GET['action']) || ($_GET['action'] == 'profile' || $_GET['action'] == '')){
        include_once 'dashboard/profile.php';
    }else{
        echo "Page not found !";
    }

    include_once 'common/footer.php';

?>


