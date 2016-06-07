<?php

namespace Secretaria\Form;

use Zend\Form\Form;

class ResetSenhaForm extends Form {

    public function __construct($name = null) {
        parent::__construct('reset-senha');

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
