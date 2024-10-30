<?php
/*
    Created By : Mahidul Islam Tamim
*/

    include_once 'common/left-menu.php';

    global $wpdb;
    global $wp;
    $cppageUrl =  home_url( $wp->request );
    $gact = false;
    
    $SttClass = new CCGP_SettingsClass();
    $CPNzoho = new ZohoCrmRequest();
    $CrmModules = new CrmModules();
    $cmncls = new CCGP_CommonClass();

    if(!isset($_GET['ppage'],$_GET['module']) || $_GET['ppage'] == 'list'){
        include_once 'crm_modules/list.php';
    }elseif(isset($_GET['ppage'],$_GET['module'],$_GET['id']) &&  $_GET['ppage'] == 'module-details'){        
        include_once 'crm_modules/module-details.php';
    }elseif(isset($_GET['ppage'],$_GET['module'],$_GET['id']) &&  $_GET['ppage'] == 'module-edit'){        
        include_once 'crm_modules/module-edit.php';
    }elseif(isset($_GET['ppage'],$_GET['module']) &&  $_GET['ppage'] == 'module-add'){        
        include_once 'crm_modules/module-add.php';
    }else{
        echo "Module Not Found";
    }


?>

<?php
    include_once 'common/footer.php';
?>