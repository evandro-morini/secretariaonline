<?php

namespace Secretaria\Form\Filter;

use Zend\InputFilter\InputFilter;

class ResetSenhaFilter extends InputFilter {
    
    public function __construct() {

        $isEmpty = \Zend\Validator\NotEmpty::IS_EMPTY;
        $invalidEmail = \Zend\Validator\EmailAddress::INVALID_FORMAT;
        $stringTooShort = \Zend\Validator\StringLength::TOO_SHORT;
        $stringTooLong = \Zend\Validator\StringLength::TOO_LONG;
        $token = \Zend\Validator\Identical::NOT_SAME;

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