<?php
class BetfairApiController extends Isolutions_Controller_Action
{
 	public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->disableLayout();
        // disable view renderer
        $this->_helper->viewRenderer->setNoRender(true);
        // Turn of error reporting; mucks with responses
        ini_set('display_errors', false);
    }

    /**
     * We don't allow index action
     */
    public function indexAction()
    {
        exit;
    }
    
    public function runAction()
    {
    	echo "DA";
    }
}
	