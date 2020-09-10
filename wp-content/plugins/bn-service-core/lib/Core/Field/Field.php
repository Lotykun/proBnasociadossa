<?php
namespace BN\Core\Field;

abstract class Field implements iField {
    protected $configuration;
    
    abstract function specific_field_metabox();
    
    abstract function save_field_metadata($post_id);

    abstract function validation_field();
    
    abstract function addActions();
    
    abstract function addFilters();
    
    abstract function renderField($data = null);

    abstract function template_field_metabox($post);
    
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
    
    public function getConf() {
        return $this->configuration;
    }
    
    public function setConf($conf) {
        $this->configuration = $conf;
    }
}
