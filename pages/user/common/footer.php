		<div class="clr"></div>
	</div>  <!-- bodyContent -->
    <div class="clr"></div>
</div><!-- ccgclient-portal -->

<div class="clr"></div>
<script type="text/javascript">
	jQuery(document).ready( function () {
	    jQuery('.datatable').DataTable({
	    	// "order": [],
	    	// "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
	    	// "pageLength": -1,
	    	// "oLanguage": {
		    //     "sEmptyTable": "No records found"
		    // },
		    "language": { 
		    	search: "<i class='fa fa-search fortrun'></i>",
            	paginate: {
	                next: '<i class="fa fa-chevron-right ArrowTable"></i>',
	                previous: '<i class="fa fa-chevron-left ArrowTable"></i>'
              	}  
          	}
	    });

	    jQuery(document).on('focus', 'textarea', function(event) {
	    	event.preventDefault();
	    	/* Act on the event */
	    	jQuery(this).addClass('activetextarea');
	    });
	} );

	function setbodyContentHeight() {
        var leftmenuH = jQuery('#ccgclient-portal #ccp-nav-accordion').height();
        if(leftmenuH > 300){
	        jQuery('#ccgclientportal-content .bodyContent').css("min-height", (leftmenuH + 43));
	    }
    }
    setTimeout( "setbodyContentHeight()", 200);
</script>

</div><!-- ccgclientportal-content -->
