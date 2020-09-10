<?php
namespace BN\CORE\Controller;

use BN\CORE\Helpers;

abstract class Controller {
    protected $action = '';
    protected $params = array();
    protected $classView = '\\BN\\Core\\ViewRenderer';
    
    abstract protected function allowedActions();

    protected function addActions() {}
    
    protected function addFilters() {}

    
    public function setAction($action) {
        $this->action = $action;
    }
    
    public function getAction() {
        return $this->action;
    }
    
    public function execute() {
        $this->validateAccess();
        $action    = $this->getAction();
        $actionName = $action . 'Action';
        if(is_callable(array($this, $actionName))){
            return $this->$actionName();
        }
    }
    
    public function renderView($viewFile, array $params = array()) {
        $viewRenderer = new $this->classView();
        return $viewRenderer->renderView( $viewFile, $params );
    }
    
    protected function validateAccess() {
        $action = $this->getAction();
        if (!in_array($action, $this->allowedActions(), true)){
            die( 'Access denied' );
        }
    }
    
    public function setMessage($status, $txt) {
        $message = array(
            'status' => $status,
            'txt' => $txt,
        );
        $this->messages[] = $message;
        if(!isset($_SESSION["messages"])) {
            $_SESSION["messages"] = array();
        }
        $_SESSION["messages"][] = $message;
    }

    public function getMessages() {
        $messages = array();
        if(!empty($this->messages) && count($this->messages) > 0) {
            $messages = $this->messages;
        }
        else if(!empty($_SESSION["messages"]) && count($_SESSION["messages"]) > 0) {
            $messages = $_SESSION["messages"];
        }

        return $messages;
    }
    
    public function getParams() {
        return array_merge( array(
            'locale' => Helpers::LOCALE,
            'messages' => $this->getMessages(),
            'assets_url' => plugins_url('assets',Helpers::PLUGIN_FILE),
            'webName' => get_bloginfo( 'name' ),
            'access' => true,
        ), $this->params);
    }
    
    public function addParams($extra) {
        return $this->params = array_merge( $this->params, $extra);
    }
    
    public function emptyParams() {
        $this->params = array();
    }
}