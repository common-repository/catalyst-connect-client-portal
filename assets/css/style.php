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
         color: <?php echo (isset($apfontC['fontcolor'])) ? $apfontC['fontcolor'] : 'rgb(34, 139, 230)';  ?>;
         font-weight: <?php echo (isset($apfontC['heddingFw'])) ? $apfontC['heddingFw'] : 'normal';  ?>;       
    }
    #ccgclient-portal .section-title{
        /*border-bottom: <?php if(isset($apfontC['fontcolor'])) echo '1px solid '.$apfontC['fontcolor']; else echo '';  ?>;*/
        color: <?php echo (isset($apfontC['fontcolor'])) ? $apfontC['fontcolor'] :  'rgb(44, 45, 45)';  ?>;
        font-weight: <?php echo (isset($apfontC['heddingFw'])) ? $apfontC['heddingFw'] : '600';  ?>;
    }

     #ccgclient-portal .section-title-module{
        /*border-bottom: <?php if(isset($apfontC['fontcolor'])) echo '1px solid '.$apfontC['fontcolor']; else echo '';  ?>;*/
        color: <?php echo (isset($apfontC['fontcolor'])) ? $apfontC['fontcolor'] :  'rgb(44, 116, 210)';  ?>;
        font-weight: <?php echo (isset($apfontC['heddingFw'])) ? $apfontC['heddingFw'] : '600';  ?>;
    }

    #ccgclientportal-content h1,
    #ccgclientportal-content h2,
    #ccgclientportal-content h3,
    #ccgclientportal-content h4{
        color: <?php echo (isset($apfontC['fontcolor'])) ? $apfontC['fontcolor'] : 'rgb(44, 45, 45)';  ?>;
        font-weight: <?php echo (isset($apfontC['heddingFw'])) ? $apfontC['heddingFw'] : 'bold';  ?>;
        text-transform: capitalize;
    }
    #ccgclientportal-content h5,
    #ccgclientportal-content h6{
        letter-spacing: inherit;
        text-transform: capitalize;
        font-weight: <?php echo (isset($apfontC['heddingFw'])) ? $apfontC['heddingFw'] : 'normal';  ?>;
    }
    #ccgclient-portal #portal-cotenier,
    #ccgclient-portal p,
    #ccgclient-portal strong,
    #ccgclient-portal b{
        color: <?php echo (isset($apfontC['bodycolor'])) ? $apfontC['bodycolor'] : 'rgb(90, 90, 90)';  ?>;
    }
    #ccgclient-portal{
        margin: 0 auto;
        min-width: 320px;
        width: 100%;
        max-width: <?php echo (isset($portalWidth) && $portalWidth !='') ? $portalWidth : '100%';  ?>;
    }
    /*#ccgclient-portal #ccp-nav-accordion li a{*/
    #ccgclient-portal #ccp-sidebar{     
        background: <?php echo (isset($apMenC['menuBgcolor'])) ? $apMenC['menuBgcolor'] : 'rgb(47, 85, 143)';  ?>;
        color: <?php echo (isset($apMenC['menufontcolor'])) ? $apMenC['menufontcolor'] : 'rgb(255, 255, 255)';  ?>;        
    }
    #ccgclient-portal #ccp-nav-accordion .ccp-dropdown-menu{
        background: <?php echo (isset($apMenC['menuBgcolor'])) ? $apMenC['menuBgcolor'] : 'rgb(47, 85, 143)';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li a{
        color: <?php echo (isset($apMenC['menufontcolor'])) ? $apMenC['menufontcolor'] : 'rgb(255, 255, 255)';  ?>; 
    }
    #ccgclient-portal #ccp-nav-accordion li a i{
        color: <?php echo (isset($apMenC['menufontcolor'])) ? $apMenC['menufontcolor'] : 'rgb(34, 139, 230)';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li a .ccgp-menu-i{
        fill: <?php echo (isset($apMenC['menufontcolor'])) ? $apMenC['menufontcolor'] : 'rgb(34, 139, 230)';  ?>;
    }

    #ccgclient-portal #ccp-nav-accordion li.active-menu a{
        background: <?php echo (isset($apMenC['menuAcBgcolor'])) ? $apMenC['menuAcBgcolor'] : 'rgb(255, 255, 255)';  ?>;
        color: <?php echo (isset($apMenC['menuacfontcolor'])) ? $apMenC['menuacfontcolor'] : 'rgb(47, 85, 143)';  ?>;
    }

    #ccgclient-portal .ccg-mobile-menu {
        background: <?php echo (isset($apMenC['menuactbgcolor'])) ? $apMenC['menuactbgcolor'] : 'rgb(47, 85, 143)';  ?>;
    }
        #ccgclient-portal #ccp-nav-accordion li.active-menu a i{
        color: <?php echo (isset($apMenC['menuacfontcolor'])) ? $apMenC['menuacfontcolor'] : 'rgb(255, 255, 255)';  ?>;
    }
    #ccgclient-portal #ccp-nav-accordion li.active-menu a .ccgp-menu-i{
        fill: <?php echo (isset($apMenC['menuacfontcolor'])) ? $apMenC['menuacfontcolor'] : 'rgb(255, 255, 255)';  ?>;
    }

    #ccgclient-portal #ccp-nav-accordion li a:focus,
    #ccgclient-portal #ccp-nav-accordion li a:active,
    #ccgclient-portal #ccp-nav-accordion li a:hover{
        background: <?php echo (isset($apMenC['menuhvBgcolor'])) ? $apMenC['menuhvBgcolor'] : 'rgb(255, 255, 255)';  ?>;
        color: <?php echo (isset($apMenC['menuhvfontcolor'])) ? $apMenC['menuhvfontcolor'] : 'rgb(47, 85, 143)';  ?>;
    }

    #ccgclient-portal #ccp-nav-accordion li .active-menu{
        background: <?php echo (isset($apMenC['menuhvBgcolor'])) ? $apMenC['menuhvBgcolor'] : 'rgb(255, 255, 255)';  ?>;
        color: <?php echo (isset($apMenC['menuhvfontcolor'])) ? $apMenC['menuhvfontcolor'] : 'rgb(2, 29, 68)';  ?>;
    }

     #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu .active-sub-menu{
        background: <?php echo (isset($apMenC['menuhvBgcolor'])) ? $apMenC['menuhvBgcolor'] : 'rgb(255, 255, 255)';  ?>;
        color: <?php echo (isset($apMenC['menuhvfontcolor'])) ? $apMenC['menuhvfontcolor'] : 'rgb(2, 29, 68)';  ?>;
    }


    #ccgclient-portal #ccp-nav-accordion li a:focus i,
    #ccgclient-portal #ccp-nav-accordion li a:active i,
    #ccgclient-portal #ccp-nav-accordion li a:hover i{
        color: <?php echo (isset($apMenC['menuhvfontcolor'])) ? $apMenC['menuhvfontcolor'] : 'rgb(2, 29, 68)';  ?>;    
    }
    #ccgclient-portal #ccp-nav-accordion li a:focus .ccgp-menu-i,
    #ccgclient-portal #ccp-nav-accordion li a:active .ccgp-menu-i,
    #ccgclient-portal #ccp-nav-accordion li a:hover .ccgp-menu-i{
        fill: <?php echo (isset($apMenC['menuhvfontcolor'])) ? $apMenC['menuhvfontcolor'] : 'rgb(255, 255, 255)';  ?>;    
    }

    #portal-cotenier .btn.btn-primary{
        <?php if(isset($primarycolor) && ($primarycolor !="")){ 
            echo 'background-color: '.$primarycolor.';'; 
        }else { ?>
            background-color: rgb(43, 156, 242);
        <?php } ?>
    }
    #ccgclient-portal .btn.addButton{
        background: <?php echo (isset($apbtnC['btnBgcolor'])) ? $apbtnC['btnBgcolor'] : 'rgb(43, 156, 242)';  ?>;
        color: <?php echo (isset($apbtnC['btnfontcolor'])) ? $apbtnC['btnfontcolor'] : 'rgb(255, 255, 255)';  ?>;
    }
    #ccgclient-portal .btn.editButton{
        background: <?php echo (isset($apbtnC['editBtnBgcolor'])) ? $apbtnC['editBtnBgcolor'] : 'transparent';  ?>;
        color: <?php echo (isset($apbtnC['editBtnfontcolor'])) ? $apbtnC['editBtnfontcolor'] : '#2B9CF2';  ?>;
    }
    #ccgclient-portal .btn.deleteButton{
        background: <?php echo (isset($apbtnC['deleteBtnBgcolor'])) ? $apbtnC['deleteBtnBgcolor'] : 'transparent';  ?>;
        color: <?php echo (isset($apbtnC['deleteBtnfontcolor'])) ? $apbtnC['deleteBtnfontcolor'] : '#2B9CF2';  ?>;
    }
    #ccgclient-portal .btn.saveButton{
        background: <?php echo (isset($apbtnC['suBtnBgcolor'])) ? $apbtnC['suBtnBgcolor'] : 'rgb(40, 120, 214)';  ?>;
        color: <?php echo (isset($apbtnC['suBtnfontcolor'])) ? $apbtnC['suBtnfontcolor'] : 'rgb(255, 255, 255)';  ?>;
    }
    #ccgclient-portal .btn.viewButton{
        background: <?php echo (isset($apbtnC['viewBtnBgcolor'])) ? $apbtnC['viewBtnBgcolor'] : 'transparent';  ?>;
        color: <?php echo (isset($apbtnC['viewBtnfontcolor'])) ? $apbtnC['viewBtnfontcolor'] : '#2B9CF2';  ?>;
    }

        
    /*#ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a{            
        background: <?php echo (isset($apMenC['menuBgcolor'])) ? $apMenC['menuBgcolor'] : 'rgb(2, 29, 68)';  ?>;
        color: <?php echo (isset($apMenC['menufontcolor'])) ? $apMenC['menufontcolor'] : 'rgb(255, 255, 255)';  ?>;
    }*/
    #ccgclient-portal #ccp-nav-accordion li.active-menu .billing-dropdown-menu a.active-sub-menu{
        color: <?php echo (isset($apMenC['menuAcBgcolor'])) ? $apMenC['menuAcBgcolor'] : 'rgb(34, 139, 230)';  ?>;
        /*background: <?php //echo (isset($apMenC['menuacfontcolor'])) ? $apMenC['menuacfontcolor'] : 'rgb(255, 255, 255)';  ?>;*/
    }
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a:focus,
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a:active,
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a:hover,
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a.active-sub-menu:focus,
    #ccgclient-portal #ccp-nav-accordion li .billing-dropdown-menu a.active-sub-menu:active,
    #ccgclient-portal #ccp-nav-accordion li.active-menu .billing-dropdown-menu a.active-sub-menu:hover{
        background: <?php echo (isset($apMenC['menuhvBgcolor'])) ? $apMenC['menuhvBgcolor'] : 'rgb(255, 255, 255)';  ?>;
        color: <?php echo (isset($apMenC['menuhvfontcolor'])) ? $apMenC['menuhvfontcolor'] : 'rgb(2, 29, 68)';  ?>;
    }

    #ccgclient-portal .ccp-top-bar{
        background-image: url(<?php echo CCGP_PLUGIN_URL; ?>assets/images/top-bar-bg-t.png); 
        <?php if(isset($primarycolor) && ($primarycolor !="")){ 
            echo 'background-color: '.$primarycolor.';'; 
        }else { ?>
            background-color: rgb(40, 113, 209);
        <?php } ?>
    }

    #ccgclient-portal .dashboard-half .dh-content::-webkit-scrollbar-thumb {
        background: <?php echo (isset($primarycolor) && ($primarycolor !="")) ? $primarycolor : "rgb(40, 113, 209)";?>;
    }

    #portal-cotenier table.table.table-hover thead tr th{

        <?php if(isset($primarycolor) && ($primarycolor !="")){ 
            echo 'color: '.$primarycolor.';'; 
        }else { ?>
            color: rgb(40, 113, 209); 
        <?php } ?>
    }
    #ccgclientportal-content .nav-tabs > li.active > a, 
    #ccgclientportal-content .nav-tabs > li.active > a:hover, 
    #ccgclientportal-content .nav-tabs > li.active > a:focus{
        color: <?php echo (isset($primarycolor) && ($primarycolor !="")) ? $primarycolor: "rgb(0, 145, 216)"; ?> ;
        border-bottom: 6px solid <?php echo (isset($primarycolor) && ($primarycolor !="")) ? $primarycolor: "rgb(0, 145, 216)"; ?>;
    }
    #ccgclientportal-content .nav-tabs > li > a:hover, 
    #ccgclientportal-content .nav-tabs > li > a:focus {
        color: <?php echo (isset($primarycolor) && ($primarycolor !="")) ? $primarycolor: "rgb(0, 145, 216)"; ?> ;
    }
    #ccgclientportal-content .fieldLabel{
        color: <?php echo (isset($primarycolor) && ($primarycolor !="")) ? $primarycolor: "#2871D1"; ?>;        
    }



 
</style>