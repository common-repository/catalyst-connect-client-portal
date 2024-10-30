<?php
/*
* Created by Mahidul Islam.
*/

$plugin_dir = WP_PLUGIN_URL . '/ccgclient-portal/';
global $wpdb;
$contacts = $wpdb->get_results("SELECT * FROM ccgclientportal_users WHERE status = 'inactive' ORDER BY id DESC");
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
                    <th style="width: 190px;">Action</th>
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
                            <td class="actionClumn" style="width: 150px;">
                                <form action="" method="post" style="float: left;margin-right: 8px;">
                                    <input type="hidden" name="id" value="<?php echo esc_attr($conDtl->id); ?>"> 
                                    <input type="submit" name="activeUser" class="btnActiveDeactive" value="Activated">
                                </form>
                                <form action="" method="post">
                                    <input type="hidden" name="id" value="<?php echo esc_attr($conDtl->id); ?>"> 
                                    <input type="submit" name="deleteUser" class="btnActiveDeactive" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php }
                } ?>
                
            </tbody>
        </table>
    </div>  
    <div class="clr"></div>
