<?php

namespace wm\Controller;

use Application\Controller\EntityUsingController;
use Zend\Mvc\Controller\AbstractRestfulController,
    Zend\View\Model\JsonModel;

class TaskController extends EntityUsingController {

    public function addAction() {
        
    }

    public function updateAction() {
        
    }

    public function deleteAction() {
        
    }

    public function searchAction() {

        return new JsonModel($array);
    }

    public function probaAction() {
        $array = array('proba' => 'OK');
        return new JsonModel($array);
    }

}
