<?php
/*EXTERNAL FUNCTIONS FOR THE PLUGIN... IN ORDER TO CALL THEM IS Helpers::function_name*/
namespace BN\TaxonomyProjectStatus;
use BN\TaxonomyProjectStatus\ViewRenderer;
class Helpers {
    private static $_settings = null;
    
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

        $value = (self::isPluginNetworkActivated() || is_multisite()) ? maybe_unserialize(get_site_option( $name, $default )) : maybe_unserialize(get_option( $name, $default ));
        if (!empty($value)) {
            return $value;
        }

        if (is_null( self::$_settings)) {
            self::$_settings = self::getDefaultSettings();
        }
        $settings = self::$_settings;

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

        return is_plugin_active_for_network( str_replace(DIRECTORY_SEPARATOR, '/', self::getPluginPath()) );
    }
    private static function getPluginPath() {
        $pluginsPath = DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR;
        return substr( BN_TAXONOMYPROJECTSTATUS_PLUGIN_FILE, strpos( BN_TAXONOMYPROJECTSTATUS_PLUGIN_FILE, $pluginsPath ) + strlen( $pluginsPath ) );
    }
    public static function getDefaultSettings() {
        $defaultSettings = require( plugin_dir_path(BN_TAXONOMYPROJECTSTATUS_PLUGIN_FILE) . 'settings.php' );
        return $defaultSettings;
    }
    public static function pluginUrl( $uri = '' ) {
        return apply_filters( 'bn_taxonomytype_project_status_plugin_url', plugins_url( $uri, BN_TAXONOMYPROJECTSTATUS_PLUGIN_FILE ), $uri );
    }
    public static function getPluginUrl() {
        return self::pluginUrl( '' );
    }
    public static function assetsUrl( $uri = '' ) {
        return self::pluginUrl( "assets/".$uri );
    }
    public static function imagesUrl( $uri = '' ) {
        return self::pluginUrl( "assets/images/".$uri );
    }
    public static function jsUrl( $uri = '' ) {
        return self::pluginUrl( "assets/js/".$uri );
    }
    public static function cssUrl( $uri = '' ) {
        return self::pluginUrl( "assets/css/".$uri );
    }
    public static function ajaxUrl(){
        $site_url = get_site_url();

        return $site_url."/wp-admin/admin-ajax.php";
    }
}