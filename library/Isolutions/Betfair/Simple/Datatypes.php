<?php
class Isolutions_Betfair_Simple_Datatypes
{
	/** MarketStatusEnum */
	const MarketStatusEnum_ACTIVE = 'ACTIVE'; // Market is open and available for betting.
	const MarketStatusEnum_CLOSED = 'CLOSED'; // Market is finalised, bets to be settled.
	const MarketStatusEnum_INACTIVE = 'INACTIVE'; // Market is not yet available for betting.
	const MarketStatusEnum_SUSPENDED = 'SUSPENDED'; // Market is temporarily closed for betting, possibly due to pending action such as a goal scored during an in-play match or removing runners from a race.
	
	
	/** MarketTypeEnum */
	const MarketTypeEnum_A = 'A'; // Asian Handicap
	const MarketTypeEnum_L = 'L'; // Line
	const MarketTypeEnum_O = 'O'; // Odds
	const MarketTypeEnum_R = 'R'; // Range
	const MarketTypeEnum_NOT_APPLICABLE = 'NOT_APPLICABLE'; // The market does not have an applicable market type
 	
	/** MarketTypeVariantEnum */
	const MarketTypeVariantEnum_D = 'D'; // Default
	const MarketTypeVariantEnum_ASL = 'ASL'; // Asian Single Line
	const MarketTypeVariantEnum_ADL = 'ADL'; // Asian Double Line
	
	/**
	 * Empty Constructor
	 */
	public function __construct(){}
}