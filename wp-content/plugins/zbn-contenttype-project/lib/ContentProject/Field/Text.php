<?php
namespace BN\ContentProject\Field;

use BN\ContentProject\LibraryController;
use BN\ContentProject\Helpers;

class Text extends Field {
    protected $configuration;

    public function validation_field(){
        
    }
    
    public function save_field_metadata($post_id){
        $updatemeta = FALSE;
        $prev_areatext = get_post_meta($post_id, $this->configuration['metakey'], true);
        $current_areatext = Helpers::getRequestPostParam($this->configuration['metakey']);
        if (isset($prev_areatext) and !empty($prev_areatext)){
            if($prev_areatext !== $current_areatext) {
                $updatemeta = TRUE;
                update_post_meta($post_id, $this->configuration['metakey'], $current_areatext);
            }
        } else {
            update_post_meta($post_id, $this->configuration['metakey'], $current_areatext);
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
}
