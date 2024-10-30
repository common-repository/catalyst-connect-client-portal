<?php

/**
* Created by Mahidul Islam .
*/

include_once 'ZohoCrmRequest.php';

class CrmModules extends ZohoCrmRequest 
{

    public $pluginPath;
    public $pluginUrl;


    function __construct($authtoken="", $newFormat = 1)
    {
        $this->pluginPath = dirname(__FILE__);
        $this->pluginUrl = CCGP_PLUGIN_URL;
        global $wpdb;
    }



    // Update CRM API Additional Settings
    function update_CRMAdditionalSettings($data){
        global $wpdb;
        unset($data['update_CRMAdditionalSettings']);
        
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'crmAddSettings' ) );
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name'  => 'crmModules',
            'option_index' => 'crmAddSettings',
            'option_value' => json_encode($data)
        ]);

        return $res;
    }


    // Update Settings
    function update_useModules($data){
        global $wpdb;
        unset($data['useModules']);
        $moduleList = $data['moduleList'];
        unset($data['moduleList']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'useModules' ) );
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'useModules',
            'option_value' => json_encode($data)
        ]);

        return $res;
    }

    // Update Module Show Permission
    function update_Module_sp($data){
        global $wpdb;
        unset($data['showPermission']);
        unset($data['updateModuleStt']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'sp_'.$data['module'] ) );
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'sp_'.$data['module'],
            'option_value' => json_encode($data)
        ]);

        return $res;
    }

    // Update Module List Table Column
    function update_Module_listTableColumn($data){
        global $wpdb;
        $module = $data['module'];
        unset($data['listTableColumn']);
        unset($data['module']);
        unset($data['updateModuleStt']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'ltc_'.$module ) );
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'ltc_'.$module,
            'option_value' => json_encode($data)
        ]);

        return $res;
    }

    // Update Module Action Permission
    function update_Module_actionPermission($data){
        global $wpdb;
        $module = $data['module'];
        unset($data['actionPermission']);
        unset($data['module']);
        unset($data['updateModuleAcp']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'acp_'.$module ) );
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'acp_'.$module,
            'option_value' => json_encode($data)
        ]);

        return $res;
    }

    // Update Module Action Permission
    function update_Module_TitleDescription($data){
        global $wpdb;
        $module = $data['module'];
        unset($data['moduleTitleDescription']);
        unset($data['module']);
        unset($data['updateModuleTD']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'mtd_'.$module ) );
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'mtd_'.$module,
            'option_value' => json_encode($data)
        ]);

        return $res;
    }

    // Update Module Layout
    function update_Module_updateLayout($data){
        $CPNzoho = new ZohoCrmRequest();
        
        global $wpdb;
        $module = $data['module'];
        $actionType = $data['actionType'];
        unset($data['module']);
        unset($data['actionType']);;
        unset($data['updateLayout']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'lay_'.$actionType.'_'.$module ) );
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'lay_'.$actionType.'_'.$module,
            'option_value' => json_encode($data)
        ]);

        return $res;
    }

    function update_Module_updateAllLayout($data){
        $CPNzoho = new ZohoCrmRequest();
        
        global $wpdb;
        $module = $data['module'];
        unset($data['module']);
        unset($data['updateAllLayout']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'layout_'.$module ) );
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'layout_'.$module,
            'option_value' => json_encode($data)
        ]);

        return $res;
    }

    public function syncModuleField($module)
    {      

        global $wpdb;
        $module = str_replace("zcrm_", "", $module);
        $CPNzoho = new ZohoCrmRequest();
        $moduleFld = $CPNzoho->getFieldsN($module);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'FieldListzcrm_'.$module));
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'FieldListzcrm_'.$module,
            'option_value' => json_encode($moduleFld)
        ]);

        return true;
    }
    
    public function getModuleFieldList($module)
    {
        global $wpdb;
        $crmMdlFlds = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index LIKE 'FieldListzcrm_".$module."'");
        if(isset($crmMdlFlds->option_index)){
            $crmMdlFlds = json_decode($crmMdlFlds->option_value, true); 
        }else{
            $this->syncModuleField($module);
            return $this->getModuleFieldList($module);
        }
        $fieldList = array();
        if(isset($crmMdlFlds['fields'])){
            foreach ($crmMdlFlds['fields'] as $fldKey => $fldDtl) {
                $fieldList[$fldDtl['api_name']] = $fldDtl;
            }
            
        }

        return $fieldList;
    }

    function addFilter($data){
        $CPNzoho = new ZohoCrmRequest();
        
        global $wpdb;
        $module = $data['module'];
        unset($data['module']);
        unset($data['updateAllLayout']);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'layout_'.$module ) );
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'fltr_'.$module,
            'option_value' => json_encode($data)
        ]);

        return $res;
    }

    public function syncSubformModuleField($module,$sffield)
    {      

        global $wpdb;
        $mnfname = $module.'__'.$sffield;
        $CPNzoho = new ZohoCrmRequest();
        $moduleFld = $CPNzoho->getFieldsN($sffield);
        $wpdb->delete( 'ccgclientportal_options', array( 'option_name' => 'crmModules', 'option_index' => 'FieldListzcrm_'.$mnfname));
        $res = $wpdb->insert("ccgclientportal_options", [ 
            'option_name' => 'crmModules',
            'option_index' => 'FieldListzcrm_'.$mnfname,
            'option_value' => json_encode($moduleFld)
        ]);

        return true;
    }
    public function getSubformFieldList($module)
    {
        global $wpdb;
        $crmMdlFlds = $wpdb->get_row("SELECT * FROM ccgclientportal_options WHERE option_name = 'crmModules' AND option_index = 'FieldListzcrm_".$module."'");
        if(isset($crmMdlFlds->option_index)){
            $crmMdlFlds = json_decode($crmMdlFlds->option_value, true); 
        }else{
            $moduleArr = explode("__", $module);
            $this->syncSubformModuleField($moduleArr[0], $moduleArr[1]);
            return $this->getSubformFieldList($module);
        }
        $fieldList = array();
        if(isset($crmMdlFlds['fields'])){
            foreach ($crmMdlFlds['fields'] as $fldKey => $fldDtl) {
                $fieldList[$fldDtl['api_name']] = $fldDtl;
            }
            
        }

        return $fieldList;
    }

    public function getPicListDisplayValue($picklist, $fValue)
    {
        global $wpdb;

        foreach ($picklist as $key => $option) {

            $ac_value = $option["actual_value"];
            $d_value = $option["display_value"];

            if($fValue == $ac_value || $fValue == $d_value ) return $d_value;
        }

        return "";
    }

    public function getPicListValue($module, $fapiname)
    {
        global $wpdb;
        $fields = $this->getModuleFieldList($module);

        $fieldList = array();
        if(isset($fields[$fapiname])){
            return $fields[$fapiname]['pick_list_values'];
            
        }

        return array();
    }

}
?>