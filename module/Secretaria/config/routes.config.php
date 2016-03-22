<?php
return array(
    'home' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/home',
            'defaults' => array(
                '__NAMESPACE__' => 'Secretaria\Controller',
                'controller' => 'Home',
                'action' => 'index'
            )
        ),
        'may_terminate' => true,
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