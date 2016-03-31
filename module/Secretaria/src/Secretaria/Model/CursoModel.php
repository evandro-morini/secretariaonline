<?php

namespace Secretaria\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Crm\Model\Grupo;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class CursoModel extends AbstractModel {
    
    protected $table = 'tb_curso';
    protected $schema = 'secretariaonline';
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
    
    public function findCursos() {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $select->where->equalTo('status', 1);

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();

        $className = '\\Secretaria\\Model\\Entity\\Curso';

        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new $className($row);
            $entities[] = $entity;
        }
        return $entities;
    }
    
}