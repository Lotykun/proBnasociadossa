<?php
namespace BN\Core;

use BN\Core\Core\ViewRenderer;

class HelpersCommon {

    protected static $_settings = null;
    
    public static function getRequestUrl() {
        return esc_url_raw($_SERVER['REQUEST_URI']);
    }
    
    public static function getRequestPostParam( $param, $default = null ) {
        if ( isset( $_POST[ $param ] ) ) {
            return $_POST[ $param ];
        } else {
            return $default;
        }
    }
    
    public static function getRequestParam( $param, $default = null ) {
        if ( isset( $_GET[ $param ] ) ) {
            if ( is_array( $_GET[ $param ] ) ) {
                return array_map( 'wp_check_invalid_utf8', $_GET[ $param ]);
            }
            else {
                return wp_check_invalid_utf8( $_GET[ $param ] );
            }
        } else {
            return $default;
        }
    }
    
    public static function protectView(ViewRenderer $view) {
        if ( ! isset( $view->allowViewRendering ) || $view->allowViewRendering !== true ) {
            wp_die( 'Access denied' );
        }
    }
    
    public static function getOption( $name, $default = null ) {
        $name    = is_string( $name ) ? $name : null;
        $default = is_bool( $default ) ? $default : null;

        $value = (static::isPluginNetworkActivated() || is_multisite()) ? maybe_unserialize(get_site_option( $name, $default )) : maybe_unserialize(get_option( $name, $default ));
        
        $value = maybe_unserialize(get_option( $name, $default ));
        if (!empty($value)) {
            return $value;
        }

        if (is_null( static::$_settings)) {
            static::$_settings = static::getDefaultSettings();
        }
        $settings = static::$_settings;

        if (isset( $settings[$name])) {
            return $settings[$name];
        } else {
            return $default;
        }
    }
    
    public static function isPluginNetworkActivated() {
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

        return is_plugin_active_for_network( str_replace(DIRECTORY_SEPARATOR, '/', static::getPluginPath()) );
    }
    
    public static function getPluginUrl() {
        return static::pluginUrl( '' );
    }
    
    public static function assetsUrl( $uri = '' ) {
        return static::pluginUrl( "assets/".$uri );
    }
    
    public static function imagesUrl( $uri = '' ) {
        return static::pluginUrl( "assets/images/".$uri );
    }
    
    public static function jsUrl( $uri = '' ) {
        return static::pluginUrl( "assets/js/".$uri );
    }
    
    public static function cssUrl( $uri = '' ) {
        return static::pluginUrl( "assets/css/".$uri );
    }
    
    public static function ajaxUrl(){
        $site_url = get_site_url();

        return $site_url."/wp-admin/admin-ajax.php";
    }
    
    public static function generateTabUrl($params) {

        $query = remove_query_arg(array_keys($_GET), esc_url_raw(static::getRequestUrl()));
        $query = "/wp-admin/admin.php";
        $query = add_query_arg( $params,$query);

        return $query;
    }
    
    public static function createJsonArray($packages) {
        $response = array();
        foreach ($packages as $value) {
            $response[$value["composer"]] = $value["version"];
        }
        return $response;
    }
    
    public static function filterLibraries($packages, $filter) {
        $response = array();
        foreach ($packages as $value) {
            if ($value[$filter["name"]] === $filter["value"]){
                $response[] = $value;
            }
        }
        
        $response = static::createJsonArray($response);
        return $response;
    }

    public static function getPluginPath() {
        $pluginsPath = DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR;
        return substr( static::PLUGIN_FILE, strpos( static::PLUGIN_FILE, $pluginsPath ) + strlen( $pluginsPath ) );
    }
    
    public static function getDefaultSettings() {
        $defaultSettings = require( plugin_dir_path(static::PLUGIN_FILE) . 'settings.php' );
        return $defaultSettings;
    }
    
    public static function pluginUrl( $uri = '' ) {
        return apply_filters( 'bn_service_core_plugin_url', plugins_url( $uri, static::PLUGIN_FILE ), $uri );
    }
    
    public static function getCoreFormaTxt() {
        $response = file_get_contents(static::ROOT."/forma.txt");
        
        return $response;
    }

}