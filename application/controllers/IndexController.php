<?php

class IndexController extends Isolutions_Controller_Action
{
	
	const EMPTY_USERNAME = "************";
	const EMPTY_PASSWORD = "************";
	const EMPTY_ERROR    = 'The username/password field is empty.';

	protected function _initBluePrint()
	{
		$this->view->headLink()->appendStylesheet('/css/screen.css', 'screen, projection');
		$this->view->headLink()->appendStylesheet('/css/print.css', 'print');
		$this->view->headLink()->appendStylesheet('/css/ie.css', 'screen, projection', 'IE');
	}
	
	/**
     * PreDispatch plugin
     */
    public function preDispatch()
    {
        //get action
        $action = $this->_request->getActionName();

        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Betfair'));
        if ($auth->hasIdentity()) {
            // if loged in and want to access login page or login-process
            if (strcasecmp($action, 'login') === 0 || strcasecmp($action, 'login-process') === 0) {
                $this->_redirect('/');
            }
        }
    	// we want to logout
        if (strcasecmp($action, 'logout') === 0) {
        	// @TODO call API LOGOUT
            $auth->clearIdentity();
            $this->_redirect('/');
        }
    }
    
    /**
     * Init initalizes before preDispatch
     * 
     * @see Zend_Controller_Action::init()
     */
    public function init() {
    	if (array_key_exists('applicationName', $this->_strings)) {
    		$this->view->headTitle($this->_strings['applicationName']);
    		$this->view->headerTitle = $this->_strings['applicationName'];
    	}
    }

    public function indexAction()
    {
    	$auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Betfair'));
        if (!$auth->hasIdentity()) {
            $this->_redirect('/login');
        }
        
        $this->_initBlueprint();
        
        $this->_helper->layout->setLayout('layout');
        
        $this->view->headLink()->appendStylesheet('/css/jquery/dynatree/skin/ui.dynatree.css');
        $this->view->headLink()->appendStylesheet('/css/jquery/gritter/css/jquery.gritter.css');
        $this->view->headLink()->appendStylesheet('/css/app.css');
        $this->view->headScript()->appendFile('/js/jquery/jquery.cookie.js');
        $this->view->headScript()->appendFile('/js/jquery/jquery.gritter.min.js');
        $this->view->headScript()->appendFile('/js/jquery/dynatree/jquery.dynatree.min.js');
        $this->view->headScript()->appendFile('/js/app.js');
    }

	public function loginAction()
	{	
		$this->_helper->layout->setLayout('login');
		$this->view->headTitle()->setSeparator(' / ');
		$this->view->headTitle('Login');
	}

	/**
	* Login Process Action
	*
	* @return void
	*/
	public function loginProcessAction()
	{
		try {
			$request = $this->getRequest();
			if ($request->isPost()) {
				
				// filters on params
				$filters = array(
					'*' => 'StringTrim'
				);
				
				// validators on params
				$validators = array(
					'username'      => array(
					'presence' => 'required',
				),
					'password'   => array(
					'presence' => 'required'
				));
				
				// input filter object
				$input = new Zend_Filter_Input($filters, $validators, $request->getPost());
				if ($input->isValid()) {
					if ($input->username == self::EMPTY_USERNAME || $input->password == self::EMPTY_PASSWORD)
						throw new Isolutions_Exception(self::EMPTY_ERROR);
					
					$authResult = Isolutions_Betfair_Response::getInstance()->authenticate($input->username, $input->password);
					if (!$authResult->isValid()) {
						$messages = $authResult->getMessages(); 
						throw new Isolutions_Exception($messages[0]);
					}
				} else {
	                $errorMessage = array();
	                $messages = $input->getMessages();
	                foreach ($messages as $param => $message) {
	                	$i = 0;
	                	foreach ($message as $k => $value) {
	                		if ($i == 1)
	                		break;
	                		$i++;
	                	}
	                }
					//@TODO Check what's going on
	                throw new Isolutions_Exception(implode('', $errorMessage));
				}
			}
		} catch (Isolutions_Exception $e) {
			$this->_helper->common->flashMessageRedirect($e->getMessage(), '/login');
        }
        
        $this->_redirect('/');
	}
}

