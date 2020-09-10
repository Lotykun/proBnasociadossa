<?php
namespace BN\Core\Field;

interface iField {
    
    public function specific_field_metabox();
    
    public function save_field_metadata($post_id);
    
    public function validation_field();
    
    public function addActions();
    
    public function addFilters();
    
    public function renderField($data = null);
    
    public function get_field_pre_value($post_id);
}
