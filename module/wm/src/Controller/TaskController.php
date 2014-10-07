<?php

namespace wm\Controller;

use Application\Controller\EntityUsingController;
use Zend\Mvc\Controller\AbstractRestfulController,
    Zend\View\Model\JsonModel;
//use wm\Entity;

class TaskController extends AbstractRestfulJsonController {

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
