<?php
/*
    Created By : Mahidul Islam Tamim
*/
class PortalTable {

    public $pluginPath;
    public $pluginUrl;


    function __construct()
    {
        $this->pluginPath = dirname(__FILE__);
        $this->pluginUrl = WP_PLUGIN_URL . '/ccgclient-portal/';
        global $wpdb;
    }

    //Table create
    function create_zoho_auth_tbl(){
        global $wpdb;
        $table_name = 'ccgclientportal_auth';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        apifor varchar(20) DEFAULT NULL,
                        orgid varchar(100) DEFAULT NULL,
                        authorized_client_name varchar(200) DEFAULT NULL,
                        redirect_uri varchar(500) DEFAULT NULL,
                        code varchar(500) DEFAULT NULL,
                        client_id varchar(500) DEFAULT NULL,
                        client_secret varchar(500) DEFAULT NULL,
                        access_token varchar(500) DEFAULT NULL,
                        refresh_token varchar(500) DEFAULT NULL,
                        create_time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY  (id)
                    );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $sql );

    }

    function create_options_tbl(){
        global $wpdb;
        $table_name = 'ccgclientportal_options';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        option_name varchar(150) DEFAULT NULL,
                        option_index varchar(100) DEFAULT NULL,
                        option_value mediumtext DEFAULT NULL,
                        create_time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY  (id)
                    );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $sql );

    }

    function create_users_tbl(){
        global $wpdb;
        $table_name = 'ccgclientportal_users';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        booksid varchar(30) DEFAULT NULL,
                        crmid varchar(30) DEFAULT NULL,
                        fname varchar(150) DEFAULT NULL,
                        lname varchar(150) DEFAULT NULL,
                        fullname varchar(150) DEFAULT NULL,
                        email varchar(100) DEFAULT NULL,
                        phone varchar(100) DEFAULT NULL,
                        username varchar(100) DEFAULT NULL,
                        password varchar(100) DEFAULT NULL,
                        status varchar(10) DEFAULT NULL,
                        condtl text DEFAULT NULL,
                        create_time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY  (id)
                    );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $sql );
    }

    function create_tempusers_tbl(){
        global $wpdb;
        $table_name = 'ccgclientportal_tempusers';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        data text DEFAULT NULL,
                        status varchar(10) DEFAULT NULL,
                        create_time datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY  (id)
                    );";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        dbDelta( $sql );

    }


    function remove_database() {

        global $wpdb;

        $wpdb->query("DROP TABLE IF EXISTS ccgclientportal_auth");
        $wpdb->query("DROP TABLE IF EXISTS ccgclientportal_options");
        $wpdb->query("DROP TABLE IF EXISTS ccgclientportal_users");
        $wpdb->query("DROP TABLE IF EXISTS ccgclientportal_tempusers");

    }



}
?>