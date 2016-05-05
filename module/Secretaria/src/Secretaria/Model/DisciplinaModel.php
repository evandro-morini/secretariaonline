<?php

namespace Secretaria\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Crm\Model\Grupo;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class DisciplinaModel extends AbstractModel {
    
    protected $table = 'tb_disciplina';
    protected $schema = 'secretariaonline';
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
    
    public function findDisciplinas() {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $select->where->equalTo('status', 1);

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();

        $className = '\\Secretaria\\Model\\Entity\\Disciplina';

        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new $className($row);
            $entities[] = $entity;
        }
        return $entities;
    }
    
    public function findTipoDisciplinas() {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier('tb_tipo_solic_disciplina', $this->getSchema()));
        $select->where->equalTo('status', 1);
        $select->where('id <> 1');
        
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();
        return $resultSet;
    }
    
}