<?php
class Isolutions_Betfair_Api
{
	const PRODUCT_ID = 82;
	
	const VENDOR_SOFTWARE_ID = 0;
	
	const LOCATION_ID = 0;
	
	const IP_ADDRESS = 0;
	
	const LOCALE = 'en';
	
	const CLIENT_STAMP = 0;
	
	/**
	 * WSDL Location
	 * 
	 * @var string
	 */
	private $_wsdl = '';

	/**
	 * 
	 * Soap client options
	 * 
	 * @var array
	 */
	private $_options = array();
	
	
	/**
	 * 
	 * Betfair SOAP Client
	 * 
	 * @var Zend_Soap_Client
	 */
	protected $_client = null;
	
	/**
	 * Betfair API constructor
	 * 
	 * @param string $wsdl
	 * @throws InvalidArgumentException
	 * @throws Betfair_Exception
	 */
	public function __construct($wsdl)
	{
		if (!$wsdl)
			throw new InvalidArgumentException('WSDL Location not provided!');
		$this->_wsdl = $wsdl;
		$this->_options = array('compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_DEFLATE | SOAP_COMPRESSION_GZIP, 'soap_version'   => SOAP_1_2);
		if ( is_null($this->_client) ) {
			$this->_client = new Zend_Soap_Client($this->_wsdl, $this->_options);
			if (!$this->_client->getFunctions()) {
				throw new Isolutions_Betfair_Exception('No functions are available in Soap_Client');
			}	
		}	
	}
	
	/**
	 * Get Identity
	 */
	protected function _getIdentity()
	{
		$identity = NULL;
		if (Zend_Registry::isRegistered('identity')) {
			$identity = Zend_Registry::get('identity');
		}
		return $identity;
	}
}