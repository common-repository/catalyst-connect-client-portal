<?php
    /*
    * Created by Mahidul Islam.
    */

    $plugin_dir = WP_PLUGIN_URL . '/ccgclient-portal/';

    global $wpdb;
    $contacts = $wpdb->get_results("SELECT * FROM ccgclientportal_users WHERE status = 'approve' ORDER BY id DESC");
?>

    <div class="col-md-12">
        <table class="datatable table table-hover contacts-list">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>User Name</th>
                    <th>Password</th>
                    <th>Action</th>
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
                            <td><?php echo $conDtl->password; ?></td>
                            <td class="actionClumn">
                                <form action="" method="post" style="margin-right: 8px;float: left;">
                                    <input type="hidden" name="id" value="<?php echo esc_attr($conDtl->id); ?>">      
                                    <input type="submit" name="approveUser" value="Approve">                                    
                                </form>
                                <form action="" method="post" style="float: left;">
                                    <input type="hidden" name="id" value="<?php echo esc_attr($conDtl->id); ?>">
                                    <input type="submit" name="deleteUserA" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php }
                } ?>
                
            </tbody>
        </table>
    </div>  
    <div class="clr"></div>
