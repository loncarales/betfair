<?php

// Zend Framework cannot deal with Resources using namespaces
//namespace Bisna\Application\Resource;

use Bisna\Doctrine\Container as DoctrineContainer;

/**
 * Zend Application Resource Doctrine class
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 */
class Bisna_Application_Resource_Doctrine extends \Zend_Application_Resource_ResourceAbstract
{
    /**
     * Initializes Doctrine Context.
     *
     * @return Bisna\Application\Doctrine\Container
     */
    public function init()
    {
        $config = $this->getOptions();
        
        // Bootstrapping Doctrine autoloaders
        $this->registerAutoloaders();
        
        // Starting Doctrine container
        $container = new DoctrineContainer($config);

        // Add to Zend Registry
        \Zend_Registry::set('doctrine', $container);

        return $container;
    }
	
    /**
    * Register Doctrine autoloaders
    *
    * @param array Doctrine global configuration
    */
    private function registerAutoloaders()
    {
    	$autoloader = \Zend_Loader_Autoloader::getInstance();
    	$doctrineIncludePath = APPLICATION_PATH . '/../library/vendor/Doctrine';
    	
    	require_once $doctrineIncludePath . '/Common/ClassLoader.php';
    	
    	$symfonyAutoloader = new \Doctrine\Common\ClassLoader('Symfony');
    	$autoloader->pushAutoloader(array($symfonyAutoloader, 'loadClass'), 'Symfony');
    	
    	$doctrineExtensionsAutoloader = new \Doctrine\Common\ClassLoader('DoctrineExtensions');
    	$autoloader->pushAutoloader(array($doctrineExtensionsAutoloader, 'loadClass'), 'DoctrineExtensions');
    	
    	$doctrineAutoloader = new \Doctrine\Common\ClassLoader('Doctrine');
    	$autoloader->pushAutoloader(array($doctrineAutoloader, 'loadClass'), 'Doctrine');
    }
}