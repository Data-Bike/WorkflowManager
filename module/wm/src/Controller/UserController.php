<?php

namespace wm\Controller;

use Application\Controller\JsonRESTEntityUsingController,
    Zend\View\Model\JsonModel;

//use wm\Entity;

class UserController extends JsonRESTEntityUsingController {

    public function create($data) {
        $user = new \wm\Entity\User();
        $user->setEmail($data['Email']);
        $user->setName($data['Name']);
        $user->setPosition($data['Position']);
        $user->setPassword($data['Password']);
        $user->setState($data['State']);

        $roles = explode(",", $data['roles']);
        foreach ($roles as $roleId) {
            $role = $this->getEntityManager()->getRepository('wm\Entity\Role')->findOneById($roleId);
            $user->getRoles()->add($role);
            $role->getUsers()->add($user);
            $this->getEntityManager()->persist($role);
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $id = $user->getId();
        $array = array('id' => $id);
        return new JsonModel($array);
    }

    public function delete($id) {
        $userToDelete = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($id);
        $this->getEntityManager()->remove($userToDelete);
        $this->getEntityManager()->flush();
        return;
    }

    public function deleteList() {
        return $this->methodNotAllowed();
    }

    public function get($id) {
        $user = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($id);
        $array = array('about' => $user->getAbout(),
            'CurateTasks' => $user->getCurateTasks(),
            'Email' => $user->getEmail(),
            'ExecuteTasks' => $user->getExecuteTasks(),
            'Name' => $user->getName(),
            'Position' => $user->getPosition(),
            'State' => $user->getState()
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
        $user = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($id);
        $user->setEmail($data['Email']);
        $user->setName($data['Name']);
        $user->setPosition($data['Position']);
        $user->setPassword($data['Password']);
        $user->setState($data['State']);

        $roles = explode(",", $data['roles']);
        foreach ($roles as $roleId) {
            $role = $this->getEntityManager()->getRepository('wm\Entity\Role')->findOneById($roleId);
            $user->getRoles()->add($role);
            $role->getUsers()->add($user);
            $this->getEntityManager()->persist($role);
        }

        $this->getEntityManager()->flush();
        return new JsonModel($data);
    }

}
