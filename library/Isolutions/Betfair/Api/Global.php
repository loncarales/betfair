<?php
class Isolutions_Betfair_Api_Global extends Isolutions_Betfair_Api
{
	const WSDL = 'https://api.betfair.com/global/v3/BFGlobalService.wsdl';
	
	/**
	 * 
	 * Class Constructor
	 */
	public function __construct() 
	{
		parent::__construct(self::WSDL);
	}
	
	/**
	 * The API Login service enables customers to log in to the API service and initiates a secure session for the user
	 * 
	 * @param string $username
	 * @param string $password
	 * @return stdClass
	 */
	public function login($username, $password) 
	{
		$request = array(
    		'username'  => $username,
    		'password'	=> $password,
    		'productId'	=> self::PRODUCT_ID,
    		'vendorSoftwareId' => self::VENDOR_SOFTWARE_ID,
    		'locationId'	=> self::LOCATION_ID,
    		'ipAddress'	=> self::IP_ADDRESS
		);
		$soapReq = array('request' => $request);
		$soapResp = $this->_client->login($soapReq);
		return Isolutions_Betfair_Response::getInstance()->handleLogin($soapResp);
	}
	
	/**
	 * The API GetEvents service allows you to navigate through the events hierarchy until you reach details of the betting 
	 * market for an event that you are interested in.
	 * 
	 * 
	 * @param int $eventId
	 * @throws Isolutions_Betfair_Exception
	 * @return stdClass
	 */
	public function getEvents($eventId)
	{
		$identity = $this->_getIdentity();
		if (is_null($identity))
			throw new Isolutions_Betfair_Exception('No Idenity Object');
		
		$request = array(
			'header' => array(
				'clientStamp' 	=> self::CLIENT_STAMP,
				'sessionToken'	=> $identity->sessionToken 
			),
			'eventParentId'	=> $eventId,
			'locale'	=> self::LOCALE
		);
		
		$soapReq  = array('request' => $request);
		$soapResp = $this->_client->getEvents($soapReq);
		return Isolutions_Betfair_Response::getInstance()->handleAllEvents($soapResp);
	}
	
	/**
	 * The API GetAllEventTypes service allows the customer to retrieve lists of all categories of sports (Games, Event Types) that have at 
	 * least one market associated with them
	 * 
	 * @throws Isolutions_Betfair_Exception
	 * @return stdClass
	 */
	public function getAllEventTypes() {
		$identity = $this->_getIdentity();
		if (is_null($identity))
			throw new Isolutions_Betfair_Exception('No Idenity Object');
		
		$request = array(
			'header' => array(
				'clientStamp' 	=> self::CLIENT_STAMP,
				'sessionToken'	=> $identity->sessionToken 
			),
			'locale'	=> self::LOCALE
		);
		$soapReq  = array('request' => $request);
		$soapResp = $this->_client->getAllEventTypes($soapReq);
		return Isolutions_Betfair_Response::getInstance()->handleEventTypes($soapResp);
	}
	
	public function getActiveEventTypes() {
		$identity = $this->_getIdentity();
		if (is_null($identity)) 
			throw new Isolutions_Betfair_Exception('No Idenity Object');
		
		$request = array(
			'header' => array(
				'clientStamp' 	=> self::CLIENT_STAMP,
				'sessionToken'	=> $identity->sessionToken 
			),
			'locale'	=> self::LOCALE
		);
		
		$soapReq  = array('request' => $request);
		$soapResp = $this->_client->getActiveEventTypes($soapReq);
		return Isolutions_Betfair_Response::getInstance()->handleEventTypes($soapResp);
	} 
}