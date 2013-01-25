<?php
class Isolutions_Betfair_Constants
{
	const INVALID_START_TIME = '0001-01-01T00:00:00.000Z';
	
	/** Common */
	const OK = '';
	const API_ERROR	= 'General API error.';
	const INVALID_LOCAL_DEFAULTING_TO_ENGLISH = 'The locale string was not recognized. Returned results are in English.';
	const NO_RESULTS = 'No data available to return.';
	
	/** LoginErrorEnum */
	const ACCOUNT_SUSPENDED = 'Account suspended.';
	const FAILED_MESSAGE = 'The user cannot login until they acknowledge a message from Betfair.';
	const INVALID_LOCATION = 'Invalid LocationID';
	const INVALID_PRODUCT  = 'Invalid productID entered';
	const INVALID_USERNAME_OR_PASSWORD = 'Incorrect username and/or password supplied, or the account is closed or locked.';
	const INVALID_VENDOR_SOFTWARE_ID = 'Invalid vendorSoftwareId supplied';
	const LOGIN_RESTRICTED_LOCATION = 'Login origin from a restricted country';
	const LOGIN_UNAUTHORIZED = 'User has not been permissioned to use API login.';
	const OK_MESSAGES = 'There are additional messages on your account. Please log in to the web site to view them.';
	const POKER_T_AND_C_ACCEPTANCE_REQUIRED = 'Account locked, Please login to the Betfair Poker website and assent to the terms and conditions.';
	const T_AND_C_ACCEPTANCE_REQUIRED = 'There are new Terms and Conditions. Continued usage of the Betfair API and/or website will be considered acceptance of the new terms.';
	const USER_NOT_ACCOUNT_OWNER = 'The specified account is not a trading account and therefore cannot be used for API access.';
	
	/** APIErrorEnum */
	//@TODO
	
	/** GetEventsErrorEnum */
	const INVALID_EVENT_ID = 'The parent id is either not valid or the parent does not have any event children.';
	
	/** GetMarketErrorEnum */
	
	const INVALID_MARKET = 'Invalid Market ID supplied. Make sure you have sent the request to the correct exchange server.';
	const MARKET_TYPE_NOT_SUPPORTED	= 'The MarketID supplied identifies a market of a type that is not supported by the API.';
	
	/** MarketStatusEnum */
	const MarketStatusEnum_CLOSED = 'Market is finalised, bets to be settled.'; // 

	
	/** Event has no markets CLOSED */
	const EVENT_CLOSED_NO_MARKETS = 'There are no markets available.';
	
	/**
	 * Empty Constructor
	 */
	public function __construct(){}
}