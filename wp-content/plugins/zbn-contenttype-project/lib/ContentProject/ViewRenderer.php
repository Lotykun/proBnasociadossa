<?php
namespace BN\ContentProject;

use BN\Core\View\View;
use BN\ContentProject\Helpers;

class ViewRenderer extends View {

    protected $path = Helpers::ROOT.'/view/';
    
    protected function extendsFuncions(&$functions) {
        $functions = array_merge($functions, array(
            new \Twig_SimpleFunction('render_twig_field', array($this,'renderTwigField')),
            new \Twig_SimpleFunction('render_wp_table', array($this,'renderWPTable')),
        ));
    }
    
    public function renderTwigField($fieldkey, $data) {
        $fieldObject = Field\Factory::get_field_instance($fieldkey);
        $fieldObject->renderField($data);
    }
    
    public function renderWPTable($table_class) {
        $class = "\BN\ContentProject\Table\\".$table_class;
        $wp_Table = new $class();
        $wp_Table->prepare_items(); 
        $wp_Table->display(); 
    }
}
