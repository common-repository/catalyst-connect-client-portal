

<div class="ibox-content ccgportal-login login-form" style="display:<?php echo ($acform == "login")?'block':'none';?>;border-radius: 10px;">

<!-- <div class="row">

    <div class="col-md-12">
        <a href="javascript:void(0)" style="color: #338dd0;text-decoration: unset;"><p style="font-size: 14px;padding: 2px;">Have an Account ?</p></a>
    </div>
    
</div> -->
<!-- <h3>Welcome to <?php echo stripslashes($portalTitle); ?></h3> -->
<h4 style="font-size: 25px; font-weight: 700; padding-bottom: 20px; padding-left: 15px; line-height: 27px;">LOGIN</h3>
<form class="m-t" role="form" action="" method="post" style="color: #000;padding-top: 0px;">

    <?php 
        wp_nonce_field('atmpt-login','csrf_token_nonce');
    ?>

    <?php if(isset($_SESSION["ccgcp_login_error"])){ ?>
        <p style="color: red;text-align: center;"><?php echo $_SESSION["ccgcp_login_error"]; ?></p>
    <?php } ?>

    <div class="form-group col-md-12">

        <div class="loginIntput">
            <label class="labelstyle">Email</label>
            <input id="username" placeholder="user@domain.com" name="username" value="<?php echo (isset($_POST['atmpt-login'])? esc_attr(sanitize_text_field($_POST['username'])):"") ?>" autocomplete="off" required="" type="text">
        </div>
        
    </div>
    <div class="form-group col-md-12">
         <div class="loginIntput">
            <label class="labelstyle">Password</label>
            <div class="clr"></div>
            <input id="password" placeholder="************" name="password" required="" autocomplete="off"  type="password" style="float: left;width: calc( 100% - 40px);">
            <i class="fas fa-eye showpass"style="float: right; display: block;"></i>
            <i class="fas fa-eye-slash hidepass" style="float: right; display: block;display: none;" ></i>
            <div class="clr"></div>
         </div>
    </div>
    <div class="form-group col-md-12">
        <?php if(isset($uPgnrlV['userSignUp'])) { ?>
        <a href="<?php echo esc_url($cppageUrl).'?ppage=sign-up'. esc_attr($page_id);?>" style="font-size: 14px; color: rgb(4, 82, 148);">New User ?</a>
        <?php } ?>
        <button type="submit" class="btn btn-primary btn-fw " name="atmpt-login" style="float:right;background: <?php echo $primarycolor;?>;border-radius: 10px;padding-left: 25px;padding-right: 25px;">Login</button>
    </div>

</form>
<div style="text-align: center;">
    
    <?php if(isset($uPgnrlV['userForgetPass'])) { ?>
        <div style="clear: both;height: 10px;"></div>
        <a href="javascript:void(0)" class="showForgetPassFrom" onclick="showForgetPassFrom()" style="font-size: 14px; color: rgb(4, 82, 148);">Forget Password ?</a>
    <?php } ?>
   
    <div class="clr"></div>
</div>
<div class="clr"></div>
</div>

<div class="ibox-content ccgportal-login forgetpassword-form" style="display:<?php echo ($acform == "forgetpass")?'block':'none';?>;border-radius: 10px;">

<div class="col-md-12">
    <a href="javascript:void(0)" style="color: #338dd0;text-decoration: unset;"><p style="font-size: 14px;padding: 2px;">Forget Password ?</p></a>
</div> 
<div class="col-md-12">
    <h4 style="font-size: 25px;font-weight: 700;padding: 0px 0px 10px;">RESET YOUR PASSWORD</h4>
</div>
<form class="m-t" role="form" action="" method="post" style="color: #000;">

    <?php 
        wp_nonce_field('reset_password','csrf_token_nonce');
    ?>

    <?php if(isset($rsterror)){ ?>
        <p style="color: red;text-align: center;"><?php echo esc_html($rstm); ?></p>
    <?php } ?>
    <?php if(isset($rsts)){ ?>
        <p style="color: green;text-align: center;"><?php echo esc_html($rstm); ?></p>
    <?php } ?>

    <div class="form-group col-md-12">

         <div class="loginIntput">
            <label class="labelstyle">Email</label>
            <input id="username" placeholder="user@domain.com" name="user_email" required="" autofocus="" type="text">
            <div class="clr"></div>
         </div>
         <div class="clr"></div>
    </div>

    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary btn-fw " style="float:right;background: <?php echo esc_attr($primarycolor);?>;border-radius: 10px;padding-left: 25px;padding-right: 25px;">Reset</button>
    </div>

</form>

<div class="form-group col-md-12" style="text-align: center;">
    <a href="javascript:void(0)" class="showLoginFrom" onclick="showLoginFrom()" style="/*float: left;*/font-size: 16px;">Login</a>      
    <div class="clr"></div>
</div>
<div class="clr"></div>
</div>
<div class="clr"></div>

<script type="text/javascript">
function showLoginFrom() {
    jQuery('.forgetpassword-form').hide();
    jQuery('.login-form').show();
}
function showForgetPassFrom() {
    jQuery('.login-form').hide();
    jQuery('.forgetpassword-form').show();
}
</script>

<script type="text/javascript">
    // function showppass() {
    //   var x = document.getElementById("myInput");
    //   if (x.type === "password") {
    //     x.type = "text";
    //     jQuery('.showpass').hide();
    //     jQuery('.hidepass').show();
    //   } else {
    //     x.type = "password";
    //     jQuery('.showpass').show();
    //     jQuery('.hidepass').hide();
    //   }
    // }
</script>

<script>
jQuery(function () {
    jQuery(document).on('click', '.showpass', function(event) {
        jQuery(this).closest('div').find('.showpass').hide();
        jQuery(this).closest('div').find('input').attr('type', 'text');
        jQuery(this).closest('div').find('.decrptpass').show();
        jQuery(this).closest('div').find('.hidepass').show();
    });
    jQuery(document).on('click', '.hidepass', function(event) {
        jQuery(this).closest('div').find('.hidepass').hide();
        jQuery(this).closest('div').find('input').attr('type', 'password');
        jQuery(this).closest('div').find('.encptpass').show();
        jQuery(this).closest('div').find('.showpass').show();
    });
});
</script>