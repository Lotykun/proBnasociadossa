<?php
namespace BN\Core\View;

use BN\Core\Helpers;

class View {
    protected $twig;
    protected $path;
    
    public function renderView($viewFile, array $params = array()) {

        $loader = new \Twig_Loader_Filesystem($this->path);
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
        );

        $this->extendsFuncions($functions);

        foreach ($functions as $function) {
            $this->twig->addFunction($function);
        }
    }

    protected function extendsFuncions(&$functions) {}
    
    public function e_trans($text, $context) {
        _e($text, $context);
    }
    
    public function trans($text, $context) {
        return __($text, $context);
    }
    
    public function wp_nonce_field($action, $value) {
        wp_nonce_field($action, $value);
    }
}