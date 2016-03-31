<?php

namespace Secretaria\Form\Filter;

use Zend\InputFilter\InputFilter;

class AlunoFilter extends InputFilter {
    
    public function __construct() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;
        $stringTooShort = \Zend\Validator\StringLength::TOO_SHORT;
        $stringTooLong = \Zend\Validator\StringLength::TOO_LONG;
        $token = \Zend\Validator\Identical::NOT_SAME;

        $this->add(array(
            'name' => 'cpf',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'O campo CPF não pode ser vazio.'
                        )
                    ),
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'stringLength',
                    'options' => array(
                        'max' => 11,
                        'min' => 11,
                        'messages' => array(
                                $stringTooShort => 'O CPF deve ter 11 digitos', 
                                $stringTooLong => 'O CPF deve ter 11 digitos' 
                        ),
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'matricula',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'O campo matrícula não pode ser vazio.'
                        )
                    ),
                    'break_chain_on_failure' => true
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'nome',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'O campo nome não pode ser vazio.'
                        )
                    ),
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'stringLength',
                    'options' => array(
                        'max' => 255
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'O campo email não pode ser vazio.'
                        )
                    ),
                    'break_chain_on_failure' => true
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'messages' => array(
                            $invalidEmail => 'Insira um endereço válido de email.'
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'O campo de senha não pode estar vazio.'
                        ),
                    ),
                ),
                array(
                    'name' => 'stringLength',
                    'options' => array(
                        'min' => 8,
                        'max' => 45,
                        'messages' => array(
                                $stringTooShort => 'A senha deve ter ao menos 8 digitos.', 
                                $stringTooLong => 'A senha não teve ultrapassar 45 digitos' 
                        ),
                    ),
                ),
            ),
        ));
        
        $this->add(array(
            'name' => 'password_repeat',
            'required' => true,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            $isEmpty => 'O campo de confirmação da senha não pode estar vazio.'
                        ),
                    ),
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password',
                        'messages' => array(
                                $token => 'A senha confirmada não confere. Por favor verifique.'
                        ),
                    )
                ),
            ),
        ));
    }
    
}