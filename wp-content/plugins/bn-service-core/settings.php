<?php
use \BN\Core\Helpers;

if (!defined('ABSPATH')){
    die;
}
$version = Helpers::getCoreFormaTxt();
return array(
    "libraries"  => array(
        "symfony" => array(
            "ID" => "symfony",
            "title" => "Symfony",
            "composer" => "symfony/symfony",
            "version" => "^3.4",
            "required" => TRUE
        ),
        "composer" => array(
            "ID" => "composer",
            "title" => "Composer",
            "composer" => "composer/composer",
            "version" => "1.6.4",
            "required" => TRUE
        )
    ),
    "core-forma-version" => $version
);
