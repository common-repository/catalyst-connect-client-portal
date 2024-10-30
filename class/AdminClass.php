<?php
/*
    Created By : Mahidul Islam Tamim
*/
include_once 'ZohoCrmRequest.php';

class AdminClass {

    public $pluginPath;
    public $pluginUrl;

    function __construct()
    {
        $this->pluginPath = dirname(__FILE__);
        $this->pluginUrl  = CCGP_PLUGIN_URL;
        global $wpdb;
    }

    public function saveLicenseInfo($licenseDtl)
    {
        global $wpdb;

        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'accoutn_info',
            'option_index' =>'profile',
            'option_value' => sanitize_text_field($licenseDtl['user_info'])
        ]);

        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'accoutn_info',
            'option_index' =>'license',
            'option_value' => json_encode(
                array(
                    'license_key'  => sanitize_text_field($licenseDtl['license_key']),
                    'license_type' => sanitize_text_field($licenseDtl['license_type'])
                )
            )
        ]);

        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'accoutn_info',
            'option_index' =>'status',
            'option_value' => "active"
        ]);

        return true;
    }


    public function saveCustomizationTabOrder($tabName)
    {
        global $wpdb;

        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'customization', 'option_index' => 'taborder' ) );

        $wpdb->insert("ccgclientportal_options", [
            'option_name' => 'customization',
            'option_index' =>'taborder',
            'option_value' => json_encode($tabName)
        ]);

        return true;
    }

}