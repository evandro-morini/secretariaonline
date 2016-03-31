<?php

namespace Secretaria\Form;

use Zend\Form\Form;

class LoginForm extends Form {

    public function __construct($name = null) {
        parent::__construct('login');

        $this->add(array(
            'name' => 'login',
            'type' => 'text',
            'options' => array(
                'label' => 'Login',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'placeholder' => 'Insira seu email',
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
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Entrar',
                'id' => 'submitbutton',
                'class' => 'btn btn-info'
            ),
        ));
    }

}
