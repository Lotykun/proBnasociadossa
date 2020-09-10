<?php
namespace BN\Core\Manager;

use \BN\Core\Helpers;
use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Manager {
    
    const VENDOR_DIR = 'vendor';
    
    public static function install($packages) {
        $prev_packages = self::dump();
        if ($packages == $prev_packages) {
            return false;
        }
        putenv('COMPOSER_HOME=' . Helpers::ROOT . '/vendor/bin/composer');
        self::createComposerJson($packages);
        chdir(Helpers::ROOT);
        // Setup composer output formatter
        $stream = fopen('php://temp', 'w+');
        $output = new StreamOutput($stream);
        // Programmatically run `composer install`
        
        $application = new Application();
        $application->setAutoExit(false);
        $code = $application->run(new ArrayInput(array('command' => 'install')), $output);
        // remove composer.lock
        if (file_exists(Helpers::ROOT . '/composer.lock')) {
            unlink(Helpers::ROOT . '/composer.lock');
        }
        // rewind stream to read full contents
        rewind($stream);
        return stream_get_contents($stream);
    }
    
    public static function dump() {
        $composer_file = Helpers::ROOT . '/composer.json';
        if (file_exists($composer_file)) {
            $composer_json = json_decode(file_get_contents($composer_file), true);
            return $composer_json['require'];
        } else {
            return array();
        }
    }
    
    public static function autoload() {
        $autoload_file = Helpers::ROOT . '/' . self::VENDOR_DIR . '/autoload.php';
        if (file_exists($autoload_file)) {
            require $autoload_file;
        }
    }
    
    protected static function createComposerJson($packages) {
        $composer_json = str_replace("\/", '/',
            json_encode(
                array(
                    'config' => array('vendor-dir' => self::VENDOR_DIR),
                    'name' => 'bn/core',
                    'licence' => 'proprietary',
                    'type' => 'project',
                    'require' => $packages,
                ),
                JSON_PRETTY_PRINT
            )
        );
        $response = file_put_contents(Helpers::ROOT . '/composer.json', $composer_json);
        chmod(Helpers::ROOT . '/composer.json',0664);
        return $response;
    }
}