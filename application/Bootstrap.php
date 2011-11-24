<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function run() {
        parent::run();

        $this->_initDoctrine();
    }
    protected function _initDoctrine() {
        require_once("doctrine/Doctrine.php");

        $doctrine = new Doctrine($this->getOption('doctrine'));
        Zend_Registry::set('em', $doctrine->getEntityManager());
        
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->pushAutoloader(array('BootStrap', 'autoload'), '');
    }
    
    public static function autoload($class) {
        $dir = APPLICATION_PATH . "/models/";
        if(file_exists($dir.$class.".php")) {
            require_once($dir.$class.".php");
        }
    }

    protected function _initNavigation() {
        $menu = array(
            array(
                'label' => 'Orders',
                'controller' => 'index',
                'action' => 'index',
                'class' => 'orders',
                'pages' => array(
                    array(
                        'label' => 'Beheer orders',
                        'controller' => 'index',
                        'action' => 'index'
                    ), array(
                        'label' => 'Nieuwe order',
                        'controller' => 'orders',
                        'action' => 'new'
                    )
                )
            ),
            array(
                'label' => 'Klanten',
                'controller' => 'klanten',
                'action' => 'index',
                'class' => 'klanten',
                'pages' => array(
                    array(
                        'label' => 'Beheer klanten',
                        'controller' => 'klanten',
                        'action' => 'index'
                    ), array(
                        'label' => 'Nieuwe klant',
                        'controller' => 'klanten',
                        'action' => 'new'
                    )
                )
            )
        );

        // initialize the navigation object with the array
        $container = new Zend_Navigation($menu);

        // Set the navigation object to the view
        Zend_Layout::startMvc(); // starts the zend layout
        $view = Zend_Layout::getMvcInstance()->getView();
        $view->navigation($container);
    }

    public static function getReferer() {
        $chunks = explode("/", str_replace('http://' . $_SERVER['HTTP_HOST'] . '/', '', $_SERVER['HTTP_REFERER']));

        $module = array_shift($chunks);
        $action = array_shift($chunks);
        
        $module = ($module ? $module : 'index');
        $action = ($action ? $action : 'index');

        $params = array();
        while($chunks) {
            $params[array_shift($chunks)] = array_shift($chunks);
        }
        
        return array('module' => $module, 'action' => $action, 'params' => $params);
    }
}

