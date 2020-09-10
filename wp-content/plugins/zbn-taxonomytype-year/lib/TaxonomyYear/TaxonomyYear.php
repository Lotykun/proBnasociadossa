<?php
namespace BN\TaxonomyYear;

class TaxonomyYear {
    static $instance = null;
    
    static function & getInstance() {
        if (null == self::$instance) {
            self::$instance = new TaxonomyYear();
        }

        return self::$instance;
    }
    public function __construct() {
    
        $Base = new Base();
        
        /*register_activation_hook($file, array(&$this, 'install'));
        register_deactivation_hook($file, array(&$this, 'deinstall'));*/
    }
}