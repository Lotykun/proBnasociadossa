<?php
namespace BN\Core;

use BN\Core\Core\ViewRenderer;

class Helpers extends HelpersCommon {

    const PLUGIN_FILE = BN_CORE_PLUGIN_FILE;
    const ROOT = BN_CORE_ROOT;
    const NAMESPACE = BN_CORE_NAMESPACE;
    const NAME = BN_CORE_NAME;
    const LOCALE = BN_CORE_LOCALE;
    const DEPLOYED_VERSION_FILE = BN_CORE_DEPLOYED_VERSION_FILE;

    protected static $_settings = null;
    
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