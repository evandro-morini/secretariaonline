<?php

namespace Secretaria\Form;

use Zend\Form\Form;

class AlunoForm extends Form {
    
    protected $cursos;

    public function __construct($cursos = null) {
        
        $this->cursos = $cursos;
        
        $optionCursos = array();
        foreach ($this->cursos as $curso) {
            $optionCursos[$curso->id] = $curso->descricao;
        }
        
        parent::__construct('aluno');

        $this->add(array(
            'name' => 'cpf',
            'type' => 'text',
            'options' => array(
                'label' => 'CPF',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'cpfinput',
                'placeholder' => 'Insira seu CPF',
                'required' => true,
                'maxlength' => 11
            ),
        ));
        $this->add(array(
            'name' => 'matricula',
            'type' => 'text',
            'options' => array(
                'label' => 'Matrícula',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'GRRXXXXXXXX',
                'required' => true,
                'maxlength' => 11
            ),
        ));
        
        $this->add(array(
            'name' => 'curso',
            'type' => 'Select',
            'options' => array(
                'label' => 'Curso',
                'empty_option' => 'Selecione seu curso',
                'value_options' => $optionCursos
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'curso',
                'required' => true,
            )
        ));
        
        $this->add(array(
            'name' => 'nome',
            'type' => 'text',
            'options' => array(
                'label' => 'Nome',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Nome Completo',
                'required' => true,
                'maxlength' => 255
            ),
        ));
        $this->add(array(
            'name' => 'dta_nasc',
            'type' => 'text',
            'options' => array(
                'label' => 'Data Nasc.',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'dateinput',
                'placeholder' => 'dd/mm/aaaa'
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'text',
            'options' => array(
                'label' => 'Email',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'usuario@dominio.com',
                'required' => true
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Senha',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => '********',
                'required' => true
            ),
        ));
        $this->add(array(
            'name' => 'password_repeat',
            'type' => 'password',
            'options' => array(
                'label' => 'Confirme sua senha',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => '********',
                'required' => true
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Enviar',
                'id' => 'submitbutton',
                'class' => 'btn btn-info'
            ),
        ));
        
    }

}
