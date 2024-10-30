jQuery(function () {
	'use strict';

	if(jQuery("select.ccg-basic-single").length){
		jQuery("select.ccg-basic-single").select2();
	}
	if(jQuery("select.ccg-basic-multiple").length){
		jQuery("select.ccg-basic-multiple").select2();
	}
});