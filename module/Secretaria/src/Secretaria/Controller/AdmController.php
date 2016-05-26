<?php

namespace Secretaria\Controller;

use Zend\View\Model\ViewModel;
use Secretaria\Model\CursoModel;
use Secretaria\Model\UsuarioModel;
use Secretaria\Model\ProfessorModel;
use Secretaria\Model\DisciplinaModel;
use Secretaria\Model\Entity\Curso;
use Secretaria\Model\Entity\Usuario;
use Secretaria\Model\Entity\Professor;
use Secretaria\Model\Entity\Disciplina;
use Zend\Authentication\AuthenticationService;

class AdmController extends AbstractController {

    public function indexAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        //controle de acesso
        if($usuarioSessao->isAdmin == 1) {
            $usuarioModel = new UsuarioModel($this->getDbAdapter());
            $cursoModel = new CursoModel($this->getDbAdapter());
            $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
            $professorModel = new ProfessorModel($this->getDbAdapter());
            $usuarios = $usuarioModel->findUsuarios();
            $cursos = $cursoModel->findCursos();
            $disciplinas = $disciplinaModel->findFkDisciplinas();
            $professores = $professorModel->findProfessores();
            //\Zend\Debug\Debug::dump($disciplinas);exit;
            return new ViewModel(array(
                'usuarios'      => $usuarios,
                'cursos'        => $cursos,
                'disciplinas'   => $disciplinas,
                'professores'   => $professores
            ));
        } else {
            return $this->redirect()->toRoute('home/denied');
        }
    }

}
