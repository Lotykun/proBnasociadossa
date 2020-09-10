<?php
/*EXTERNAL FUNCTIONS FOR THE PLUGIN... IN ORDER TO CALL THEM IS Helpers::function_name*/
namespace BN\ContentProject;

use BN\ContentProject\ViewRenderer;
use BN\Core\HelpersCommon;

class Helpers extends HelpersCommon {

    const PLUGIN_FILE = BN_CONTENTPROJECT_PLUGIN_FILE;
    const ROOT = BN_CONTENTPROJECT_ROOT;
    const NAMESPACE = BN_CONTENTPROJECT_NAMESPACE;
    const NAME = BN_CONTENTPROJECT_NAME;
    const LOCALE = BN_CONTENTPROJECT_LOCALE;
    const CPT_NAME_SING = BN_CONTENTPROJECT_CPT_NAME_SING;
    const CPT_NAME_PLU = BN_CONTENTPROJECT_CPT_NAME_PLU;

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
        return apply_filters( 'uenole_contenttype_post_plugin_url', plugins_url( $uri, static::PLUGIN_FILE ), $uri );
    }
    
}