

<?php 
    $apData = array();
    $apData = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'appearance'");
    foreach ($apData as $apOval) {
      if($apOval->option_index == 'menucolor') $apMenC = json_decode($apOval->option_value, true);
      if($apOval->option_index == 'buttoncolor')$apbtnC = json_decode($apOval->option_value, true);
      if($apOval->option_index == 'font_color')$apfontC = json_decode($apOval->option_value, true);
    }
?>

<style type="text/css">

    #ccgclient-portal .portal-header h4{
        color: <?php echo (isset($apfontC['heddingFc'])) ? $apfontC['heddingFc'] : '#228be6';  ?>;
        font-weight: <?php echo (isset($apfontC['heddingFw'])) ? $apfontC['heddingFw'] : 'normal';  ?>;
    }
    #ccgclient-portal .section-title{
        border-bottom: 1px solid <?php echo (isset($apfontC['heddingFc'])) ? $apfontC['heddingFc'] : '#228be6';  ?>;
        color: <?php echo (isset($apfontC['heddingFc'])) ? $apfontC['heddingFc'] : '#228be6';  ?>;
        font-weight: <?php echo (isset($apfontC['heddingFw'])) ? $apfontC['heddingFw'] : 'normal';  ?>;
    }
    #ccgclientportal-content h1,
    #ccgclientportal-content h2,
    #ccgclientportal-content h3,
    #ccgclientportal-content h4{
        color: <?php echo (isset($apfontC['heddingFc'])) ? $apfontC['heddingFc'] : '#228be6';  ?>;
        font-weight: <?php echo (isset($apfontC['heddingFw'])) ? $apfontC['heddingFw'] : 'normal';  ?>;
    }
    #ccgclient-portal #portal-cotenier,
    #ccgclient-portal p,
    #ccgclient-portal strong,
    #ccgclient-portal b{
        color: <?php echo (isset($apfontC['bodyFc'])) ? $apfontC['bodyFc'] : '#5a5a5a';  ?>;
    }
    #ccgclient-portal{
        margin: 0 auto;
        min-width: 320px;
        width: 100%;
        max-width: <?php echo (isset($portalWidth) && $portalWidth !='') ? $portalWidth : '100%';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li a{
        background: <?php echo (isset($apMenC['menubckcolor'])) ? $apMenC['menubckcolor'] : '#FFFFFF';  ?>;
        color: <?php echo (isset($apMenC['menufontcolor'])) ? $apMenC['menufontcolor'] : '#000000';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li a i{
        color: <?php echo (isset($apMenC['menufontcolor'])) ? $apMenC['menufontcolor'] : '#228be6';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li a .ccgp-menu-i{
        fill: <?php echo (isset($apMenC['menufontcolor'])) ? $apMenC['menufontcolor'] : '#228be6';  ?>;
    }

    #ccgclient-portal #ccp-nav-accordion li.active-menu a{
        background: <?php echo (isset($apMenC['menuactbgcolor'])) ? $apMenC['menuactbgcolor'] : '#228be6';  ?>;
        color: <?php echo (isset($apMenC['menuactfc'])) ? $apMenC['menuactfc'] : '#FFFFFF';  ?>;
    }

    #ccgclient-portal .ccg-mobile-menu {
        background: <?php echo (isset($apMenC['menuactbgcolor'])) ? $apMenC['menuactbgcolor'] : '#2871d1';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li.active-menu a i{
        color: <?php echo (isset($apMenC['menuactfc'])) ? $apMenC['menuactfc'] : '#FFFFFF';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li.active-menu a .ccgp-menu-i{
        fill: <?php echo (isset($apMenC['menuactfc'])) ? $apMenC['menuactfc'] : '#FFFFFF';  ?>;
    }

    #ccgclient-portal #ccp-nav-accordion li a:focus,
    #ccgclient-portal #ccp-nav-accordion li a:active,
    #ccgclient-portal #ccp-nav-accordion li a:hover{
        background: <?php echo (isset($apMenC['menuhovbg'])) ? $apMenC['menuhovbg'] : '#3ea1f5';  ?>;
        color: <?php echo (isset($apMenC['menuhovf'])) ? $apMenC['menuhovf'] : '#FFFFFF';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li a:focus i,
    #ccgclient-portal #ccp-nav-accordion li a:active i,
    #ccgclient-portal #ccp-nav-accordion li a:hover i{
        color: <?php echo (isset($apMenC['menuhovf'])) ? $apMenC['menuhovf'] : '#FFFFFF';  ?>;    
    }
    #ccgclient-portal #ccp-nav-accordion li a:focus .ccgp-menu-i,
    #ccgclient-portal #ccp-nav-accordion li a:active .ccgp-menu-i,
    #ccgclient-portal #ccp-nav-accordion li a:hover .ccgp-menu-i{
        fill: <?php echo (isset($apMenC['menuhovf'])) ? $apMenC['menuhovf'] : '#FFFFFF';  ?>;    
    }

    #ccgclient-portal .btn.addButton{
        background: <?php echo (isset($apbtnC['addButtonBg'])) ? $apbtnC['addButtonBg'] : '#337ab7';  ?>;
        color: <?php echo (isset($apbtnC['addButtonFnt'])) ? $apbtnC['addButtonFnt'] : '#FFFFFF';  ?>;
    }
    #ccgclient-portal .btn.editButton{
        background: <?php echo (isset($apbtnC['editButtonBg'])) ? $apbtnC['editButtonBg'] : '#337ab7';  ?>;
        color: <?php echo (isset($apbtnC['editButtonFnt'])) ? $apbtnC['editButtonFnt'] : '#FFFFFF';  ?>;
    }
    #ccgclient-portal .btn.deleteButton{
        background: <?php echo (isset($apbtnC['deleteButtonBg'])) ? $apbtnC['deleteButtonBg'] : '#f0ad4e';  ?>;
        color: <?php echo (isset($apbtnC['deleteButtonFnt'])) ? $apbtnC['deleteButtonFnt'] : '#FFFFFF';  ?>;
    }
    #ccgclient-portal .btn.saveButton{
        background: <?php echo (isset($apbtnC['saveButtonBG'])) ? $apbtnC['saveButtonBG'] : '#337ab7';  ?>;
        color: <?php echo (isset($apbtnC['saveButtonFnt'])) ? $apbtnC['saveButtonFnt'] : '#FFFFFF';  ?>;
    }
    #ccgclient-portal .btn.viewButton{
        background: <?php echo (isset($apbtnC['viewButtonBg'])) ? $apbtnC['viewButtonBg'] : '#337ab7';  ?>;
        color: <?php echo (isset($apbtnC['viewButtonFnt'])) ? $apbtnC['viewButtonFnt'] : '#FFFFFF';  ?>;
    }

        
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a{            
        background: <?php echo (isset($apMenC['menubckcolor'])) ? $apMenC['menubckcolor'] : '#FFFFFF';  ?>;
        color: <?php echo (isset($apMenC['menufontcolor'])) ? $apMenC['menufontcolor'] : '#000000';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li.active-menu .billing-dropdown-menu a.active-sub-menu{
        color: <?php echo (isset($apMenC['menuactbgcolor'])) ? $apMenC['menuactbgcolor'] : '#228be6';  ?>;
        /*background: <?php echo (isset($apMenC['menuactfc'])) ? $apMenC['menuactfc'] : '#FFFFFF';  ?>;*/
    }
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a:focus,
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a:active,
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a:hover,
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a.active-sub-menu:focus,
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a.active-sub-menu:active,
    #ccgclient-portal #ccp-nav-accordion li.active-menu .billing-dropdown-menu a.active-sub-menu:hover{
        background: <?php echo (isset($apMenC['menuhovbg'])) ? $apMenC['menuhovbg'] : '#3ea1f5';  ?>;
        color: <?php echo (isset($apMenC['menuhovf'])) ? $apMenC['menuhovf'] : '#FFFFFF';  ?>;
    }

    #ccgclient-portal .ccp-top-bar{
        background-image: url(<?php echo PLUGIN_URL; ?>assets/images/top-bar-bg-t.png); 
        <?php if(isset($primarycolor) && ($primarycolor !="")){ 
            echo 'background-color: '.$primarycolor.';'; 
        }else { ?>
        	background-color: #2871d1;
        <?php } ?>
    }

    #ccgclient-portal .dashboard-half .dh-content::-webkit-scrollbar-thumb {
        background: <?php echo (isset($primarycolor) && ($primarycolor !="")) ? $primarycolor : "#2871d1";?>;
    }

    #portal-cotenier table.table.table-hover thead tr th{

        <?php if(isset($primarycolor) && ($primarycolor !="")){ 
            echo 'background: '.$primarycolor.';'; 
        }else { ?>
            background: #2871d1; 
        <?php } ?>
    }
</style>