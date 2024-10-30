<?php
/*
* Created by Mahidul Islam.
*/

// $plugin_dir = WP_PLUGIN_URL . '/ccgclient-portal/';
$plugin_dir = CCGP_PLUGIN_URL;
global $wpdb;
$contacts = $wpdb->get_results("SELECT * FROM ccgclientportal_users WHERE status = 'active' ORDER BY id DESC");   
?>

    <div class="col-md-12">
        <table class="datatable table table-hover contacts-list">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>User Name</th>
                    <!-- <th>Password</th> -->
                    <th>Deactivate</th>
                </tr>
            </thead>
            <tbody>

                <?php if($contacts){
                    foreach ($contacts as $key => $conDtl) { ?>
                        <tr>
                            <td><?php echo $conDtl->fullname; ?></td>
                            <td><?php echo $conDtl->email; ?></td>
                            <td><?php echo $conDtl->phone; ?></td>
                            <td><?php echo $conDtl->username; ?></td>
                            <!-- <td>
                                <font class="decrptpass" style="display: none;"><?php echo $conDtl->password; ?></font>
                                <font class="encptpass"><?php echo str_repeat('*', strlen($conDtl->password)); ?></font>
                                <i class="fas fa-eye showpass"></i>
                                <i class="fas fa-eye-slash hidepass" style="display: none;"></i>
                            </td> -->
                            <td class="actionClumn">
                                <form action="" method="post" style="float: left;">
                                    <?php 
                                        wp_nonce_field('inactiveUser','csrf_token_nonce');
                                    ?>
                                    <input type="hidden" name="id" value="<?php echo esc_attr($conDtl->id); ?>">                                
                                    <input type="submit" name="inactiveUser" class="btnActiveDeactive" value="Deactivate">
                                </form>

                                <a href="<?php echo esc_url(admin_url()); ?>admin.php?page=usersmanagement&action=userdetails&uid=<?php echo $conDtl->id; ?>" class="btnActiveDeactive" style="float: left;margin-left: 12px;">Details</a>
                            </td>
                        </tr>
                    <?php }
                } ?>
                
            </tbody>
        </table>
    </div>  
    <div class="clr"></div>
