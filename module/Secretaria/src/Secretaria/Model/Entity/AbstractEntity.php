<?php
namespace Secretaria\Model\Entity;

abstract class AbstractEntity
{
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
        if(method_exists($this, 'setExtras')) {
            $this->setExtras();
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Invalid Method ' . $method);
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (! method_exists($this, $method)) {
            throw new \Exception('Invalid Method: ' . $method);
        }
        return $this->$method();
    }

    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));

            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    /*
     * Necessária a implementação do método abaixo para utilização no paginator da model.
     * O método recebe um array com o resultset e hidrata a entidade
     */
    public function exchangeArray($dados)
    {
        $this->setOptions($dados);
        return $this;
    }

    /*
     * Necessária para gravar registro no banco. Transforma a entidade em array.
     */
    public function toArray()
    {
        $arrayReturn = array();
        foreach ($this as $key => $value) {
            if($value || $value === 0){
                $newKey = strtolower(preg_replace('/(?<!^)([A-Z])/', '_\\1', $key));
                $arrayReturn[$newKey] = $value;
            }
        }
        return $arrayReturn;
    }
}

