<?php

namespace Secretaria\Controller;

use Zend\View\Model\ViewModel;

class AdmController extends AbstractController {

    public function indexAction() {
        return new ViewModel();
    }

}
