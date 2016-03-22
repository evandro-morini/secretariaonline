<?php

namespace Secretaria\Model\Entity;

class Usuario extends AbstractEntity
{
    protected $id;
    protected $cpf;
    protected $matricula;
    protected $nome;
    protected $dtaNasc;
    protected $email;
    protected $pwd;
    protected $fkPerfil;
    protected $adm;
    
    function getId() {
        return $this->id;
    }

    function getCpf() {
        return $this->cpf;
    }

    function getMatricula() {
        return $this->matricula;
    }

    function getNome() {
        return $this->nome;
    }

    function getDtaNasc() {
        return $this->dtaNasc;
    }

    function getEmail() {
        return $this->email;
    }

    function getPwd() {
        return $this->pwd;
    }

    function getFkPerfil() {
        return $this->fkPerfil;
    }

    function getAdm() {
        return $this->adm;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDtaNasc($dtaNasc) {
        $this->dtaNasc = $dtaNasc;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPwd($pwd) {
        $this->pwd = $pwd;
    }

    function setFkPerfil($fkPerfil) {
        $this->fkPerfil = $fkPerfil;
    }

    function setAdm($adm) {
        $this->adm = $adm;
    }
}