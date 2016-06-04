<?php

namespace Secretaria\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Crm\Model\Grupo;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class PerfilModel extends AbstractModel {
    
    protected $table = 'tb_perfil';
    protected $schema = 'secretariaonline';
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
    
    public function findPerfis() {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $select->where->equalTo('status', 1);
        $select->order('descricao');

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();

        $className = '\\Secretaria\\Model\\Entity\\Perfil';

        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new $className($row);
            $entities[] = $entity;
        }
        return $entities;
    }
    
    public function findFkPerfis() {
        $sql = <<<EOT
            SELECT
                perf.id,
                perf.descricao AS desc_perfil,
                curso.descricao AS desc_curso,
                perf.`status`
            FROM
                tb_perfil AS perf
            LEFT JOIN 
                tb_curso AS curso ON perf.fk_curso = curso.id 
            WHERE 
                perf.`status` = 1
            ORDER BY
                perf.descricao
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
    }
    
    public function insertPerfil(\Secretaria\Model\Entity\Perfil $perfil)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $newData = array(
            'fk_curso'       => $perfil->getFkCurso(),
            'descricao'      => $perfil->getDescricao(),
            'status'    => $perfil->getStatus()
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $resultSet = $statement->execute();
        return $resultSet->getGeneratedValue();
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
            $entity = new \Secretaria\Model\Entity\Perfil($resultSet->current());
        }

        return $entity;
    }
    
    public function updatePerfil($idPerfil, \Secretaria\Model\Entity\Perfil $perfil)
    {
        $data = array(
            'descricao' => $perfil->getDescricao(),
            'fk_curso'  => $perfil->getFkCurso(),
            'status'    => $perfil->getStatus()
        );
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $update->set($data);
        $update->where('id = '. $idPerfil);
        $statement = $sql->prepareStatementForSqlObject($update);
        $resultSet = $statement->execute();
    }
    
    public function findAllFkPerfis() {
        $sql = <<<EOT
            SELECT
                perf.id,
                perf.descricao AS desc_perfil,
                curso.descricao AS desc_curso,
                perf.`status`
            FROM
                tb_perfil AS perf
            LEFT JOIN 
                tb_curso AS curso ON perf.fk_curso = curso.id
            ORDER BY
                perf.descricao
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
    }
    
}