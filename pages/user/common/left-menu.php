<?php
include_once('header.php');

$gnrlData = array();
$gnrlData = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'general-settings'");
foreach ($gnrlData as $gnrlOval) {
  if($gnrlOval->option_index == 'portal-main-menu') $prtlmm = $gnrlOval;
  if($gnrlOval->option_index == 'portalWidth')$portalWidth = $gnrlOval->option_value;
  if($gnrlOval->option_index == 'primarycolor')$primarycolor = $gnrlOval->option_value;
  if($gnrlOval->option_index == 'showAccMngr')$showAccMngr = $gnrlOval->option_value;
  if($gnrlOval->option_index == 'webtab')$webtab = json_decode($gnrlOval->option_value, true);
}

$mmp = 'Left';

if(isset($prtlmm->option_value) && $prtlmm->option_value == 'Left')$mmp = 'Left';
if(isset($prtlmm->option_value) && $prtlmm->option_value == 'Top')$mmp = 'Top';
?>

<?php if($mmp == 'Left'){ ?>
    <link rel="stylesheet" href="<?php echo CCGP_PLUGIN_URL; ?>assets/css/menu-left.css" type="text/css">    
<?php } ?>
<?php if($mmp == 'Top'){ ?>
    <link rel="stylesheet" href="<?php echo CCGP_PLUGIN_URL; ?>assets/css/menu-top.css" type="text/css">

    <style type="text/css">
        
    #ccgclient-portal .ccp-top-bar{
        background-image: unset !important; 
        background-color: #fff !important; 
        
    }
    .acc-IMG{
      cursor: pointer;
    }



    </style>    
<?php } ?>

<?php include_once CCGP_PLUGIN_PATH.'/assets/css/style.php'; ?>

<script type="text/javascript">
    function showPortal() {        
        jQuery("#ccgclient-portal").show();
    }
    setTimeout( "showPortal()", 200);
</script>

<?php
global $wp;
$cppageUrl = home_url( $wp->request );
$cpageac   = "";
if(isset($_GET['ppage']))   $cpage   = $_GET['ppage'];
if(isset($_GET['action'])) $cpageac = $_GET['action'];

$tabOrder = array();
$ordrres  = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'customization' AND option_index = 'taborder'");
if(isset($ordrres->option_value)) $tabOrder = json_decode($ordrres->option_value, true);


?>

<div id="ccgclient-portal" style="display: none;">
    <div class="col-md-12">
        <div class="ccg-mobile-menu col-md-12">
            Menu
            <button class="ccgportal-navbar-toggler" type="button">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    <div id="ccp-sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="ccp-nav-accordion">
        <?php if(isset($showAccMngr)){
            $profilepicpath = CCGP_PLUGIN_PATH.'/pages/user/attachments/profilepic/'.$accId.'/profile.png';
            $profilepicUrl = CCGP_PLUGIN_URL.'pages/user/attachments/profilepic/'.$accId.'/profile.png';
        ?>            
            <li class="account-manager">

                <!-- <form class="form-pic" action="" method="post" enctype="multipart/form-data">
                   <input type="hidden" name="accids" value="<?php echo $accId ?>">
                  <input type="file" name="profileimgs" placeholder=""  class="form-control imgupload" value="image" style=" height: 50px!important;display: none;">
                </form> -->

                <?php if (file_exists($profilepicpath)) { ?>
                    <img class="profile-image-ac acc-IMG" src="<?php echo $profilepicUrl; ?>" style="cursor: pointer;">
                <?php }else{ ?>
                    <img class="profile-image-ac acc-IMG" src="<?php echo CCGP_PLUGIN_URL; ?>assets/images/profile-image-m.png" style="cursor: pointer;">
                <?php }?>

                   <!-- <i class="fa fa-camera" id="camraa" style="position: absolute;margin-left: 100px;color: #c2e9fd;cursor: pointer;margin-top: -120px;"></i> -->

                <span class="account-name"><?php 
                
                	if(isset($_SESSION["ccgcp_loged"]['AccMngr'])) {
		                $AccMngr = $_SESSION["ccgcp_loged"]['AccMngr']; 
		                echo $AccMngr['first_name'].' '.$AccMngr['last_name']; 

		            }
                ?></span></br>
                <span>Account Manager</span>
            </li>   
        <?php } ?>

            <li class="<?php if($cpage == 'dashboard' || $cpage ==""){echo 'active-menu';$menuname = "Home";} ?> dashboard">
                <a class=" active" href="<?php echo esc_url($cppageUrl); ?>?ppage=dashboard<?php echo $page_id; ?>">
                    <img src="<?php echo CCGP_PLUGIN_URL;?>assets/images/nicon/portal-frontend-icons-21.png" class="iconLeftmenu">

                    <span>Home</span>
                </a>
            </li>

    <?php      
    $spProfile = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'sp_zcrm_Accounts'");
    if(isset($spProfile->option_value)) $spProfileV = json_decode($spProfile->option_value, true); else $spProfileV = array();
    if(isset($spProfileV['showMenu'])){ ?>

        <li class="<?php if($cpage == 'profile' || $cpage == 'profile-edit'){echo 'active-menu'; $menuname = "My Account";} ?>">
            <a href="<?php echo $cppageUrl; ?>?ppage=profile<?php echo $page_id; ?>">
                <img src="<?php echo CCGP_PLUGIN_URL ?>assets/images/nicon/portal-frontend-icons-23.png" class="iconLeftmenu">
               
                <span><?php echo (isset($spProfileV['menuLabel']) && $spProfileV['menuLabel'] !="") ? stripslashes($spProfileV['menuLabel']) : "My Account" ?></span>
            </a>
        </li>
    <?php } ?>

    <?php
    $useMdlV = array();
    $useModules = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index = 'useModules'");
    if(isset($useModules->option_value)) $useMdlV = json_decode($useModules->option_value, true);
    
    $crmModuleMenu = array();
    foreach ($useMdlV as $umkey => $useMdlDtl) {  

        $spMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'sp_$umkey'");
        if(isset($spMdl->option_value)) $spMdlV = json_decode($spMdl->option_value, true); else $spMdlV = array();

        if(isset($_GET['module'])) $cmodule = $_GET['module']; else $cmodule ="";
        $module = str_replace("zcrm_", "", $umkey);
        if(isset($spMdlV['showMenu'])){

            $tbac = "" ;
            if($cmodule == $module){
              $tbac = "active-menu"; 
              $menuname = stripslashes($spMdlV["menuLabel"]);
            }

            // $crmModuleMenu[$umkey] = '<li class="'.$tbac.'">
            //     <a href="'.$cppageUrl.'?ppage=list&module='.$module.'&origin=CRM'.$page_id.'">
            //         <svg id="list_1_" data-name="list (1)" xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
            //           <g id="Group_4" data-name="Group 4">
            //             <g id="Group_3" data-name="Group 3">
            //               <path id="Path_3" data-name="Path 3" d="M448,0H64A64.079,64.079,0,0,0,0,64V448a64.079,64.079,0,0,0,64,64H448a64.079,64.079,0,0,0,64-64V64A64.079,64.079,0,0,0,448,0Zm21.333,448A21.355,21.355,0,0,1,448,469.333H64A21.354,21.354,0,0,1,42.667,448V64A21.368,21.368,0,0,1,64,42.667H448A21.368,21.368,0,0,1,469.333,64Z" class="ccgp-menu-i"/>
            //             </g>
            //           </g>
            //           <g id="Group_6" data-name="Group 6">
            //             <g id="Group_5" data-name="Group 5">
            //               <path id="Path_4" data-name="Path 4" d="M147.627,119.893a22.028,22.028,0,0,0-4.48-7.04l-3.2-2.56a16.179,16.179,0,0,0-3.84-1.92,13.549,13.549,0,0,0-3.84-1.28,20.4,20.4,0,0,0-12.373,1.28,24.637,24.637,0,0,0-7.04,4.48,22.028,22.028,0,0,0-4.48,7.04,20.1,20.1,0,0,0,0,16.214,22.028,22.028,0,0,0,4.48,7.04A22.433,22.433,0,0,0,128,149.334a31.97,31.97,0,0,0,4.267-.427,13.549,13.549,0,0,0,3.84-1.28,16.179,16.179,0,0,0,3.84-1.92l3.2-2.56A22.433,22.433,0,0,0,149.335,128,21.29,21.29,0,0,0,147.627,119.893Z" class="ccgp-menu-i"/>
            //             </g>
            //           </g>
            //           <g id="Group_8" data-name="Group 8">
            //             <g id="Group_7" data-name="Group 7">
            //               <path id="Path_5" data-name="Path 5" d="M384,106.667H213.333a21.333,21.333,0,0,0,0,42.666H384a21.333,21.333,0,1,0,0-42.666Z" class="ccgp-menu-i"/>
            //             </g>
            //           </g>
            //           <g id="Group_10" data-name="Group 10">
            //             <g id="Group_9" data-name="Group 9">
            //               <path id="Path_6" data-name="Path 6" d="M148.907,251.733a13.549,13.549,0,0,0-1.28-3.84,16.179,16.179,0,0,0-1.92-3.84l-2.56-3.2a24.637,24.637,0,0,0-7.04-4.48,21.339,21.339,0,0,0-16.213,0,24.637,24.637,0,0,0-7.04,4.48l-2.56,3.2a16.178,16.178,0,0,0-1.92,3.84,13.549,13.549,0,0,0-1.28,3.84,32.121,32.121,0,0,0-.427,4.267A21.127,21.127,0,0,0,128,277.334,21.127,21.127,0,0,0,149.335,256,32.49,32.49,0,0,0,148.907,251.733Z" class="ccgp-menu-i"/>
            //             </g>
            //           </g>
            //           <g id="Group_12" data-name="Group 12">
            //             <g id="Group_11" data-name="Group 11">
            //               <path id="Path_7" data-name="Path 7" d="M384,234.667H213.333a21.333,21.333,0,0,0,0,42.666H384a21.333,21.333,0,1,0,0-42.666Z" class="ccgp-menu-i"/>
            //             </g>
            //           </g>
            //           <g id="Group_14" data-name="Group 14">
            //             <g id="Group_13" data-name="Group 13">
            //               <path id="Path_8" data-name="Path 8" d="M147.627,375.893a20.658,20.658,0,0,0-27.733-11.52,24.636,24.636,0,0,0-7.04,4.48,24.637,24.637,0,0,0-4.48,7.04A21.31,21.31,0,1,0,149.335,384,17.918,17.918,0,0,0,147.627,375.893Z" class="ccgp-menu-i"/>
            //             </g>
            //           </g>
            //           <g id="Group_16" data-name="Group 16">
            //             <g id="Group_15" data-name="Group 15">
            //               <path id="Path_9" data-name="Path 9" d="M384,362.667H213.333a21.333,21.333,0,0,0,0,42.666H384a21.333,21.333,0,0,0,0-42.666Z" class="ccgp-menu-i"/>
            //             </g>
            //           </g>
            //         </svg>

            //         <span>'.stripslashes($spMdlV["menuLabel"]).'</span>
            //     </a>
            // </li>'; 
            $crmModuleMenu[$umkey] = '<li class="'.$tbac.'">
                <a href="'.$cppageUrl.'?ppage=list&module='.$module.'&origin=CRM'.$page_id.'">';

              if($module == 'Contacts'){
                 $crmModuleMenu[$umkey] .= '<img src="'. CCGP_PLUGIN_URL .'assets/images/nicon/portal-frontend-icons-35.png" class="iconLeftmenu">';
              }
              else if ($module == 'Deals'){
                 $crmModuleMenu[$umkey] .= '<img src="'. CCGP_PLUGIN_URL .'assets/images/nicon/portal-frontend-icons-33.png" class="iconLeftmenu">';
              }
              else {
                  $crmModuleMenu[$umkey] .= '<img src="'. CCGP_PLUGIN_URL .'assets/images/nicon/portal-frontend-icons-31.png" class="iconLeftmenu">';
              }

                $crmModuleMenu[$umkey] .= ' <span>'.stripslashes($spMdlV["menuLabel"]).'</span>
                </a>
            </li>'; 
        }  
    }

    $intgrationMenu = array();

    $wtMenu = array();
    if(isset($webtab['content'])){
      $webtabV = json_decode($webtab['content'], true);
      if(isset($webtabV['link']) && $webtabV['link'] !=""){
          $webtabl = ($webtabV['link'] == null)?json_decode($webtabV['link']):[];
          $webtabt = ($webtabV['title'] == null)?json_decode($webtabV['title']):[];

          if(!empty($webtabl)){
            foreach ($webtabl as $wt => $wtt) { 
                if($webtabt[$wt] !=""){
                    $wtTitle = stripslashes($webtabt[$wt]);
  
                    $mactive = "";
                    if(isset($_GET['url']) && ($webtabl[$wt] == $_GET['url']) && ($cpage == 'webtab')){ 
                      $mactive = "active-menu"; 
                      $menuname = $wtTitle;
                    }
  
                  $wtMenu["wt_".$wtTitle] = '
                    <li class="nav-item '.$mactive.'">
                      <a href="'.$cppageUrl.'?ppage=webtab&wtid='.$wt.'&url='.urlencode($webtabl[$wt]).$page_id.'">
                        <img src="'. CCGP_PLUGIN_URL .'assets/images/nicon/portal-frontend-icons-34.png" class="iconLeftmenu">
                        <span>'.$wtTitle.'</span>
                      </a>
                    </li>';
                }
            }
          }
        }
    }

    ?>


    <?php if($tabOrder != null){

        foreach ($tabOrder as $ordr_menu) {
          $ordr_menu = stripslashes($ordr_menu);
          ?>
            <?php 
                if(isset($crmModuleMenu[$ordr_menu])) {
                    echo $crmModuleMenu[$ordr_menu];
                    unset($crmModuleMenu[$ordr_menu]);
                }
            ?>

            <?php             
              if(isset($wtMenu[$ordr_menu])) {
                echo $wtMenu[$ordr_menu];
                unset($wtMenu[$ordr_menu]);
              }
            ?>

        <?php } ?>

        <?php foreach ($crmModuleMenu as $unordr_menu) {
            echo $unordr_menu;
        } ?>
        <?php foreach ($intgrationMenu as $unordri_menu) {
            echo $unordri_menu;
        } ?>

        <?php foreach ($wtMenu as $wt_menu) {
            echo $wt_menu;
        } ?>

    <?php     
    }else{ ?>
    
        <?php foreach ($crmModuleMenu as $ordr_menu) {
            echo $ordr_menu;
        } ?>


        <?php foreach ($intgrationMenu as $unordri_menu) {
            echo $unordri_menu;
        } ?>

        <?php foreach ($wtMenu as $wt_menu) {
          echo $wt_menu;
        } ?>


    <?php } ?>

    <?php 
    $crmid    = $_SESSION["ccgcp_loged"]['id'];
    $contacts = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE status = 'active' AND crmid = $crmid ORDER BY id DESC");

    if(isset($contacts->id)){
	    $utid = $contacts->id;
	    $pqlink = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'userdata-ql' AND option_index = $utid");
	    
	    if(isset($pqlink->option_value)){
	    	$pqlinkV = json_decode($pqlink->option_value, true);
            $pqlinkL = json_decode($pqlinkV['link']);
            $pqlinkT = json_decode($pqlinkV['title']);  

		    if(count($pqlinkT) > 0){
		    	foreach ($pqlinkT as $lk => $pqlt) {
		    		if($pqlt !=""){
                $mactive = false;
                if(isset($_GET['url']) && ($pqlinkL[$lk] == $_GET['url']) && ($cpage == 'pqlink')) $mactive = true; 
                ?>
				        <li class="<?php if($mactive){ echo 'active-menu'; $menuname = stripslashes($pqlt);} ?>">
				            <a href="<?php echo $cppageUrl; ?>?ppage=pqlink&url=<?php echo $pqlinkL[$lk] ?><?php echo $page_id; ?>">
				                <!-- <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 384 384" style="enable-background:new 0 0 384 384;" xml:space="preserve">
                              <g>
                                <rect x="213.333" y="0" width="170.667" height="128" class="ccgp-menu-i"/>
                                <rect x="0" y="0" width="170.667" height="213.333" class="ccgp-menu-i"/>
                                <rect x="0" y="256" width="170.667" height="128" class="ccgp-menu-i"/>
                                <rect x="213.333" y="170.667" width="170.667" height="213.333" class="ccgp-menu-i"/>
                            </g>
                          </svg> -->
                          <img src="<?php echo CCGP_PLUGIN_URL ?>assets/images/nicon/portal-frontend-icons-34.png" class="iconLeftmenu">
                        
				                <span><?php echo stripslashes($pqlt) ?></span>
				            </a>
				        </li>
				    <?php }
				}
			}
		}
    } ?>

    </ul>
        <!-- sidebar menu end-->
  </div>
  
  <div class="bodyContent">
    <div class="ccp-top-bar" style="margin-left: 0px;border-radius: 0px;">      

        <div class="top-right f-r">
            <ul class="top-right-menu">  

                <?php 
                if(isset($spProfileV['showMenu'])){ ?>
                    <li class="<?php if($cpage == 'profile' || $cpage == 'profile-edit')echo 'active-menu'; ?>">
                        <a href="<?php echo $cppageUrl; ?>?ppage=profile<?php echo $page_id; ?>">
                            <!-- <span style="margin-right: 8px;float: left;"><?php //echo (isset($spProfileV['menuLabel']) && $spProfileV['menuLabel'] !="") ? $spProfileV['menuLabel'] : "Profile" ?></span> -->
                            <img class="iconLeftmenu" src="<?php echo CCGP_PLUGIN_URL;?>assets/images/nicon/portal-frontend-icons-22.png" >
                            <div class="clr"></div>
                        </a>
                    </li>
                <?php } ?>

                <li class="logout">
                    <a href="<?php echo $cppageUrl; ?>?ppage=logout<?php echo $page_id; ?>">
                        <img class="iconLeftmenu" src="<?php echo CCGP_PLUGIN_URL;?>assets/images/nicon/portal-frontend-icons-36.png" >
                        <div class="clr"></div>
                    </a>
                </li>
                <div class="clr"></div>
            </ul>
            <div class="clr"></div>
        </div>
        <!-- top-right -->

        <div class="top-Left">

            <?php if(isset($_GET['ppage']) && $_GET['ppage'] == 'profile') {
              $menuHead = 'Account';
            } ?>

          

        <?php if(isset($menuname) && $menuname !='Home') {
          if(isset($_SERVER['HTTP_REFERER'])){
            $_SESSION['ccgcp_loged']['prev_url'] = $_SERVER['HTTP_REFERER'];
          }
          if(isset($_SESSION['ccgcp_loged']['prev_url'])) $prev_url = $_SESSION['ccgcp_loged']['prev_url'];
          else $prev_url = "#";

          ?>
            <a href="<?php echo $prev_url; ?>">
              <i class="fa fa-arrow-left faArrow" style="color: #2f558f;font-size: 16px;transform: scale(1.6,1.5);"></i>            
              <span class="headTitle"><?php if(isset($menuname)) echo $menuname ?></span>
            </a>
          <?php } else {?>
            <span class="headTitle" style="padding-left:0px;"><?php if(isset($menuname)) echo $menuname ?></span> 

          <?php } ?>
          
        </div>

    </div>
    <div class="clr"></div>

<script type="text/javascript">

    jQuery('.acc-IMG,#camraa').click(function(){ 
        
        jQuery('.imgupload').trigger('click'); 

      });

      jQuery(".imgupload").change(function(){
          // readURL(this);
          jQuery('.form-pic').submit();
      });


    jQuery(document).on('click', '.billing-toggle.show-dropdown', function(event) {
        event.preventDefault();
        jQuery(this).addClass('hide-dropdown');
        jQuery(this).removeClass('show-dropdown');
        jQuery(".billing-dropdown-menu").fadeIn().animate();
    });      
    jQuery(document).on('click', '.billing-toggle.hide-dropdown', function(event) {
        event.preventDefault();
        jQuery(this).addClass('show-dropdown');
        jQuery(this).removeClass('hide-dropdown');
        jQuery(".billing-dropdown-menu").hide();
    });      
</script>
<script type="text/javascript">
    jQuery(document).on('click', '.ccgportal-navbar-toggler', function(event) {
        event.preventDefault();
        jQuery('#ccp-sidebar').slideToggle(  );
    });       
</script>