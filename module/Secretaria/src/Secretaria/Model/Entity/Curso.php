<?php

namespace Secretaria\Model\Entity;

class Curso extends AbstractEntity
{
    
    protected $id;
    protected $cod;
    protected $descricao;
    protected $status;
    
    function getId() {
        return $this->id;
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