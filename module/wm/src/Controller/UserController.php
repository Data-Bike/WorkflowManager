<?php

namespace wm\Controller;

use Application\Controller\JsonRESTEntityUsingController,
    Zend\View\Model\JsonModel;

//use wm\Entity;

class UserController extends JsonRESTEntityUsingController {

    public function create($data) {
        $user = new \wm\Entity\User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setPosition($data['position']);
        $user->setPassword(md5($data['password']));
        $user->setState($data['state']);
        $user->setUsername($data['username']);

        $roles = explode(",", $data['roles']);
        foreach ($roles as $roleId) {
            $role = $this->getEntityManager()->getRepository('wm\Entity\Role')->findOneById($roleId);
            if ($role) {
                $user->getRoles()->add($role);
                $role->getUsers()->add($user);
                $this->getEntityManager()->persist($role);
            }
        }

        $chefList = explode(",", $data['chefList']);
        foreach ($chefList as $userId) {
            $chef = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($userId);
            if ($chef) {
                $user->getBosses()->add($chef);
                $chef->getMembers()->add($user);
                $this->getEntityManager()->persist($chef);
            }
        }

        $memberList = explode(",", $data['memberList']);
        foreach ($memberList as $userId) {
            $member = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($userId);
            if ($member) {
                $user->getMembers()->add($member);
                $member->getBosses()->add($user);
                $this->getEntityManager()->persist($member);
            }
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
        $users = $this->getEntityManager()->getRepository('wm\Entity\User')->getMyMembers();
        $array = $this->entitysToArray($users);
        return new JsonModel($array);
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
        $user = new \wm\Entity\User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setPosition($data['position']);
        $user->setPassword(md5($data['password']));
        $user->setState($data['state']);
        $user->setUsername($data['username']);

        $roles = explode(",", $data['roles']);
        foreach ($roles as $roleId) {
            $role = $this->getEntityManager()->getRepository('wm\Entity\Role')->findOneById($roleId);
            if ($role) {
                $user->getRoles()->add($role);
                $role->getUsers()->add($user);
                $this->getEntityManager()->persist($role);
            }
        }

        $chefList = explode(",", $data['chefList']);
        foreach ($chefList as $userId) {
            $chef = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($userId);
            if ($chef) {
                $user->getBosses()->add($chef);
                $chef->getMembers()->add($user);
                $this->getEntityManager()->persist($chef);
            }
        }

        $memberList = explode(",", $data['memberList']);
        foreach ($memberList as $userId) {
            $member = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($userId);
            if ($member) {
                $user->getMembers()->add($member);
                $member->getUsers()->add($user);
                $this->getEntityManager()->persist($member);
            }
        }

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        $id = $user->getId();
        $array = array('id' => $id);
        return new JsonModel($data);
    }

}
