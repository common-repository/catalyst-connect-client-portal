<?php
include_once 'common/left-menu.php';

global $wpdb;
global $wp;
$pluginUrl = CCGP_PLUGIN_URL;
$cppageUrl =  home_url( $wp->request );
$dsIfr = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'dashboard' AND option_index = 'dashboardiframe'");

$dsbOptions = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'dashboard'");
foreach ($dsbOptions as $dbOval) {
  if($dbOval->option_index == 'sliderimages')$dbBnr = json_decode($dbOval->option_value, true);
  if($dbOval->option_index == 'aboutUstext')$dbOat = json_decode($dbOval->option_value, true);
  if($dbOval->option_index == 'dashboardiframe')$dsIfr = json_decode($dbOval->option_value, true);
  if($dbOval->option_index == 'quickLink')$dbOql = json_decode($dbOval->option_value, true);
  if($dbOval->option_index == 'iframeembed')$dsbifem = json_decode($dbOval->option_value, true);
  if($dbOval->option_index == 'color')$dbColor = json_decode($dbOval->option_value, true);
  if($dbOval->option_index == 'Layout_dashboardContent')$dbcLayout = json_decode($dbOval->option_value, true);
}
$conDtl = $_SESSION["ccgcp_loged"];

$crmid = $_SESSION["ccgcp_loged"]['id'];
$contacts = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE status = 'active' AND crmid = $crmid ORDER BY id DESC");

$account_manager_permissionJs = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'dashboard' AND option_index = 'account_manager_permission' ORDER BY id DESC");
if(isset($account_manager_permissionJs->option_value)){
    $account_manager_permission = json_decode($account_manager_permissionJs->option_value);
}

$conInfo = $_SESSION["ccgcp_loged"];
$accDtl = array();
$AccMngr = array();
if(isset($_SESSION["ccgcp_loged"]["accDtl"])) $accDtl = $_SESSION["ccgcp_loged"]["accDtl"];
if(isset($_SESSION["ccgcp_loged"]['AccMngr'])) $AccMngr = $_SESSION["ccgcp_loged"]['AccMngr'];

?>

<style type="text/css">
    #ccgclient-portal .dashboard-half .dh-header{
        background:<?php echo (isset($dbColor['headbckcolor']) && $dbColor['headbckcolor']!="") ? $dbColor['headbckcolor'] : '#2871d1';  ?>;
        color:<?php echo (isset($dbColor['headfontcolor']) && $dbColor['headfontcolor']!="") ? $dbColor['headfontcolor'] : '#FFFF';  ?>
    }

    #ccgclient-portal .dashboard-half .dh-header .icon{
        color:<?php echo (isset($dbColor['headfontcolor']) && $dbColor['headfontcolor']!="") ? $dbColor['headfontcolor'] : '#FFFF';  ?>
    }

    #ccgclient-portal #portal-cotenier, #ccgclient-portal p, #ccgclient-portal strong, #ccgclient-portal b{
        color: #000000;
    }
</style>

<div id="portal-cotenier" style="padding-top: 20px;">
    <div class="dashboard-page ">

        <?php
        $dbcLayoutC = array();
        $dbcLayoutC['section'] = array('dashboardbanner','accountmanager','aboutus','quicklink','videolink','iframeembed');
        $dbcLayoutC['layoutFldColumn'] = array('12','6','6','6','6','12');
        if(!isset($dbcLayout['section'])){
            $dbcLayout = $dbcLayoutC;
            $dbcLayout = array_merge($dbcLayoutC);
        }

        $dbcLayoutN['section'] = array_unique( array_merge($dbcLayout['section'], $dbcLayoutC['section']));
        $dbcLayoutN['layoutFldColumn'] =$dbcLayout['layoutFldColumn'];
        ?>

        <?php foreach ($dbcLayoutN['section'] as $key => $dcs) {
            if(isset($dbcLayoutN['layoutFldColumn'][$key])){
                $columnSize = $dbcLayoutN['layoutFldColumn'][$key];
            }else $columnSize = "12";
            ?>

            <?php if($dcs == 'dashboardbanner'){ ?>
                <?php if(isset($dbBnr['permission']) && ($dbBnr['permission'] == 'on')){ ?>
                    <div class="col-md-<?php echo $columnSize; ?>" style="    margin-bottom: 0px;">
                        <div class="dashboard-banner">

                            <?php if(isset($dbBnr['content'])){
                                $dbIsiV = json_decode($dbBnr['content'], true);
                                foreach ($dbIsiV as $slim) { if($slim !='')$bannerImg = $slim; } 
                            }
                            if(isset($bannerImg))$bannerImgUrl = $bannerImg;
                            else $bannerImgUrl = $pluginUrl.'assets/images/banner-2.png';
                            ?>

                            <img src="<?php echo $bannerImgUrl; ?>" alt="Banner">
                        </div>
                    </div>
                    <div class="clr"></div> 
                <?php } ?>
            <?php } ?>

            <?php if($dcs == 'accountmanager'){ ?>

                <?php if(isset($account_manager_permission->permission)){ ?>
                    <div class="col-md-<?php echo $columnSize; ?>">
                        <div class="dashboard-half ">
                            <div class="dh-header">
                                <i class="fas fa-address-card icon"></i>
                                <font>Account Manager</font>
                            </div>
                            <div class="clr"></div>

                            <div class="dh-content">
                                <span>
                                    <strong>Name :</strong> &nbsp; <?php if( isset($AccMngr['full_name']))echo esc_html($AccMngr['full_name']); ?>
                                </span>
                                <span>
                                    <strong>Phone :</strong> &nbsp; <?php if(isset($AccMngr['phone']))echo esc_html($AccMngr['phone']); ?>
                                </span>
                                <span>
                                    <strong>Email :</strong> &nbsp; <?php if(isset($AccMngr['email']))echo esc_html($AccMngr['email']); ?>
                                </span>
                                <div class="clr"></div>
                            </div>

                            <div class="clr"></div>
                        </div>

                        <div class="clr"></div>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if($dcs == 'quicklink'){ ?>
                <?php if(isset($dbOql['permission']) && ($dbOql['permission'] == 'on')){ ?>
                    <div class="col-md-<?php echo $columnSize; ?>">
                        <div class="dashboard-half Quick-Link">
                            <div class="dh-header">
                                <i class="fas fa-external-link-alt icon"></i>
                                <font><?php echo (isset($dbOql['label']) && $dbOql['label']!="") ? stripslashes($dbOql['label']) : 'Quick Link';  ?></font>
                            </div>
                            <div class="clr"></div>

                            <div class="dh-content">
                             
                                <?php if(isset($dbOql['content'])){
                                    $dbOpqlV = json_decode($dbOql['content'], true);
                                    if(isset($dbOpqlV['link']) && $dbOpqlV['link'] !=""){
                                        $dbOpqlt = json_decode($dbOpqlV['title']);
                                        $dbOpqll = json_decode($dbOpqlV['link']);
                                        if(isset($dbOpqlV['linkaf']))$linkaf = json_decode($dbOpqlV['linkaf']);

                                        foreach ($dbOpqll as $lk => $qlLst) { 
                                            $aclinkurl = "";
                                            if(isset($linkaf[$lk])){
                                                $linkafarr = explode("___", $linkaf[$lk]);
                                                if(isset($linkafarr[1])){
                                                    $qpAcfld = $linkafarr[0];
                                                    $aclinkurl = (isset($accDtl[$qpAcfld])) ? $accDtl[$qpAcfld] : "";
                                                }
                                            }
                                            if($qlLst !=""){ ?>
                                                <span>
                                                    <a target="_blank" href="<?php echo $qlLst;  ?>"><?php echo esc_url($dbOpqlt[$lk]);  ?></a>
                                                </span>
                                            <?php }

                                            elseif($aclinkurl !=""){ ?>
                                                <span>
                                                    <a target="_blank" href="<?php echo $aclinkurl;  ?>"><?php echo esc_url($dbOpqlt[$lk]);  ?></a>
                                                </span>
                                            <?php }
                                        }
                                    }
                                } 
                                ?>         
                                <div class="clr"></div>
                            </div>

                            <div class="clr"></div>
                        </div>

                        <div class="clr"></div>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if($dcs == 'aboutus'){ ?>
                <?php if(isset($dbOat['permission']) && ($dbOat['permission'] == 'on')){ ?>
                    <div class="col-md-<?php echo $columnSize; ?>">
                        <div class="dashboard-half">
                            <div class="dh-header">
                                <i class="fas fa-info-circle icon"></i>
                                <font><?php echo (isset($dbOat['label'])) ? esc_attr(stripslashes($dbOat['label'])) : 'About Us';  ?></font>
                            </div>
                            <div class="clr"></div>

                            <div class="dh-content">
                                <span style="border:none;padding-bottom: 0px;"><?php echo (isset($dbOat['content'])) ? esc_attr(stripslashes($dbOat['content'])) : '';  ?></span>
                            </div>

                            <div class="clr"></div>
                        </div>

                        <div class="clr"></div>
                    </div>
                <?php } ?>
            <?php } ?>

            <?php if($dcs == 'videolink'){ ?>
                <?php if(isset($dsIfr['permission']) && ($dsIfr['permission'] == 'on')){ ?>
                    <div class="col-md-<?php echo $columnSize; ?>">
                        <div class="dashboard-half video-sec <?php echo ($columnSize == '12') ? 'col12' : ''; ?>">
                            <div class="dh-header">
                                <i class="fas fa-play-circle icon"></i>
                                <font><?php echo (isset($dsIfr['label']) && $dsIfr['label']!="") ? esc_attr(stripslashes($dsIfr['label'])) : 'Video';  ?></font>
                            </div>
                            <div class="clr"></div>

                            <div class="dh-content">
                                <?php if(isset($dsIfr['content']) && $dsIfr['content'] !=""){ ?>
                                    <iframe src="<?php echo esc_url(str_replace("/watch?v=", "/embed/", $dsIfr['content'])); ?>"></iframe>
                                <?php } ?>
                            </div>

                            <div class="clr"></div>
                        </div>

                        <div class="clr"></div>
                    </div>            
                <?php } ?>
            <?php } ?>

            <?php if($dcs == 'iframeembed'){ ?>

                <?php if(isset($dsbifem['permission']) && ($dsbifem['permission'] == 'on')){ ?>

                    <?php if(isset($dsbifem['content'])) $dsbifemc = json_decode($dsbifem['content'], true);
                    $ifeurl = (isset($dsbifemc['url']) && $dsbifemc['url'] !="") ? $dsbifemc['url'] : '';
                    if(($ifeurl == "") && (isset($dsbifemc['AcFld']))){
                        $ifembAcfArr = explode("___", $dsbifemc['AcFld']);
                        if(isset($ifembAcfArr[1])){
                            $accUrlFld = $ifembAcfArr[0];
                            $ifeurl = (isset($accDtl[$accUrlFld])) ? $accDtl[$accUrlFld] : "";
                        }
                    }

                    if($ifeurl !=""){ ?>
                        <div class="col-md-<?php echo $columnSize; ?>" >
                            <div class="dashboard-half ifem-sec <?php echo ($columnSize == '12') ? 'col12' : ''; ?>">
                                <div class="dh-header">
                                    <i class="fas fa-play-circle icon"></i>
                                    <font><?php echo (isset($dsbifem['label']) && $dsIfr['label']!="") ? esc_attr(stripslashes($dsbifem['label'])) : 'Video';  ?></font>
                                </div>
                                <div class="clr"></div>

                                <div class="dh-content">
                                    <iframe sandbox="allow-same-origin allow-scripts allow-forms" src='<?php echo esc_url($ifeurl); ?>'></iframe>
                                </div>

                                <div class="clr"></div>
                            </div>

                            <div class="clr"></div>
                        </div>   
                    <?php } ?>         
                <?php } ?>
            <?php } ?>

        <?php } ?>
        <div class="clr"></div>
    </div>
    

    <div class="clr"></div>
</div>

<?php
include_once 'common/footer.php';
?>