<?php
    include_once 'common/left-menu.php';
	global $wp;
	$cppageUrl =  home_url( $wp->request );
	$pluginUrl =  WP_PLUGIN_URL . '/ccgclient-portal/';
    
?>

<div id="portal-cotenier">
    <div class="row">
        <div class="col-md-12" style="text-align: center;">
            <!-- <img src="<?php // echo $pluginUrl; ?>assets/images/404-page.jpg"> -->
            <img src="<?php echo plugins_url( 'assets/images/404-page.jpg', _FILE_ );?>">
        </div>
    </div>
</div>

<?php
    include_once 'common/footer.php';
?>