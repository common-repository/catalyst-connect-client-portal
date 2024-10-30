
<style type="text/css">
	#ccgclientportal-content #portal-cotenier .webTabinput {
	    width: calc(50% - 31px);
	    float: left;
	    margin-right: 8px;
	}
	#ccgclientportal-content #portal-cotenier .tabCon {
	    margin-bottom: 8px;
	}
</style>
<form role="form" action="" id="updatewebtab" method="post" class="box-shadow" style="margin: 0px -15px;padding: 30px 15px">

    

    <input type="hidden" class="formid" value="updatewebtab">


	<h4 class="col-md-12">Web Tab</h4>
	<div class="clr"></div>
    <div class="form-group">
        <div class="col-md-8" id="tabListCon">                     
            <?php if(isset($webtab['content'])){
                $webtabV = json_decode($webtab['content'], true);
                if(isset($webtabV['link']) && $webtabV['link'] !=""){
                    $webtabl = json_decode($webtabV['link']);
                    $webtabt = json_decode($webtabV['title']);

                    foreach ($webtabl as $wt => $wtt) { 
                        if($webtabt[$wt] !=""){ ?>
                            <div class="tabCon">                         
                                <input type="text" name="webTabT[]" class="form-control webTabinput" value="<?php echo esc_attr(stripslashes($webtabt[$wt]));  ?>" >
                                <input type="text" name="webLink[]" class="form-control webTabinput" value="<?php echo esc_url($wtt);  ?>" >
                                <button type="button" class="btn btn-danger removeTab" ><i class="fas fa-minus"></i></button>
                                <div class="clr"></div>
                            </div>   
                            <div class="clr"></div>
                        <?php }
                    }
                }
            } ?>                    
            <div class="tabCon">                          
                <input type="text" name="webTabT[]" class="form-control webTabinput" placeholder="exe: Google">
                <input type="text" name="webLink[]" class="form-control webTabinput" placeholder="exe: https://google.com">
                <button type="button" id="addLink" class="btn btn-primary" ><i class="fas fa-plus"></i></button>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    
    <script type="text/javascript">
        jQuery(document).on('click', '.removeTab', function(event) {
            event.preventDefault();
            jQuery(this).closest('.tabCon').remove();
        });
        jQuery(document).on('click', '#addLink', function(event) {
            event.preventDefault();
            var html_ = '<div class="tabCon">\
                <input type="text" name="webTabT[]" class="form-control webTabinput" placeholder="exe: Google">\
                <input type="text" name="webLink[]" class="form-control webTabinput">\
                <button type="button" class="btn btn-danger removeTab" ><i class="fas fa-minus"></i></button>\
                <div class="clr"></div>\
            </div>';
            jQuery("#tabListCon").append(html_);
        });   
    </script>

    <div class="clr"></div>
    <hr>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="savewebtab" value="Update">
    </div>
    <div class="clr"></div>

</form>