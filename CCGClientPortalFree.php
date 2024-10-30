<?php
/*
Plugin Name: Client Portal Free
Plugin URI: https://catalystconnect.com
Description: Zoho Client Portal - Free Version. To provide a visible link to the portal, copy this short code [ccgclient_portal_free] and paste it wherever on your site.
Author: Catalyst Connect
Version: 2.2.0
Author URI: https://catalystconnect.com
*/

define('CCGP_PLUGIN_PATH', dirname(__FILE__) );
define('CCGP_PLUGIN_URL', WP_PLUGIN_URL . '/catalyst-connect-client-portal/');
define('CCGP_TEMPFILE', ABSPATH.'/_temp_out.txt' );

add_action("activated_plugin", "activation_handler1");

function activation_handler1(){
    $cont = ob_get_contents();
    if(!empty($cont)) file_put_contents(CCGP_TEMPFILE, $cont );
}

add_action( "pre_current_active_plugins", "pre_output1" );

function pre_output1($action){
    if(is_admin() && file_exists(CCGP_TEMPFILE))
    {
        $cont = file_get_contents(CCGP_TEMPFILE);
        if(!empty($cont)){
            echo '<div class="error"> Error Message:' . $cont . '</div>';
            @unlink(CCGP_TEMPFILE);
        }
    }
}

if (!function_exists('dbg'))   {
    // echo "sdfsdfd";
    function dbg($data="")
    {
        $current_user = wp_get_current_user();
        if (user_can($current_user, "administrator")) {
            echo '<div class="clr"></div><pre style="background: #1d2327; padding: 20px; border-radius: 5px; width: 100%; color: white !important; font-size: 15px; white-space: pre-wrap; word-break: break-all; max-width: 100%;">';
            var_dump($data);
            echo '</pre><div class="clr"></div>';
        }
    }
}

class CCGClientPortalFree {

    public $pluginPath;
    public $pluginUrl;
    public $zoho_crm_vendors;

    function __construct()
    {
        ob_start();
        // session_start();
        add_action('init', array($this, 'register_my_session'));

        $this->pluginPath = dirname(__FILE__);
        $this->pluginUrl  = CCGP_PLUGIN_URL;
        global $wpdb;

        add_action( 'admin_enqueue_scripts', array($this, 'load_li' ));
        add_action( 'admin_enqueue_scripts', array($this, 'admin_style' ));
        
        add_action( 'wp_enqueue_scripts', array($this, 'load_li' ));
        add_action( 'wp_enqueue_scripts', array($this, 'user_style' ));

        $this->includeClasses();
        // For media upload
        add_action ( 'admin_enqueue_scripts', function () {
            if (is_admin ()) wp_enqueue_media ();
        } );

        add_action( 'plugins_loaded', array( 'CCGP_PageTemplater', 'get_instance' ) );

        //table create
        register_activation_hook( __FILE__, array($this, 'create_table' ) );
        // Create Custom User Role
        register_activation_hook( __FILE__, array($this, 'add_roles_on_plugin_activation' ) );

        register_deactivation_hook( __FILE__, array($this, 'role_roles_on_plugin_dectivation') );

        // Add Menue in Admin panel
        $this->addMenus();

        //page short code for user page
        add_shortcode( 'ccgclient_portal_free', array($this,'ccgclient_portal_shortcode_func') );

        //Ajax
        add_action( 'wp_ajax_updateIntigrationStatus', array($this,'ajax_updateIntigrationStatus') );
        add_action( 'wp_ajax_ccgpp_ajaxrequest', array($this,'ajax_ccgpp_ajaxrequest') );
        add_action( 'wp_ajax_ccgpp_autosave', array($this,'ajax_ccgpp_autosave') );

    }
    function add_roles_on_plugin_activation() {
       add_role( 'client', 'Client', array( 
            'read' => true, 
        ) );
    }
    function role_roles_on_plugin_dectivation() {
       if( get_role('client') ){
            remove_role( 'client' );
        }
    }
    function register_my_session()
    {
        if( !session_id() ) session_start();    
    }

    function admin_style(){  
        
        wp_register_style('ccg-bootstrap', plugin_dir_url( __FILE__ ) . 'assets/css/ccg-bootstrap.css');
        if ( ! wp_style_is( 'ccg-bootstrap', 'enqueued' )) wp_enqueue_style('ccg-bootstrap');  

        wp_register_style('admin_custom_style', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css' );
        wp_enqueue_style('admin_custom_style'); 

        // wp_register_style('admin_custom_style-1', plugin_dir_url( __FILE__ ) . 'assets/css/admin-style-1.css' );
        // wp_enqueue_style('admin_custom_style-1'); 
    }

    function user_style(){
        wp_register_style('ccg-bootstrap', plugin_dir_url( __FILE__ ) . 'assets/css/ccg-bootstrap.css');
        if ( ! wp_style_is( 'ccg-bootstrap', 'enqueued' )) wp_enqueue_style('ccg-bootstrap');   

        wp_register_style('custom_style_css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css' );
        wp_enqueue_style('custom_style_css');

        wp_register_style('select2', plugin_dir_url( __FILE__ ) . 'assets/plugin/select2/dist/css/select2.min.css' );
        if ( ! wp_style_is( 'select2', 'enqueued' ))wp_enqueue_style('select2');

        wp_enqueue_script('select2', plugin_dir_url( __FILE__ ) . 'assets/plugin/select2/dist/js/select2.min.js'); 
        if ( ! wp_script_is( 'select2', 'enqueued' )) wp_enqueue_script('select2');

    }

    function load_li(){
        /**
        * Register global styles.
        */
        wp_register_style('ccg-colorbox', plugin_dir_url( __FILE__ ) . 'assets/plugin/colorbox/colorbox.css');
        if ( ! wp_style_is( 'ccg-colorbox', 'enqueued' )) wp_enqueue_style('ccg-colorbox');

        wp_register_style('bootstrap_datetime', plugin_dir_url( __FILE__ ) . 'assets/css/bootstrap-datetimepicker.min.css');
        if ( ! wp_style_is( 'bootstrap_datetime', 'enqueued' )) wp_enqueue_style('bootstrap_datetime');

        wp_register_style('ccg-font-awesome', plugin_dir_url( __FILE__ ) . 'assets/css/plugin/font-awesomel.min.css'); 
        if ( ! wp_style_is( 'ccg-font-awesome', 'enqueued' )) wp_enqueue_style('ccg-font-awesome');

        wp_register_style('datatable', plugin_dir_url( __FILE__ ) . 'assets/css/jquery.dataTables.min.css');
        if ( ! wp_style_is( 'datatable', 'enqueued' )) wp_enqueue_style('datatable');

        wp_register_style('colorPicker', plugin_dir_url( __FILE__ ) . 'assets/css/bootstrap-colorpicker.min.css');
        if ( ! wp_style_is( 'colorPicker', 'enqueued' )) wp_enqueue_style('colorPicker'); 
       
        wp_register_style('dragulacss', plugin_dir_url( __FILE__ ) . 'assets/css/plugin/dragula/dist/dragula.min.css');
        wp_enqueue_style('dragulacss');


        /**
        * Register global scripts.
        */
        wp_register_style('common_style', plugin_dir_url( __FILE__ ) . 'assets/css/common-style.css' );
        wp_enqueue_style('common_style');

        wp_register_style('common_style-1', plugin_dir_url( __FILE__ ) . 'assets/css/common-style-1.css' );
        wp_enqueue_style('common_style-1');
        
        wp_register_script('bootstrap',plugins_url('assets/js/bootstrap.min.js',__FILE__ ), array('jquery'));
        if ( ! wp_script_is( 'bootstrap', 'enqueued' )) wp_enqueue_script('bootstrap');

        wp_register_script('dataTables',plugins_url('assets/js/jquery.dataTables.min.js',__FILE__ ), array('jquery'));
        if ( ! wp_script_is( 'dataTables', 'enqueued' )) wp_enqueue_script('dataTables');

        wp_register_script('dragulajs',plugins_url('assets/css/plugin/dragula/dist/dragula.min.js',__FILE__ ), array('jquery'));
        wp_enqueue_script('dragulajs');
        
        wp_register_script('colorPicker_ccg',plugins_url('assets/js/bootstrap-colorpicker.min.js',__FILE__ ), array('jquery'));
        if ( ! wp_script_is( 'colorPicker_ccg', 'enqueued' )) wp_enqueue_script('colorPicker_ccg');
       
        wp_register_script('custom_script_ccg',plugins_url('assets/js/script.js',__FILE__ ), array('jquery'));
        if ( ! wp_script_is( 'custom_script_ccg', 'enqueued' )) wp_enqueue_script('custom_script_ccg');

        wp_register_script('ccg-colorbox',plugin_dir_url( __FILE__ ) . 'assets/plugin/colorbox/jquery.colorbox-min.js');
        if ( ! wp_script_is( 'ccg-colorbox', 'enqueued' )) wp_enqueue_script('ccg-colorbox');
    }

    function add_my_custom_page() {
        // Create post object
        $my_post = array(
            'post_title'    => wp_strip_all_tags( 'Client Portal' ),
            'post_content'  => '
                <!--Do not delete this page-->
                [ccgclient_portal]
            ',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
        );

        // Insert the post into the database
        $new_page_id = wp_insert_post( $my_post );

        if ( $new_page_id && ! is_wp_error( $new_page_id ) ){
            $template = $this->pluginPath.'/pagetemplate/page_fullwidth-template.php'; 
            update_post_meta($new_page_id, '_wp_page_template', $template);
        }
    }

    public function create_table()
    {
        include_once 'class/PortalTable.php';
        $PortalTable = new PortalTable();
        $PortalTable->create_zoho_auth_tbl();
        $PortalTable->create_options_tbl();
        $PortalTable->create_users_tbl();
        $PortalTable->create_tempusers_tbl();
    }

    public function remove_table()
    {
        include_once 'class/PortalTable.php';
        $PTable = new PortalTable();
        $PTable->remove_database();
    }

    public function includeClasses()
    {
        include_once 'class/PortalTable.php';
        include_once 'class/CommonClass.php';

        include_once('class/AdminClass.php');
        include_once('class/Users.php');
        include_once('class/SettingsClass.php');
        include_once('class/CrmModules.php');
        include_once('class/CPNzoho.php');
        include_once('class/ZohoCrmRequest.php');

    }

    public function addMenus()
    {        
        add_action( 'admin_menu', array($this, 'ccgclientportal_admin_menu') );

        global $wpdb;
        $table_name = "ccgclientportal_options";
        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

        if ($wpdb->get_var( $query ) == $table_name ) {

            $sqlaccin = "SELECT * FROM ccgclientportal_options WHERE option_name = 'accoutn_info' AND option_index = 'status' AND option_value = 'active'";
            $accinfo = $wpdb->get_row($sqlaccin);

            if(isset($accinfo->option_index)){
                add_action( 'admin_menu', array($this, 'add_submenu_pageLicense') );
                add_action( 'admin_menu', array($this, 'add_submenu_pageZohoSettings') );
                add_action( 'admin_menu', array($this, 'add_submenu_pageZohoModules') );
                add_action( 'admin_menu', array($this, 'add_submenu_pageUsers') );
                add_action( 'admin_menu', array($this, 'add_submenu_pageSettings') );

                // add_action( 'admin_menu', array($this, 'add_submenu_pagePrivacy_Policy') );
                // add_action( 'admin_menu', array($this, 'add_submenu_pageTerms_of_service') );
                add_action( 'admin_menu', array($this, 'add_submenu_pageInstruction') );
            }

        }
    }

    public function ccgclientportal_admin_menu()
    {
        // $icon_url = CCGP_PLUGIN_URL.'assets/images/plugin_icon.png';
        $icon_url = CCGP_PLUGIN_URL.'assets/images/fav-20.png';
        add_menu_page( 
            'Zoho Client Portal', 
            'Client Portal', 
            'manage_options', 
            'dashboard', 
            array($this, 'submenu_LicensePage'), 
            $icon_url, 
            6 );
    }

    public function add_submenu_pageLicense()
    {
        add_submenu_page( 
            'dashboard', // Parent Slug
            'License', // Page Title
            'License', // Menu Title
            'manage_options',
            'dashboard', // Slug
            array($this, 'submenu_LicensePage')
        );
    }

    function submenu_LicensePage() {
        global $wpdb;
        global $wp;
        include_once CCGP_PLUGIN_PATH.'/pages/admin/dashboard.php';
    }

    public function add_submenu_pageZohoSettings()
    {
        add_submenu_page( 
            'dashboard', // Parent Slug
            'Zoho API Settings', // Page Title
            'Zoho API Settings', // Menu Title
            'manage_options',
            'ccgpp-zohocrmapi', // Slug
            array($this, 'submenu_ZohocrmapiPage')
        );
    }

    function submenu_ZohocrmapiPage() {
        global $wpdb;
        global $wp;
        include_once CCGP_PLUGIN_PATH.'/pages/admin/zohocrmapi.php';
    }

    public function add_submenu_pageZohoModules()
    {
        add_submenu_page( 
            'dashboard', // Parent Slug
            'Customization', // Page Title
            'Customization', // Menu Title
            'manage_options',
            'customization', // Slug
            array($this, 'submenu_Crm_modulesPage')
        );
    }

    function submenu_Crm_modulesPage() {
        global $wpdb;
        global $wp;
        include_once CCGP_PLUGIN_PATH.'/pages/admin/crm_modules.php';
    }
    
    public function add_submenu_pageUsers()
    {
        add_submenu_page( 
            'dashboard', // Parent Slug
            'Users Management', // Page Title
            'User Management', // Menu Title
            'manage_options',
            'usersmanagement', // Slug
            array($this, 'submenu_UsersmanagementPage')
        );
    }
    
    function submenu_UsersmanagementPage() {
        global $wpdb;
        global $wp;
        include_once CCGP_PLUGIN_PATH.'/pages/admin/usersManagement.php';
    }
    
    public function add_submenu_pageSettings()
    {
        add_submenu_page( 
            'dashboard', // Parent Slug
            'Design Settings', // Page Title
            'Design Settings', // Menu Title
            'manage_options',
            'ccgpp-settings', // Slug
            array($this, 'submenu_settingsPage')
        );
    }
    function submenu_settingsPage() {
        global $wpdb;
        global $wp;
        include_once CCGP_PLUGIN_PATH.'/pages/admin/settings.php';
    }
    public function add_submenu_pagePrivacy_Policy()
    {
        add_submenu_page( 
            'dashboard', // Parent Slug
            'Privacy Policy', // Page Title
            'Privacy Policy', // Menu Title
            'manage_options',
            'privacy_policy', // Slug
            "https://catalystconnect.com/privacy-policy"
        );
    }
    public function add_submenu_pageTerms_of_service()
    {
        add_submenu_page( 
            'dashboard', // Parent Slug
            'Terms of Service', // Page Title
            'Terms of Service', // Menu Title
            'manage_options',
            'terms-of-service', // Slug
            "https://catalystconnect.com/client-portal-plugin-terms-of-service/"
        );
    }
    public function add_submenu_pageInstruction()
    {
        add_submenu_page( 
            'dashboard', // Parent Slug
            'Info', // Page Title
            'Info', // Menu Title
            'manage_options',
            'ccgpp-instructions', // Slug
            array($this, 'submenu_instructionPage')
        );
    }
    function submenu_instructionPage() {
        global $wpdb;
        global $wp;
        include_once CCGP_PLUGIN_PATH.'/pages/admin/instruction.php';
    }


    function ccgclient_portal_shortcode_func()
    {
        ob_start();
        include_once 'pages/user/index.php';
        return ob_get_clean();
    }

    public function ajax_updateIntigrationStatus()
    {
        $SttClass = new CCGP_SettingsClass();
        echo $SttClass->updateIntegrationsStatus($_POST);
        exit();
    }

    // Auto save ajax function
    public function ajax_ccgpp_autosave()
    {
        $SettingsClass = new CCGP_SettingsClass();

        if(isset($_POST['for']) && ($_POST['for'] == "updateSettingls")){
            unset($_POST['for']);
            unset($_POST['action']);
            $upStt = $SettingsClass->updateSettingls($_POST);
            if($upStt) echo "save"; 
            else echo "not save";    
            exit();       
        }

        elseif(isset($_POST['for']) && ($_POST['for'] == "updateOp_dashboardContent")){
            unset($_POST['for']);
            unset($_POST['action']);
            $upStt = $SettingsClass->updateOp_dashboardContent($_POST);
            if($upStt) echo "save"; 
            else echo "not save";    
            exit();       
        }

        elseif(isset($_POST['for']) && ($_POST['for'] == "updateLayout_dashboardContent")){
            unset($_POST['for']);
            unset($_POST['action']);
            $upStt = $SettingsClass->updateLayout_dashboardContent($_POST);
            if($upStt) echo "save"; 
            else echo "not save";    
            exit();       
        }

        elseif(isset($_POST['for']) && ($_POST['for'] == "updatedefaultApperance")){
            unset($_POST['for']);
            unset($_POST['action']);
            if(isset($_GET['csrf_token_nonce']) && wp_verify_nonce($_GET['csrf_token_nonce'],'updatedefaultApperance')){
                $upStt = $SettingsClass->updateap_fontColor($_POST);
                $upStt = $SettingsClass->updateap_menuColor($_POST);
                $upStt = $SettingsClass->updateap_btnColor($_POST);
                if($upStt) echo "save"; 
                else echo "not save";
            }else{
                echo "not save";
            }   
            exit();       
        }

        elseif(isset($_POST['for']) && ($_POST['for'] == "updatefontcolor")){
            unset($_POST['for']);
            unset($_POST['action']);
            $upStt = $SettingsClass->updateap_fontColor($_POST);
            if($upStt) echo "save"; 
            else echo "not save";    
            exit();       
        }
        
        elseif(isset($_POST['for']) && ($_POST['for'] == "updatemenucolor")){
            unset($_POST['for']);
            unset($_POST['action']);
            $upStt = $SettingsClass->updateap_menuColor($_POST);
            if($upStt) echo "save"; 
            else echo "not save";   
            exit();       
        }

        elseif(isset($_POST['for']) && ($_POST['for'] == "updateap_btnColor")){
            unset($_POST['for']);
            unset($_POST['action']);
            $upStt = $SettingsClass->updateap_btnColor($_POST);
            if($upStt) echo "save"; 
            else echo "not save";   
            exit();       
        }

        elseif(isset($_POST['for']) && ($_POST['for'] == "updatewebtab")){
            unset($_POST['for']);
            unset($_POST['action']);

            $upStt = $SettingsClass->updatewebtab($_POST);
            if($upStt) echo "save"; 
            else echo "not save";    
            exit();       
        }
    }
    // Ajax requests
    public function ajax_ccgpp_ajaxrequest()
    {
        if(isset($_POST['for']) && ($_POST['for'] == "getFields")){
            $CrmModules = new CrmModules();
            $meFld = $CrmModules->getModuleFieldList($_POST['module']);
            $resArr = array();
            $subformFld = '<option value="">--Select One--</option>';
            if(is_array($meFld)){
                foreach ($meFld as $key => $value) {
                    if($value['view_type']['view'] == true){
                        $subformFld .='<option value="'.$value['api_name'].'___'.$value['field_label'] .'">'.$value['field_label'].'</option>';
                    }
                }
            }

            echo $subformFld;
            exit();
        }
        elseif(isset($_POST['for']) && ($_POST['for'] == "getFieldsSubform")){
            // $view_type = $_POST['view_type'];
            // $CrmModules = new CrmModules();
            // $CrmModules->syncModuleField($_POST['module']);
            // $meFld = $CrmModules->getModuleFieldList($_POST['module']);
            // $resArr = array();
            // $subformFld = '<option value="">--Select One--</option>';
            // if(is_array($meFld)){
            //     foreach ($meFld as $key => $value) {
            //         if($value['view_type'][$view_type] == true){
            //             $subformFld .='<option value="'.$value['api_name'].'___'.$value['field_label'] .'">'.$value['field_label'].'</option>';
            //         }
            //     }
            // }

            // echo $subformFld;
            // exit();
            $view_type = $_POST['view_type'];
            $CrmModules = new CrmModules();
            $CrmModules->syncSubformModuleField($_POST['module'], $_POST['subform']);
            $mnfname = $_POST['module'].'__'.$_POST['subform'];
            $meFld = $CrmModules->getSubformFieldList($mnfname);
            $resArr = array();
            $subformFld = '<option value="">--Select One--</option>';
            if(is_array($meFld)){
                foreach ($meFld as $key => $value) {
                    // echo "<pre>";var_dump($value);echo "</pre>";
                    if($value['view_type'][$view_type] == true){
                        $subformFld .='<option value="'.$value['api_name'].'___'.$value['field_label'] .'">'.$value['field_label'].'</option>';
                    }
                }
            }

            echo $subformFld;
            exit();
        }
        elseif(isset($_POST['for']) && ($_POST['for'] == "searchRecords")){
           
            if(isset($_GET['csrf_token_nonce']) && wp_verify_nonce($_GET['csrf_token_nonce'],'ajax_search_request')){
                $module = $_POST['module'];
                $criteria = $_POST['criteria'];
                $CPNzoho = new ZohoCrmRequest();
                $moduleData = $CPNzoho->getSearchRecordsN($module, $criteria);
                $resArr = array();
                $accountList = '<option value="">--Select One--</option>';
                if((is_array($moduleData['data'])) && ($moduleData['count'] > 0)){
                    foreach ($moduleData['data'] as $key => $value) {
                        $accountList .='<option value="'.$value['id'].'">'.$value['Account_Name'].'</option>';                    
                    }
                }

                echo $accountList;
            }
           
            exit();
        }        
        elseif(isset($_POST['for']) && ($_POST['for'] == "searchModule")){
            $module = $_POST['module'];
            $string = $_POST['string'];
            $CPNzoho = new ZohoCrmRequest();
            $moduleData = $CPNzoho->getSearchRecordsByPDCN($module, 'word', $string);
            $resArr = array();
            $moduleList = '<option value="">--Select One--</option>';
            if((is_array($moduleData['data'])) && ($moduleData['count'] > 0)){
                foreach ($moduleData['data'] as $key => $value) {
                    $rname = "";
                    if(isset($value['Subject'])) $rname = $value['Subject'];
                    if(isset($value[rtrim($module, "s").'_Name'])) $rname = $value[rtrim($module, "s").'_Name'];
                    if(isset($value[$module.'_Name'])) $rname = $value[$module.'_Name'];
                    if(isset($value[rtrim($module, "s").'Name'])) $rname = $value[rtrim($module, "s").'Name'];
                    if(isset($value[$module.'Name'])) $rname = $value[$module.'Name'];
                    if(isset($value['Full_Name'])) $rname = $value['Full_Name'];
                    if(isset($value['Name'])) $rname = $value['Name'];
                    $moduleList .='<option value="'.$value['id'].'">'.$rname.'</option>';                    
                }
            }

            echo $moduleList;
            exit();
        }
        elseif(isset($_POST['for']) && ($_POST['for'] == "saveCustomizationTabOrder")){
            if(isset($_POST['tabName'])){
                $tabName = $_POST['tabName'];
                $AdminClass = new AdminClass();
                $moduleData = $AdminClass->saveCustomizationTabOrder($tabName);
                echo json_encode(array('status'=> 'success'));            
            }else{
                echo json_encode(array('status'=> 'failed', 'message'=>'Tab not found')); 
            } 
        }
        exit();
    }


}




class CCGP_PageTemplater {

    /**
     * A reference to an instance of this class.
     */
    private static $instance;

    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;

    /**
     * Returns an instance of this class. 
     */
    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new CCGP_PageTemplater();
        } 

        return self::$instance;

    } 

    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {

        $this->templates = array();


        // Add a filter to the attributes metabox to inject template into the cache.
        if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {

            // 4.6 and older
            add_filter(
                'page_attributes_dropdown_pages_args',
                array( $this, 'register_project_templates' )
            );

        } else {

            // Add a filter to the wp 4.7 version attributes metabox
            add_filter(
                'theme_page_templates', array( $this, 'add_new_template' )
            );

        }

        // Add a filter to the save post to inject out template into the page cache
        add_filter(
            'wp_insert_post_data', 
            array( $this, 'register_project_templates' ) 
        );


        // Add a filter to the template include to determine if the page has our 
        // template assigned and return it's path
        add_filter(
            'template_include', 
            array( $this, 'view_project_template') 
        );


        // Add your templates to this array.
        $this->templates = array(
            'pagetemplate/page_fullwidth-template.php' => 'CCG Client Portal',
        );
            
    } 

    /**
     * Adds our template to the page dropdown for v4.7+
     *
     */
    public function add_new_template( $posts_templates ) {
        $posts_templates = array_merge( $posts_templates, $this->templates );
        return $posts_templates;
    }

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     */
    public function register_project_templates( $atts ) {

        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

        // Retrieve the cache list. 
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        } 

        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );

        return $atts;

    } 

    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template( $template ) {
        
        // Get global post
        global $post;

        // Return template if post is empty
        if ( ! $post ) {
            return $template;
        }

        // Return default template if we don't have a custom one defined
        if ( ! isset( $this->templates[get_post_meta( 
            $post->ID, '_wp_page_template', true 
        )] ) ) {
            return $template;
        } 

        $file = plugin_dir_path( __FILE__ ). get_post_meta( 
            $post->ID, '_wp_page_template', true
        );

        // Just to be safe, we check if the file exist first
        if ( file_exists( $file ) ) {
            return $file;
        } else {
            echo $file;
        }

        // Return template
        return $template;

    }

}


$zohopwp = new CCGClientPortalFree();

?>