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
            'editar-usuario' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => 'editar-usuario',
                    'defaults' => array(
                        'action' => 'editarUsuario'
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
    ),
    'solicitacao' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/solicitacao',
            'defaults' => array(
                '__NAMESPACE__' => 'Secretaria\Controller',
                'controller' => 'Solicitacao',
                'action' => 'index'
            )
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'correcao-matricula' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/correcao-matricula',
                    'defaults' => array(
                        'action' => 'correcaoMatricula'
                    )
                )
            ),
            'cancelamento-matricula' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/cancelamento-matricula',
                    'defaults' => array(
                        'action' => 'cancelamentoMatricula'
                    )
                )
            ),
            'aproveitamento-conhecimento' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/aproveitamento-conhecimento',
                    'defaults' => array(
                        'action' => 'aproveitamentoConhecimento'
                    )
                )
            ),
            'outras-solicitacoes' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/outras-solicitacoes',
                    'defaults' => array(
                        'action' => 'outrasSolicitacoes'
                    )
                )
            ),
            'adiantamento-disciplina' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/adiantamento-disciplina',
                    'defaults' => array(
                        'action' => 'adiantamentoDisciplina'
                    )
                )
            ),
            'inserir-disciplina' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/inserir-disciplina',
                    'defaults' => array(
                        'action' => 'inserirDisciplina'
                    )
                )
            ),
            'minhas-solicitacoes' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/minhas-solicitacoes',
                    'defaults' => array(
                        'action' => 'minhasSolicitacoes'
                    )
                )
            ),
            'pesquisar-protocolo' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/pesquisar-protocolo',
                    'defaults' => array(
                        'action' => 'pesquisarProtocolo'
                    )
                )
            ),
            'visualizar' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/visualizar[/:protocolo]',
                    'defaults' => array(
                        'action' => 'visualizar',
                        'protocolo' => '0'
                    )
                )
            ),
            'atribuir' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/atribuir',
                    'defaults' => array(
                        'action' => 'atribuir'
                    )
                )
            ),
            'cancelar' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/cancelar',
                    'defaults' => array(
                        'action' => 'cancelar'
                    )
                )
            ),
            'encaminhar' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/encaminhar',
                    'defaults' => array(
                        'action' => 'encaminhar'
                    )
                )
            ),
            'encerrar' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/encerrar',
                    'defaults' => array(
                        'action' => 'encerrar'
                    )
                )
            ),
        )
    ),
    'tarefas' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/tarefas',
            'defaults' => array(
                '__NAMESPACE__' => 'Secretaria\Controller',
                'controller' => 'Tarefa',
                'action' => 'index'
            )
        ),
        'may_terminate' => true
    ),
    'adm' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/adm',
            'defaults' => array(
                '__NAMESPACE__' => 'Secretaria\Controller',
                'controller' => 'Adm',
                'action' => 'index'
            )
        ),
        'may_terminate' => true
    ),
);