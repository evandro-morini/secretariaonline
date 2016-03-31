<?php

namespace Secretaria\Controller;

use Secretaria\Form\AlunoForm;
use Secretaria\Form\Filter\AlunoFilter;
use Secretaria\Model\Entity\Usuario;
use Secretaria\Model\Entity\Curso;
use Secretaria\Model\UsuarioModel;
use Secretaria\Model\CursoModel;
use Secretaria\Controller\CryptoController;
use Zend\View\Model\ViewModel;

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
                //BUSCA DA MATRICULA DO ALUNO NO ARQUIVO CSV
                $grantedFile = $_SERVER['DOCUMENT_ROOT'] . 'secretariaonline/public/csv/granted.csv';
                try{
                    $csvContent = file_get_contents($grantedFile);
                    $csvRows = explode("\n", $csvContent);
                    $valid = false;
                    foreach ($csvRows as $userData) {
                        if(strpos($userData, $data['matricula']) !== false){
                            //aluno validado
                            $valid = true;
                        } 
                    }
                    if($valid === false) {
                        $this->flashMessenger()->addErrorMessage('Sua matrícula não foi encontrada em nosso banco de dados. Informe a secretaria do seu curso.');
                        $this->redirect()->refresh();
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
                        if(!empty($data['dta_nasc']) && strpos($data['dta_nasc'], '_') === false) {
                            $newUser->setDtaNasc($this->formatDateTimeBr($data['dta_nasc']));
                        }
                        $usuarioModel = new UsuarioModel($this->getDbAdapter());
                        $idUsuario = $usuarioModel->insertUser($newUser);
                        $idMatriculado = $usuarioModel->insertEnrollment($idUsuario, $data['curso'], $data['matricula']);
                        
                        if($idMatriculado) {
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
                                "connection_config" => array( "username" => "secretaria.online.ufpr@gmail.com",
                                "password" => "ufpr2016","ssl" => "tls" )
                            ));

                            $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                            $transport->send($message);
                            $this->flashMessenger()->addSuccessMessage("Cadastro realizado com sucesso! Confira seu email para realizar a ativação!");
                            $this->redirect()->refresh();
                        }
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('Essa operação não pode ser realizada nesse momento. Informe a secretaria do seu curso.');
                    $this->redirect()->refresh();
                }
            } else {
                $this->flashMessenger()->addErrorMessage($alunoForm->getMessages());
                $this->redirect()->refresh();
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

}
