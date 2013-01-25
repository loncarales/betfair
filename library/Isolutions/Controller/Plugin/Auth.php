<?php 
class Isolutions_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
	/**
	* Auth object
	*
	* @var Zend_Auth
	*/
	protected $_auth = null;
	
	/**
	* Constructor
	*
	* @return void
	**/
	public function __construct()
	{
		if (null === $this->getAuth()) {
			// set storage for logged in user
			$authData = Zend_Auth::getInstance();
			$authData->setStorage(new Zend_Auth_Storage_Session('Betfair'));
			$this->setAuth($authData);
		}	
	}
	
	/**
	* Predispatch
	* Checks if the current user identified by roleName has rights to the requested url (module/controller/action)
	* If not, it will call denyAccess to be redirected to errorPage
	*
	* @return void
	**/
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		// if not dispatchable, then don't check acl.
		// if not dispatchable Error Controller will take over
		$isDispatchable = Zend_Controller_Front::getInstance()
		->getDispatcher()
		->isDispatchable($request);
		
		if ($isDispatchable) {
			$identity = ($this->getAuth()->hasIdentity()) ? $this->getAuth()->getIdentity() : null;
			
			// Save to registry identity
			Zend_Registry::set('identity', $identity);
		}
	}
	
	/**
	* Sets the Auth object
	*
	* @param mixed $authData
	* @return void
	**/
	public function setAuth(Zend_Auth $authData)
	{
		$this->_auth = $authData;
	}
	
	/**
	 * Returns the Aauth object
	 *
	 * @return Zend_Auth
	 **/
	public function getAuth()
	{
		return $this->_auth;
	}
}