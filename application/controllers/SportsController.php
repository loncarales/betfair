<?php
class SportsController extends Isolutions_Controller_Action
{
	/**
	*
	* @var Zend_Auth
	*/
	protected $_identity = null;
	
	protected $_icons = array(
		'1' => ", icon: 'events/1.png'",
		'2' => ", icon: 'events/2.png'",
		'4' => ", icon: 'events/4.png'",
		'14' => ", icon: 'events/1.png'",
		'6422' => ", icon: 'events/6422.png'",
		'6423' => ", icon: 'events/6423.png'",
		'7522' => ", icon: 'events/7522.png'"
	);
	
	/**
	 *
	 * @var Zend_Json
	 */
	protected $_json = null;
	
	/**
	 * Date Timezone
	 * 
	 * @var unknown_type
	 */
	protected $_dateTimezone = 'Europe/London';
	
	/**
	 * Handle Both Active and All Events
	 * 
	 * @param array $results
	 * @param string $title
	 * @return array
	 */
	protected function _handleEvents($results, $title) {
		if (!is_null($results->sessionToken)) {
			$events = array();
			if ($results->events) {
				foreach ($results->events as $event) {
					$icons = $this->_icons;
					$icon =  (array_key_exists($event->id, $icons)) ? $icons[$event->id] : '';
					$events[] = array(
						'title' => $event->name,
						'id'	=> $event->id,
						'class'	=> 'folder',
						'data'	=> "addClass: 'event', isLazy:true" . $icon
					);
					$nextMarketId = ($event->nextMarketId) ? $event->nextMarketId : 0;
					if ($nextMarketId) {
						$events[] = array(
							'title' => 'Next Event',
							'id'	=> $event->nextMarketId .'|'. $event->exchangeId,
							'class' => 'file',
							'data'	=> "addClass: 'market'"
						);
					}
				}
			}
			$this->view->events = $events;
			$this->view->sportsTitle = $title;
			
			$data = array(
	        	'success' => true,
	            'html'	=> $this->view->render('/sports/sports.phtml')
			);
		} else {
			$data = array(
	        	'success' => false,
	            'error'	  => 'Invalid sessionToken'
			);
		}

		return $data;
	}
	
	public function init()
	{
		$this->_json = $this->getHelper('Json');
		$this->_json->suppressExit = true;
		
		$this->_dateTimezone = date_default_timezone_get();
		
		$this->_identity = $this->_helper->common->getIdentity();
		// We are not logged in so no need for subscribe controller
		if (is_null($this->_identity)) {
			$this->_helper->common->log('No identity object');
			$data = array(
                'success'          => false,
                'errorMessage'     => 'No identity object'
			);
			$this->_json->sendJson($data);
		}
		
	}
	
	/**
	 * Renders All Sports Events
	 */
	public function allAction()
	{
		// allow only GET via AJAX
		$this->_helper->common->isXmlHttpRequestGet();
		
		$betfair = new Isolutions_Betfair_Api_Global();
		try {
			$results = $betfair->getAllEventTypes();
			$data = $this->_handleEvents($results, "All Events");
		} catch (Isolutions_Betfair_Exception $e) {
			$data = array(
	        	'success' => false,
	            'error'	  => $e->getMessage()
			);
		}
		$this->_json->sendJson($data);
	}
	
	/**
	 * 
	 * Renders Active Sports Events
	 */
	public function activeAction()
	{
		// allow only GET via AJAX
		$this->_helper->common->isXmlHttpRequestGet();
		
		$betfair = new Isolutions_Betfair_Api_Global();
		try {
			$results = $betfair->getActiveEventTypes();
			$data = $this->_handleEvents($results, "Active Events");
		} catch (Isolutions_Betfair_Exception $e) {
			$data = array(
	        	'success' => false,
	            'error'	  => $e->getMessage()
			);
		}
		$this->_json->sendJson($data);
	}
	
	public function marketInfoAction() 
	{
		// allow only GET via AJAX
		//$this->_helper->common->isXmlHttpRequestGet();
		
		$request = $this->getRequest();
		
		$marketId 	= $request->getQuery('marketId', 0);
		$exchangeId = $request->getQuery('exchangeId', 0);
		$eventId	= $request->getQuery('eventId', 0);
		try {
			$betfair = new Isolutions_Betfair_Api_Exchange($exchangeId);
			$marketResults  = $betfair->getMarketInfo($marketId);
			$marketStatus = $marketResults->marketInfo->marketStatus;
			switch ($marketStatus) {
				case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_CLOSED:
					$statusMsg = Isolutions_Betfair_Constants::MarketStatusEnum_CLOSED;
				case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_INACTIVE:
					$betfair = new Isolutions_Betfair_Api_Global();
					$results = $betfair->getEvents($eventId);
					if ($results->error != '' && $results->error == Isolutions_Betfair_Constants::INVALID_EVENT_ID) {
						
					}
					break;
				case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_ACTIVE:
				case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_SUSPENDED:
					$data = array(
						'success' => true,
						'marketInfo' => $marketResults->marketInfo
					);
					break;
			}
		} catch (Isolutions_Exception $e) {
			$data = array(
	        	'success' => false,
	            'error'	  => $e->getMessage()
			);
		}
		$this->_json->sendJson($data);
	}
	
	public function marketAction()
	{
		// allow only GET via AJAX
		//$this->_helper->common->isXmlHttpRequestGet();
		
		$request = $this->getRequest();
		
		$marketId 	= $request->getQuery('marketId', 0);
		$exchangeId = $request->getQuery('exchangeId', 0);
		try {
			// get market
			$betfair = new Isolutions_Betfair_Api_Exchange($exchangeId);
			$result  = $betfair->getMarket($marketId);
			//var_dump($result);
			if ($result->error != '') 
				throw  new Isolutions_Exception($result->error);
			$market = $result->market;
			$marketStatus = $market->status;
			$eventId 	  = $market->eventId;
			$event = $this->_helper->betfair->getEventFromMenuPath($market->menuPath);
			switch ($marketStatus) {
				case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_CLOSED:
					$statusMsg = Isolutions_Betfair_Constants::MarketStatusEnum_CLOSED;
				case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_INACTIVE:
					$betfair = new Isolutions_Betfair_Api_Global();
					$results = $betfair->getEvents($eventId);
					if ($results->error != '' && $results->error == Isolutions_Betfair_Constants::INVALID_EVENT_ID) {
						$statusMsg = Isolutions_Betfair_Constants::EVENT_CLOSED_NO_MARKETS;
						$data = array(
							'success' => true,
							'event'	=> false,
							'market' => false,
							'title' => $event,
							'text'  => $statusMsg
						);
					}
					
					break;
				case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_ACTIVE:
				case Isolutions_Betfair_Simple_Datatypes::MarketStatusEnum_SUSPENDED:
					$data = array(
						'success' => true,
						'event'	=> array(
							'eventName' => $event,
							'eventId' => $eventId
						),
						'market' => $market
					);
					break;
			}
		} catch (Isolutions_Betfair_Exception $e) {
			
		} catch (Isolutions_Exception $e) {
			$data = array(
	        	'success' => false,
	            'error'	  => $e->getMessage()
			);
		}
		$this->_json->sendJson($data);
	}
	
	public function eventAction()
	{
		// allow only GET via AJAX
		$this->_helper->common->isXmlHttpRequestGet();
		
		$request = $this->getRequest();
		
		$eventId = $request->getQuery('eventId', 0);
		$events = array();
		try {
			if ($eventId) {
				// get Events
				$betfair = new Isolutions_Betfair_Api_Global();
				$results = $betfair->getEvents($eventId);
				if (isset($results->events)) {
					foreach ($results->events as $event) {	
						$events[] = array(
							'title' => $event->name,
							'key'	=> $event->id,
							'isLazy' => true,
							'isFolder' => true,
							'addClass' => 'event'
						);
					}
				}
				if (isset($results->markets)) {
					foreach ($results->markets as $market) {
						$title = $market->name;
						/** 0001-01-01T00:00:00.000Z is invalid */
						if ($market->start!= Isolutions_Betfair_Constants::INVALID_START_TIME) {
							$marketDate = new Zend_Date($market->start, Zend_Date::ISO_8601);
							$marketDate->setTimezone($this->_dateTimezone);
							$title = $marketDate->toString("HH:mm") . ' ' . $title;
						}
						$events[] = array(
							'title' => $title,
							'key' => $market->id.'|'.$market->exchangeId,
							'addClass' => 'market'
						);
					}
				}
			}
			
			if (!$events) {
				$events[] = array(
					'title' => 'No Markets',
					'addClass' => 'no-markets'
				);
			}
		} catch (Isolutions_Betfair_Exception $e) {
			$events[] = array(
				'title' => 'No Markets',
				'addClass' => 'no-markets',
				'success'          => false,
                'errorMessage'     => $e->getMessage()
			);
		}
		//$log = Zend_Registry::get('log');
		//$log->info($events);
		$this->_json->sendJson($events);
	}
}