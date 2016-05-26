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
    
    public function findById($id) {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $select->where->equalTo('id', $id);
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
    
    public function getSubject($idUsuario)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier('tb_usuario_curso', $this->getSchema()));
        $select->join(array('curso' => new \Zend\Db\Sql\TableIdentifier('tb_curso', $this->getSchema())), 'tb_usuario_curso.fk_curso = curso.id');
        $select->where->equalTo('tb_usuario_curso.fk_usuario', $idUsuario);
        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute()->current();
        return $resultSet;
    }
    
    public function updateUser($idUser, \Secretaria\Model\Entity\Usuario $user)
    {
        $data = array(
            'cpf'       => $user->getCpf(),
            'nome'      => $user->getNome(),
            'dta_nasc'  => $user->getDtaNasc(),
            'email'     => $user->getEmail(),
            'pwd'       => $user->getPwd(),
            'fk_perfil' => $user->getFkPerfil(),
            'adm'       => 0, //0 = não administrador
            'status'    => $user->getStatus()
        );
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $update->set($data);
        $update->where('id = '. $idUser);
        $statement = $sql->prepareStatementForSqlObject($update);
        $resultSet = $statement->execute();
    }
    
    public function updateEnrollment($idUsuario, $idCurso, $matricula)
    {
        $data = array(
            'fk_curso'   => $idCurso,
            'matricula'  => $matricula
        );
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update(new \Zend\Db\Sql\TableIdentifier('tb_usuario_curso', $this->getSchema()));
        $update->set($data);
        $update->where('fk_usuario = '. $idUsuario);
        $statement = $sql->prepareStatementForSqlObject($update);
        $resultSet = $statement->execute();
    }
    
    public function countEnrollment($matricula)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier('tb_usuario_curso', $this->getSchema()));
        $select->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)')));
        $select->where('matricula like ' . "'$matricula'");
        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute()->current();
        return (int)$result['num'];
    }
    
    public function findListaServidores($idUsuario, $idCurso) {
        $sql = <<<EOT
            SELECT
                usu.id,
                CONCAT(
                        usu.nome,
                        ' - ',
                        prfl.descricao
                ) AS descricao
            FROM
                tb_usuario AS usu
            JOIN 
                tb_perfil AS prfl ON prfl.id = usu.fk_perfil
            JOIN 
                tb_curso AS curso ON curso.id = prfl.fk_curso
            WHERE
                usu.id <> $idUsuario
            AND 
                prfl.fk_curso = $idCurso
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
    }
    
    public function findUsuarios() {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $select->order('nome');

        $statement = $sql->prepareStatementForSqlObject($select);
        $resultSet = $statement->execute();

        $className = '\\Secretaria\\Model\\Entity\\Usuario';

        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new $className($row);
            $entities[] = $entity;
        }
        return $entities;
    }

}
