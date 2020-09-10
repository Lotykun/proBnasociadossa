<?php
namespace BN\ContentProject;

class ContentProject {
    static $instance = null;
    
    static function & getInstance() {
        if (null == self::$instance) {
            self::$instance = new ContentProject();
        }

        return self::$instance;
    }

    public function __construct() {
        $Base = new Base();
        //register_activation_hook(__FILE__, array(self::$instance, 'install'));
        //register_deactivation_hook(__FILE__, array(self::$instance, 'deinstall'));
    }

    public function install(){
        /*ACTIONS TO DO WHEN PLUGIN IS INSTALLED*/
    }
    
    public function deinstall(){
        /*ACTIONS TO DO WHEN PLUGIN IS DEINSTALLED*/
    }
}