<?php

namespace Secretaria\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Crm\Model\Grupo;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;

class SolicitacaoModel extends AbstractModel {

    protected $table = 'tb_solicitacao';
    protected $schema = 'secretariaonline';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function insertTask(\Secretaria\Model\Entity\Solicitacao $solicitacao) {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $newData = array(
            'fk_usuario' => $solicitacao->getFkUsuario(),
            'fk_curso' => $solicitacao->getFkCurso(),
            'fk_tipo_solicitacao' => $solicitacao->getFkTipoSolicitacao(),
            'observacao' => $solicitacao->getObservacao(),
            'arquivo' => $solicitacao->getArquivo(),
            'fk_status' => 1, //STATUS NOVO
            'dta_abertura' => date("Y-m-d H:i:s")
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $resultSet = $statement->execute();
        return $resultSet->getGeneratedValue();
    }

    public function insertProtocol($idSolicitacao, $protocolo) {
        $data = array(
            'protocolo' => $protocolo
        );
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $update->set($data);
        $update->where('id = ' . $idSolicitacao);
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute();
    }

    public function insertTaskSubject($idSolicitacao, $idDisciplina, $fkTipoSolicitacaoDisciplina) {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier('tb_solicitacao_disciplina', $this->getSchema()));
        $newData = array(
            'fk_solicitacao' => $idSolicitacao,
            'fk_disciplina' => $idDisciplina,
            'fk_tipo_solic_disciplina' => $fkTipoSolicitacaoDisciplina
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }
    
    public function findAllTasks($idCurso, $status) {
        $sql = <<<EOT
            SELECT
                solic.id,
                usu.matricula,
                solic.protocolo,
                tiposolic.descricao as tipo,
                DATE_FORMAT(
                        solic.dta_abertura,
                        '%d/%m/%Y %H:%i'
                ) AS data_exibir,
                solic.fk_status,
                DATE_FORMAT(
                        solic.dta_fechamento,
                        '%d/%m/%Y %H:%i'
                ) AS dta_fechamento,
                stats.descricao
            FROM
                tb_solicitacao AS solic
            JOIN 
                tb_usuario_curso as usu on solic.fk_usuario = usu.fk_usuario
            JOIN 
                tb_tipo_solicitacao AS tiposolic ON solic.fk_tipo_solicitacao = tiposolic.id
            JOIN 
                tb_status AS stats ON solic.fk_status = stats.id
            WHERE
                solic.fk_curso = $idCurso
            AND
                solic.fk_status = $status
            ORDER BY
                solic.dta_abertura
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
    }

    public function findTasksByUser($idUsuario) {
        $sql = <<<EOT
            SELECT
                solic.id,
                usu.matricula,
                solic.protocolo,
                tiposolic.descricao as tipo,
                DATE_FORMAT(
                        solic.dta_abertura,
                        '%d/%m/%Y %H:%i'
                ) AS data_exibir,
                solic.fk_status,
                stats.descricao
            FROM
                tb_solicitacao AS solic
            JOIN 
                tb_usuario_curso as usu on solic.fk_usuario = usu.fk_usuario
            JOIN 
                tb_tipo_solicitacao AS tiposolic ON solic.fk_tipo_solicitacao = tiposolic.id
            JOIN 
                tb_status AS stats ON solic.fk_status = stats.id
            WHERE
                solic.fk_usuario = $idUsuario
            ORDER BY
                solic.dta_abertura
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
    }

    public function findTasksByProtocol($protocolo) {
        $sql = <<<EOT
            SELECT
                solic.id,
                solic.fk_tipo_solicitacao as id_tipo,
                usuario.nome,
                usuario.email,
                usuario.telefone,
                solic.fk_usuario,
                curso.descricao as nome_curso,
                uscurso.matricula,
                solic.protocolo,
                tiposolic.descricao AS tipo,
                DATE_FORMAT(
                        solic.dta_abertura,
                        '%d/%m/%Y %H:%i'
                ) AS data_exibir,
                solic.fk_status,
                stats.descricao,
                solic.observacao,
                solic.arquivo,
                DATE_FORMAT(
                        solic.dta_fechamento,
                        '%d/%m/%Y %H:%i'
                ) AS dta_fechamento
            FROM
                tb_solicitacao AS solic
            JOIN tb_usuario_curso AS uscurso ON solic.fk_usuario = uscurso.fk_usuario
            JOIN tb_curso AS curso ON uscurso.fk_curso = curso.id
            JOIN tb_usuario AS usuario ON solic.fk_usuario = usuario.id
            JOIN tb_tipo_solicitacao AS tiposolic ON solic.fk_tipo_solicitacao = tiposolic.id
            JOIN tb_status AS stats ON solic.fk_status = stats.id
            WHERE
                    solic.protocolo = '$protocolo'
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute()->current();
    }

    public function searchProtocol($protocolo, $idUsuario = null) {
        $whereAux = '';
        if (!empty($idUsuario)) {
            $whereAux = "AND solic.fk_usuario = $idUsuario ";
        }
        $sql = <<<EOT
            SELECT
                solic.id,
                usu.matricula,
                solic.protocolo,
                tiposolic.descricao as tipo,
                DATE_FORMAT(
                        solic.dta_abertura,
                        '%d/%m/%Y %H:%i'
                ) AS data_exibir,
                solic.fk_status,
                stats.descricao
            FROM
                tb_solicitacao AS solic
            JOIN 
                tb_usuario_curso as usu on solic.fk_usuario = usu.fk_usuario
            JOIN 
                tb_tipo_solicitacao AS tiposolic ON solic.fk_tipo_solicitacao = tiposolic.id
            JOIN 
                tb_status AS stats ON solic.fk_status = stats.id
            WHERE
                solic.protocolo like '%$protocolo%' 
            $whereAux
            ORDER BY
                solic.dta_abertura
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
    }
    
    public function findTaskSubjects($idSolicitacao) {
        $sql = <<<EOT
            SELECT
                soldisc.fk_solicitacao,
                tipo.id AS tipo_id,
                tipo.descricao AS tipo_operacao,
                dis.id AS disciplina_id,
                dis.descricao AS nome_disciplina
            FROM
                tb_solicitacao_disciplina AS soldisc
            JOIN tb_tipo_solic_disciplina AS tipo ON soldisc.fk_tipo_solic_disciplina = tipo.id
            JOIN tb_disciplina AS dis ON soldisc.fk_disciplina = dis.id
            WHERE
                soldisc.fk_solicitacao = $idSolicitacao
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
    }
    
    public function insertAtribuido($fkSolicitacao, $fkUsuario) {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier('tb_atribuido', $this->getSchema()));
        $newData = array(
            'fk_solicitacao' => $fkSolicitacao,
            'fk_usuario' => $fkUsuario,
            'dta_inc' => date("Y-m-d H:i:s")
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $resultSet = $statement->execute();
        return $resultSet->getGeneratedValue();
    }
    
    public function insertEncaminhado($fkSolicitacao, $fkUsuario, $fkNovoUsuario, $justificativa) {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier('tb_encaminhado', $this->getSchema()));
        $newData = array(
            'fk_solicitacao' => $fkSolicitacao,
            'fk_usuario_old' => $fkUsuario,
            'fk_usuario_new' => $fkNovoUsuario,
            'justificativa'  => $justificativa,
            'dta_inc' => date("Y-m-d H:i:s")
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $resultSet = $statement->execute();
        return $resultSet->getGeneratedValue();
    }
    
    public function insertEncerrado($fkSolicitacao, $fkUsuario, $justificativa) {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert(new \Zend\Db\Sql\TableIdentifier('tb_encerrado', $this->getSchema()));
        $newData = array(
            'fk_solicitacao' => $fkSolicitacao,
            'fk_usuario' => $fkUsuario,
            'justificativa'  => $justificativa,
            'dta_inc' => date("Y-m-d H:i:s")
        );
        $insert->values($newData);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $resultSet = $statement->execute();
        return $resultSet->getGeneratedValue();
    }
    
    public function updateStatusTask($idSolicitacao, $fkStatus) {
        if((int)$fkStatus == 4 || (int)$fkStatus == 5) {
            $data = array(
                'fk_status' => $fkStatus,
                'dta_fechamento' => date("Y-m-d H:i:s")
            );
        } else {
            $data = array(
                'fk_status' => $fkStatus,
                'dta_alteracao' => date("Y-m-d H:i:s")
            );
        }
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update(new \Zend\Db\Sql\TableIdentifier($this->table, $this->getSchema()));
        $update->set($data);
        $update->where('id = ' . $idSolicitacao);
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute();
    }
    
    public function findUltimoEncaminhamento($idSolicitacao) {
        $sql = <<<EOT
            SELECT
                usu.matricula,
                solic.protocolo,
                tiposolic.descricao AS tipo,
                DATE_FORMAT(
                        solic.dta_abertura,
                        '%d/%m/%Y %H:%i'
                ) AS data_exibir,
                solic.fk_status,
                DATE_FORMAT(
                        solic.dta_fechamento,
                        '%d/%m/%Y %H:%i'
                ) AS dta_fechamento,
                stats.descricao,
                attr.fk_usuario AS servidor
            FROM
                tb_atribuido AS attr
            JOIN 
                tb_solicitacao AS solic ON attr.fk_solicitacao = solic.id
            JOIN 
                tb_usuario_curso AS usu ON solic.fk_usuario = usu.fk_usuario
            JOIN 
                tb_tipo_solicitacao AS tiposolic ON solic.fk_tipo_solicitacao = tiposolic.id
            JOIN 
                tb_status AS stats ON solic.fk_status = stats.id
            WHERE
                    attr.fk_solicitacao = $idSolicitacao
            ORDER BY
                    dta_inc DESC
            LIMIT 1
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute()->current();
    }
    
    public function findLastTaskForward($idSolicitacao, $idUsuario = null) {
        $whereAux = '';
        if (!empty($idUsuario)) {
            $whereAux = "AND enc.fk_usuario_old = $idUsuario ";
        }
        $sql = <<<EOT
            SELECT
                usu.matricula,
                solic.protocolo,
                tiposolic.descricao AS tipo,
                DATE_FORMAT(
                        solic.dta_abertura,
                        '%d/%m/%Y %H:%i'
                ) AS data_exibir,
                solic.fk_status,
                DATE_FORMAT(
                        solic.dta_fechamento,
                        '%d/%m/%Y %H:%i'
                ) AS dta_fechamento,
                stats.descricao,
                enc.fk_usuario_old AS servidor,
                enc.fk_usuario_new AS novo_servidor
            FROM
                tb_encaminhado AS enc
            JOIN 
                tb_solicitacao AS solic ON enc.fk_solicitacao = solic.id
            JOIN 
                tb_usuario_curso AS usu ON solic.fk_usuario = usu.fk_usuario
            JOIN 
                tb_tipo_solicitacao AS tiposolic ON solic.fk_tipo_solicitacao = tiposolic.id
            JOIN 
                tb_status AS stats ON solic.fk_status = stats.id
            WHERE
                    enc.fk_solicitacao = $idSolicitacao 
            $whereAux
            ORDER BY
                    dta_inc DESC
            LIMIT 1
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute()->current();
    }
    
    public function findTaskHistory($idSolicitacao) {
        $sql = <<<EOT
            SELECT
                *
            FROM
                (
                    SELECT
			'atribuido' AS tipo,
			attr.fk_solicitacao AS id,
			attr.fk_usuario AS id_usuario,
			usu.nome AS nome_usuario,
			NULL AS id_novo_usuario,
			NULL AS nome_novo_usuario,
			DATE_FORMAT(
				attr.dta_inc,
				'%d/%m/%Y %H:%i'
			) AS data_exibir,
			attr.dta_inc,
                        NULL AS justificativa
                    FROM
			tb_atribuido AS attr
                    JOIN tb_usuario AS usu ON usu.id = attr.fk_usuario
                    UNION
                    SELECT
                        'encaminhado' AS tipo,
                        enca.fk_solicitacao AS id,
                        enca.fk_usuario_old AS id_usuario,
                        usu.nome AS nome_usuario,
                        enca.fk_usuario_new AS id_novo_usuario,
                        newusu.nome AS nome_novo_usuario,
                        DATE_FORMAT(
                                enca.dta_inc,
                                '%d/%m/%Y %H:%i'
                        ) AS data_exibir,
                        enca.dta_inc,
                        enca.justificativa
                    FROM
                        tb_encaminhado AS enca
                    JOIN tb_usuario AS usu ON usu.id = enca.fk_usuario_old
                    JOIN tb_usuario AS newusu ON newusu.id = enca.fk_usuario_new
                    UNION
                    SELECT
                        'encerrado' AS tipo,
                        ence.fk_solicitacao AS id,
                        ence.fk_usuario AS id_usuario,
                        usu.nome AS nome_usuario,
                        NULL AS id_novo_usuario,
                        NULL AS nome_novo_usuario,
                        DATE_FORMAT(
                                ence.dta_inc,
                                '%d/%m/%Y %H:%i'
                        ) AS data_exibir,
                        ence.dta_inc,
                        ence.justificativa
                    FROM
                        tb_encerrado AS ence
                    JOIN tb_usuario AS usu ON usu.id = ence.fk_usuario
                ) relatorio
            WHERE
                id = $idSolicitacao
            ORDER BY
                dta_inc
EOT;
        $statement = $this->adapter->query($sql);
        return $statement->execute();
    }
    
    public function findTOpenTasks($fkUsuario, $fkTipoSolicitacao) {
        $sql = <<<EOT
            SELECT
                COUNT(*)
            FROM
                tb_solicitacao
            WHERE
                fk_usuario = $fkUsuario
            AND 
                fk_tipo_solicitacao = $fkTipoSolicitacao
            AND 
                fk_status IN (1, 2, 3)
EOT;
        $statement = $this->adapter->query($sql);
        return (int)$statement->execute()->current()['COUNT(*)'];
    }

}
