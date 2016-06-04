<?php

namespace Secretaria\Model\Entity;

class Perfil extends AbstractEntity
{
    
    protected $id;
    protected $fkCurso;
    protected $descricao;
    protected $status;
    
    function getId() {
        return $this->id;
    }

    function getFkCurso() {
        return $this->fkCurso;
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

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setStatus($status) {
        $this->status = $status;
    }
    
}