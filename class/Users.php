<?php
/*
    Created By : Mahidul Islam Tamim
*/
include_once 'ZohoCrmRequest.php';

class CCGP_Users {

    public $pluginPath;
    public $pluginUrl;


    function __construct()
    {
        $this->pluginPath = dirname(__FILE__);
        $this->pluginUrl = CCGP_PLUGIN_URL;
        global $wpdb;
    }


    public function loginUserByWpUser($email)
    {
        global $wpdb;

        $contacts = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE status = 'active' AND username = '$email' ");

        if(isset($contacts->crmid)){
            $conId = $contacts->crmid;

            $CPNzoho = new ZohoCrmRequest();
            $module = $CPNzoho->getRecordsByIdN($conId, 'Contacts', 'user', 'yes');

            // echo "<pre>";var_dump($module);echo "</pre>";exit();
            if(isset($module['status']) && ($module['status'] =='failed')){
                $_SESSION["ccgcp_login_error"] = "Please contact your administrator";
            }else{
                if(isset($module['data'][0]['Email']) && count($module['data']) > 0){
                    $conDtl = $module['data'][0];


                    // Data save in Session
                    unset($_SESSION["ccgcpp_logout"]);
                    $_SESSION["ccgcp_loged"] = $conDtl;

                    $accId = $_SESSION["ccgcp_loged"]['Account_Name']['id'];

                    // Login to WP
                    // $creds = array( 'user_login' => $usern, 'user_password' => $pass, 'remember' => true );                     
                    // $user = wp_signon( $creds, false );                        
                    // if(isset($user->ID)){
                    //     wp_set_current_user($user->ID);
                    //     wp_set_auth_cookie( $user->ID);
                    // }

                    $accDtl = $CPNzoho->getRecordsByIdN($accId, 'Accounts', 'user', 'yes');
                    if(isset($accDtl['data'][0]['Owner']['id'])){
                        $Ownerid = $accDtl['data'][0]['Owner']['id'];
                        $Owner = $CPNzoho->getUserByIdN($Ownerid, 'user', 'yes');
                        if(is_array($Owner['users'][0])){
                            $_SESSION["ccgcp_loged"]['AccMngr'] = $Owner['users'][0];
                        }
                    }

                    // Redirect to Dashboard
                    // header("Location: ".$cppageUrl."?page=dashboard");
                }else $_SESSION["ccgcp_login_error"] = "Please contact your administrator";//"User delete from CRM";
            }
            
        }else $_SESSION["ccgcp_login_error"] = "Invalid User Name / Password";
    }



    public function addUser($data)
    {
        global $wpdb;

        $umsetres = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'um-settings' AND option_index = 'additionalstt'");

        if(isset($umsetres->option_index)) $umsetresV = json_decode($umsetres->option_value, true);
        if(isset($umsetresV['userautoapve'])){
            return $this->addUserNew($data);
        }else{

            $dataArr = array(
                'data' => json_encode($data),
                'status' => 'pending'
            );
            $wpdb->insert("ccgclientportal_tempusers", $dataArr);
            return $arrayName = array('code' => '200','status' => "success" );   
        }

    }

    public function addUserNew($data)
    {
        global $wpdb;
        $CPNzoho = new ZohoCrmRequest();
        
        // Contact Array
        $contact_array = array(
            'First_Name'=>$data['first_name'],
            'Last_Name'=>$data['last_name'],
            'Email'=>$data['email'],
            'Phone'=>$data['phone']
        );     


        if(isset($_POST['crmAccIdNew']) && $_POST['crmAccIdNew'] !=""){
            $accId = $_POST['crmAccIdNew'];
        }else{
            // Account Array  
            $acc_array = array(
                'Account_Name'=> $data['Account_Name'],
                'Website'=> $data['website']                
            );    

            // Add Accounts to CRM
            $accRes = $CPNzoho->insertRecordsN(array($acc_array), 'Accounts');
            if(isset($accRes->data[0]->details->id) && $accRes->data[0]->code == "SUCCESS"){

                $accId = $accRes->data[0]->details->id;

            }else return $arrayName = array('code' => '','status' => "errorcrm", 'message' => "Account does not create in CRM" );
        }

        if(isset($accId)){

            $contact_array['Account_Name'] = array('id'=> $accId);
            $contact_array['Account'] = array('id'=> $accId);
        }

        $umsetres = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'um-settings' AND option_index = 'sctoCRM'");
        if(isset($umsetres->option_index)) $umsetresV = json_decode($umsetres->option_value, true);
        if(isset($umsetresV['sctoCRMp'])){
            $contact_array[$umsetresV['username']] = $data['username'];
            // $contact_array[$umsetresV['password']] = $data['password'];
        }

        // Add Contact to CRM
        $conRes = $CPNzoho->insertRecordsN(array($contact_array), 'Contacts');

        if(isset($conRes->data[0]->details->id) && ($conRes->data[0]->code == "SUCCESS")){
            $conId = $conRes->data[0]->details->id;

            // Add Portal User
            $dataN = array(
                'username' => $data['username'], 
                'password' => $data['password'], 
                'user' => $conId, 
            );

            $this->addUserToLocalN($dataN, 'active');
            $successM = "You can login now.";

            return $arrayName = array('code' => '200','status' => "success", "message" => $successM );          

        } else return $arrayName = array('code' => '404','status' => "errorcrm" );          
        

    }
    function addUserToLocalN($data, $status = 'pending'){
        global $wpdb;

        $crmid = $data['user'];

        $CPNzoho = new ZohoCrmRequest();
        $crmData = $CPNzoho->getRecordsByIdN($crmid, "Contacts");

        if(isset($crmData['data'][0])){
            $conDtl = $crmData['data'][0];

            $dataArr = array(
                'crmid' => $crmid,
                'fname' => (isset($conDtl['First_Name'])) ? $conDtl['First_Name'] : "",
                'lname' => (isset($conDtl['Last_Name'])) ? $conDtl['Last_Name'] : "",
                'fullname' => (isset($conDtl['Full_Name'])) ? $conDtl['Full_Name'] : "",
                'email' => (isset($conDtl['Email'])) ? $conDtl['Email'] : "",
                'phone' => (isset($conDtl['Phone'])) ? $conDtl['Phone'] : "",
                'username' => $data['username'],
                'password' => $data['password'],
                'status' => $status,
                'condtl' => json_encode($crmData)
            );

            $wpdb->insert("ccgclientportal_users", $dataArr);

            // Inser user to wp_user table as a Client
            $this->addWp_User($dataArr);

            return true;
        } else return false;

    }

    // Add User For Existing
    function addUserForExist($data){
        global $wpdb;

        $crmid = $data['crmConId'];

        $CPNzoho = new ZohoCrmRequest();
        $crmData = $CPNzoho->getRecordsByIdN($crmid, "Contacts");

        if( isset($crmData['data'][0]) ){
            $conDtl = $crmData['data'][0];

            $status = "approve";
            $umstt = $wpdb->get_results("SELECT * FROM ccgclientportal_options WHERE option_name = 'um-settings' ");
            if($umstt){
                foreach ($umstt as $ums) {
                    if($ums->option_index == 'sctoCRM') $umsetresV = json_decode($ums->option_value, true);
                    if($ums->option_index == 'additionalstt') $umatoapv = json_decode($ums->option_value, true);
                }
            }
            if(isset($umatoapv['userautoapve'])) $status = "active";

            $dataArr = array(
                'booksid' => '',//$booksid,
                'crmid' => $crmid,
                'fname' => (isset($conDtl['First_Name'])) ? $conDtl['First_Name'] : "",
                'lname' => (isset($conDtl['Last_Name'])) ? $conDtl['Last_Name'] : "",
                'fullname' => (isset($conDtl['Full_Name'])) ? $conDtl['Full_Name'] : "",
                'email' => (isset($conDtl['Email'])) ? $conDtl['Email'] : "",
                'phone' => (isset($conDtl['Phone'])) ? $conDtl['Phone'] : "",
                'username' => $data['username'],
                'password' => $data['password'],
                'status' => $status,
                'condtl' => json_encode($crmData)
            );


            $wpdb->insert("ccgclientportal_users", $dataArr);

            // Inser user to wp_user table as a Client
            $this->addWp_User($dataArr);

            if(isset($umsetresV['sctoCRMp'])){
                $contact_array = array();
                $contact_array[$umsetresV['username']] = $data['username'];
                // $contact_array[$umsetresV['password']] = $data['password'];
                $upres = $CPNzoho->updateRecordsN($crmid, array($contact_array), "Contacts");
            }
            if($status == "approve") $successM = "Your account is under review. We will be in touch soon.";
            elseif($status == "active") $successM = "You can login now.";

            return $arrayName = array('code' => '200','status' => "success", "message" => $successM );
        } else return $arrayName = array('code' => '404','status' => "errorcrm" );

    }


    public function addWp_User($data)
    {
        global $wpdb;

        $exists = email_exists($data['email']);
        if ( $exists ){
            $user_id = (int) $exists; // correct ID
            $res = wp_update_user( array(
                'ID' => $user_id,
                'user_pass' => $data['password']
            ) );
        } else{
            $WP_array = array (
                'user_login'    =>  $data['username'],
                'user_email'    =>  $data['email'],
                'user_pass'     =>  $data['password'],
                'first_name'    =>  $data['fname'],
                'last_name'     =>  $data['lname'],
                'nickname'      =>  $data['lname'],
                'role'      =>  'client',
            ) ;
            $id = wp_insert_user( $WP_array ) ;
        }

        $umstt = $wpdb->get_row("SELECT * FROM ccgclientportal_users WHERE crmid =  ".$data['crmid']);
        $wpdb->update('ccgclientportal_users', array('password'=> md5($data['password'])), array('crmid'=>$umstt->crmid));

        return true;
    }


    


}
?>