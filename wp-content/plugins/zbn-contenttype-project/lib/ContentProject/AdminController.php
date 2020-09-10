<?php
namespace BN\ContentProject;

use BN\ContentProject\Helpers;
use BN\Core\Metadata;

class AdminController extends BaseController {
    static $instance = null;
        
    static function & getInstance() {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    protected function allowedActions() {
        return array(
            'configure',
            'delete',
            'savedata',
        );
    }
    public function execute() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Access denied' );
        }
        parent::execute();
    }
    
    public function configureAction() {
        $literals = array(
            "actions" => array(
                "validation" => __('Edit Validation', Helpers::LOCALE),
                "delete" => __('Delete', Helpers::LOCALE),
            ),
            "placeholder" => __('Please Fill', Helpers::LOCALE),
            "question" => __('Are you Sure?', Helpers::LOCALE),
        );
        wp_localize_script(Helpers::NAME.'-configure', 'ajaxurl', Helpers::ajaxUrl());
        wp_localize_script(Helpers::NAME.'-configure', 'configureLiterals', $literals);
        wp_enqueue_script(Helpers::NAME.'-configure');
        wp_enqueue_style(Helpers::NAME.'-configure');
        $fields = Helpers::getOption(Helpers::NAMESPACE."_fields");
        $this->addParams(array(
            "fields" => $fields["extra"],
            "formUrl" => "#"
        ));
        $params = $this->getParams();
        add_thickbox();
        echo $this->renderView( 'admin/configure.twig', $params );
    }
    
    public function deleteAction() {
        $this->emptyParams();
        if (count($_POST)) {
            if ( !wp_verify_nonce( isset( $_POST['_contenttypepostnonce'] ) ? $_POST['_contenttypepostnonce'] : null, 'configure' )) {
                print 'Sorry, your nonce did not verify.';
                exit;
            }
            $delete = $this->deleteConfigureData();
            if ($delete["result"]){
                $message = __('Settings deleted with success!!!', Helpers::LOCALE);
                $type = "notice-success";
            } else {
                $message = $delete["ERROR_message"];
                $type = "notice-error";
            }
            $this->addParams(array(
                "notice" => array(
                    "message" => $message,
                    "type" => $type
                )
            ));
        }
        $this->configureAction();
    }
    
    public function savedataAction() {
        $this->emptyParams();
        if (count($_POST)) {
            if ( !wp_verify_nonce( isset( $_POST['_contenttypeprojectnonce'] ) ? $_POST['_contenttypeprojectnonce'] : null, 'configure' )) {
                print 'Sorry, your nonce did not verify.';
                exit;
            }
            $saved = $this->saveConfigureData();
            if ($saved["result"]){
                $message = __('Settings saved with success!!!', Helpers::LOCALE);
                $type = "notice-success";
            } else {
                $message = $saved["ERROR_message"];
                $type = "notice-error";
            }
            $this->addParams(array(
                "notice" => array(
                    "message" => $message,
                    "type" => $type
                )
            ));
        }
        $this->configureAction();
    }
    
    private function saveConfigureData() {

        $key = Helpers::NAMESPACE."_fields";        
        
        $response = Metadata::saveConfigureData($key);
        
        return $response;
    }
    
    private function deleteConfigureData(){
        
        $response = Metadata::deleteConfigureData($key);
        
        return $response;
    }
}