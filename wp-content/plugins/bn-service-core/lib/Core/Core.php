<?php
namespace BN\Core;

use \BN\Core\Helpers;

class Core {
    static $instance = null;
    
    static function & getInstance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    public function __construct() {
        $Base = new Base();
    }
    public function install(){
        $packages = Helpers::getOption("libraries");
        $filter["name"] = "required";
        $filter["value"] = TRUE;
        $packages_filter = Helpers::filterLibraries($packages, $filter);
        $composer_json = str_replace("\/", '/',
            json_encode(
                array(
                    'config' => array('vendor-dir' => 'vendor'),
                    'name' => 'bn/core',
                    'licence' => 'proprietary',
                    'type' => 'project',
                    'require' => $packages_filter,
                ),
                JSON_PRETTY_PRINT
            )
        );
        file_put_contents(Helpers::ROOT . '/composer.json', $composer_json);
        chmod(Helpers::ROOT . '/composer.json',0664);
    }
    public function deinstall(){
        /*ACTIONS TO DO WHEN PLUGIN IS DEINSTALLED*/
    }
}