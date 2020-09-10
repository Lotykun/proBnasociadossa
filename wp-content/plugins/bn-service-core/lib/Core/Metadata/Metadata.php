<?php
namespace BN\Core\Metadata;

use \BN\Core\Helpers;

class Metadata {

    static public function saveConfigureData($key) {

        $response = array();
        $preload_data = array();
        $data = array();
        $preload_settings = Helpers::getOption($key);
        
        foreach ($_POST as $key => $value) {
            if (substr($key, 0, strlen("field_")) === "field_"){
                $parts = explode("_",$key);
                $field_id = $parts[1];
                $field_component = $parts[2];
                if (!isset($preload_data[$field_id]["id"]) || empty($preload_data[$field_id]["id"])){
                    $preload_data[$field_id]["id"] = $field_id;
                }
                $preload_data[$field_id][$field_component] = $value;
            }
        }
        foreach ($preload_data as $field => $value) {
            $data[$field]["id"] = $field;
            $data[$field]["label"] = $preload_data[$field]["label"];
            $data[$field]["type"] = $preload_data[$field]["type"];
            if (isset($preload_data[$field]["validation"]) && $preload_data[$field]["validation"] == "1"){
                $data[$field]["validation"] = array();
                if (isset($preload_data[$field]["validationwidth"])){
                    $data[$field]["validation"]["width"] = (int) $preload_data[$field]["validationwidth"];
                }
                if (isset($preload_data[$field]["validationpattern"])){
                    $data[$field]["validation"]["pattern"] = $preload_data[$field]["validationpattern"];
                }
            }
            $data[$field]["readonly"] = (isset($preload_data[$field]["readonly"]) && $preload_data[$field]["readonly"] == "1") ? TRUE : FALSE;
            $data[$field]["required"] = (isset($preload_data[$field]["isrequired"]) && $preload_data[$field]["isrequired"] == "1") ? TRUE : FALSE;
            $data[$field]["enabled"] = (isset($preload_data[$field]["isenabled"]) && $preload_data[$field]["isenabled"] == "1") ? TRUE : FALSE;
            if (isset($preload_data[$field]["hasmetabox"]) && $preload_data[$field]["hasmetabox"] == "1"){
                $data[$field]["metabox"] = array();
                if (isset($preload_data[$field]["metaboxid"])){
                    $data[$field]["metabox"]["id"] = $preload_data[$field]["metaboxid"];
                }
                if (isset($preload_data[$field]["metaboxcontext"])){
                    $data[$field]["metabox"]["context"] = $preload_data[$field]["metaboxcontext"];
                }
                if (isset($preload_data[$field]["metaboxlabel"])){
                    $data[$field]["metabox"]["label"] = $preload_data[$field]["metaboxlabel"];
                }
            }
            $data[$field]["metakey"] = $preload_data[$field]["metakey"];
        }
        if ($data != $preload_settings["extra"]){
            $preload_settings["extra"] = $data;
            $result = update_site_option($key, serialize($preload_settings));
            if ($result){
                $message = __('SETTINGS SAVED!!!', Helpers::LOCALE);
            } else {
                $message = __('DATABASE BROKEN!!!', Helpers::LOCALE);
            }
        } else {
            $result = FALSE;
            $message = __('NOTHING CHANGED...NOTHING TO DO!!!', Helpers::LOCALE);
        }
        
        $response["result"] = $result;
        $response["ERROR_message"] = $message;
        
        return $response;
    }

    static public function deleteConfigureData(){
        $response = array();
        
        $response["result"] = TRUE;
        $response["ERROR_message"] = __('DATABASE BROKEN!!!', Helpers::LOCALE);
        
        return $response;
    }

}