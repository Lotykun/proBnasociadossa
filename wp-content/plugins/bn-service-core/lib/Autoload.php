<?php
namespace BN\Core;

//Requiere directos que necesitamos que se carguen primero antes de la clase autoload
require_once BN_CORE_ROOT.'/lib/Core/Controller/Controller.php';

class Autoload {

    static $instance = null;
    static $path;
    static $namespace = __NAMESPACE__;
    
    static function getInstance($path) {
        if (null == static::$instance) {
            static::$instance = new static();
        }
        
        static::$path = $path;
        return static::$instance;
    }
    
    public function __construct() {
        spl_autoload_register( array( $this, 'autoload' ) );
    }
    
    public function autoload($class) {
        $classParts = explode("\\", $class);
        if (count($classParts)>1) {
            $classname = str_replace("_","",end($classParts));
            $namespace = $classParts[0]."\\".$classParts[1];
            if (static::$namespace === $namespace) {
                if (isset(static::$path) && !empty(static::$path)) {
                    $it = new \RecursiveDirectoryIterator(static::$path);
                    $iterator = new \RecursiveIteratorIterator($it);
                    $display = Array ( 'php' );
                    foreach($iterator as $file){
                        $parts = explode('.', $file);
                        if (in_array(strtolower(array_pop($parts)), $display)) {
                            if ($file->getFileName() == $classname.".php") {
                                if (!class_exists($class)) {
                                    require_once $file->getPathname();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
