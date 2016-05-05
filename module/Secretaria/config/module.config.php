<?php
namespace Secretaria;

return array(
    'router' => array(
        'routes' => require __DIR__ . '/routes.config.php'
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Secretaria\Controller\Home' => Controller\HomeController::class,
            'Secretaria\Controller\Autenticacao' => Controller\AutenticacaoController::class,
            'Secretaria\Controller\Solicitacao' => Controller\SolicitacaoController::class,
            'Secretaria\Controller\Crypto' => Controller\CryptoController::class
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'secretaria/index/index' => __DIR__ . '/../view/secretaria/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        )
    )
    /*,
    'view_helpers' => array(
        'invokables' => array(
            'getTabs' => 'Crm\View\Helper\GetTabs',
            'getSideMenu' => 'Crm\View\Helper\GetSideMenu',
            'getNotificacao' =>'Crm\View\Helper\GetNotificacao'
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array()
        )
    )*/
);

