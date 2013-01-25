<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
     * Set Config to Registry
     */
    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
        return $config;
    }

    /**
     * Init Session
     */
    protected function _initSession()
    {
        if ($this->hasPluginResource('Session')) {
            $options = $this->getPluginResource('Session')->getOptions();
            if (!Zend_Session::isStarted()) {
                // set Options
                Zend_Session::setOptions($options);
                // Session Fixation
                $defaultNamespace = new Zend_Session_Namespace();
                if (!isset($defaultNamespace->initialized)) {
                    Zend_Session::regenerateId();
                    $defaultNamespace->initialized = true;
                }
            }
        }
    }
    
	/**
     * Init Log
     */
    protected function _initLog()
    {
        $options = $this->getOptions();
        $log = new Zend_Log();
        $fileWriter = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/logs/'.Zend_Date::now()->get("yyyy-MM-dd").'_log.txt');
        $log->addWriter($fileWriter);
        // FireBug
        if ($options['firebug']['enabled']) {
          $fbWriter = new Zend_Log_Writer_Firebug();
          $log->addWriter($fbWriter);
          $fbWriter->setPriorityStyle(8, 'TABLE');
          $log->addPriority('TABLE', 8);
        }
        Zend_Registry::set('log', $log);
        return $log;
    }
    
    /**
     * Init View
     */
    protected function _initView()
    {
        $options = $this->getOptions();
        if (!isset($options['resources']['view'])) return;
        $config = $options['resources']['view'];
        if (isset($config)) {
            $view = new Zend_View($config);
        } else {
            $view = new Zend_View;
        }
        if (isset($config['doctype'])) {
            $view->doctype($config['doctype']);
        }
        if (isset($config['charset'])) {
            $view->headMeta()->appendHttpEquiv('Content-Type','text/html; charset=' . $config['charset']);
        }
        $jquery = isset($options['jquery']) ? $options['jquery'] : false;
        if ($jquery) {
        	if ($jquery['enabled']) {
        		//jQuery-enable a view instance
        		ZendX_JQuery::enableView($view);
        		foreach ($jquery['options'] as $jqueryOption => $jqueryValue) {
        			$view->jQuery()->$jqueryOption($jqueryValue);
        		}
        		if ($jquery['uienabled']) {
	        		foreach ($jquery['uioptions'] as $jqueryUiOption => $jqueryUiValue) {
	        			$view->jQuery()->$jqueryUiOption($jqueryUiValue);
	        		}
	        		$view->JQuery()->uiEnable();
        		}
        		$view->jQuery()->enable();
        	}
        }
        
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);
        return $view;
    }

    protected function _initResponse()
    {
        $options = $this->getOptions();
        if (!isset($options['response']['defaultContentType'])) {
            return;
        }
        $response = new Zend_Controller_Response_Http;
        $response->setHeader('Content-Type',
            $options['response']['defaultContentType'], true);
        $this->bootstrap('FrontController');
        $this->getResource('FrontController')->setResponse($response);

        // Add helpers prefixed with My_Action_Helpers in My/Action/Helpers/
        Zend_Controller_Action_HelperBroker::addPrefix('Isolutions_Controller_Action_Helper');
        
        // bootstrap Cache
        $this->bootstrap('Cache');
        $cache = $this->getResource('Cache');
        
    	//add custom routes
        $routes   = $cache->load(md5(__METHOD__));
        if ($routes === false) {
            $routes = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini');
            $cache->save($routes,md5(__METHOD__));
        }
        $router = $this->getResource('FrontController')->getRouter();
        if (isset($routes->routes)) {
            $router->addConfig($routes, 'routes');
        }
        
        // register plugin
        $this->getResource('FrontController')->registerPlugin(new Isolutions_Controller_Plugin_Auth(), 1);
    }
}

