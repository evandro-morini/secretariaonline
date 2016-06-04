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
        $select->order('descricao');

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
    
    public function findPerfilCurso($perfil) {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier('tb_perfil', $this->getSchema()));
        $select->columns(array('fk_curso'));
        $select->where('id = '. $perfil);
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute()->current();
        return $resultSet['fk_curso'];
    }
    
    public function insertCurso(\Secretaria\Model\Entity\Curso $curso)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $newData = array(
            'cod'       => $curso->getCod(),
            'descricao' => $curso->getDescricao(),
            'status'    => $curso->getStatus()
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $resultSet = $statement->execute();
        return $resultSet->getGeneratedValue();
    }
    
    public function findAllCursos() {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $select->order('descricao');

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
    
    public function updateStatus($id, $status)
    {
        $data = array(
            'status' => $status
        );
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $update->set($data);
        $update->where('id = '. $id);
        $statement = $sql->prepareStatementForSqlObject($update);
        $resultSet = $statement->execute();
    }
    
    public function findAllById($id) {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $select->where->equalTo('id', $id);

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();

        $entity = false;
        if ($resultSet->count()) {
            $entity = new \Secretaria\Model\Entity\Curso($resultSet->current());
        }

        return $entity;
    }
    
    public function updateCurso($idCurso, \Secretaria\Model\Entity\Curso $curso)
    {
        $data = array(
            'cod'  => $curso->getCod(),
            'descricao' => $curso->getDescricao(),
            'status'    => $curso->getStatus()
        );
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $update->set($data);
        $update->where('id = '. $idCurso);
        $statement = $sql->prepareStatementForSqlObject($update);
        $resultSet = $statement->execute();
    }
    
}