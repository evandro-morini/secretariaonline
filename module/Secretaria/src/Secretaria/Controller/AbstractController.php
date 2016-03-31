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
    
    public function formatDateTime($datetime)
    {
        if(is_null($datetime)) {
            $datetime = "Data não informada";
        } else {
            $formatData = explode(" ", $datetime);
            $dmy = explode("-", $formatData[0]);
            $hms = explode(":", $formatData[1]);
            $datetime = $dmy[2] . "/" . $dmy[1] . "/" . $dmy[0] . ' ' . $hms[0] . ':' . $hms[1];
            return $datetime;
        }
    }

    public function formatDateTimeBr($datetime)
    {
        if(is_null($datetime)) {
            $datetime = "Data não informada";
        } else {
            $formatData = explode(" ", $datetime);
            $dmy = explode("/", $formatData[0]);
            if(count($formatData) > 1) {
                $datetime = $dmy[2] . "-" . $dmy[1] . "-" . $dmy[0] . ' ' . $formatData[1];
            } else {
                $datetime = $dmy[2] . "-" . $dmy[1] . "-" . $dmy[0];
            }
            return $datetime;
        }
    }

}
