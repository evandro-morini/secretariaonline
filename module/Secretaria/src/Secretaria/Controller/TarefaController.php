<?php

namespace Secretaria\Controller;

use Secretaria\Model\CursoModel;
use Secretaria\Model\SolicitacaoModel;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;

class TarefaController extends AbstractController {

    public function indexAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        if ($usuarioSessao->perfil != 1) {
            $cursoModel = new CursoModel($this->getDbAdapter());
            $idCurso = $cursoModel->findPerfilCurso($usuarioSessao->perfil);
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $abertas = $solicitacaoModel->findAllTasks($idCurso, 1);
            $atribuidas = $solicitacaoModel->findAllTasks($idCurso, 2);
            $atribuidasEnc = $solicitacaoModel->findAllTasks($idCurso, 3);
            $encerradas = $solicitacaoModel->findAllTasks($idCurso, 4);
            $canceladas = $solicitacaoModel->findAllTasks($idCurso, 5);

            //validando as tarefas atribuidas
            $atribuidasFinal = array();
            $encaminhadasFinal = array();
            foreach ($atribuidas as $atribuida) {
                $atribuidaUsuario = $solicitacaoModel->findUltimoEncaminhamento($atribuida['id']);
                if ($atribuidaUsuario['servidor'] == $usuarioSessao->pkUsuario) {
                    $atribuidasFinal[] = $atribuidaUsuario;
                }
            }
            
            foreach ($atribuidasEnc as $atribuidaEnc) {
                $atribuidaEncUsuario = $solicitacaoModel->findLastTaskForward($atribuidaEnc['id'], null);
                if ($atribuidaEncUsuario['novo_servidor'] == $usuarioSessao->pkUsuario) {
                    $atribuidasFinal[] = $atribuidaEncUsuario;
                }
                $encaminhadaUsuario = $solicitacaoModel->findLastTaskForward($atribuidaEnc['id'], $usuarioSessao->pkUsuario);
                if ($encaminhadaUsuario['servidor'] == $usuarioSessao->pkUsuario && $encaminhadaUsuario['servidor'] != $atribuidaEncUsuario['novo_servidor']) {
                    $encaminhadasFinal[] = $encaminhadaUsuario;
                }
            }

            return new ViewModel(array(
                'abertas' => $abertas,
                'atribuidas' => $atribuidasFinal,
                'encaminhadas' => $encaminhadasFinal,
                'encerradas' => $encerradas,
                'canceladas' => $canceladas
            ));
        } else {
            return $this->redirect()->toRoute('home/denied');
        }
    }

}
