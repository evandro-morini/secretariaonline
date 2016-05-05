<?php

namespace Secretaria\Controller;

use Secretaria\Model\UsuarioModel;
use Secretaria\Model\DisciplinaModel;
use Secretaria\Model\Entity\Solicitacao;
use Secretaria\Model\SolicitacaoModel;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class SolicitacaoController extends AbstractController {
    
    public function indexAction() {
        return new ViewModel();
    }
    
    public function correcaoMatriculaAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $matricula = $usuarioModel->getEnrollment($usuarioSessao->pkUsuario);
        $curso = $usuarioModel->getSubject($usuarioSessao->pkUsuario);
        $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
        $disciplinas = $disciplinaModel->findDisciplinas();
        
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacao = new Solicitacao();
            $solicitacao->setFkUsuario($data['idUsuario']);
            $solicitacao->setFkCurso($data['idCurso']);
            $solicitacao->setFkTipoSolicitacao(4); //Tipo correção
            $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
            
            if($idSolicitacao) {
                $total = (int)$data['totalDisciplinas'];
                for($i = 1; $i <= $total; $i++) {
                    $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina'.$i], $data['tipo'.$i]);
                }
                $protocolo = date("Ymd") . 'CO' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);
                $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                $this->redirect()->refresh();
            }
        }
        
        return new ViewModel(array(
            'nomeUsuario' => $usuarioSessao->nome,
            'idUsuario' => $usuarioSessao->pkUsuario,
            'matricula' => $matricula,
            'curso' => $curso,
            'disciplinas'=> $disciplinas
        ));
    }
    
    public function cancelamentoMatriculaAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $matricula = $usuarioModel->getEnrollment($usuarioSessao->pkUsuario);
        $curso = $usuarioModel->getSubject($usuarioSessao->pkUsuario);
        $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
        $disciplinas = $disciplinaModel->findDisciplinas();
        
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacao = new Solicitacao();
            $solicitacao->setFkUsuario($data['idUsuario']);
            $solicitacao->setFkCurso($data['idCurso']);
            $solicitacao->setFkTipoSolicitacao(3); //Tipo cancelamento
            $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
            
            if($idSolicitacao) {
                $total = (int)$data['totalDisciplinas'];
                $fkTipoSolicitacaoDisciplina = 1; //Status não se aplica
                for($i = 1; $i <= $total; $i++) {
                    $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina'.$i], $fkTipoSolicitacaoDisciplina);
                }
                $protocolo = date("Ymd") . 'CA' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);
                $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                $this->redirect()->refresh();
            }
        }
        
        return new ViewModel(array(
            'nomeUsuario' => $usuarioSessao->nome,
            'idUsuario' => $usuarioSessao->pkUsuario,
            'matricula' => $matricula,
            'curso' => $curso,
            'disciplinas'=> $disciplinas
        ));
    }
    
    public function aproveitamentoConhecimentoAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $matricula = $usuarioModel->getEnrollment($usuarioSessao->pkUsuario);
        $curso = $usuarioModel->getSubject($usuarioSessao->pkUsuario);
        $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
        $disciplinas = $disciplinaModel->findDisciplinas();
        
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacao = new Solicitacao();
            $solicitacao->setFkUsuario($data['idUsuario']);
            $solicitacao->setFkCurso($data['idCurso']);
            $solicitacao->setFkTipoSolicitacao(2); //Tipo aproveitamento
            $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
            
            if($idSolicitacao) {
                $total = (int)$data['totalDisciplinas'];
                $fkTipoSolicitacaoDisciplina = 1; //Status não se aplica
                for($i = 1; $i <= $total; $i++) {
                    $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina'.$i], $fkTipoSolicitacaoDisciplina);
                }
                $protocolo = date("Ymd") . 'AP' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);
                $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                $this->redirect()->refresh();
            }
        }
        
        return new ViewModel(array(
            'nomeUsuario' => $usuarioSessao->nome,
            'idUsuario' => $usuarioSessao->pkUsuario,
            'matricula' => $matricula,
            'curso' => $curso,
            'disciplinas'=> $disciplinas
        ));
    }
    
    public function outrasSolicitacoesAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $matricula = $usuarioModel->getEnrollment($usuarioSessao->pkUsuario);
        $curso = $usuarioModel->getSubject($usuarioSessao->pkUsuario);
        
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacao = new Solicitacao();
            $solicitacao->setFkUsuario($data['idUsuario']);
            $solicitacao->setFkCurso($data['idCurso']);
            $solicitacao->setObservacao($data['observacao']);
            $solicitacao->setFkTipoSolicitacao(5); //Tipo Outros
            
            //validação do anexo
            $files =  $request->getFiles()->toArray();
            if($files['arquivo']['error'] > 0) {
                $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
                if($idSolicitacao) {
                    $protocolo = date("Ymd") . 'OS' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                    $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);
                    $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                    $this->redirect()->refresh();
                }
            } else {
                $httpadapter = new \Zend\File\Transfer\Adapter\Http(); 
                $filesize  = new \Zend\Validator\File\Size(array('max' => 10485760)); //10mb tamanho máximo  
                $extension = new \Zend\Validator\File\Extension(array('extension' => array('doc', 'jpg', 'pdf')));
                $httpadapter->setValidators(array($filesize, $extension), $files['arquivo']['name']);
                if($httpadapter->isValid()) {
                    $httpadapter->setDestination($_SERVER['DOCUMENT_ROOT'] . 'secretariaonline/public/uploads/');
                    if($httpadapter->receive($files['arquivo']['name'])) {
                        $solicitacao->setArquivo($files['arquivo']['name']);
                    }

                    $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
                    if($idSolicitacao) {
                        $protocolo = date("Ymd") . 'OS' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                        $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);
                        $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                        $this->redirect()->refresh();
                    }

                } else {
                    $this->flashMessenger()->addErrorMessage('Erro: O arquivo enviado não está de acordo com as especificações. Favor verificar!');
                    $this->redirect()->refresh();
                }
            }
            
        }

        return new ViewModel(array(
            'nomeUsuario' => $usuarioSessao->nome,
            'idUsuario' => $usuarioSessao->pkUsuario,
            'matricula' => $matricula,
            'curso' => $curso
        ));
    }
    
    public function adiantamentoDisciplinaAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $matricula = $usuarioModel->getEnrollment($usuarioSessao->pkUsuario);
        $curso = $usuarioModel->getSubject($usuarioSessao->pkUsuario);
        $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
        $disciplinas = $disciplinaModel->findDisciplinas();
        
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacao = new Solicitacao();
            $solicitacao->setFkUsuario($data['idUsuario']);
            $solicitacao->setFkCurso($data['idCurso']);
            $solicitacao->setFkTipoSolicitacao(1); //Tipo adiantamento
            
            //validação do anexo
            $files =  $request->getFiles()->toArray();
            if($files['arquivo']['error'] > 0) {
                $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
                if($idSolicitacao) {
                    $total = (int)$data['totalDisciplinas'];
                    $fkTipoSolicitacaoDisciplina = 1; //Status não se aplica
                    for($i = 1; $i <= $total; $i++) {
                        $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina'.$i], $fkTipoSolicitacaoDisciplina);
                    }
                    $protocolo = date("Ymd") . 'AD' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                    $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);
                    $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                    $this->redirect()->refresh();
                }
            } else {
                $httpadapter = new \Zend\File\Transfer\Adapter\Http(); 
                $filesize  = new \Zend\Validator\File\Size(array('max' => 10485760)); //10mb tamanho máximo  
                $extension = new \Zend\Validator\File\Extension(array('extension' => array('doc', 'jpg', 'pdf')));
                $httpadapter->setValidators(array($filesize, $extension), $files['arquivo']['name']);
                if($httpadapter->isValid()) {
                    $httpadapter->setDestination($_SERVER['DOCUMENT_ROOT'] . 'secretariaonline/public/uploads/');
                    if($httpadapter->receive($files['arquivo']['name'])) {
                        $solicitacao->setArquivo($files['arquivo']['name']);
                    }

                    $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
                    if($idSolicitacao) {
                        $total = (int)$data['totalDisciplinas'];
                        $fkTipoSolicitacaoDisciplina = 1; //Status não se aplica
                        for($i = 1; $i <= $total; $i++) {
                            $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina'.$i], $fkTipoSolicitacaoDisciplina);
                        }
                        $protocolo = date("Ymd") . 'AD' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                        $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);
                        $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                        $this->redirect()->refresh();
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('Erro: O arquivo enviado não está de acordo com as especificações. Favor verificar!');
                    $this->redirect()->refresh();
                }
            }
        }
        
        return new ViewModel(array(
            'nomeUsuario' => $usuarioSessao->nome,
            'idUsuario' => $usuarioSessao->pkUsuario,
            'matricula' => $matricula,
            'curso' => $curso,
            'disciplinas'=> $disciplinas
        ));
    }
    
    //Ajax Function
    public function inserirDisciplinaAction() {
        $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
        $disciplinas = $disciplinaModel->findDisciplinas();
        $count = $this->params()->fromPost('count');
        $tipo = (int)$this->params()->fromPost('tipo');
        $count += 1;
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);

            $viewModel->setVariables(array(
                'disciplinas' => $disciplinas,
                'count' => $count,
                'tipo' => $tipo
            ));
            return $viewModel;
        }
    }
    
    public function minhasSolicitacoesAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
        $solicitacoes = $solicitacaoModel->findTasksByUser($usuarioSessao->pkUsuario);
        return new ViewModel(array(
            'solicitacoes' => $solicitacoes
        ));
    }
    
    public function pesquisarProtocoloAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $protocolo = $data['protocolo'];
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacoes = $solicitacaoModel->searchProtocol($protocolo, $usuarioSessao->pkUsuario);

            return new ViewModel(array(
                'solicitacoes' => $solicitacoes,
                'protocolo' => $protocolo
            )); 
        }
    }
    
    public function visualizarAction() {
        $protocolo = $this->params()->fromRoute('protocolo');
        $disciplinas = null;
        $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
        $solicitacoes = $solicitacaoModel->findTasksByProtocol($protocolo);
        if(isset($solicitacoes)) {
            $disciplinas = $solicitacaoModel->findTaskSubjects($solicitacoes['id']);
        }
        return new ViewModel(array(
            'solicitacoes' => $solicitacoes,
            'protocolo' => $protocolo,
            'disciplinas' => $disciplinas
        )); 
    }
    
}