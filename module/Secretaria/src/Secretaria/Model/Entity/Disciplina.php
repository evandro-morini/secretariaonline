<?php

namespace Secretaria\Model\Entity;

class Disciplina extends AbstractEntity
{
    
    protected $id;
    protected $fkCurso;
    protected $fkProfessor;
    protected $cod;
    protected $descricao;
    protected $status;
    
    function getId() {
        return $this->id;
    }

    function getFkCurso() {
        return $this->fkCurso;
    }

    function getFkProfessor() {
        return $this->fkProfessor;
    }

    function getCod() {
        return $this->cod;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getStatus() {
        return $this->status;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFkCurso($fkCurso) {
        $this->fkCurso = $fkCurso;
    }

    function setFkProfessor($fkProfessor) {
        $this->fkProfessor = $fkProfessor;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setStatus($status) {
        $this->status = $status;
    }
    
}
    