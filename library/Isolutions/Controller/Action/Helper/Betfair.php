<?php
/**
* Isolutions Library
*
* @author Ales Loncar <ales@internet-solutions.si>
* @category   Isolutions
* @package    Isolutions_Controller
* @copyright  Copyright (c) 2012 Internet Solutions (http://www.internet-solutions.si/)
*
*/

/**
 * Isolutions Controller Action
 *
 * @author Ales Loncar <ales@internet-solutions.si>
 * @category   Isolutions
 * @package    Isolutions_Controller
 * @copyright  Copyright (c) 2012 Internet Solutions (http://www.internet-solutions.si/)
 *
 */
class Isolutions_Controller_Action_Helper_Betfair extends Zend_Controller_Action_Helper_Abstract
{	
	
	/**
	 * Get Event From whole menu Path
	 * 
	 * @param string $menuPath
	 * @return string
	 */
	public function getEventFromMenuPath($menuPath)
	{
		$tmp = array_map('trim',explode("\\",$menuPath));
		$paths = array_filter($tmp);
		$event = array_pop($paths);
		return $event;
	}
}