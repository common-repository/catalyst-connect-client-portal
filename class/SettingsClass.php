<?php
/*
    Created By : Mahidul Islam Tamim
*/
include_once 'ZohoCrmRequest.php';

class CCGP_SettingsClass {

    public $pluginPath;
    public $pluginUrl;

    function __construct()
    {
        $this->pluginPath = dirname(__FILE__);
        $this->pluginUrl = CCGP_PLUGIN_URL;
        global $wpdb;
    }

    // Update Settings
    function updateOp_dashboardContent($data){
        global $wpdb;

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'dashboard', 'option_index' => 'sliderimages' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'dashboard',
            'option_index' => 'sliderimages',
            'option_value' => json_encode(
                array(
                    'permission' => (isset($data['BannerPermission'])) ? 'on' : 'off',
                    'content' => json_encode($data['sliderimages'])
                )
            )
        ]);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'dashboard', 'option_index' => 'aboutUstext' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'dashboard',
            'option_index' => 'aboutUstext',
            'option_value' => json_encode( 
                array(
                    'permission' => (isset($data['aboutUsPermission'])) ? 'on' : 'off',
                    'label' => $data['aboutUsLabel'],
                    'content' => $data['aboutUstext']
                )
            )
        ]);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'dashboard', 'option_index' => 'dashboardiframe' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'dashboard',
            'option_index' => 'dashboardiframe',
            'option_value' => json_encode( 
                array(
                    'permission' => (isset($data['dashboardIframePermission'])) ? 'on' : 'off',
                    'label' => $data['dashboardIframeLabel'],
                    'content' => $data['dashboardiframe']
                )
            )
        ]);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'dashboard', 'option_index' => 'quickLink' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'dashboard',
            'option_index' => 'quickLink',
            'option_value' => json_encode( 
                array(
                    'permission' => (isset($data['quickLinkPermission'])) ? 'on' : 'off',
                    'label' => $data['quickLinkTLabel'],
                    'content' => json_encode( 
                        array(
                            'title' => json_encode($data['quickLinkT']),
                            'link' => json_encode($data['quickLink']),
                            'linkaf' => json_encode($data['quickLinkAF'])
                        )
                    )
                )
            )
        ]);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'dashboard', 'option_index' => 'iframeembed' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'dashboard',
            'option_index' => 'iframeembed',
            'option_value' => json_encode( 
                array(
                    'permission' => (isset($data['iframeEmbedpermission'])) ? 'on' : 'off',
                    'label' => $data['iframeEmbedLabel'],
                    'content' => json_encode( array('url' => $data['iframeEmbedUrl'],'AcFld' => $data['AccountsFld']))
                )
            )
        ]);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'dashboard', 'option_index' => 'color' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'dashboard',
            'option_index' => 'color',
            'option_value' => json_encode(
                array(
                    'headbckcolor' => $data['headbckcolor'],                    
                    'headfontcolor' => $data['headfontcolor'],                    
                )
            )
        ]);

        return true;
    }

    public function updateLayout_dashboardContent($data)
    {     
        global $wpdb;

        unset($data['dcLayoutupdate']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'dashboard', 'option_index' => 'Layout_dashboardContent' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'dashboard',
            'option_index' => 'Layout_dashboardContent',
            'option_value' => json_encode( $data )
        ]);
        
        return true;
    }


    // Update Settings
    function updateSettingls($data){
        global $wpdb;

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'general-settings', 'option_index' => 'portalTitle' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'general-settings',
            'option_index' => 'portalTitle',
            'option_value' => $data['portalTitle']
        ]);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'general-settings', 'option_index' => 'portal-main-menu' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'general-settings',
            'option_index' => 'portal-main-menu',
            'option_value' => $data['menupossition']
        ]);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'general-settings', 'option_index' => 'portalWidth' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'general-settings',
            'option_index' => 'portalWidth',
            'option_value' => $data['portalWidth']
        ]);

        if(isset($data['primarycolor'])){
            $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'general-settings', 'option_index' => 'primarycolor' ) );
            $wpdb->insert("ccgclientportal_options", [
                'option_name' => 'general-settings',
                'option_index' => 'primarycolor',
                'option_value' => $data['primarycolor']
            ]);
        }

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'general-settings', 'option_index' => 'showAccMngr' ) );
        if(isset($data['showAccMngr'])){
            $wpdb->insert("ccgclientportal_options", [
                'option_name' => 'general-settings',
                'option_index' => 'showAccMngr',
                'option_value' => $data['showAccMngr']
            ]);
        }

        return true;
    }

    // Update Appearance Menu Color
    function updateap_menuColor($data){
        global $wpdb;
        unset($data['ap_menuColor']);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'appearance', 'option_index' => 'menucolor' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'appearance',
            'option_index' => 'menucolor',
            'option_value' => json_encode($data)
        ]);

        return true;

    }

    // Update Appearance Button Color
    function updateap_btnColor($data){
        global $wpdb;
        unset($data['ap_btnColor']);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'appearance', 'option_index' => 'buttoncolor' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'appearance',
            'option_index' => 'buttoncolor',
            'option_value' => json_encode($data)
        ]);

        return true;
    }

    // Update Web Tab
    function updatewebtab($data){
        global $wpdb;
        unset($data['savewebtab']);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'general-settings', 'option_index' => 'webtab' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'general-settings',
            'option_index' => 'webtab',
            'option_value' => json_encode(
                array( 'content' => json_encode( 
                        array(
                            'link'   => json_encode($data['webLink']),
                            'title'  => json_encode($data['webTabT']),
                            'width'  => json_encode($data['width']),
                            'Icon'   => json_encode($data['Icon']),
                            'height' => json_encode($data['height']),
                            'openin' => json_encode($data['openin']),
                        )
                    )
                )
            )
        ]);

        return true;
    }

    // User Secrets
    function assigneUserSecrets($data){
        global $wpdb;
        $user_id = $data['user_id'];
        unset($data['user_id']);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'usersecrets', 'option_index' => $user_id ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'usersecrets',
            'option_index' => $user_id,
            'option_value' => json_encode($data)
        ]);

        return true;
    }

    // User Secrets permission
    function userSecretsPermission($data){
        global $wpdb;
        $user_id = $data['ac_user_id'];
        unset($data['ac_user_id']);
        unset($data['updateModuleAcp']);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'usersecrets_permission', 'option_index' => $user_id ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'usersecrets_permission',
            'option_index' => $user_id,
            'option_value' => json_encode($data)
        ]);

        return true;
    }

    // User Secrets permission
    function saveUserQuickLink($data){
        global $wpdb;
        $user_id = $data['ql_user_id'];
        unset($data['ql_user_id']);
        unset($data['updateUserLink']);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'userdata-ql', 'option_index' => $user_id ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'userdata-ql',
            'option_index' => $user_id,
            'option_value' => json_encode(
                array(
                    'link' => json_encode($data['quickLink']),
                    'title' => json_encode($data['quickLinkT'])
                )
            )
        ]);

        return true;
    }

    // Account Manager permission
    function saveAccountManagerPermission($data){
        global $wpdb;
        unset($data['updateAccountManager']);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'dashboard', 'option_index' => 'account_manager_permission' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'dashboard',
            'option_index' => 'account_manager_permission',
            'option_value' => json_encode($data)
        ]);

        return true;
    }

    // Account Manager permission
    function updatePermissionLevel($data){
        global $wpdb;
        unset($data['updatePermissionLevel']);
        $uid = $data["uid"];
        $permission = $data["permission"];

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'userdata-pl', 'option_index' => $uid ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'userdata-pl',
            'option_index' => $uid,
            'option_value' => $permission
        ]);

        return true;
    }

    // Update Appearance Font Color
    function updateap_fontColor($data){
        global $wpdb;
        unset($data['ap_fontColor']);

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'appearance', 'option_index' => 'font_color' ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'appearance',
            'option_index' => 'font_color',
            'option_value' => json_encode($data)
        ]);

        return true;
    }

    // Update Permission
    function generalPermission($data){
        global $wpdb;
        if(isset($data['generalPermission'])){
            unset($data['generalPermission']);
            
            $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'userpermission', 'option_index' => 'generalPermission' ) );
            $wpdb->insert("ccgclientportal_options", [
                'option_name' => 'userpermission',
                'option_index' => 'generalPermission',
                'option_value' => json_encode($data)
            ]);
        }

        return true;

    }

    // Update Permission
    function menuUserpermission($data){
        global $wpdb;
        if(isset($data['menuUserpermission'])){
            $menuFor= $data['menuFor'];

            unset($data['menuFor']);
            unset($data['menuUserpermission']);
            if(!isset($data['Billing'])){
                unset($data['Quotes']);
                unset($data['SalesOrders']);
                unset($data['Invoices']);
                unset($data['RetainerInvoices']);
            }
            $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'menuPermission', 'option_index' => $menuFor ) );
            $wpdb->insert("ccgclientportal_options", [
                'option_name' => 'menuPermission',
                'option_index' => $menuFor,
                'option_value' => json_encode($data)
            ]);
        }

        return true;

    }

    // Update integrations
    function updateIntegrationsStatus($data){
        global $wpdb;

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'integrations', 'option_index' => $data['api'] ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'integrations',
            'option_index' => $data['api'],
            'option_value' => $data['status']
        ]);

        return true;
    }

    // Add Portal User From CRM
    function addUserforExist($data){
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
                'status' => 'active',
                'condtl' => json_encode($crmData)
            );

            $wpdb->insert("ccgclientportal_users", $dataArr);

            // Inser user to wp_user table as a Client
            $this->addWp_User($dataArr);

            return true;
        } else return false;

    }

    public function updateApiCall($service)
    {
        global $wpdb;

        $res = $wpdb->get_row("SELECT * FROM ccgclientportal_options 
            WHERE option_name = 'apiCall_".$service."' AND option_index = '".date("Y-m-d")."' ");

        if(isset($res->option_value)){
            $wpdb->update( "ccgclientportal_options", 
                array( 'option_value' => (int)$res->option_value + 1),
                array('id'=>$res->id));
        }else{
            $wpdb->insert("ccgclientportal_options", [
                'option_name' => 'apiCall_'.$service,
                'option_index' =>date("Y-m-d"),
                'option_value' => 1
            ]);
        }

        return true;
    }



    // New Function for free plugin 
    function updateUser_domain($post, $rfrom='admin', $countable='no'){
        global $wp;
        global $wpdb;

        $data = array();
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'accoutn_info', 'option_index' => "user_domain" ) );
        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'accoutn_info',
            'option_index' => "user_domain",
            'option_value' => $post['domain']
        ]);
        return true;


    }






}
?>