<?php
namespace BN\ContentProject\Field;

use BN\ContentProject\LibraryController;
use BN\ContentProject\Helpers;
use BN\ContentProject\External\Factory;
class Featuredgallery extends Field {
    protected $configuration;
    private $server;
    private $url;
    private $entryid;

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
        add_action('wp_ajax_getfeaturedgalleryview', array(&$this, 'renderEmbedExternal'));
        add_action('admin_enqueue_scripts', array(&$this, 'featuredgalleryEnqueueScripts')); 
    }
    
    public function addFilters() {
        
    }
    
    /*public function get_field_pre_value($post_id){
        $field_value = unserialize(get_post_meta($post_id, $this->configuration["metakey"], TRUE));
        $response = (isset($field_value['server']) && !empty($field_value['server'])) ? $field_value : "";
        return $response;
    }*/
    
    public function __construct($conf, $server = null, $url = null, $entryid = null) {
        if (isset($server) && !empty($server)){
            $this->server = $server;
        }
        if (isset($url) && !empty($url)){
            $this->url = $url;
        }
        if (isset($entryid) && !empty($entryid)){
            $this->entryid = $entryid;
        }
        parent::__construct($conf);
    }
    
    public function specific_field_metabox(){
        $tab_default = __('Create Gallery');
        $tabs_to_show = array(
            __('Create Gallery')
        );
        $messages = array(
            "removeattachment" => __('Remove Featured Gallery','zbn-contenttype-project'),
            "setattachment" => __('Set Featured Gallery', 'zbn-contenttype-project')
        );
        wp_localize_script('zbn-contenttype-project-'.$this->configuration["id"].'-metabox', 'tabdefault', $tab_default);
        wp_localize_script('zbn-contenttype-project-'.$this->configuration["id"].'-metabox', 'tabstoshow', $tabs_to_show);
        wp_localize_script('zbn-contenttype-project-'.$this->configuration["id"].'-metabox', 'messages', $messages);
    }
    
    public function renderField($featuredgallery = null){
        $shortcode = (isset($featuredgallery)) ? $featuredgallery : "";
        echo do_shortcode($shortcode);
    }
    
    public function renderEmbedExternal() {
        $data = array();
        $shortcode = Helpers::getRequestPostParam("shortcode");
        
        $data['success'] = (isset($shortcode) && !empty($shortcode)) ? TRUE : FALSE;
        $data['html'] = do_shortcode(stripslashes($shortcode));
        wp_send_json($data);
    }
    
    public function featuredgalleryEnqueueScripts() {
        wp_register_script(
            'zbn-contenttype-project-featuredgallery-metabox', 
            Helpers::jsUrl('zbn-contenttype-project-featuredgallery-metabox.js'), 
            array('jquery'), 
            '20170604', 
            false
        );
    }
    
    public function setServer($server){
        $this->server = $server;
    }
    
    public function setUrl($url){
        $this->url = $url;
    }
    
    public function setEntryid($entryid){
        $this->entryid = $entryid;
    }
    
    public function getServer(){
        return $this->server;
    }
    
    public function getUrl(){
        return $this->url;
    }
    
    public function getEntryid(){
        return $this->entryid;
    }
    
}
