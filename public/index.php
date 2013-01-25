<?php
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
    
// Define application environment
$servers  = array_fill_keys(array('betfair.localhost'), 'development');
$servers += array_fill_keys(array(''), 'staging');
$servers += array_fill_keys(array(''),'production');

if(isset($servers[$_SERVER['SERVER_NAME']])) {
    define('APPLICATION_ENV', $servers[$_SERVER['SERVER_NAME']]);
} else {
    // Define application environment // development
    // Define application environment
    defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
        exit("Application is not within configured servers! Server name is " . $_SERVER['SERVER_NAME']);
}
unset($servers);

// Define application name
defined('APPLICATION_NAME')
    || define('APPLICATION_NAME', (getenv('APPLICATION_NAME') ? getenv('APPLICATION_NAME') : 'BetfairWatchdog'));


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
	realpath(APPLICATION_PATH . '/../library/vendor'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';
/** Zend_Cache */
require_once 'Zend/Cache.php';
/** Zend_Config_Ini */
require_once 'Zend/Config/Ini.php';

$frontendOptions = array(
    'name' => 'File',
    'params' => array(
        'lifetime' => null,
        'automatic_cleaning_factor' => 0,
        'automatic_serialization' => true,
        'master_files' => array(
            APPLICATION_PATH . '/configs/application.ini',
            APPLICATION_PATH . '/configs/http.ini'
        )
    )
);
// Slowest
$backendOptions = array(
    'name' => 'File',
    'params' => array(
        'cache_dir' => APPLICATION_PATH . '/../data/cache/config',
    )
);
$configCache = Zend_Cache::factory(
    $frontendOptions['name'],
    $backendOptions['name'],
    $frontendOptions['params'],
    $backendOptions['params']
);

$finalConfig = null;
if (!($finalConfig = $configCache->load(APPLICATION_NAME . 'Configuration'))) {
    $configFiles = array(
        APPLICATION_PATH . '/configs/application.ini',
        APPLICATION_PATH . '/configs/http.ini'

    );
    $masterConfig = null;
    foreach($configFiles as $file) {
        $config = new Zend_Config_Ini($file, APPLICATION_ENV, array('allowModifications'=>true));
        if (is_null($masterConfig)) {
            $masterConfig = $config;
        } else {
            $masterConfig->merge($config);
        }
    }
    $finalConfig = $masterConfig->toArray();
    $configCache->save($finalConfig, APPLICATION_NAME . 'Configuration');
}

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    $finalConfig
);
$application->bootstrap()
            ->run();