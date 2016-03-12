<?php
namespace Secretaria\Controller;
use Zend\View\Model\ViewModel;

class HomeController extends AbstractController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}

