<?php

namespace wm\Controller;

use Application\Controller\JsonRESTEntityUsingController,
    Zend\View\Model\JsonModel;

//use wm\Entity;

class UserController extends JsonRESTEntityUsingController {

    public function create($data) {
        $user = new \wm\Entity\User();
        $user->setCurateTasks($data['CurateTasks']);
        $user->setEmail($data['Email']);
        $user->setExecuteTasks($data['ExecuteTasks']);
        $user->setName($data['Name']);
        $user->setPosition($data['Position']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $id = $user->getId();
        $array = array('id' => $id);
        return new JsonModel($array);
    }

    public function delete($id) {
        $userToDelete = $this->entityManager->getRepository('Entity\User')->findOneById($id);
        $this->entityManager->remove($userToDelete);
        $this->entityManager->flush();
        return;
    }

    public function deleteList() {
        return $this->methodNotAllowed();
    }

    public function get($id) {
        $user = $this->entityManager->getRepository('Entity\User')->findOneById($id);
        $array = array('about' => $user->getAbout(),
            'CurateTasks' => $user->getCurateTasks(),
            'Email' => $user->getEmail(),
            'ExecuteTasks' => $user->getExecuteTasks(),
            'Name' => $user->getName(),
            'Position' => $user->getPosition()
        );
        return new JsonModel($array);
    }

    public function getList() {
        return $this->methodNotAllowed();
    }

    public function head($id = null) {
        $array = array('id' => $id);
        return new JsonModel($array);
    }

    public function options() {
        return $this->methodNotAllowed();
    }

    public function patch($id, $data) {
        $array = array('id' => $id, 'data' => $data);
        return new JsonModel($array);
    }

    public function replaceList($data) {
        $array = array('data' => $data);
        return new JsonModel($array);
    }

    public function patchList($data) {
        $array = array('data' => $data);
        return new JsonModel($array);
    }

    public function update($id, $data) {
        $user = $this->entityManager->getRepository('Entity\User')->findOneById($id);
        $user->setCurateTasks($data['CurateTasks']);
        $user->setEmail($data['Email']);
        $user->setExecuteTasks($data['ExecuteTasks']);
        $user->setName($data['Name']);
        $user->setPosition($data['Position']);
        $this->entityManager->flush();
        return new JsonModel($data);
    }

}
