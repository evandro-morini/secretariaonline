<?php
return array(
    'home' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/index',
            'defaults' => array(
                '__NAMESPACE__' => 'Secretaria\Controller',
                'controller' => 'Home',
                'action' => 'index'
            )
        ),
        'may_terminate' => true,
    ),  
);