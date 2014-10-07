<?php

namespace wm\Controller;

use Application\Controller\JsonRESTEntityUsingController,
    Zend\View\Model\JsonModel;
//use wm\Entity;

class TaskController extends JsonRESTEntityUsingController {

    public function create($data) {
        return $this->methodNotAllowed();
    }

    public function delete($id) {
        return $this->methodNotAllowed();
    }

    public function deleteList() {
        return $this->methodNotAllowed();
    }

    public function get($id) {
        $array = array('proba' => 'OK1');
        return new JsonModel($array);
    }

    public function getList() {
        return $this->methodNotAllowed();
    }

    public function head($id = null) {
        return $this->methodNotAllowed();
    }

    public function options() {
        return $this->methodNotAllowed();
    }

    public function patch($id, $data) {
        return $this->methodNotAllowed();
    }

    public function replaceList($data) {
        return $this->methodNotAllowed();
    }

    public function patchList($data) {
        return $this->methodNotAllowed();
    }

    public function update($id, $data) {
        return $this->methodNotAllowed();
    }

}
