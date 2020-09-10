<?php
namespace BN\Core;

use BN\Core\Table;
use BN\Core\Helpers;

class ViewRenderer {
    protected $twig;
    
    public function renderView($viewFile, array $params = array()) {
        $templates = Helpers::ROOT.'/view/';

        $loader = new \Twig_Loader_Filesystem($templates);
        $this->twig = new \Twig_Environment($loader);

        $this->addFilters();
        $this->addFunctions();

        return $this->twig->render($viewFile, $params);
    }
    
    protected function addFilters() {

    }
    
    protected function addFunctions() {
        $functions = array(
            new \Twig_SimpleFunction('_e', array($this,'e_trans')),
            new \Twig_SimpleFunction('__', array($this,'trans')),
            new \Twig_SimpleFunction('wp_nonce_field', array($this,'wp_nonce_field')),
            new \Twig_SimpleFunction('render_wp_table', array($this,'renderWPTable')),
        );

        foreach ($functions as $function) {
            $this->twig->addFunction($function);
        }
    }
    
    public function e_trans($text,$context) {
        _e($text, $context);
    }
    
    public function trans($text,$context) {
        return __($text, $context);
    }
    
    public function wp_nonce_field($action,$value) {
        wp_nonce_field($action,$value);
    }
    
    public function renderWPTable($table_class) {
        $class = "\BN\Core\Table\\".$table_class;
        $wp_Table = new $class();
        $wp_Table->prepare_items(); 
        $wp_Table->display(); 
    }
}