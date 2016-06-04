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
        $select->order('descricao');

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
    
    public function findFkDisciplinas() {
        $sql = <<<EOT
            SELECT
                disc.id,
                disc.cod AS cod_disciplina,
                disc.descricao AS desc_disciplina,
                disc.status,
                curso.cod AS cod_curso,
                curso.descricao AS desc_curso,
                prof.nome
            FROM
                tb_disciplina AS disc
            JOIN 
                tb_curso AS curso ON curso.id = disc.fk_curso
            JOIN 
                tb_professor AS prof ON prof.id = disc.fk_professor 
            WHERE 
                disc.status = 1
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
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
    
    public function insertDisciplina(\Secretaria\Model\Entity\Disciplina $disciplina)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $newData = array(
            'fk_curso'   => $disciplina->getFkCurso(),
            'fk_professor'  => $disciplina->getFkProfessor(),
            'cod'   => $disciplina->getCod(),
            'descricao'  => $disciplina->getDescricao(),
            'status' => $disciplina->getStatus()
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $resultSet = $statement->execute();
        return $resultSet->getGeneratedValue();
    }
    
    public function findAllFkDisciplinas() {
        $sql = <<<EOT
            SELECT
                disc.id,
                disc.cod AS cod_disciplina,
                disc.descricao AS desc_disciplina,
                disc.status,
                curso.cod AS cod_curso,
                curso.descricao AS desc_curso,
                prof.nome
            FROM
                tb_disciplina AS disc
            JOIN 
                tb_curso AS curso ON curso.id = disc.fk_curso
            JOIN 
                tb_professor AS prof ON prof.id = disc.fk_professor
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
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
    
    public function updateDisciplina($idDisciplina, \Secretaria\Model\Entity\Disciplina $disciplina)
    {
        $data = array(
            'fk_curso'  => $disciplina->getFkCurso(),
            'fk_professor' => $disciplina->getFkProfessor(),
            'cod'  => $disciplina->getCod(),
            'descricao' => $disciplina->getDescricao(),
            'status'    => $disciplina->getStatus()
        );
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $update->set($data);
        $update->where('id = '. $idDisciplina);
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
            $entity = new \Secretaria\Model\Entity\Disciplina($resultSet->current());
        }

        return $entity;
    }
    
}