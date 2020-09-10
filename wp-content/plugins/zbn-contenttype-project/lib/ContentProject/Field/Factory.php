<?php
namespace BN\ContentProject\Field;

use BN\Core\Field\iFactory;
use BN\ContentProject\Helpers;

class Factory implements iFactory {
    public static function get_field_instance($name) {
        $fields = Helpers::getOption(Helpers::NAMESPACE."_fields");
        if (isset($name) && !empty($name)){
            $conf = $fields['extra'][$name];
            $class = "\BN\ContentProject\Field\\".ucfirst($conf["type"]);
            $outputClass = new $class($conf);
        } else {
            $outputClass = "";
        }
        return $outputClass;
    }
}
