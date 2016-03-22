<?php
namespace Secretaria\Model;

use Zend\Db\Sql\Select;
use Zend\Authentication\AuthenticationService;

abstract class AbstractModel extends \Zend\Db\TableGateway\AbstractTableGateway
{
    public function getClassName()
    {
        $class = explode('\\', get_class($this));
        return end($class);
    }

    public function getSchema()
    {
        return isset($this->schema) ? $this->schema : null;
    }
}