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
            'denied' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => 'denied',
                    'defaults' => array(
                        'action' => 'denied'
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
        'may_terminate' => true,
        'child_routes' => array(
            'novo-servidor' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/novo-servidor',
                    'defaults' => array(
                        'action' => 'novoServidor'
                    )
                )
            ),
            'novo-perfil' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/novo-perfil',
                    'defaults' => array(
                        'action' => 'novoPerfil'
                    )
                )
            ),
            'novo-curso' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/novo-curso',
                    'defaults' => array(
                        'action' => 'novoCurso'
                    )
                )
            ),
            'nova-disciplina' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/nova-disciplina',
                    'defaults' => array(
                        'action' => 'novaDisciplina'
                    )
                )
            ),
            'novo-professor' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/novo-professor',
                    'defaults' => array(
                        'action' => 'novoProfessor'
                    )
                )
            ),
            'editar-usuario' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/editar-usuario[/:id]',
                    'defaults' => array(
                        'action' => 'editarUsuario',
                        'id' => '0'
                    )
                )
            ),
            'editar-perfil' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/editar-perfil[/:id]',
                    'defaults' => array(
                        'action' => 'editarPerfil',
                        'id' => '0'
                    )
                )
            ),
            'editar-curso' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/editar-curso[/:id]',
                    'defaults' => array(
                        'action' => 'editarCurso',
                        'id' => '0'
                    )
                )
            ),
            'editar-disciplina' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/editar-disciplina[/:id]',
                    'defaults' => array(
                        'action' => 'editarDisciplina',
                        'id' => '0'
                    )
                )
            ),
            'editar-professor' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/editar-professor[/:id]',
                    'defaults' => array(
                        'action' => 'editarProfessor',
                        'id' => '0'
                    )
                )
            ),
            'alterar-status-usuario' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inativar-usuario[/:id]',
                    'defaults' => array(
                        'action' => 'alterarStatusUsuario',
                        'id' => '0'
                    )
                )
            ),
            'alterar-status-perfil' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inativar-perfil[/:id]',
                    'defaults' => array(
                        'action' => 'alterarStatusPerfil',
                        'id' => '0'
                    )
                )
            ),
            'alterar-status-curso' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inativar-curso[/:id]',
                    'defaults' => array(
                        'action' => 'alterarStatusCurso',
                        'id' => '0'
                    )
                )
            ),
            'alterar-status-disciplina' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inativar-disciplina[/:id]',
                    'defaults' => array(
                        'action' => 'alterarStatusDisciplina',
                        'id' => '0'
                    )
                )
            ),
            'alterar-status-professor' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/inativar-professor[/:id]',
                    'defaults' => array(
                        'action' => 'alterarStatusProfessor',
                        'id' => '0'
                    )
                )
            ),
        )
    ),
);
