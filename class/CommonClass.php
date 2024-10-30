<?php
/*
    Created By : Mahidul Islam Tamim
*/
    
class CCGP_CommonClass {

    public $pluginPath;
    public $pluginUrl;

    function __construct()
    {
        $this->pluginPath = dirname(__FILE__);
        $this->pluginUrl  = CCGP_PLUGIN_URL;
        global $wpdb;
    }

    public function getOptions($name, $index)
    {
        global $wpdb;
    	return $spMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = '$name' AND option_index = '$index'"); 
    }

    public function getOptionsLike($name, $index)
    {
        global $wpdb;
    	return $spMdl = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name LIKE '$name' AND option_index LIKE '%$index'"); 
    }

    public function getOptionBy_nmindx($name, $index)
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = '$name' AND option_index = '$index'"); 
    }

    public function getOptionsBy_nmindx($name, $index)
    {
        global $wpdb;
        return $wpdb->get_result("SELECT * FROM ccgclientportal_options WHERE option_name = '$name' AND option_index = '$index'"); 
    }

    public function ZohoMsg($for, $res)
    {
        if(!is_array($res)) $res = json_decode($res, true);
        if($for == 'crm'){
            if(isset($res['code'])){
                // crm
                if($res['code'] == 'INVALID_MODULE') $message = "The module name given seems to be invalid";
            }
        }
    }
}
?>