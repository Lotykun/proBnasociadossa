<?php
namespace BN\ContentProject\Field;

use BN\ContentProject\LibraryController;
use BN\ContentProject\Helpers;
use BN\Core\Field\iField;

abstract class Field implements iField {
    protected $configuration;
    
    abstract function specific_field_metabox();
    
    abstract function save_field_metadata($post_id);

    abstract function validation_field();
    
    abstract function addActions();
    
    abstract function addFilters();
    
    abstract function renderField($data = null);
    
    public function get_field_pre_value($post_id){
        return get_post_meta($post_id, $this->configuration["metakey"], TRUE);
    }
    
    public function __construct($conf = null) {
        if (isset($conf) && !empty($conf)){
            $this->configuration = $conf;
        }
    }
    
    public function init() {
        $this->addActions();
        $this->addFilters();
    }
    
    public function register_field_metabox() {
        add_meta_box(
            $this->configuration['metabox']['id'], 
            $this->configuration['metabox']['label'], 
            array(&$this,'template_field_metabox'), 
            null, 
            $this->configuration['metabox']['context'],
            'high'
        );
    }
    
    public function template_field_metabox($post) {
        $controller = LibraryController::getInstance();
        $controller->emptyParams();
        $field_post_meta = $this->get_field_pre_value($post->ID);
        
        $this->specific_field_metabox();
        
        wp_localize_script(Helpers::NAME.'-'.$this->configuration["type"].'-metabox', 'ajaxurl', Helpers::ajaxUrl());
        wp_enqueue_script(Helpers::NAME.'-'.$this->configuration["type"].'-metabox');
        if (isset($field_post_meta) && !empty($field_post_meta)){
            $controller->addParams(array(
                "data" => $field_post_meta
            ));
        }
        $controller->addParams(array(
            "field_id" => $this->configuration['metakey'],
            "field_label" => $this->configuration['label']
        ));
        $params = $controller->getParams();
        echo $controller->renderView('front/metabox/'.$this->configuration["type"].'_metabox.twig', $params);
    }
    
    public function getConf() {
        return $this->configuration;
    }
    
    public function setConf($conf) {
        $this->configuration = $conf;
    }
}
