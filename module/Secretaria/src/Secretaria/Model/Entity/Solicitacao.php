<?php

namespace Secretaria\Model\Entity;

class Solicitacao extends AbstractEntity
{
    
    protected $id;
    protected $fkUsuario;
    protected $fkCurso;
    protected $protocolo;
    protected $fkStatus;
    protected $dtaAbertura;
    protected $dtaAlteracao;
    protected $dtaFechamento;
    protected $fkTipoSolicitacao;
    protected $observacao;
    protected $arquivo;
    
    function getId() {
        return $this->id;
    }

    function getFkUsuario() {
        return $this->fkUsuario;
    }

    function getFkCurso() {
        return $this->fkCurso;
    }

    function getProtocolo() {
        return $this->protocolo;
    }

    function getFkStatus() {
        return $this->fkStatus;
    }

    function getDtaAbertura() {
        return $this->dtaAbertura;
    }

    function getDtaAlteracao() {
        return $this->dtaAlteracao;
    }

    function getDtaFechamento() {
        return $this->dtaFechamento;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setFkUsuario($fkUsuario) {
        $this->fkUsuario = $fkUsuario;
    }

    function setFkCurso($fkCurso) {
        $this->fkCurso = $fkCurso;
    }

    function setProtocolo($protocolo) {
        $this->protocolo = $protocolo;
    }

    function setFkStatus($fkStatus) {
        $this->fkStatus = $fkStatus;
    }

    function setDtaAbertura($dtaAbertura) {
        $this->dtaAbertura = $dtaAbertura;
    }

    function setDtaAlteracao($dtaAlteracao) {
        $this->dtaAlteracao = $dtaAlteracao;
    }

    function setDtaFechamento($dtaFechamento) {
        $this->dtaFechamento = $dtaFechamento;
    }
    
    function getFkTipoSolicitacao() {
        return $this->fkTipoSolicitacao;
    }

    function setFkTipoSolicitacao($fkTipoSolicitacao) {
        $this->fkTipoSolicitacao = $fkTipoSolicitacao;
    }
    
    function getObservacao() {
        return $this->observacao;
    }

    function getArquivo() {
        return $this->arquivo;
    }

    function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    function setArquivo($arquivo) {
        $this->arquivo = $arquivo;
    }
    
}