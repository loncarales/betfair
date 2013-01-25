<?php 
class Isolutions_Betfair_Api_Exchange extends Isolutions_Betfair_Api
{
	protected $_exchangeWsdl = array(
		1 => 'https://api.betfair.com/exchange/v5/BFExchangeService.wsdl',
		2 => 'https://api-au.betfair.com/exchange/v5/BFExchangeService.wsdl'
	);
	
	/**
	 * 
	 * Class Constructor
	 */
	public function __construct($exchangeId) 
	{
		if (!array_key_exists($exchangeId, $this->_exchangeWsdl))
			throw new InvalidArgumentException('Exchange Id not valid');
			
		parent::__construct($this->_exchangeWsdl[$exchangeId]);
	}
	
	/**
	 * The API GetMarketInfo service allows you to input a Market ID and retrieve market data for the market requested.
	 * To get a Market ID for the betting market associated with an event you are interested in, use the GetEvents command.
	 * 
	 * @param int $marketId
	 * @throws Isolutions_Betfair_Exception
	 * @return stdClass
	 */
	public function getMarketInfo($marketId)
	{
		$identity = $this->_getIdentity();
		if (is_null($identity))
			throw new Isolutions_Betfair_Exception('No Idenity Object');
			
		$request = array(
			'header' => array(
				'clientStamp' 	=> self::CLIENT_STAMP,
				'sessionToken'	=> $identity->sessionToken 
			),
			'marketId'	=> $marketId
		);
		
		$soapReq  = array('request' => $request);
		$soapResp = $this->_client->getMarketInfo($soapReq);
		return Isolutions_Betfair_Response::getInstance()->handleGetMarketInfo($soapResp);
	}
	
	
	/**
	 * The API GetMarket service allows the customer to input a Market ID and retrieve all static market data for the market requested.
	 * To get a Market ID for the betting market associated with an event you are interested in, use the GetEvents command.
	 * 
	 * @param int $marketId
	 * @throws Isolutions_Betfair_Exception
	 * @return stdClass
	 */
	public function getMarket($marketId)
	{
		$identity = $this->_getIdentity();
		if (is_null($identity))
			throw new Isolutions_Betfair_Exception('No Idenity Object');
			
		$request = array(
			'header' => array(
				'clientStamp' 	=> self::CLIENT_STAMP,
				'sessionToken'	=> $identity->sessionToken 
			),
			'marketId'	=> $marketId,
			'locale'	=> self::LOCALE,
			'includeCouponLinks' => 0
		);
		
		$soapReq  = array('request' => $request);
		$soapResp = $this->_client->getMarket($soapReq);
		return Isolutions_Betfair_Response::getInstance()->handleGetMarket($soapResp);
	}
}