<?php
namespace BN\Core;

use BN\Core\Helpers;
use BN\Core\Manager\Manager;

class LibraryController extends BaseController {
    static $instance = null;

    static function & getInstance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    protected function allowedActions() {
        return array(
            'init',
            'asyncfunction',
        );
    }
    
    public function execute() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            wp_die( 'Access denied' );
        }
        parent::execute();
    }
    
    public function initAction() {
        $literals = array(
            "actions" => array(
                "delete" => __('Delete', Helpers::LOCALE),
            ),
            "placeholder" => __('Please Fill', Helpers::LOCALE),
            "question" => __('Are you Sure?', Helpers::LOCALE),
        );
        $libraries = Helpers::getOption("libraries");
        if (count($_POST)) {
            if (!wp_verify_nonce( isset( $_POST['_corenonce'] ) ? $_POST['_corenonce'] : null, 'init')){
                print 'Sorry, your nonce did not verify.';
                exit;
            }
            /*$packages = array(
                "kaltura" => array(
                    "composer" => "kaltura/api-client-library",
                    "version" => "v13.17.0"
                )
            );*/
            $saved = $this->saveConfigureData();
            $return = Manager::install(Helpers::createJsonArray($saved["data"]));
            $type = ($saved["result"]) ? "notice-success" : "notice-error";
            $message = $saved["message"].PHP_EOL.$return;
            
            $this->addParams(array(
                "notice" => array(
                    "message" => $message,
                    "type" => $type
                )
            ));
        }
        wp_localize_script(Helpers::NAME.'-configure', 'ajaxurl', Helpers::ajaxUrl());
        wp_localize_script(Helpers::NAME.'-configure', 'configureLiterals', $literals);
        wp_enqueue_script(Helpers::NAME.'-configure');
        wp_enqueue_style(Helpers::NAME.'-configure');
        
        $this->addParams(array(
            'libraries' => $libraries,
            'formUrl' => "#",
        ));
        $params = $this->getParams();
        echo $this->renderView( 'front/init.twig', $params );
    }
    
    public function asyncfunctionAction() {
        $data = array();
        $element = Helpers::getRequestPostParam("element");
        wp_send_json($data);
    }
    
    private function saveConfigureData(){
        $response = array();
        $preload_data = array();
        $data = array();
        $preload_settings = Helpers::getOption("libraries");
        
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, strlen("library_")) === "library_"){
                $parts = explode("_",$key);
                $library_id = $parts[1];
                $library_component = $parts[2];
                $preload_data[$library_id][$library_component] = $value;
            }
        }
        foreach ($preload_data as $key => $value) {
            $data[$key]['ID'] = $key;
            $data[$key]['title'] = $value['title'];
            $data[$key]['composer'] = $value['composer'];
            $data[$key]['version'] = $value['version'];
            $data[$key]['required'] = (bool)$value['required'];
        }
        if ($data != $preload_settings){
            $response["data"] = $data;
            $result = update_site_option("libraries", serialize($data));
            $message = ($result) ? __('SETTINGS SAVED!!!', Helpers::LOCALE) : __('DATABASE BROKEN!!!', Helpers::LOCALE);
        } else {
            $result = FALSE;
            $message = __('NOTHING CHANGED...NOTHING TO DO!!!', Helpers::LOCALE);
        }
        
        $response["result"] = $result;
        $response["message"] = $message;
        
        return $response;
    }
    
}