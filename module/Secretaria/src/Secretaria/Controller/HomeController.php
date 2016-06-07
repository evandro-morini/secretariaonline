<?php

namespace Secretaria\Controller;

use Secretaria\Form\AlunoForm;
use Secretaria\Form\Filter\AlunoFilter;
use Secretaria\Controller\CryptoController;
use Zend\View\Model\ViewModel;
use Secretaria\Model\CursoModel;
use Secretaria\Model\PerfilModel;
use Secretaria\Model\UsuarioModel;
use Secretaria\Model\Entity\Usuario;
use Zend\Authentication\AuthenticationService;

class HomeController extends AbstractController {

    public function indexAction() {
        return new ViewModel();
    }

    public function novoUsuarioAction() {
        //preenchendo select de cursos
        $cursoModel = new CursoModel($this->getDbAdapter());
        $listaCursos = $cursoModel->findCursos();
        //formulario novo aluno
        $alunoForm = new AlunoForm($listaCursos);
        $alunoForm->setInputFilter(new AlunoFilter());

        //requisição via post
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $alunoForm->setData($data);

            if ($alunoForm->isValid()) {
                //verifica se já não possui cadastro
                $usuarioModel = new UsuarioModel($this->getDbAdapter());
                $validacoes = $usuarioModel->countEnrollment($data['matricula']);
                $validacoes += $usuarioModel->countEmail($data['email']);

                if ($validacoes == 0) {
                    //BUSCA DA MATRICULA DO ALUNO NO ARQUIVO CSV
                    $grantedFile = $_SERVER['DOCUMENT_ROOT'] . 'secretariaonline/public/csv/granted.csv';
                    try {
                        $csvContent = file_get_contents($grantedFile);
                        $csvRows = explode("\n", $csvContent);
                        $valid = false;
                        foreach ($csvRows as $userData) {
                            if (strpos($userData, $data['matricula']) !== false) {
                                //aluno validado
                                $valid = true;
                            }
                        }
                        if ($valid === false) {
                            $this->flashMessenger()->addErrorMessage('Sua matrícula não foi encontrada em nosso banco de dados. Informe a secretaria do seu curso.');
                            //$this->redirect()->refresh();
                        } else {
                            //FORMULÁRIO VALIDADO E GRR VALIDADO -> SALVAR DADOS
                            $crypto = new CryptoController();
                            $cryptoPwd = $crypto->criarAction($data['password']);

                            $newUser = new Usuario();
                            $newUser->setCpf($data['cpf']);
                            $newUser->setNome($data['nome']);
                            $newUser->setEmail($data['email']);
                            $newUser->setPwd($cryptoPwd);
                            $newUser->setFkPerfil(1); //fk do perfil de aluno
                            $newUser->setStatus(0); //só será ativo após confirmação via email
                            //validação da data
                            if (!empty($data['telefone']) && strpos($data['telefone'], '_') === false) {
                                $newUser->setTelefone($data['telefone']);
                            }
                            $idUsuario = $usuarioModel->insertUser($newUser);
                            $idMatriculado = $usuarioModel->insertEnrollment($idUsuario, $data['curso'], $data['matricula']);

                            if ($idMatriculado) {
                                $bodyPart = new \Zend\Mime\Message();
                                $bodyMessage = new \Zend\Mime\Part('Olá ' . $newUser->getNome() . ', seja bem vindo a Secretaria Online UFPR, por favor, <a href="http://localhost/secretariaonline/public/ativar-cadastro/' . $newUser->getCpf() . '">clique aqui</a> para ativar seu cadastro.');
                                $bodyMessage->type = 'text/html';
                                $bodyPart->setParts(array($bodyMessage));
                                $message = new \Zend\Mail\Message();
                                $message->addTo($newUser->getEmail(), $newUser->getNome())
                                        ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                                        ->setSubject('Ativação de cadastro')
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
                                $this->flashMessenger()->addSuccessMessage("Cadastro realizado com sucesso! Confira seu email para realizar a ativação!");
                                $this->redirect()->refresh();
                            }
                        }
                    } catch (\Exception $e) {
                        $this->flashMessenger()->addErrorMessage('Essa operação não pode ser realizada nesse momento. Informe a secretaria do seu curso.');
                        //$this->redirect()->refresh();
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('A matrícula/email informados já estão cadastrados em nosso sistema. Favor verificar.');
                    //$this->redirect()->refresh(); 
                }
            } else {
                $this->flashMessenger()->addErrorMessage($alunoForm->getMessages());
                //$this->redirect()->refresh();
            }
        }

        $this->layout()->setTemplate('layout/layout-no-session');
        return new ViewModel(array(
            'form' => $alunoForm
        ));
    }

    public function ativarCadastroAction() {
        $cpf = $this->params()->fromRoute('cpf');
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $usuarioModel->activateUser($cpf);
        $this->layout()->setTemplate('layout/layout-no-session');
        return new ViewModel();
    }

    public function editarUsuarioAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $id = $usuarioSessao->pkUsuario;
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $perfilModel = new PerfilModel($this->getDbAdapter());
        $cursoModel = new CursoModel($this->getDbAdapter());
        $user = $usuarioModel->findById($id); //entidade usuario
        //\Zend\Debug\Debug::dump($user);exit;
        $matricula = $usuarioModel->getSubject($id);
        $perfisCadastroUsuario = $perfilModel->findFkPerfis(); //array perfis
        $cursosUsuario = $cursoModel->findCursos();

        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $updateUser = new Usuario();
            $updateUser->setCpf($data['cpf']);
            $updateUser->setNome($data['nome']);
            $updateUser->setTelefone($data['telefone']);
            $updateUser->setEmail($data['email']);
            $updateUser->setStatus(1);
            $updateUser->setAdm($user->getAdm());
            $updateUser->setFkPerfil($user->getFkPerfil());
            if (!empty($data['password'])) {
                $crypto = new CryptoController();
                $cryptoPwd = $crypto->criarAction($data['password']);
                $updateUser->setPwd($cryptoPwd);
            } else {
                $updateUser->setPwd($user->getPwd());
            }

            if ($user->getFkPerfil() == 1) {
                $usuarioModel->updateEnrollment($id, $data['curso'], $data['matricula']);
            }

            $usuarioModel->updateUser($id, $updateUser);
            $this->flashMessenger()->addSuccessMessage("Usuário atualizado com sucesso!");
            $this->redirect()->refresh();
        }

        return new ViewModel(array(
            'id' => $id,
            'usuario' => $user,
            'matricula' => $matricula['matricula'],
            'idCurso' => $matricula['fk_curso'],
            'perfisUsuario' => $perfisCadastroUsuario,
            'cursosUsuario' => $cursosUsuario
        ));
    }

    public function deniedAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        $this->layout()->setTemplate('layout/layout-no-session');
        return new ViewModel(array(
            'perfil' => $usuarioSessao->perfil
        ));
    }

    public function resetSenhaAction() {
        //preenchendo select de cursos
        $cursoModel = new CursoModel($this->getDbAdapter());
        $listaCursos = $cursoModel->findCursos();
        //formulario novo aluno
        $alunoForm = new \Secretaria\Form\ResetSenhaForm();
        $alunoForm->setInputFilter(new \Secretaria\Form\Filter\ResetSenhaFilter());

        //requisição via post
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $alunoForm->setData($data);

            if ($alunoForm->isValid()) {
                //verifica se já não possui cadastro
                $usuarioModel = new UsuarioModel($this->getDbAdapter());
                $validacoes = $usuarioModel->countEmail($data['email']);
                if ($validacoes > 0) {
                    $crypto = new CryptoController();
                    $cryptoPwd = $crypto->criarAction($data['password']);
                    $usuarioModel->updatePwd($data['email'], $cryptoPwd);

                    $bodyPart = new \Zend\Mime\Message();
                    $bodyMessage = new \Zend\Mime\Part('Olá, recebemos uma solicitação de troca de senha para a conta vinculada a este email ('. $data['email'] . '). A nova senha escolhida é <b>' . $data['password'] . '</b>. Em caso de dúvidas, por favor contate a secretaria do seu curso.');
                    $bodyMessage->type = 'text/html';
                    $bodyPart->setParts(array($bodyMessage));
                    $message = new \Zend\Mail\Message();
                    $message->addTo($data['email'], $data['email'])
                            ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                            ->setSubject('Reset de Senha')
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
                    $this->flashMessenger()->addSuccessMessage("Troca de senha realizada com sucesso.");
                    $this->redirect()->refresh();
                } else {
                    $this->flashMessenger()->addErrorMessage('O email informado não consta em nosso sistema. Favor verificar.');
                }
            } else {
                $this->flashMessenger()->addErrorMessage($alunoForm->getMessages());
                //$this->redirect()->refresh();
            }
        }
        $this->layout()->setTemplate('layout/layout-no-session');
        return new ViewModel(array(
            'form' => $alunoForm
        ));
    }

}
