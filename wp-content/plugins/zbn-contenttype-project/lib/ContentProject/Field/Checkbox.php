<?php
namespace BN\ContentProject\Field;

use BN\ContentProject\LibraryController;
use BN\ContentProject\Helpers;

class Checkbox extends Field {
    protected $configuration;

    public function validation_field(){
        
    }
    
    public function save_field_metadata($post_id){
        $current_areatext = Helpers::getRequestPostParam($this->configuration['metakey']);
        if (isset($current_areatext)){
            update_post_meta($post_id, $this->configuration['metakey'], TRUE);
        } else {
            update_post_meta($post_id, $this->configuration['metakey'], FALSE);
        }
    }
    
    public function addActions() {
        
    }
    
    public function addFilters() {
        
    }
    
    public function specific_field_metabox(){
        
    }
    
    public function renderField($data = null){
        
    }
    
    public function get_field_pre_value($post_id){
        $postmeta = (bool) get_post_meta($post_id, $this->configuration["metakey"], TRUE);
        
        $result = ($postmeta) ? "TRUE" : "FALSE";
        return $result;
    }
}
