<?php
namespace Secretaria\Controller;
use Zend\Session\Container;

class AbstractController extends \Zend\Mvc\Controller\AbstractActionController
{
    public function getDbAdapter()
    {
        return $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    }
    
    public function getSessionContainer($namespace)
    {
        $container = new Container($namespace);
        return $container;
    }

}
