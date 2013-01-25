<?php
/**
 * Isolutions Library
 *
 * @author Ales Loncar <ales@internet-solutions.si>
 * @category   Isolutions
 * @package    Isolutions_Controller
 * @copyright  Copyright (c) 2012 Internet Solutions (http://www.internet-solutions.si/)
 *
 */

/**
 * Isolutions Controller Action
 *
 * @author Ales Loncar <ales@internet-solutions.si>
 * @category   Isolutions
 * @package    Isolutions_Controller
 * @copyright  Copyright (c) 2012 Internet Solutions (http://www.internet-solutions.si/)
 *
 */
class Isolutions_Controller_Action extends Zend_Controller_Action
{
	
	/**
	 * Global Strings
	 * 
	 * @var array
	 */
	protected $_strings = array();
	
    /**
    * before init method
    *
    * @return void
    * @access protected
    */
    protected function _beforeInit()
    {
    	if (Zend_Registry::isRegistered('config')) {
	    	$config = Zend_Registry::get('config');
	    	if (isset($config->strings)) {
	    		$this->_strings = $config->strings->toArray();
	    	}
    	}
    }//end _beforeInit()

    /**
    * after init method
    *
    * @return void
    * @access protected
    */
    protected function _afterInit()
    {
    	/* Initialize action controller here */
    	$flashMessages = $this->_helper->getHelper('FlashMessenger')->getMessages();
    	// view variables
    	$this->view->flashMessage = ($flashMessages) ? $flashMessages[0] : '';
    	
    }//end _afterInit()

    /**
     * Class constructor
     *
     * The request and response objects should be registered with the
     * controller, as should be any additional optional arguments; these will be
     * available via {@link getRequest()}, {@link getResponse()}, and
     * {@link getInvokeArgs()}, respectively.
     *
     * When overriding the constructor, please consider this usage as a best
     * practice and ensure that each is registered appropriately; the easiest
     * way to do so is to simply call parent::__construct($request, $response,
     * $invokeArgs).
     *
     * After the request, response, and invokeArgs are set, the
     * {@link $_helper helper broker} is initialized.
     *
     * Finally, {@link init()} is called as the final action of
     * instantiation, and may be safely overridden to perform initialization
     * tasks; as a general rule, override {@link init()} instead of the
     * constructor to customize an action controller's instantiation.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Controller_Response_Abstract $response
     * @param array $invokeArgs Any additional invocation arguments
     * @return void
     */
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        // Your stuff here to run before init()
        $this->_beforeInit();

        // Note: $this->getRequest, $this->getResponse() and $this->getInvokeArgs() (also action helpers)
        // are not available until after the construct is run, but you can use $request, $response and $invokeArgs.
        parent::__construct($request, $response, $invokeArgs);

        // Your stuff below here... to run after init(), but before self::preDispatch()
        $this->_afterInit();
    }
}