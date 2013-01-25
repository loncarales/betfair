<?php
/**
 * Isolutions Library
 *
 * @author Ales Loncar <ales@internet-solutions.si>
 * @category   Isolutions
 * @package    Isolutions_Application
 * @copyright  Copyright (c) 2012 Internet Solutions (http://www.internet-solutions.si/)
 *
 */

/**
 * Isolutions Controller Action
 *
 * @author Ales Loncar <ales@internet-solutions.si>
 * @category   Isolutions
 * @package    Isolutions_Application
 * @copyright  Copyright (c) 2012 Internet Solutions (http://www.internet-solutions.si/)
 *
 */
 class Isolutions_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Cache to use
     *
     * @var Zend_Cache
     */
    protected $_cache;

    /**
    * Defined by Zend_Application_Resource_Resource
    *
    * @return Zend_Cache
    */
    public function init()
    {
        return $this->getCache();
    }

    /**
     * Retrieve cache object
     *
     * @return Zend_Cache
     */
    public function getCache()
    {
        if (null === $this->_cache) {
            $options = $this->getOptions();

            if (!isset($options['frontend'])) {
                throw new Zend_Application_Resource_Exception('No Zend_Cache Frontend data provided.');
            }
            // cache frontend
            $frontend        = isset($options['frontend']['type']) ? $options['frontend']['type'] : 'Core';
            $frontendOptions = $options['frontend']['options'];

            if (!isset($options['backend'])) {
                throw new Zend_Application_Resource_Exception('No Zend_Cache Backend data provided.');
            }
            // cache backend
            $backend         = isset($options['backend']['type']) ? $options['backend']['type'] : 'File';
            $backendOptions  = $options['backend']['options'];


            switch (strtolower($backend)) {
                case 'file':
                default:
                            if (!isset($backendOptions['cache_dir'])) {
                                $backendOptions['cache_dir'] = APPLICATION_PATH . '/../data/cache/files';
                            }
                    break;
                case 'xcache':
                            if (!extension_loaded('xcache')) {
                               throw new Zend_Application_Bootstrap_Exception(
                                   'PHP extension Xcache is not loaded!'
                               );
                            }
                    break;
                case 'apc':
                    if (!extension_loaded('apc')) {
                       throw new Zend_Application_Bootstrap_Exception(
                           'PHP extension APC is not loaded!'
                       );
                    }
                    $backendOptions  = array();
                    break;
                case 'memcached':
                    if (!extension_loaded('memcache')) {
                       throw new Zend_Application_Bootstrap_Exception(
                           'PHP extension Memcache is not loaded!'
                       );
                    }
                    break;
            }

            $cache = Zend_Cache::factory(
                 $frontend,
                 $backend,
                 $frontendOptions,
                 $backendOptions
             );

             $this->_cache = $cache;
             Zend_Registry::set('cache',$cache);
        }

        return $this->_cache;
    }
}