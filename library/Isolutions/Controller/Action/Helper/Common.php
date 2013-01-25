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
class Isolutions_Controller_Action_Helper_Common extends Zend_Controller_Action_Helper_Abstract
{	
	/**
	* Checks if request is Ajax and POST method
	*
	* @param string $url
	* @return void
	*/
	public function isXmlHttpRequestPost($url = '/')
	{		
		//only if XmlHttpRequest and GET allowed
		try {
			if (!$this->getRequest()->isXmlHttpRequest())
				throw new Isolutions_Exception('Not XmlHttpRequest');
			if (!$this->getRequest()->isPost())
				throw new Isolutions_Exception('Not POST Request');
		} catch (Isolutions_Exception $e) {
			$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
			// redirect user to home page again
			$redirector->gotoUrlAndExit($url);
		}
	}
	
	/**
	* Checks if request is Ajax and GET method
	*
	* @param string $url
	* @return void
	*/
	public function isXmlHttpRequestGet($url = '/')
	{
		//only if XmlHttpRequest and GET allowed
		try {
			if (!$this->getRequest()->isXmlHttpRequest())
				throw new Isolutions_Exception('Not XmlHttpRequest');
			if (!$this->getRequest()->isGet()) 
				throw new Isolutions_Exception('Not GET Request');
		} catch (Isolutions_Exception $e) {
			$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
			// redirect user to home page again
			$redirector->gotoUrlAndExit($url);
		}
	}
	
	/**
	* Log Message to Logger
	*
	* @param string $msg
	* @param int $priority
	*/
	public function log($msg, $priority = Zend_Log::INFO)
	{
		if (Zend_Registry::isRegistered('log')) {
			$logger = Zend_Registry::get('log');
			$logger->log($msg,$priority);
		}
	}
	
	/**
	*  Log Message And Redirect to Home
	*
	* @param Zend_Controller_Action_Request $request
	* @param Yal_Exception $e
	* @param Zend_Log $priority
	*/
	public function logRedirect(Zend_Controller_Request_Http $request, Yal_Exception $e, $priority = Zend_Log::INFO)
	{
		if (Zend_Registry::isRegistered('log')) {
			$logger = Zend_Registry::get('log');
			$error  = 'requestUri: ' . $request->getRequestUri() . ', MCA: '
			. $request->getModuleName() . '::'. $request->getControllerName() . '::'
			. $request->getActionName() . ', File:' . $e->getFile() . ', Line: '
			. $e->getLine() . ', Message: ' . $e->getMessage();
			$logger->log($error, $priority);
		}
		$flashMessage   = 'An Error Ocurred: ' . $e->getMessage();
		$flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
		$flashMessenger->addMessage($flashMessage);
	
		$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
		$redirector->gotoUrl('/');
	}
	
	/**
	* Flash Message and redirect
	*
	* @param string $msg
	* @param string $url
	*/
	public function flashMessageRedirect($msg, $url = '/')
	{
		$flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
		$flashMessenger->addMessage($msg);
	
		$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
		$redirector->gotoUrl($url);
	}
	
	/**
	* Get Identity Object
	*
	* @return mixed
	*/
	public function getIdentity()
	{
		$identity = NULL;
		if (Zend_Registry::isRegistered('identity')) {
			$identity = Zend_Registry::get('identity');
		}
		return $identity;
	}
	
	
}