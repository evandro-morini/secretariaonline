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
        $select->where->equalTo('status', 1);

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();

        $entity = false;
        if ($resultSet->count()) {
            $entity = new \Secretaria\Model\Entity\Usuario($resultSet->current());
        }

        return $entity;
    }
    
    public function insertUser(\Secretaria\Model\Entity\Usuario $user)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $newData = array(
            'cpf'       => $user->getCpf(),
            'nome'      => $user->getNome(),
            'dta_nasc'  => $user->getDtaNasc(),
            'email'     => $user->getEmail(),
            'pwd'       => $user->getPwd(),
            'fk_perfil' => $user->getFkPerfil(),
            'adm'       => 0, //0 = não administrador
            'status'    => $user->getStatus()
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $resultSet = $statement->execute();
        return $resultSet->getGeneratedValue();
    }
    
    public function insertEnrollment($idUsuario, $idCurso, $matricula)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier('tb_usuario_curso', $this->getSchema()));
        $newData = array(
            'fk_usuario' => $idUsuario,
            'fk_curso'   => $idCurso,
            'matricula'  => $matricula
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $resultSet = $statement->execute();
        return $resultSet->getGeneratedValue();
    }
    
    public function activateUser($cpf)
    {
        $data = array(
            'status' => 1 //status ATIVO
        );
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $update->set($data);
        $update->where("cpf LIKE '". $cpf . "'");
        $statement = $sql->prepareStatementForSqlObject($update);
        $resultSet = $statement->execute();
    }
    
    public function getEnrollment($idUsuario)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier('tb_usuario_curso', $this->getSchema()));
        $select->columns(array('matricula'));
        $select->where->equalTo('fk_usuario', $idUsuario);

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute()->current();

        return $resultSet['matricula'];
    }

}
