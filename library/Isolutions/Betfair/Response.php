<?php 
class Isolutions_Betfair_Response
{
	/**
     * Betfair Response handles the response of the API Service
     * 
     * @var Isolutions_Betfair_Response
     */
	private static $instance = null;

	private function __construct(){}

	/**
     * Retrieves the default instance.
     *
     * @return Isolutions_Betfair_Response
     */
	public static function getInstance()
	{
		if ( is_null( self::$instance ) )
		{
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Authenticate Through Betfair API
	 * 
	 * @param string $username
	 * @param string $password
	 * @return Zend_Auth_Result
	 */
	public function authenticate($username, $password) {
		$auth = Zend_Auth::getInstance();
		$auth->setStorage(new Zend_Auth_Storage_Session('Betfair'));
		$adapter = new Isolutions_Auth_Adapter_BetfairApi();
		$adapter->setUsername($username)
				->setPassword($password);
		// Authenticate on adapter
		return  $auth->authenticate($adapter);
	}
	
	public function handleGetMarketInfo($soapResponse)
	{
		$response = null;
		if (isset($soapResponse->Result->header)) {
			$sessionToken = $soapResponse->Result->header->sessionToken;
			$errorMsg = null;
			
			// look for error response
			$errorCode = $soapResponse->Result->errorCode;
			switch ($errorCode) {
				case 'OK':
					$errorMsg = Isolutions_Betfair_Constants::OK;
					break;
				case 'API_ERROR':
					//@TODO handle API Error
					$errorMsg = Isolutions_Betfair_Constants::API_ERROR;
					break;
				case 'INVALID_LOCAL_DEFAULTING_TO_ENGLISH':
					$errorMsg = Isolutions_Betfair_Constants::INVALID_LOCAL_DEFAULTING_TO_ENGLISH;
					break;
				case 'INVALID_MARKET':
					$errorMsg = Isolutions_Betfair_Constants::INVALID_MARKET;
					break;
				case 'MARKET_TYPE_NOT_SUPPORTED':
					$errorMsg = Isolutions_Betfair_Constants::MARKET_TYPE_NOT_SUPPORTED;
					break;
			}
			
			$response = new stdClass();
			$response->sessionToken = $sessionToken;
			$response->error = $errorMsg;
			if ($errorMsg == '' || $errorMsg == 'INVALID_LOCAL_DEFAULTING_TO_ENGLISH') {
				$marketInfo = array();
				if (property_exists($soapResponse->Result, "marketLite")) {
					$response->marketInfo = $soapResponse->Result->marketLite;
				}
			}
		}
		// @TODO handle SessionToken AND remove it from response
		return $response;
	}
	
	public function handleGetMarket($soapResponse)
	{
		$response = null;
		if (isset($soapResponse->Result->header)) {
			$sessionToken = $soapResponse->Result->header->sessionToken;
			$errorMsg = null;
			
			// look for error response
			$errorCode = $soapResponse->Result->errorCode;
			switch ($errorCode) {
				case 'OK':
					$errorMsg = Isolutions_Betfair_Constants::OK;
					break;
				case 'API_ERROR':
					//@TODO handle API Error
					$errorMsg = Isolutions_Betfair_Constants::API_ERROR;
					break;
				case 'INVALID_LOCAL_DEFAULTING_TO_ENGLISH':
					$errorMsg = Isolutions_Betfair_Constants::INVALID_LOCAL_DEFAULTING_TO_ENGLISH;
					break;
				case 'INVALID_MARKET':
					$errorMsg = Isolutions_Betfair_Constants::INVALID_MARKET;
					break;
				case 'MARKET_TYPE_NOT_SUPPORTED':
					$errorMsg = Isolutions_Betfair_Constants::MARKET_TYPE_NOT_SUPPORTED;
					break;
			}
			$response = new stdClass();
			$response->sessionToken = $sessionToken;
			$response->error = $errorMsg;
			if ($errorMsg == '' || $errorMsg == 'INVALID_LOCAL_DEFAULTING_TO_ENGLISH') {
				$market = array();
				$marketStatus = $soapResponse->Result->market->marketStatus;
				$market['status'] = $marketStatus;
				$market['eventId'] = $soapResponse->Result->market->parentEventId;
				$market['menuPath'] = $soapResponse->Result->market->menuPath;
				$market['countryISO3'] = $soapResponse->Result->market->countryISO3;
				$market['discountAllowed'] = $soapResponse->Result->market->discountAllowed;
				$market['lastRefresh'] = $soapResponse->Result->market->lastRefresh;
				$market['marketBaseRate'] = $soapResponse->Result->market->marketBaseRate;
				$market['marketDescription'] = $soapResponse->Result->market->marketDescription;
				$market['marketDescriptionHasDate'] = $soapResponse->Result->market->marketDescriptionHasDate;
				$market['marketDisplayTime'] = $soapResponse->Result->market->marketDisplayTime;
				$market['markeId'] = $soapResponse->Result->market->marketId;
				$market['marketSuspendTime'] = $soapResponse->Result->market->marketSuspendTime;
				$market['marketTime'] = $soapResponse->Result->market->marketTime;
				$market['marketType'] = $soapResponse->Result->market->marketType;
				$market['marketTypeVariant'] = $soapResponse->Result->market->marketTypeVariant;
				$market['name'] = $soapResponse->Result->market->name;
				$market['numberOfWinners'] = $soapResponse->Result->market->numberOfWinners;
				$market['unit'] = $soapResponse->Result->market->unit;
				$market['maxUnitValue'] = $soapResponse->Result->market->maxUnitValue;
				$market['minUnitValue'] = $soapResponse->Result->market->minUnitValue;
				$market['interval'] = $soapResponse->Result->market->interval;
				$market['runnersMayBeAdded'] = $soapResponse->Result->market->runnersMayBeAdded;
				$market['timezone'] = $soapResponse->Result->market->timezone;
				$market['licenceId'] = $soapResponse->Result->market->licenceId;
				$market['bspMarket'] = $soapResponse->Result->market->bspMarket;
				//@TODO Coupons
				$couponLiks = $soapResponse->Result->market->couponLinks;
				$runners = array();			
				switch ($marketStatus) {
					case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_CLOSED:
					case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_INACTIVE:
						
						break;
					case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_ACTIVE:
					case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_SUSPENDED:
						$runnerItems = $soapResponse->Result->market->runners;
						if (property_exists($runnerItems, "Runner")) {
							$totalR = count($soapResponse->Result->market->runners->Runner);
								if ($totalR > 1) {
								$bfRunners   = $this->objectToArray($runnerItems->Runner);
							} else {
								$bfRunners[] = $this->objectToArray($runnerItems->Runner);
							}
							foreach ($bfRunners as $runner) {
								$runners[] = array(
									'id' 	=> $runner['selectionId'],
									'name'	=> $runner['name'],
									'asianLineId' => $runner['asianLineId'],
									'handicap'	=> $runner['handicap']
								);	
							}
						}
						$market['runners'] = $runners;
						break;
				}
				$response->market = $this->arrayToObject($market);
			}
		}
		// @TODO handle SessionToken AND remove it from response
		return $response;
	}
	
	public function handleAllEvents($soapResponse)
	{
		$response = null;
		if (isset($soapResponse->Result->header)) {
			$sessionToken = $soapResponse->Result->header->sessionToken;
			$errorMsg = null;
			
			// look for error response
			$errorCode = $soapResponse->Result->errorCode;
			switch ($errorCode) {
				case 'OK':
					$errorMsg = Isolutions_Betfair_Constants::OK;
					break;
				case 'API_ERROR':
					//@TODO handle API Error
					$errorMsg = Isolutions_Betfair_Constants::API_ERROR;
					break;
				case 'INVALID_EVENT_ID':
					$errorMsg = Isolutions_Betfair_Constants::INVALID_EVENT_ID;
					break;
				case 'INVALID_LOCAL_DEFAULTING_TO_ENGLISH':
					$errorMsg = Isolutions_Betfair_Constants::INVALID_LOCAL_DEFAULTING_TO_ENGLISH;
					break;
				case 'NO_RESULTS':
					$errorMsg = Isolutions_Betfair_Constants::NO_RESULTS;
					break;
			}
			$response = new stdClass();
			$response->sessionToken = $sessionToken;
			$response->error = $errorMsg;
			if ($errorMsg == '' || $errorMsg == 'INVALID_LOCAL_DEFAULTING_TO_ENGLISH') {
				// events
				$events = array();;
				
				$eventItems = $soapResponse->Result->eventItems;
				if (property_exists($eventItems, "BFEvent")) {
					$totalEvents = count($soapResponse->Result->eventItems->BFEvent);
					if ($totalEvents > 1) {
						$bfEvents = $this->objectToArray($eventItems->BFEvent);
					} else {
						$bfEvents[] = $this->objectToArray($eventItems->BFEvent);
					}
					foreach ($bfEvents as $event) {
						//@TODO handle Coupons
						if ($event['eventName'] != 'Coupons') {
							$events[$event['orderIndex']] = array(
								'id' => $event['eventId'],
								'name' => $event['eventName'],
								'level'	=> $event['menuLevel']
							);
						}
					}
					ksort($events);	
				}
				$response->events = $this->arrayToObject($events);
				
				// markets
				$markets = array();
				
				$marketItems = $soapResponse->Result->marketItems;
				if (property_exists($marketItems, "MarketSummary")) {
					$totalMarkets = count($soapResponse->Result->marketItems->MarketSummary);
					if ($totalMarkets > 1) {
						$marketSum = $this->objectToArray($marketItems->MarketSummary);
					} else {
						$marketSum[] = $this->objectToArray($marketItems->MarketSummary);
					}
					foreach ($marketSum as $market) {
						$markets[] = array(
							'id' 	=> $market['marketId'],
							'name'	=> $market['marketName'],
							'exchangeId' => $market['exchangeId'],
							'start'	=> $market['startTime'],
							'timezone'	=> $market['timezone']
						);	
					}
				}
				$response->markets = $this->arrayToObject($markets);
			}
		}
		// @TODO handle SessionToken AND remove it from response
		return $response;
	}
	
	/**
	 * Handle Event Types Response (both All and Active)
	 * 
	 * @param stdClass $soapResponse
	 * @return stdClass 
	 */
	public function handleEventTypes($soapResponse)
	{
		$response = null;
		if (isset($soapResponse->Result->header)) {
			$sessionToken = $soapResponse->Result->header->sessionToken;
			$errorMsg = null;
			
			// look for error response
			$errorCode = $soapResponse->Result->errorCode;
			switch ($errorCode) {
				case 'OK':
					$errorMsg = Isolutions_Betfair_Constants::OK;
					break;
				case 'API_ERROR':
					//@TODO handle API Error
					$errorMsg = Isolutions_Betfair_Constants::API_ERROR;
					break;
				case 'INVALID_LOCAL_DEFAULTING_TO_ENGLISH':
					$errorMsg = Isolutions_Betfair_Constants::INVALID_LOCAL_DEFAULTING_TO_ENGLISH;
					break;
				case 'NO_RESULTS':
					$errorMsg = Isolutions_Betfair_Constants::NO_RESULTS;
					break;
			}
			$response = new stdClass();
			$response->sessionToken = $sessionToken;
			$response->error = $errorMsg;
			if ($errorMsg == '' || $errorMsg == 'INVALID_LOCAL_DEFAULTING_TO_ENGLISH') {
				$events = array();
				$eventTypeItems = $soapResponse->Result->eventTypeItems;
				if (property_exists($eventTypeItems, "EventType")) {
					$eventTypes = $this->objectToArray($eventTypeItems->EventType);
				
					foreach ($eventTypes as $eventType) {
						$events[$eventType['name']] = array(
							'id' => $eventType['id'],
							'name' => $eventType['name'],
							'nextMarketId'	=> $eventType['nextMarketId'],
							'exchangeId'	=> $eventType['exchangeId']
						);
					}
				}
				ksort($events);
 				$response->events = $this->arrayToObject($events);
			}
		}
		// @TODO handle SessionToken AND remove it from response
		return $response;
	}
	
	/**
	 * @TODO MOVE TO HELPER
	 * Enter description here ...
	 * @param unknown_type $d
	 */
	protected function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__METHOD__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
	
	protected function arrayToObject($d) {
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return (object) array_map(__METHOD__, $d);
		}
		else {
			// Return object
			return $d;
		}
	}
	
	/**
	 * Hanlde Login Response
	 * 
	 * @param stdClass $loginResponse
	 * @return stdClass
	 */
	public function handleLogin($loginResponse) 
	{
		$response = null;
		if (isset($loginResponse->Result->header)) {
			$sessionToken = $loginResponse->Result->header->sessionToken;
			$errorMsg = null;
			$currency = null;
			if (isset ($loginResponse->Result->currency)) 
				$currency = $loginResponse->Result->currency;
			
			if (is_null($sessionToken)) {
				// some error happened
				$errorCode = $loginResponse->Result->errorCode;
				switch ($errorCode) {
					case 'ACCOUNT_SUSPENDED':
						$errorMsg = Isolutions_Betfair_Constants::ACCOUNT_SUSPENDED;
					break;
					case 'API_ERROR':
						//@TODO handle API Error
						$errorMsg = Isolutions_Betfair_Constants::API_ERROR;
					break;
					case 'FAILED_MESSAGE':
						$errorMsg = Isolutions_Betfair_Constants::FAILED_MESSAGE;
					break;
					case 'INVALID_LOCATION':
						$errorMsg = Isolutions_Betfair_Constants::INVALID_LOCATION;
					break;
					case 'INVALID_PRODUCT':
						$errorMsg = Isolutions_Betfair_Constants::INVALID_LOCATION;
					break;
					case 'INVALID_USERNAME_OR_PASSWORD':
						$errorMsg = Isolutions_Betfair_Constants::INVALID_USERNAME_OR_PASSWORD;
					break;
					case 'INVALID_VENDOR_SOFTWARE_ID':
						$errorMsg = Isolutions_Betfair_Constants::INVALID_VENDOR_SOFTWARE_ID;
					break;
					case 'LOGIN_RESTRICTED_LOCATION':
						$errorMsg = Isolutions_Betfair_Constants::LOGIN_RESTRICTED_LOCATION;
					break;
					case 'LOGIN_UNAUTHORIZED':
						$errorMsg = Isolutions_Betfair_Constants::LOGIN_UNAUTHORIZED;
					break;
					case 'POKER_T_AND_C_ACCEPTANCE_REQUIRED':
						$errorMsg = Isolutions_Betfair_Constants::POKER_T_AND_C_ACCEPTANCE_REQUIRED;
					break;
					case 'T_AND_C_ACCEPTANCE_REQUIRED':
						$errorMsg = Isolutions_Betfair_Constants::T_AND_C_ACCEPTANCE_REQUIRED;
					break;
					case 'USER_NOT_ACCOUNT_OWNER':
						$errorMsg = Isolutions_Betfair_Constants::USER_NOT_ACCOUNT_OWNER;
					break;							
					default:
						$errorMsg = 'Unknown Error Message';
					break;
				}
			}
			$response = new stdClass();
			$response->sessionToken = $sessionToken;
			$response->error = $errorMsg;
			$response->currency = $currency;
		}
		
		return $response;
	}
}