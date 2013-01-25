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
 * Isolutions Application Resource View Headscript
 *
 * @author Ales Loncar <ales@internet-solutions.si>
 * @category   Isolutions
 * @package    Isolutions_Application
 * @copyright  Copyright (c) 2012 Internet Solutions (http://www.internet-solutions.si/)
 *
 */
class Isolutions_Application_Resource_Headscripts extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Zend_View
     */
    protected $_view = null;

    /**
     * HeadScript view helper initialization
     *
     * @return void
     */
    public function init()
    {
        $this->getBootstrap()->bootstrap('view');
        $this->_view = $this->getBootstrap()->getResource('view');
        $this->setHeadScripts();
    }

    /**
     * Set <script> elements
     *
     * @return void
     */
    public function setHeadScripts()
    {
        foreach ($this->getOptions() as $headScript => $options) {

            $method = ((array_key_exists('method', $options)) &&
                (isset($options['method'])))
                    ? $options['method']
                    : 'headScript';

            $mode = ((array_key_exists('mode', $options)) &&
                (isset($options['mode'])))
                    ? $options['mode']
                    : 'FILE';

            $spec = ((array_key_exists('spec', $options)) &&
                (isset($options['spec'])))
                    ? $options['spec']
                    : null;

            $placement = ((array_key_exists('placement', $options)) &&
                (isset($options['placement'])))
                    ? $options['placement']
                    : 'APPEND';

            switch ($method) {
                case 'appendScript':
                    $type = ((array_key_exists('type', $options)) &&
                            (isset($options['type'])))
                                ? $options['type']
                                : 'text/javascript';
                    $attrs = ((array_key_exists('attrs', $options)) &&
                            (isset($options['attrs'])))
                                ? $options['attrs']
                                : array();
                    $script = ((array_key_exists('script', $options)) &&
                            (isset($options['script'])))
                                ? $options['script']
                                : null;
                    $this->_view->headScript()->appendScript($script, $type, $attrs);
                    break;

                default:
                    $this->_view->headScript($mode, $spec, $placement);
                    break;
            }            
        }
    }
}