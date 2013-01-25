<?php
class Isolutions_Betfair_Exception extends Isolutions_Exception
{
	
	
	public function __construct($message) {
		$logger = Zend_Registry::get('log');
		
		$errorMsg = 'Exception caught in '.$this->getFile().', line '.$this->getLine().':'.$this->getMessage();
		$logger->log($errorMsg, Zend_Log::ERR);
		
		parent::__construct($message);
	}
}