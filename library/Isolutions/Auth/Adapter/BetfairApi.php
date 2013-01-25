<?php
/**
* Isolutions Library
*
* @author Ales Loncar <ales.loncar@internet-solutions.si>
* @category   Isolutions
* @package    Isolutions_Auth
* @copyright  Copyright (c) 2012 Internet Solutions (http://www.internet-solutions.si/)
*
*/

/**
 * Auth Adapter for Betfair Api
 *
 * @author Ales Loncar <ales.loncar@internet-solutions.si>
 * @category   Isolutions
 * @package    Isolutions_Auth
 * @copyright  Copyright (c) 2012 Internet Solutions (http://www.internet-solutions.si/)
 *
 */
class Isolutions_Auth_Adapter_BetfairApi implements Zend_Auth_Adapter_Interface
{
	/**
	* $_identity - Identity value
	*
	* @var string
	*/
	protected $_identity = null;
	
	/**
	* $_username - Username
	*
	* @var string
	*/
	protected $_username = null;
	
	/**
	 * $_password - Password
	 *
	 * @var string
	 */
	protected $_password = null;
	
	/**
	* $_authenticateResultInfo
	*
	* @var array
	*/
	protected $_authenticateResultInfo = null;
	
	/**
	* Sets username and password for authentication
	*
	* @return void
	*/
	public function __construct($username = null, $password = null)
	{
		if (null !== $username) {
			$this->setUsername($username);
		}
		
		if (null !== $password) {
			$this->setPassword($password);
		}
	}
	
	/**
	* setUsername() - set the username
	*
	* @param  string $username
	* @return Isolutions_Auth_Adapter_BetfairApi Provides a fluent interface
	*/
	public function setUsername($username)
	{
		$this->_username = $username;
		return $this;
	}
	
	/**
	 * setPassword() - set the password
	 *
	 * @param  string $identityColumn
	 * @return Isolutions_Auth_Adapter_BetfairApi Provides a fluent interface
	 */
	public function setPassword($password)
	{
		$this->_password = $password;
		return $this;
	}
	
	/**
	* authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
	* attempt an authenication.  Previous to this call, this adapter would have already
	* been configured with all nessissary information to successfully connect to a database
	* table and attempt to find a record matching the provided identity.
	*
	* @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
	* @return Zend_Auth_Result
	*/
	public function authenticate()
	{
		$this->_authenticateSetup();
		$resultIdentity = $this->_authenticateThroughApi();
		$authResult = $this->_authenticateValidateResult($resultIdentity);
		return $authResult;
	}
	
	/**
	* _authenticateSetup() - This method abstracts the steps involved with making sure
	* that this adapter was indeed setup properly with all required peices of information.
	*
	* @throws Zend_Auth_Adapter_Exception - in the event that setup was not done properly
	* @return true
	*/
	protected function _authenticateSetup()
	{
		$exception = null;
		
		if ($this->_username == '') {
			$exception = 'A username value was not provided prior to authentication with Isolutions_Auth_Adapter_BetfairApi';
		} elseif ($this->_password == '') {
			$exception = 'A password value was not provided prior to authentication with Isolutions_Auth_Adapter_BetfairApi';
		}
		
		$this->_authenticateResultInfo = array(
		            'code'     => Zend_Auth_Result::FAILURE,
		            'identity' => $this->_identity,
		            'messages' => array()
		);
		
		return true;
	}
	
	/**
	* _authenticateThroughtApi() - This method tries to authenticate user through API
	*
	* @throws Zend_Auth_Adapter_Exception - when a invalid select object is encoutered
	* @return array
	*/
	protected function _authenticateThroughApi()
	{
		try {
			$betfair = new Isolutions_Betfair_Api_Global();
			$apiResponse = $betfair->login($this->_username, $this->_password);
		} catch (Exception $e) {
            /**
             * @see Zend_Auth_Adapter_Exception
             */
            throw new Zend_Auth_Adapter_Exception('The supplied parameters to Isolutions_Auth_Adapter_BetfairApi failed to '
                                                . 'produce a valid result');
        }
        return $apiResponse;
	}
	
	/**
	* _authenticateValidateResult() - This method attempts to validate that the record in the
	* result set is indeed a record that matched the identity provided to this adapter.
	*
	* @param stdClass $resultIdentity
	* @return Zend_Auth_Result
	*/
	protected function _authenticateValidateResult(stdClass $resultIdentity)
	{
		if (is_null($resultIdentity->sessionToken)) {
			$this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
			$this->_authenticateResultInfo['messages'][] = $resultIdentity->error;
			return $this->_authenticateCreateAuthResult();
		}
		
		$this->_authenticateResultInfo['code'] = Zend_Auth_Result::SUCCESS;
		$this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
		$this->_authenticateResultInfo['identity']   = $resultIdentity;
		return $this->_authenticateCreateAuthResult();
	}
	
	/**
	* _authenticateCreateAuthResult() - This method creates a Zend_Auth_Result object
	* from the information that has been collected during the authenticate() attempt.
	*
	* @return Zend_Auth_Result
	*/
	protected function _authenticateCreateAuthResult()
	{
		return new Zend_Auth_Result(
			$this->_authenticateResultInfo['code'],
			$this->_authenticateResultInfo['identity'],
			$this->_authenticateResultInfo['messages']
		);
	}
}