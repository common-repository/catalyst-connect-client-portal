<?php
    include_once 'common/left-menu.php';
    $linkUrl = (isset($_GET['url'])) ? $_GET['url'] : "";

?>

<style type="text/css">
    .quicklink-page iframe{
        min-height: 600px;
        width: 100%;
        border: solid 1px #ccc;
        margin-top: 10px;
        margin: 0px;
    }
</style>
<div id="portal-cotenier">
    <div class="col-md-12">
		<div class="quicklink-page page-body">              
            <iframe sandbox="allow-same-origin allow-scripts allow-forms" class="webiframe" src="<?php echo esc_url($linkUrl); ?>"></iframe>            
        </div>  
    </div>  

</div>

<script type="text/javascript">

    function setIframeHeight() {
        var leftmenuH = jQuery('#ccgclient-portal #ccp-nav-accordion').height();
        jQuery('iframe.webiframe').css({
            height: (leftmenuH - 150)
        });;
        console.log(leftmenuH);
    }

    setTimeout( "setIframeHeight()", 300);

</script>


<?php
    include_once 'common/footer.php';
?>