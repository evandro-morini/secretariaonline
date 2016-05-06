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
        if($usuarioSessao->perfil != 1) {
            $cursoModel = new CursoModel($this->getDbAdapter());
            $idCurso = $cursoModel->findPerfilCurso($usuarioSessao->perfil);
            $solicitacaoModel = new SolicitacaoModel($this->getDbAdapter());
            $abertas = $solicitacaoModel->findAllTasks($idCurso, 1);
            $encaminhadas = $solicitacaoModel->findAllTasks($idCurso, 3);
            $encerradas = $solicitacaoModel->findAllTasks($idCurso, 4);
            $canceladas = $solicitacaoModel->findAllTasks($idCurso, 5);

            return new ViewModel(array(
                'abertas' => $abertas,
                'encaminhadas' => $encaminhadas,
                'encerradas' => $encerradas,
                'canceladas' => $canceladas
            ));
        } else {
            return $this->redirect()->toRoute('logout');
        }
    }
    
}