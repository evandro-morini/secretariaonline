<?php

namespace Secretaria;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\ViewEvent;
use Zend\View\Renderer\PhpRenderer;
use Zend\Mvc\Router\RouteMatch;
use Zend\Authentication\AuthenticationService;

class Module {

    protected $whitelist = array(
        'autenticacao',
        'home/novo-usuario',
        'home/ativar-cadastro'
    );

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        // Instancia autenticação
        $auth = new AuthenticationService();
        // Lista branca
        $list = $this->whitelist;

        $eventManager->attach(MvcEvent::EVENT_ROUTE, function ($e) use($list, $auth) {

            $response = $e->getResponse();
            $router = $e->getRouter();
            $match = $e->getRouteMatch();
            $name = $match->getMatchedRouteName();

            /*
             * Validando autenticação usuário
             */
            // Rota não encontrada, 404
            if (!$match instanceof RouteMatch) {
                return;
            }

            if ($auth->hasIdentity()) {

                // Variável utilizada no layout para exibir informações do usuário
                $viewModel = $e->getViewModel();
                $viewModel->nome_usuario_layout = $auth->getIdentity()->nome;
                $viewModel->isAdmin = $auth->getIdentity()->isAdmin;
                $viewModel->perfil = $auth->getIdentity()->perfil;

                // Usuário autenticado
                if ($name == 'autenticacao') {
                    // Rota da página de autenticação, direciona para home
                    $urlHome = $router->assemble(array(), array(
                        'name' => 'home'
                    ));
                    $response->getHeaders()
                            ->addHeaderLine('Location', $urlHome);
                    $response->setStatusCode(302);
                    return $response;
                }
                return;
            }

            // Rota na whitelist
            if (in_array($name, $list)) {
                return;
            }

            $urlLogin = $router->assemble(array(), array(
                'name' => 'autenticacao'
            ));
            // Se não caiu em nenhuma condição referente a usuário logado, redireciona usuário para página de login
            $response->getHeaders()
                    ->addHeaderLine('Location', $urlLogin);
            $response->setStatusCode(302);
            return $response;
        }, - 100);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
