<?php

namespace Secretaria\Controller;

use Secretaria\Form\LoginForm;
use Secretaria\Form\Filter\LoginFilter;
use Secretaria\Model\Entity\Usuario;
use Secretaria\Model\UsuarioModel;
use Secretaria\Controller\CryptoController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable;

class AutenticacaoController extends AbstractController {

    public function indexAction() {
        $request = $this->getRequest();
        $loginForm = new LoginForm();
        $loginForm->setInputFilter(new LoginFilter());

        if ($request->isPost()) {
            $data = $request->getPost();
            $loginForm->setData($data);

            if ($loginForm->isValid()) {
                $auth = new AuthenticationService();
                $data = $loginForm->getData();
                $crypto = new CryptoController();
                $cryptoPwd = $crypto->criarAction($data['password']);
                
                $authAdapter = new DbTable($this->getDbAdapter(), 'tb_usuario', 'email', 'pwd');
                $authAdapter->setIdentity($data['login']);
                $authAdapter->setCredential($cryptoPwd);
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $modelUsuario = new UsuarioModel($this->getDbAdapter());
                    $usuario = $modelUsuario->findByEmail($data['login']);
                    
                    try {
                        if ($usuario instanceof Usuario) {
                            $objUsuario = new \stdClass();
                            $objUsuario->pkUsuario = $usuario->getId();
                            $objUsuario->nome = $usuario->getNome();
                            $objUsuario->perfil = $usuario->getFkPerfil();
                            $objUsuario->isAdmin = $usuario->getAdm();
                            
                            // Grava sessão do usuário
                            $auth->getStorage()->write($objUsuario);
                            if((int)$usuario->getFkPerfil() == 1) {
                                return $this->redirect()->toRoute('solicitacao/minhas-solicitacoes');
                            } else {
                                return $this->redirect()->toRoute('tarefas');
                            }
                        } else {
                            $auth->clearIdentity();
                            $this->flashMessenger()->addErrorMessage('Login inválido ou sem permissão para o acesso. Favor, verificar!');
                            $this->redirect()->refresh();
                        }
                    } catch (\Exception $e) {
                        $auth->clearIdentity();
                        $this->flashMessenger()->addErrorMessage('Erro inesperado ao gravar sessão.');
                        $this->redirect()->refresh();
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('Erro ao autenticar. Verifique login e senha.');
                    $this->redirect()->refresh();
                }                 
            } else {
                $this->flashMessenger()->addErrorMessage($loginForm->getMessages());
                $this->redirect()->refresh();
            }
        }

        $this->layout()->setTemplate('layout/layout-no-session');
        return new ViewModel(array(
            'form' => $loginForm
        ));
    }

    public function logoutAction() {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $auth->clearIdentity();
            $container = new Container();
            $container->getManager()->destroy();
        }
        return $this->redirect()->toRoute('autenticacao');
    }

}
