<?php
/*
    Created By : Mahidul Islam Tamim
*/
include_once 'SettingsClass.php';
include_once 'CPNzoho.php';

class ZohoCrmRequest extends CPNzoho
{
    public $licens;
    public $authtoken;
    public $newFormat;

    function __construct($authtoken="", $newFormat = 1)
    {
        global $wpdb;
        $this->setAuth();
    }

    public function generateAccesstoken($data)
    {  
        unset($data['save_CRM_info']);
        if($data['code'] !=""){            
            $zohoRes = $this->generate_authtoken($data); 
            $zohoRes = json_decode($zohoRes, true);
        }else $zohoRes = array();


        if(isset($zohoRes['refresh_token'])){
            global $wpdb;
            $wpdb->insert("ccgclientportal_auth", [
                'apifor' => 'crm',
                'orgid' => $data['orgid'],
                'authorized_client_name' => $data['authorized_client_name'],
                'redirect_uri' => $data['redirect_uri'],
                'client_id' => $data['client_id'],
                'client_secret' => $data['client_secret'],
                'access_token' => $zohoRes['access_token'],
                'refresh_token' => $zohoRes['refresh_token'],
                'create_time' => date('Y-m-d H:i:s')
            ]);
        }

        return $zohoRes;        
    }


    public function getSearchRecordsByPDCN($module, $column, $string, $rfrom='admin', $countable='no')
    {        

        $res = $this->getSearchRecordsByPDC($module, $column, $string);

        return json_decode($res, true);
    }


    public function deleteFileN($module, $id, $attachment_id, $rfrom='admin', $countable='no')
    {        

        $res = $this->deleteFile($module, $id, $attachment_id);
        return json_decode($res);
    }


    public function addUser($data)
    {
        if(!isset($data['addForExistAccAccept'])){
            $criteriaA = "(Account_Name:equals:".$data['Account_Name'].")";
            $accExist = $this->getSearchRecordsN('Accounts', $criteriaA);
            if(isset($accExist['data'][0]['id'])){
                return $arrayName = array('code' => '','status' => "ac_exist", 'data' => $accExist );
            }
        }

        if(isset($data['crmAccId'])){
            $accId = $data['crmAccId'];
        }

        $criteriaC = "(Email:equals:".$data['email'].")";
        $conExist = $this->getSearchRecordsN('Contacts', $criteriaC);
        if(isset($conExist['data'][0]['id'])){
            return $arrayName = array('code' => '','status' => "con_exist", 'data' => $conExist );
        }

        // Contact Array
        $contact_array = array(
            'First_Name'=>sanitize_text_field($data['first_name']),
            'Last_Name'=>sanitize_text_field($data['last_name']),
            'Email'=>sanitize_email($data['email']),
            'Phone'=>sanitize_text_field($data['phone'])
        );     


        if(!isset($data['addForExistAccAccept'])){
            // Account Array  
            $acc_array = array(
                'Account_Name'=> $data['Account_Name'],
                'Website'=> $data['website'],

                'Billing_Street' => $data['Billing_Street'], 
                'Billing_City' => $data['Billing_City'], 
                'Billing_State' => $data['Billing_State'], 
                'Billing_Code' => $data['Billing_Zip'], 
                'Billing_Country' => $data['Billing_Country'],
            
                'Shipping_Street' => $data['Shipping_Street'], 
                'Shipping_City' => $data['Shipping_City'], 
                'Shipping_State' => $data['Shipping_State'], 
                'Shipping_Code' => $data['Shipping_Zip'], 
                'Shipping_Country' => $data['Shipping_Country']
                
            );    

            // Add Accounts to CRM
            $accRes = $this->insertRecordsN(array($acc_array), 'Accounts');

            if($accRes->data[0]->code == "SUCCESS"){
                $accId = $accRes->data[0]->details->id;
            }else return $arrayName = array('code' => '','status' => "errorcrm", 'message' => "Account does not create in CRM" );
        }

        if(isset($accId)){
            $contact_array['Account_Name'] = array('id'=> $accId);
            $contact_array['Account'] = array('id'=> $accId);
        }


        // Add Contact to CRM
        $conRes = $this->insertRecordsN(array($contact_array), 'Contacts');

        if($conRes->data[0]->code == "SUCCESS"){
            $conId = $conRes->data[0]->details->id;

            // Add Portal User
            $dataN = array(
                'username' => $data['username'], 
                'password' => $data['password'], 
                'user' => $conId, 
            );
            $this->addUserforExist($dataN);

            return $arrayName = array('code' => '200','status' => "success", 'message' => "Contact created successfully" );            

        }else return $arrayName = array('code' => '','status' => "errorcrm", 'message' => "Contact does not create in CRM" );           
        

    }


    public function addUserNew($data)
    {
        global $wpdb;
        
        $criteriaC = "(Email:equals:".$data['email'].")";
        $conExist = $this->getSearchRecordsN('Contacts', $criteriaC);
        if(isset($conExist['data'][0]['id'])){
            return $arrayName = array('code' => '','status' => "con_exist", 'data' => $conExist );
        }

        // Contact Array
        $contact_array = array(
            'First_Name'    =>sanitize_text_field($data['first_name']),
            'Last_Name'     =>sanitize_text_field($data['last_name']),
            'Email'         =>sanitize_email($data['email']),
            'Phone'         =>sanitize_text_field($data['phone'])
        );     


        if(isset($_POST['crmAccIdNew']) && sanitize_text_field($_POST['crmAccIdNew']) !=""){
            $accId = sanitize_text_field($_POST['crmAccIdNew']);
        }else{
            // Account Array  
            $acc_array = array(
                'Account_Name'=> sanitize_text_field($data['Account_Name']),
                'Website'=> sanitize_text_field($data['website'])               
            );    

            // Add Accounts to CRM
            $accRes = $this->insertRecordsN(array($acc_array), 'Accounts');
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
        $conRes = $this->insertRecordsN(array($contact_array), 'Contacts');
        if(isset($conRes->data[0]->details->id) && ($conRes->data[0]->code == "SUCCESS")){
            $conId = $conRes->data[0]->details->id;

            // Add Portal User
            $dataN = array(
                'username' => $data['username'], 
                'password' => $data['password'], 
                'user' => $conId, 
            );
            $this->addUserforExist($dataN);

            return $arrayName = array('code' => '200','status' => "success", 'message' => "Contact created successfully" );            

        }else return $arrayName = array('code' => '','status' => "errorcrm", 'message' => "Contact does not create in CRM" );           
        

    }


    public function savePrfilePhotoN($accId)
    {

        $target_dir = CCGP_PLUGIN_PATH.'/pages/user/attachments/profilepic/'.$accId;

        $imageName = $target_dir.'/profile.png';

        if (file_exists($imageName)) {  
            unlink($imageName); 
        }                   

        $file = $this->savePrfilePhoto("Accounts",$accId);
        if (!file_exists($target_dir))  mkdir($target_dir, 0777, true);        
        if($file!='')file_put_contents($imageName, $file);
        
        return true;
    }


    // Add Portal User From CRM
    function addUserforExist($data){
        global $wpdb;

        $crmid = $data['user'];

        $crmData = $this->getRecordsByIdN($crmid, "Contacts");

        if(isset($crmData['data'][0])){
            $conDtl = $crmData['data'][0];

            $dataArr = array(
                // 'booksid' => $booksid,
                'crmid' => $crmid,
                'fname' => (isset($conDtl['First_Name'])) ? sanitize_text_field($conDtl['First_Name']) : "",
                'lname' => (isset($conDtl['Last_Name'])) ? sanitize_text_field($conDtl['Last_Name']) : "",
                'fullname' => (isset($conDtl['Full_Name'])) ? sanitize_text_field($conDtl['Full_Name']) : "",
                'email' => (isset($conDtl['Email'])) ? sanitize_text_field($conDtl['Email']) : "",
                'phone' => (isset($conDtl['Phone'])) ? sanitize_text_field($conDtl['Phone']) : "",
                'username' => $data['username'],
                'password' => $data['password'],
                'status' => 'active',
                'condtl' => json_encode($crmData)
            );

            $wpdb->insert("ccgclientportal_users", $dataArr);

            // Inser user to wp_user table as a Client
            $this->addWp_User($dataArr);

            return true;
        } else return false;

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
                'user_email'    =>  sanitize_email($data['email']),
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

    public function getSearchRecordsN($module, $criteria, $rfrom='admin', $countable='no')
    {        

        $res = $this->getSearchRecords($module, $criteria);

        return json_decode($res, true);
    }

    public function getRelatedRecordsN($module, $id, $parentModule, $rfrom='admin', $countable='no')
    {        

        $res = $this->getRelatedRecords($module, $id, $parentModule);

        return json_decode($res, true);
    }

    public function getRecordsByIdN($crmId, $module, $rfrom='admin', $countable='no')
    {        

        $res = $this->getRecordsById($crmId, $module);
        return json_decode($res, true);
    }

    public function getFilesN($module, $crmId, $rfrom='admin', $countable='no')
    {        

        $res = $this->getFiles($module, $crmId);
        return json_decode($res);
    }

    public function downloadFileN($module, $id, $attachment_id, $rfrom='admin', $countable='no')
    {

        $res = $this->downloadFile($module, $id, $attachment_id);
        return $res;
    }

    public function updateRecordsN($crmId, $zohoArr, $module, $rfrom='admin', $countable='no')
    {        

        $res = $this->updateRecords($crmId, $zohoArr, $module);
        return json_decode($res);
    }

    public function insertRecordsN($zohoArr, $module, $rfrom='admin', $countable='no')
    {        

        $data = array();
        $data['action'] = "insertRecords";        
        $data['rfrom'] = $rfrom;
        $data['countable'] = $countable;
        
        
        $data['module'] = $module;
        $data['zohoArr'] = $zohoArr;

        $res = $this->insertRecords($zohoArr, $module);
        return json_decode($res);
    }

    public function getFieldsN($module, $rfrom='admin', $countable='no')
    {        
        $res = $this->getFields($module);
        return json_decode($res);
    }
    public function getModuleN($rfrom='admin', $countable='no')
    {         
        $res = $this->getModule();
        return json_decode($res);
    }


  

}