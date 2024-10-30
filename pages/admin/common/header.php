<div class="ccgclientportal-content" id="ccgclientportal-content">

<?php 
$pluginUrl = CCGP_PLUGIN_URL;
?>

<?php
if( version_compare( get_bloginfo('version'), '4.9', '<' ) ){
?>
<div class="alert alert-warning alert-dismissible" style="margin-top: 23px; width: 99% !important;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong class="text-warning">Warning!</strong> Your current Wordpress version is <?=esc_html(get_bloginfo('version'));?>, You need to upgrade it to 4.9 or newer to use the plugin properly.
</div>
<?php
}
?>

<?php
if( version_compare( phpversion(), '5.6', '<' ) ){
?>
<div class="alert alert-warning alert-dismissible" style="margin-top: 23px; width: 99% !important;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong class="text-warning">Warning!</strong> Your current PHP version is <?=esc_html(phpversion());?>, You need to upgrade it to 5.6 or newer to use the plugin properly.
</div>
<?php
}
?>


<script type="text/javascript">
	jQuery(document).ready( function () {
	    jQuery('.datatable').DataTable({
            "language": { search: "<i class='fa fa-search fortrun'></i>",
            paginate: {
                next: '<i class="fa fa-chevron-right ArrowTable"></i>',
                previous: '<i class="fa fa-chevron-left ArrowTable"></i>'
              }  }
        });
	} );
    jQuery(document).ready(function(){
        jQuery('[data-toggle="tooltip"]').tooltip();      
    });
</script>

<script>
    jQuery(function () {
    // Basic instantiation:
    jQuery('.colorpicker').colorpicker({
            customClass: 'colorpicker-2x',
            sliders: {
                saturation: {
                    maxLeft: 200,
                    maxTop: 200
                },
                hue: {
                    maxTop: 200
                },
                alpha: {
                    maxTop: 200
                }
            }
        });
      
        // Example using an event, to change the color of the .jumbotron background:

        jQuery(document).on('change', '.colorpicker', function(event) {
        	var colorN = jQuery(this).val();
            jQuery(this).css('background-color', colorN);
        });

        jQuery(document).on('click', '.showpass', function(event) {
            jQuery(this).closest('td').find('.showpass').hide();
            jQuery(this).closest('td').find('.encptpass').hide();
            jQuery(this).closest('td').find('.decrptpass').show();
            jQuery(this).closest('td').find('.hidepass').show();
        });
        jQuery(document).on('click', '.hidepass', function(event) {
            jQuery(this).closest('td').find('.hidepass').hide();
            jQuery(this).closest('td').find('.decrptpass').hide();
            jQuery(this).closest('td').find('.encptpass').show();
            jQuery(this).closest('td').find('.showpass').show();
        });
    });
</script>
<script type="text/javascript">
    jQuery(document).ready( function($) {
        jQuery( "ul.wp-submenu a[href='admin.php?page=privacy_policy']" ).attr( 'target', '_blank' );
        jQuery( "ul.wp-submenu a[href='admin.php?page=privacy_policy']" ).attr( 'href', 'https://catalystconnect.com/privacy-policy' );
        
        jQuery( "ul.wp-submenu a[href='admin.php?page=terms-of-service']" ).attr( 'target', '_blank' );
        jQuery( "ul.wp-submenu a[href='admin.php?page=terms-of-service']" ).attr( 'href', 'https://catalystconnect.com/terms-of-service/' );
        
    });
</script>

<style>
    .colorpicker-2x .colorpicker-saturation {
        width: 200px;
        height: 200px;
    }

    .colorpicker-2x .colorpicker-hue,
    .colorpicker-2x .colorpicker-alpha {
        width: 30px;
        height: 200px;
    }

    .colorpicker-2x .colorpicker-color,
    .colorpicker-2x .colorpicker-color div {
        height: 30px;
    }
    
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
</style>