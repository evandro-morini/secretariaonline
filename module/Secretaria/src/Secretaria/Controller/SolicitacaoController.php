<?php

namespace Secretaria\Controller;

use Secretaria\Model\CursoModel;
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
            //verificando se o usuário já possui uma solicitação do mesmo tipo em aberto
            $solicitacoesAbertas = $solicitacaoModel->findTOpenTasks($usuarioSessao->pkUsuario, 4);
            if ($solicitacoesAbertas > 0) {
                $this->flashMessenger()->addErrorMessage('Você já possui uma solicitação deste tipo em aberto. Aguarde o atendimento, ou caso necessite fazer mudanças, cancele o protocolo pendente e faça uma nova solicitação.');
                $this->redirect()->refresh();
            } else {

                $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);

                if ($idSolicitacao) {
                    $total = (int) $data['totalDisciplinas'];
                    for ($i = 1; $i <= $total; $i++) {
                        $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina' . $i], $data['tipo' . $i]);
                    }
                    $protocolo = date("Ymd") . 'CO' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                    $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);

                    $email = $usuarioModel->findEmailById($usuarioSessao->pkUsuario);
                    $bodyPart = new \Zend\Mime\Message();
                    $bodyMessage = new \Zend\Mime\Part('Olá ' . $usuarioSessao->nome . ', foi aberto o protocolo ' . $protocolo . ' referente a sua solicitação de Correção de Matrícula. Você será notificado assim que este protocolo for encerrado. Obrigado por utilizar a secretaria online!');
                    $bodyMessage->type = 'text/html';
                    $bodyPart->setParts(array($bodyMessage));
                    $message = new \Zend\Mail\Message();
                    $message->addTo($email, $usuarioSessao->nome)
                            ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                            ->setSubject('Abertura - Protocolo ' . $protocolo)
                            ->setBody($bodyPart)
                            ->setEncoding('UTF-8');

                    $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                        "name" => "gmail",
                        "host" => "smtp.gmail.com",
                        "port" => 587,
                        "connection_class" => "plain",
                        "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                            "password" => "ufpr2016", "ssl" => "tls")
                    ));
                    $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                    $transport->send($message);

                    $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                    $this->redirect()->refresh();
                }
            }
        }

        return new ViewModel(array(
            'nomeUsuario' => $usuarioSessao->nome,
            'idUsuario' => $usuarioSessao->pkUsuario,
            'matricula' => $matricula,
            'curso' => $curso,
            'disciplinas' => $disciplinas
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
            //verificando se o usuário já possui uma solicitação do mesmo tipo em aberto
            $solicitacoesAbertas = $solicitacaoModel->findTOpenTasks($usuarioSessao->pkUsuario, 3);
            if ($solicitacoesAbertas > 0) {
                $this->flashMessenger()->addErrorMessage('Você já possui uma solicitação deste tipo em aberto. Aguarde o atendimento, ou caso necessite fazer mudanças, cancele o protocolo pendente e faça uma nova solicitação.');
                $this->redirect()->refresh();
            } else {

                $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);

                if ($idSolicitacao) {
                    $total = (int) $data['totalDisciplinas'];
                    $fkTipoSolicitacaoDisciplina = 1; //Status não se aplica
                    for ($i = 1; $i <= $total; $i++) {
                        $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina' . $i], $fkTipoSolicitacaoDisciplina);
                    }
                    $protocolo = date("Ymd") . 'CA' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                    $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);

                    $email = $usuarioModel->findEmailById($usuarioSessao->pkUsuario);
                    $bodyPart = new \Zend\Mime\Message();
                    $bodyMessage = new \Zend\Mime\Part('Olá ' . $usuarioSessao->nome . ', foi aberto o protocolo ' . $protocolo . ' referente a sua solicitação de Cancelamento de Matrícula. Você será notificado assim que este protocolo for encerrado. Obrigado por utilizar a secretaria online!');
                    $bodyMessage->type = 'text/html';
                    $bodyPart->setParts(array($bodyMessage));
                    $message = new \Zend\Mail\Message();
                    $message->addTo($email, $usuarioSessao->nome)
                            ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                            ->setSubject('Abertura - Protocolo ' . $protocolo)
                            ->setBody($bodyPart)
                            ->setEncoding('UTF-8');

                    $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                        "name" => "gmail",
                        "host" => "smtp.gmail.com",
                        "port" => 587,
                        "connection_class" => "plain",
                        "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                            "password" => "ufpr2016", "ssl" => "tls")
                    ));
                    $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                    $transport->send($message);

                    $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                    $this->redirect()->refresh();
                }
            }
        }

        return new ViewModel(array(
            'nomeUsuario' => $usuarioSessao->nome,
            'idUsuario' => $usuarioSessao->pkUsuario,
            'matricula' => $matricula,
            'curso' => $curso,
            'disciplinas' => $disciplinas
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
            //verificando se o usuário já possui uma solicitação do mesmo tipo em aberto
            $solicitacoesAbertas = $solicitacaoModel->findTOpenTasks($usuarioSessao->pkUsuario, 2);
            if ($solicitacoesAbertas > 0) {
                $this->flashMessenger()->addErrorMessage('Você já possui uma solicitação deste tipo em aberto. Aguarde o atendimento, ou caso necessite fazer mudanças, cancele o protocolo pendente e faça uma nova solicitação.');
                $this->redirect()->refresh();
            } else {

                $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);

                if ($idSolicitacao) {
                    $total = (int) $data['totalDisciplinas'];
                    $fkTipoSolicitacaoDisciplina = 1; //Status não se aplica
                    for ($i = 1; $i <= $total; $i++) {
                        $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina' . $i], $fkTipoSolicitacaoDisciplina);
                    }
                    $protocolo = date("Ymd") . 'AP' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                    $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);

                    $email = $usuarioModel->findEmailById($usuarioSessao->pkUsuario);
                    $bodyPart = new \Zend\Mime\Message();
                    $bodyMessage = new \Zend\Mime\Part('Olá ' . $usuarioSessao->nome . ', foi aberto o protocolo ' . $protocolo . ' referente a sua solicitação de Aproveitamento de conhecimento. Você será notificado assim que este protocolo for encerrado. Obrigado por utilizar a secretaria online!');
                    $bodyMessage->type = 'text/html';
                    $bodyPart->setParts(array($bodyMessage));
                    $message = new \Zend\Mail\Message();
                    $message->addTo($email, $usuarioSessao->nome)
                            ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                            ->setSubject('Abertura - Protocolo ' . $protocolo)
                            ->setBody($bodyPart)
                            ->setEncoding('UTF-8');

                    $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                        "name" => "gmail",
                        "host" => "smtp.gmail.com",
                        "port" => 587,
                        "connection_class" => "plain",
                        "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                            "password" => "ufpr2016", "ssl" => "tls")
                    ));
                    $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                    $transport->send($message);

                    $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                    $this->redirect()->refresh();
                }
            }
        }

        return new ViewModel(array(
            'nomeUsuario' => $usuarioSessao->nome,
            'idUsuario' => $usuarioSessao->pkUsuario,
            'matricula' => $matricula,
            'curso' => $curso,
            'disciplinas' => $disciplinas
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
            //verificando se o usuário já possui uma solicitação do mesmo tipo em aberto
            $solicitacoesAbertas = $solicitacaoModel->findTOpenTasks($usuarioSessao->pkUsuario, 5);
            if ($solicitacoesAbertas > 0) {
                $this->flashMessenger()->addErrorMessage('Você já possui uma solicitação deste tipo em aberto. Aguarde o atendimento, ou caso necessite fazer mudanças, cancele o protocolo pendente e faça uma nova solicitação.');
                $this->redirect()->refresh();
            } else {

                //validação do anexo
                $files = $request->getFiles()->toArray();
                if ($files['arquivo']['error'] > 0) {
                    $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
                    if ($idSolicitacao) {
                        $protocolo = date("Ymd") . 'OS' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                        $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);

                        $email = $usuarioModel->findEmailById($usuarioSessao->pkUsuario);
                        $bodyPart = new \Zend\Mime\Message();
                        $bodyMessage = new \Zend\Mime\Part('Olá ' . $usuarioSessao->nome . ', foi aberto o protocolo ' . $protocolo . ' referente a sua solicitação. Você será notificado assim que este protocolo for encerrado. Obrigado por utilizar a secretaria online!');
                        $bodyMessage->type = 'text/html';
                        $bodyPart->setParts(array($bodyMessage));
                        $message = new \Zend\Mail\Message();
                        $message->addTo($email, $usuarioSessao->nome)
                                ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                                ->setSubject('Abertura - Protocolo ' . $protocolo)
                                ->setBody($bodyPart)
                                ->setEncoding('UTF-8');

                        $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                            "name" => "gmail",
                            "host" => "smtp.gmail.com",
                            "port" => 587,
                            "connection_class" => "plain",
                            "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                                "password" => "ufpr2016", "ssl" => "tls")
                        ));
                        $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                        $transport->send($message);

                        $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                        $this->redirect()->refresh();
                    }
                } else {
                    $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                    $filesize = new \Zend\Validator\File\Size(array('max' => 10485760)); //10mb tamanho máximo  
                    $extension = new \Zend\Validator\File\Extension(array('extension' => array('doc', 'jpg', 'pdf')));
                    $httpadapter->setValidators(array($filesize, $extension), $files['arquivo']['name']);
                    if ($httpadapter->isValid()) {
                        $httpadapter->setDestination($_SERVER['DOCUMENT_ROOT'] . 'secretariaonline/public/uploads/');
                        if ($httpadapter->receive($files['arquivo']['name'])) {
                            $solicitacao->setArquivo($files['arquivo']['name']);
                        }

                        $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
                        if ($idSolicitacao) {
                            $protocolo = date("Ymd") . 'OS' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                            $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);

                            $email = $usuarioModel->findEmailById($usuarioSessao->pkUsuario);
                            $bodyPart = new \Zend\Mime\Message();
                            $bodyMessage = new \Zend\Mime\Part('Olá ' . $usuarioSessao->nome . ', foi aberto o protocolo ' . $protocolo . ' referente a sua solicitação. Você será notificado assim que este protocolo for encerrado. Obrigado por utilizar a secretaria online!');
                            $bodyMessage->type = 'text/html';
                            $bodyPart->setParts(array($bodyMessage));
                            $message = new \Zend\Mail\Message();
                            $message->addTo($email, $usuarioSessao->nome)
                                    ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                                    ->setSubject('Abertura - Protocolo ' . $protocolo)
                                    ->setBody($bodyPart)
                                    ->setEncoding('UTF-8');

                            $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                                "name" => "gmail",
                                "host" => "smtp.gmail.com",
                                "port" => 587,
                                "connection_class" => "plain",
                                "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                                    "password" => "ufpr2016", "ssl" => "tls")
                            ));
                            $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                            $transport->send($message);

                            $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                            $this->redirect()->refresh();
                        }
                    } else {
                        $this->flashMessenger()->addErrorMessage('Erro: O arquivo enviado não está de acordo com as especificações. Favor verificar!');
                        $this->redirect()->refresh();
                    }
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
            //verificando se o usuário já possui uma solicitação do mesmo tipo em aberto
            $solicitacoesAbertas = $solicitacaoModel->findTOpenTasks($usuarioSessao->pkUsuario, 1);
            if ($solicitacoesAbertas > 0) {
                $this->flashMessenger()->addErrorMessage('Você já possui uma solicitação deste tipo em aberto. Aguarde o atendimento, ou caso necessite fazer mudanças, cancele o protocolo pendente e faça uma nova solicitação.');
                $this->redirect()->refresh();
            } else {

                //validação do anexo
                $files = $request->getFiles()->toArray();
                if ($files['arquivo']['error'] > 0) {
                    $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
                    if ($idSolicitacao) {
                        $total = (int) $data['totalDisciplinas'];
                        $fkTipoSolicitacaoDisciplina = 1; //Status não se aplica
                        for ($i = 1; $i <= $total; $i++) {
                            $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina' . $i], $fkTipoSolicitacaoDisciplina);
                        }
                        $protocolo = date("Ymd") . 'AD' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                        $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);

                        $email = $usuarioModel->findEmailById($usuarioSessao->pkUsuario);
                        $bodyPart = new \Zend\Mime\Message();
                        $bodyMessage = new \Zend\Mime\Part('Olá ' . $usuarioSessao->nome . ', foi aberto o protocolo ' . $protocolo . ' referente a sua solicitação de Adiantamento de disciplina. Você será notificado assim que este protocolo for encerrado. Obrigado por utilizar a secretaria online!');
                        $bodyMessage->type = 'text/html';
                        $bodyPart->setParts(array($bodyMessage));
                        $message = new \Zend\Mail\Message();
                        $message->addTo($email, $usuarioSessao->nome)
                                ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                                ->setSubject('Abertura - Protocolo ' . $protocolo)
                                ->setBody($bodyPart)
                                ->setEncoding('UTF-8');

                        $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                            "name" => "gmail",
                            "host" => "smtp.gmail.com",
                            "port" => 587,
                            "connection_class" => "plain",
                            "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                                "password" => "ufpr2016", "ssl" => "tls")
                        ));
                        $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                        $transport->send($message);

                        $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                        $this->redirect()->refresh();
                    }
                } else {
                    $httpadapter = new \Zend\File\Transfer\Adapter\Http();
                    $filesize = new \Zend\Validator\File\Size(array('max' => 10485760)); //10mb tamanho máximo  
                    $extension = new \Zend\Validator\File\Extension(array('extension' => array('doc', 'jpg', 'pdf')));
                    $httpadapter->setValidators(array($filesize, $extension), $files['arquivo']['name']);
                    if ($httpadapter->isValid()) {
                        $httpadapter->setDestination($_SERVER['DOCUMENT_ROOT'] . 'secretariaonline/public/uploads/');
                        if ($httpadapter->receive($files['arquivo']['name'])) {
                            $solicitacao->setArquivo($files['arquivo']['name']);
                        }

                        $idSolicitacao = $solicitacaoModel->insertTask($solicitacao);
                        if ($idSolicitacao) {
                            $total = (int) $data['totalDisciplinas'];
                            $fkTipoSolicitacaoDisciplina = 1; //Status não se aplica
                            for ($i = 1; $i <= $total; $i++) {
                                $solicitacaoModel->insertTaskSubject($idSolicitacao, $data['disciplina' . $i], $fkTipoSolicitacaoDisciplina);
                            }
                            $protocolo = date("Ymd") . 'AD' . str_pad($idSolicitacao, 3, "0", STR_PAD_LEFT);
                            $solicitacaoModel->insertProtocol($idSolicitacao, $protocolo);

                            $email = $usuarioModel->findEmailById($usuarioSessao->pkUsuario);
                            $bodyPart = new \Zend\Mime\Message();
                            $bodyMessage = new \Zend\Mime\Part('Olá ' . $usuarioSessao->nome . ', foi aberto o protocolo ' . $protocolo . ' referente a sua solicitação de Adiantamento de disciplina. Você será notificado assim que este protocolo for encerrado. Obrigado por utilizar a secretaria online!');
                            $bodyMessage->type = 'text/html';
                            $bodyPart->setParts(array($bodyMessage));
                            $message = new \Zend\Mail\Message();
                            $message->addTo($email, $usuarioSessao->nome)
                                    ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                                    ->setSubject('Abertura - Protocolo ' . $protocolo)
                                    ->setBody($bodyPart)
                                    ->setEncoding('UTF-8');

                            $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                                "name" => "gmail",
                                "host" => "smtp.gmail.com",
                                "port" => 587,
                                "connection_class" => "plain",
                                "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                                    "password" => "ufpr2016", "ssl" => "tls")
                            ));
                            $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                            $transport->send($message);

                            $this->flashMessenger()->addSuccessMessage("Solicitação aberta com sucesso, seu número de protocolo é $protocolo");
                            $this->redirect()->refresh();
                        }
                    } else {
                        $this->flashMessenger()->addErrorMessage('Erro: O arquivo enviado não está de acordo com as especificações. Favor verificar!');
                        $this->redirect()->refresh();
                    }
                }
            }
        }

        return new ViewModel(array(
            'nomeUsuario' => $usuarioSessao->nome,
            'idUsuario' => $usuarioSessao->pkUsuario,
            'matricula' => $matricula,
            'curso' => $curso,
            'disciplinas' => $disciplinas
        ));
    }

    //Ajax Function
    public function inserirDisciplinaAction() {
        $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
        $disciplinas = $disciplinaModel->findDisciplinas();
        $count = $this->params()->fromPost('count');
        $tipo = (int) $this->params()->fromPost('tipo');
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
            if ((int) $usuarioSessao->perfil == 1) {
                $solicitacoes = $solicitacaoModel->searchProtocol($protocolo, $usuarioSessao->pkUsuario);
            } else {
                $solicitacoes = $solicitacaoModel->searchProtocol($protocolo, null);
            }

            return new ViewModel(array(
                'solicitacoes' => $solicitacoes,
                'protocolo' => $protocolo
            ));
        }
    }

    public function visualizarAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $usuarioPerfil = (int) $usuarioSessao->perfil;
        $protocolo = $this->params()->fromRoute('protocolo');
        $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
        $cursoModel = new CursoModel($this->getDbAdapter());
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $solicitacoes = $solicitacaoModel->findTasksByProtocol($protocolo);
        $encServidores = array();

        //preenchimento das disciplinas (caso existam)
        $disciplinas = null;
        if ($solicitacoes) {
            $disciplinas = $solicitacaoModel->findTaskSubjects($solicitacoes['id']);
            $historico = $solicitacaoModel->findTaskHistory($solicitacoes['id']);
            //caso a solicitação não exista, retorna a tela anterior
        } else {
            if ($usuarioPerfil == 1) {
                return $this->redirect()->toRoute('solicitacao/minhas-solicitacoes');
            } else {
                return $this->redirect()->toRoute('tarefas');
            }
        }

        //configurando a visualização conforme o nível de acesso do usuário
        $granted = 0;
        //caso o usuário seja aluno
        if ($usuarioPerfil == 1) {
            $granted = 1;
            if ($usuarioSessao->pkUsuario != (int) $solicitacoes['fk_usuario']) {
                return $this->redirect()->toRoute('home/denied');
            }
            //caso o usuario seja servidor
        } else {
            $idCurso = $cursoModel->findPerfilCurso($usuarioSessao->perfil);
            $encServidores = $usuarioModel->findListaServidores($usuarioSessao->pkUsuario, $idCurso);
            if ((int) $solicitacoes['fk_status'] == 1) {
                $granted = 2; //tarefa disponível para atribuição    
            } elseif ((int) $solicitacoes['fk_status'] == 2) {
                $atribuidaUsuario = $solicitacaoModel->findUltimoEncaminhamento($solicitacoes['id']);
                if ($atribuidaUsuario['servidor'] != $usuarioSessao->pkUsuario) {
                    $granted = 3; //tarefa está atribuida, porem para outro servidor
                } else {
                    $granted = 4; //tarefa atribuida para o servidor logado, permissão total
                }
            } elseif ((int) $solicitacoes['fk_status'] == 3) {
                $atribuidaUsuario = $solicitacaoModel->findLastTaskForward($solicitacoes['id'], null);
                if ($atribuidaUsuario['novo_servidor'] != $usuarioSessao->pkUsuario) {
                    $granted = 3; //tarefa está atribuida, porem para outro servidor
                } else {
                    $granted = 2; //tarefa atribuida para o servidor logado, permissão total
                }
            }
        }

        return new ViewModel(array(
            'solicitacoes' => $solicitacoes,
            'protocolo' => $protocolo,
            'disciplinas' => $disciplinas,
            'servidores' => $encServidores,
            'historico' => $historico,
            'granted' => $granted,
        ));
    }

    public function atribuirAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $idUsuario = $usuarioSessao->pkUsuario;
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $protocolo = $data['protocolo'];
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacoes = $solicitacaoModel->findTasksByProtocol($protocolo);
            $idAtribuido = $solicitacaoModel->insertAtribuido($solicitacoes['id'], $idUsuario);
            if ($idAtribuido) {
                $solicitacaoModel->updateStatusTask($solicitacoes['id'], 2);
            }
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $this->redirect()->toRoute('solicitacao/visualizar', array('protocolo' => $protocolo));
        }
    }

    public function cancelarAction() {
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $protocolo = $data['protocolo'];
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacoes = $solicitacaoModel->findTasksByProtocol($protocolo);
            $solicitacaoModel->updateStatusTask($solicitacoes['id'], 5);

            $bodyPart = new \Zend\Mime\Message();
            $bodyMessage = new \Zend\Mime\Part('Olá ' . $solicitacoes['nome'] . ', o protocolo ' . $protocolo . ' foi cancelado conforme solicitado. Obrigado por utilizar a secretaria online!');
            $bodyMessage->type = 'text/html';
            $bodyPart->setParts(array($bodyMessage));
            $message = new \Zend\Mail\Message();
            $message->addTo($solicitacoes['email'], $solicitacoes['nome'])
                    ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                    ->setSubject('Cancelamento - Protocolo ' . $protocolo)
                    ->setBody($bodyPart)
                    ->setEncoding('UTF-8');

            $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                "name" => "gmail",
                "host" => "smtp.gmail.com",
                "port" => 587,
                "connection_class" => "plain",
                "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                    "password" => "ufpr2016", "ssl" => "tls")
            ));
            $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
            $transport->send($message);

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $this->redirect()->toRoute('solicitacao/visualizar', array('protocolo' => $protocolo));
        }
    }

    public function encaminharAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $idUsuario = $usuarioSessao->pkUsuario;
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $protocolo = $data['protocolo'];
            $novoUsuario = $data['newusuario'];
            $justificativa = $data['justificativa'];
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacoes = $solicitacaoModel->findTasksByProtocol($protocolo);
            $idEncaminhado = $solicitacaoModel->insertEncaminhado($solicitacoes['id'], $idUsuario, $novoUsuario, $justificativa);
            if ($idEncaminhado) {
                $solicitacaoModel->updateStatusTask($solicitacoes['id'], 3);
            }
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $this->redirect()->toRoute('tarefas');
        }
    }

    public function encerrarAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $idUsuario = $usuarioSessao->pkUsuario;
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $protocolo = $data['protocolo'];
            $justificativa = $data['justificativa'];
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $solicitacoes = $solicitacaoModel->findTasksByProtocol($protocolo);
            $idEncerrado = $solicitacaoModel->insertEncerrado($solicitacoes['id'], $idUsuario, $justificativa);
            if ($idEncerrado) {
                $solicitacaoModel->updateStatusTask($solicitacoes['id'], 4);

                $bodyPart = new \Zend\Mime\Message();
                $bodyMessage = new \Zend\Mime\Part('Olá ' . $solicitacoes['nome'] . ', o protocolo ' . $protocolo . ' foi encerrado com a seguinte observação: "<i>' . $justificativa . '</i>". Obrigado por utilizar a secretaria online!');
                $bodyMessage->type = 'text/html';
                $bodyPart->setParts(array($bodyMessage));
                $message = new \Zend\Mail\Message();
                $message->addTo($solicitacoes['email'], $solicitacoes['nome'])
                        ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                        ->setSubject('Encerramento - Protocolo ' . $protocolo)
                        ->setBody($bodyPart)
                        ->setEncoding('UTF-8');

                $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                    "name" => "gmail",
                    "host" => "smtp.gmail.com",
                    "port" => 587,
                    "connection_class" => "plain",
                    "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                        "password" => "ufpr2016", "ssl" => "tls")
                ));
                $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                $transport->send($message);
            }
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $this->redirect()->toRoute('solicitacao/visualizar', array('protocolo' => $protocolo));
        }
    }

}
