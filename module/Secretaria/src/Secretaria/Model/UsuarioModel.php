<?php

namespace Secretaria\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Crm\Model\Grupo;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class UsuarioModel extends AbstractModel {

    protected $table = 'tb_usuario';
    protected $schema = 'secretariaonline';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function findByEmail($email) {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $select->where->equalTo('email', $email);
        //$select->limit(1);

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();

        $entity = false;
        if ($resultSet->count()) {
            $entity = new \Secretaria\Model\Entity\Usuario($resultSet->current());
        }

        return $entity;
    }

}
