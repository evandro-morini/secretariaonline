<?php

namespace Secretaria\Controller;

use Zend\View\Model\ViewModel;
use Secretaria\Model\CursoModel;
use Secretaria\Model\PerfilModel;
use Secretaria\Model\UsuarioModel;
use Secretaria\Model\ProfessorModel;
use Secretaria\Model\DisciplinaModel;
use Secretaria\Model\Entity\Curso;
use Secretaria\Model\Entity\Perfil;
use Secretaria\Model\Entity\Usuario;
use Secretaria\Model\Entity\Professor;
use Secretaria\Model\Entity\Disciplina;
use Secretaria\Controller\CryptoController;
use Zend\Authentication\AuthenticationService;

class AdmController extends AbstractController {

    public function indexAction() {
        $auth = new AuthenticationService();
        $usuarioSessao = $auth->getIdentity();
        //controle de acesso
        if ($usuarioSessao->isAdmin == 1) {
            $usuarioModel = new UsuarioModel($this->getDbAdapter());
            $perfilModel = new PerfilModel($this->getDbAdapter());
            $cursoModel = new CursoModel($this->getDbAdapter());
            $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
            $professorModel = new ProfessorModel($this->getDbAdapter());
            $usuarios = $usuarioModel->listUsersAdm();
            $perfis = $perfilModel->findAllFkPerfis();
            $perfisCadastroUsuario = $perfilModel->findFkPerfis();
            $cursos = $cursoModel->findAllCursos();
            $cursosCadastroPerfil = $cursoModel->findCursos();
            $cursosDisciplina = $cursoModel->findCursos();
            $disciplinas = $disciplinaModel->findAllFkDisciplinas();
            $professores = $professorModel->findAllProfessores();
            $professoresDisciplina = $professorModel->findProfessores();
            //\Zend\Debug\Debug::dump($perfis);exit;
            return new ViewModel(array(
                'usuarios' => $usuarios,
                'perfis' => $perfis,
                'perfisUsuario' => $perfisCadastroUsuario,
                'cursos' => $cursos,
                'cursosPerfil' => $cursosCadastroPerfil,
                'cursosDisciplina' => $cursosDisciplina,
                'disciplinas' => $disciplinas,
                'professores' => $professores,
                'professoresDisciplina' => $professoresDisciplina
            ));
        } else {
            return $this->redirect()->toRoute('home/denied');
        }
    }

    public function novoServidorAction() {
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $usuarioModel = new UsuarioModel($this->getDbAdapter());
            $newUser = new Usuario();
            $newUser->setCpf($data['cpf']);
            $newUser->setNome($data['nome']);
            $newUser->setFkPerfil($data['perfil']);
            $newUser->setTelefone($data['telefone']);
            $newUser->setEmail($data['email']);
            $newUser->setStatus(1);
            if (!is_null($data['adm'])) {
                $newUser->setAdm(1);
            } else {
                $newUser->setAdm(0);
            }

            $crypto = new CryptoController();
            $cryptoPwd = $crypto->criarAction($data['password']);
            $newUser->setPwd($cryptoPwd);

            $idServidor = $usuarioModel->insertServidor($newUser);

            if ($idServidor) {
                $bodyPart = new \Zend\Mime\Message();
                $bodyMessage = new \Zend\Mime\Part('Olá ' . $newUser->getNome() . ' . Você foi cadastrado pelo administrador do nosso sistema. A senha para seu primeiro login é ' . $data['password'] . '. Seja bem vindo!');
                $bodyMessage->type = 'text/html';
                $bodyPart->setParts(array($bodyMessage));
                $message = new \Zend\Mail\Message();
                $message->addTo($newUser->getEmail(), $newUser->getNome())
                        ->addFrom('secretaria.online.ufpr@gmail.com', 'Secretaria Online')
                        ->setSubject('Ativação de cadastro - Servidor')
                        ->setBody($bodyPart)
                        ->setEncoding('UTF-8');

                $smtpOptions = new \Zend\Mail\Transport\SmtpOptions(array(
                    "name" => "gmail",
                    "host" => "smtp.gmail.com",
                    "port" => 587,
                    "connection_class" => "plain",
                    "connection_config" => array("username" => "secretaria.online.ufpr@gmail.com",
                        "password" => "ufpr2016", "ssl" => "tls")
                ));

                $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
                $transport->send($message);
            }

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $this->redirect()->toRoute('adm');
        }
    }

    public function novoPerfilAction() {
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $perfilModel = new PerfilModel($this->getDbAdapter());
            $newPerfil = new Perfil();
            $newPerfil->setDescricao($data['descricao']);
            $newPerfil->setFkCurso($data['curso']);
            $newPerfil->setStatus(1);

            $idPerfil = $perfilModel->insertPerfil($newPerfil);

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $this->redirect()->toRoute('adm');
        }
    }

    public function novoCursoAction() {
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $cursoModel = new CursoModel($this->getDbAdapter());
            $newCurso = new Curso();
            $newCurso->setCod($data['cod']);
            $newCurso->setDescricao($data['descricao']);
            $newCurso->setStatus(1);

            $idCurso = $cursoModel->insertCurso($newCurso);

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $this->redirect()->toRoute('adm');
        }
    }

    public function novaDisciplinaAction() {
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
            $newDisciplina = new Disciplina();
            $newDisciplina->setFkCurso($data['curso']);
            $newDisciplina->setFkProfessor($data['professor']);
            $newDisciplina->setCod($data['cod']);
            $newDisciplina->setDescricao($data['descricao']);
            $newDisciplina->setStatus(1);

            $idDisciplina = $disciplinaModel->insertDisciplina($newDisciplina);

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $this->redirect()->toRoute('adm');
        }
    }

    public function novoProfessorAction() {
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $profModel = new ProfessorModel($this->getDbAdapter());
            $newProf = new Professor();
            $newProf->setNome($data['nome']);
            $newProf->setEmail($data['email']);
            $newProf->setStatus(1);

            $idPerfil = $profModel->insertProfessor($newProf);

            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            return $this->redirect()->toRoute('adm');
        }
    }

    public function editarUsuarioAction() {
        $id = $this->params()->fromRoute('id');
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $perfilModel = new PerfilModel($this->getDbAdapter());
        $cursoModel = new CursoModel($this->getDbAdapter());
        $user = $usuarioModel->findById($id); //entidade usuario
        $matricula = $usuarioModel->getSubject($id);
        $perfisCadastroUsuario = $perfilModel->findFkPerfis(); //array perfis
        $cursosUsuario = $cursoModel->findCursos();

        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $updateUser = new Usuario();
            $updateUser->setCpf($data['cpf']);
            $updateUser->setNome($data['nome']);
            $updateUser->setTelefone($data['telefone']);
            $updateUser->setEmail($data['email']);
            $updateUser->setStatus(1);
            
            if (!empty($data['password'])) {
                $crypto = new CryptoController();
                $cryptoPwd = $crypto->criarAction($data['password']);
                $updateUser->setPwd($cryptoPwd);
            } else {
                $updateUser->setPwd($user->getPwd());
            }

            if ($user->getFkPerfil() == 1) {
                $updateUser->setFkPerfil($user->getFkPerfil());
                $usuarioModel->updateEnrollment($id, $data['curso'], $data['matricula']);
            } else {
                $updateUser->setFkPerfil($data['perfil']);
                if (!is_null($data['adm'])) {
                    $updateUser->setAdm(1);
                } else {
                    $updateUser->setAdm(0);
                }
                
            }
            
            $usuarioModel->updateUser($id, $updateUser);
            $this->flashMessenger()->addSuccessMessage("Usuário atualizado com sucesso!");
            $this->redirect()->refresh();
        }

        return new ViewModel(array(
            'id' => $id,
            'usuario' => $user,
            'matricula' => $matricula['matricula'],
            'idCurso' => $matricula['fk_curso'],
            'perfisUsuario' => $perfisCadastroUsuario,
            'cursosUsuario' => $cursosUsuario
        ));
    }

    public function editarPerfilAction() {
        $id = $this->params()->fromRoute('id');
        $perfilModel = new PerfilModel($this->getDbAdapter());
        $cursoModel = new CursoModel($this->getDbAdapter());
        $perfil = $perfilModel->findAllById($id);
        $cursos = $cursoModel->findCursos();
        
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $newPerfil = new Perfil();
            $newPerfil->setDescricao($data['descricao']);
            $newPerfil->setStatus(1);
            if(!is_null($data['curso'])) {
                $newPerfil->setFkCurso($data['curso']);
            } else {
                $newPerfil->setFkCurso($perfil->getFkCurso());
            }
            $perfilModel->updatePerfil($id, $newPerfil);
            $this->flashMessenger()->addSuccessMessage("Perfil atualizado com sucesso!");
            $this->redirect()->refresh();
        }
        return new ViewModel(array(
            'id' => $id,
            'perfil' => $perfil,
            'cursos' => $cursos,
        ));
    }

    public function editarCursoAction() {
        $id = $this->params()->fromRoute('id');
        $cursoModel = new CursoModel($this->getDbAdapter());
        $curso = $cursoModel->findAllById($id);
        
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $updateCurso = new Curso();
            $updateCurso->setCod($data['cod']);
            $updateCurso->setDescricao($data['descricao']);
            $updateCurso->setStatus(1);
            $cursoModel->updateCurso($id, $updateCurso);
            $this->flashMessenger()->addSuccessMessage("Curso atualizado com sucesso!");
            $this->redirect()->refresh();
        }
        
        return new ViewModel(array(
            'id' => $id,
            'curso' => $curso
        ));
    }

    public function editarDisciplinaAction() {
        $id = $this->params()->fromRoute('id');
        $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
        $professorModel = new ProfessorModel($this->getDbAdapter());
        $cursoModel = new CursoModel($this->getDbAdapter());
        $disciplina = $disciplinaModel->findAllById($id);
        $cursos = $cursoModel->findCursos();
        $professores = $professorModel->findProfessores();
        
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $updateDisciplina = new Disciplina();
            $updateDisciplina->setCod($data['cod']);
            $updateDisciplina->setDescricao($data['descricao']);
            $updateDisciplina->setFkCurso($data['curso']);
            $updateDisciplina->setFkProfessor($data['professor']);
            $updateDisciplina->setStatus(1);
            $disciplinaModel->updateDisciplina($id, $updateDisciplina);
            $this->flashMessenger()->addSuccessMessage("Disciplina atualizada com sucesso!");
            $this->redirect()->refresh();
            
        }
        
        return new ViewModel(array(
            'id' => $id,
            'disciplina' => $disciplina,
            'cursos' => $cursos,
            'professores' => $professores
        ));
        
    }

    public function editarProfessorAction() {
        $id = $this->params()->fromRoute('id');
        $profModel = new ProfessorModel($this->getDbAdapter());
        $professor = $profModel->findAllById($id);
        
        //Parametros vindos da requisicao
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $updateProf = new Professor();
            $updateProf->setNome($data['nome']);
            $updateProf->setEmail($data['email']);
            $updateProf->setStatus(1);
            $profModel->updateProfessor($id, $updateProf);
            $this->flashMessenger()->addSuccessMessage("Professor atualizado com sucesso!");
            $this->redirect()->refresh();
        }
        
        return new ViewModel(array(
            'id' => $id,
            'professor' => $professor
        ));
    }
    
    public function alterarStatusUsuarioAction() {
        $id = $this->params()->fromRoute('id');
        $usuarioModel = new UsuarioModel($this->getDbAdapter());
        $user = $usuarioModel->findAllById($id);
        if($user->getStatus() == 1) {
            $usuarioModel->updateStatus($id, 0);
        } else {
            $usuarioModel->updateStatus($id, 1);
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $this->redirect()->toRoute('adm');
    }
    
    public function alterarStatusPerfilAction() {
        $id = $this->params()->fromRoute('id');
        $perfilModel = new PerfilModel($this->getDbAdapter());
        $perfil = $perfilModel->findAllById($id);
        if($perfil->getStatus() == 1) {
            $perfilModel->updateStatus($id, 0);
        } else {
            $perfilModel->updateStatus($id, 1);
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $this->redirect()->toRoute('adm');
    }
    
    public function alterarStatusCursoAction() {
        $id = $this->params()->fromRoute('id');
        $cursoModel = new CursoModel($this->getDbAdapter());
        $curso = $cursoModel->findAllById($id);
        if($curso->getStatus() == 1) {
            $cursoModel->updateStatus($id, 0);
        } else {
            $cursoModel->updateStatus($id, 1);
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $this->redirect()->toRoute('adm');
    }
    
    public function alterarStatusDisciplinaAction() {
        $id = $this->params()->fromRoute('id');
        $disciplinaModel = new DisciplinaModel($this->getDbAdapter());
        $disciplina = $disciplinaModel->findAllById($id);
        
        if($disciplina->getStatus() == 1) {
            $disciplinaModel->updateStatus($id, 0);
        } else {
            $disciplinaModel->updateStatus($id, 1);
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $this->redirect()->toRoute('adm');
    }
    
    public function alterarStatusProfessorAction() {
        $id = $this->params()->fromRoute('id');
        $profModel = new ProfessorModel($this->getDbAdapter());
        $professor = $profModel->findAllById($id);
        if($professor->getStatus() == 1) {
            $profModel->updateStatus($id, 0);
        } else {
            $profModel->updateStatus($id, 1);
        }
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $this->redirect()->toRoute('adm');
    }

}
