<?php

namespace Secretaria\Model;

use Zend\Db\Adapter\Adapter;

class ProfessorModel extends AbstractModel {
    
    protected $table = 'tb_professor';
    protected $schema = 'secretariaonline';
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
    
    public function findProfessores() {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $select->order('nome');

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();

        $className = '\\Secretaria\\Model\\Entity\\Professor';

        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new $className($row);
            $entities[] = $entity;
        }
        return $entities;
    }
    
}