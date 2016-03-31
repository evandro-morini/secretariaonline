<?php
return array(
    'home' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/',
            'defaults' => array(
                '__NAMESPACE__' => 'Secretaria\Controller',
                'controller' => 'Home',
                'action' => 'index'
            )
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'novo-usuario' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => 'novo-usuario',
                    'defaults' => array(
                        'action' => 'novoUsuario'
                    )
                )
            ),
            'ativar-cadastro' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => 'ativar-cadastro[/:cpf]',
                    'defaults' => array(
                        'action' => 'ativarCadastro',
                        'cpf' => 0
                    )
                )
            ),
        )
    ),
    'autenticacao' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/autenticacao',
            'defaults' => array(
                '__NAMESPACE__' => 'Secretaria\Controller',
                'controller' => 'Autenticacao',
                'action' => 'index'
            )
        ),
        'may_terminate' => true
    ),
    'logout' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/logout',
            'defaults' => array(
                '__NAMESPACE__' => 'Secretaria\Controller',
                'controller' => 'Autenticacao',
                'action' => 'logout'
            )
        ),
        'may_terminate' => true
    )
);