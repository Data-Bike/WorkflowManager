<?php

namespace wm\Controller;

use Application\Controller\JsonRESTEntityUsingController,
    Zend\View\Model\JsonModel;

class TaskController extends JsonRESTEntityUsingController {

    public function __construct() {
        $this->setEntityManager($this->getDoctrine()->getEntityManager());
    }

    public function create($data) {

        $Task = new \wm\Entity\Task();
        $Task->setAbout($data['about']);
        $Task->setFinishDateTime($data['FinishDateTime']);
        $Task->setName($data['Name']);
        $Task->setStartDateTime($data['StartDateTime']);

        $curators = explode(",", $data['curators']);
        foreach ($curators as $curatorId) {
            $curator = $this->entityManager->getRepository('Entity\User')->findOneById($id);
            $Task->getCurators()->add($curator);
            $curator->getCurateTasks()->add($Task);
            $this->entityManager->persist($curator);
        }

        $executors = explode(",", $data['Executors']);
        foreach ($executors as $executorId) {
            $executor = $this->entityManager->getRepository('Entity\User')->findOneById($id);
            $Task->getExecutors()->add($executor);
            $executor->getExecuteTasks()->add($Task);
            $this->entityManager->persist($executor);
        }

        $necessarys = explode(",", $data['Necessary']);
        foreach ($necessarys as $necessaryId) {
            $necessary = $this->entityManager->getRepository('Entity\Task')->findOneById($id);
            $Task->getNecessary()->add($necessary);
            $necessary->getInvNecessary()->add($Task);
            $this->entityManager->persist($necessary);
        }

        $sufficientlys = explode(",", $data['Sufficiently']);
        foreach ($sufficientlys as $sufficientlyId) {
            $sufficiently = $this->entityManager->getRepository('Entity\Task')->findOneById($id);
            $Task->getSufficiently()->add($sufficiently);
            $sufficiently->getInvSufficiently()->add($Task);
            $this->entityManager->persist($sufficiently);
        }

        $this->entityManager->persist($Task);
        $this->entityManager->flush();

        $id = $Task->getId();
        $array = array('id' => $id);
        return new JsonModel($array);
    }

    public function delete($id) {
        $taskToDelete = $this->entityManager->getRepository('Entity\Task')->findOneById($id);
        $this->entityManager->remove($taskToDelete);
        $this->entityManager->flush();
        return;
    }

    public function deleteList() {
        return $this->methodNotAllowed();
    }

    public function get($id) {
        $task = $this->entityManager->getRepository('Entity\Task')->findOneById($id);
        $array = array('about' => $task->getAbout(),
            'curators' => $task->getCurators(),
            'Executors' => $task->getExecutors(),
            'FinishDateTime' => $task->getFinishDateTime(),
            'Name' => $task->getName(),
            'Necessary' => $task->getNecessary(),
            'StartDateTime' => $task->getStartDateTime(),
            'Sufficiently' => $task->getSufficiently()
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
        $task = $this->entityManager->getRepository('Entity\Task')->findOneById($id);
        $task->setAbout($data['about']);
        $task->setFinishDateTime($data['FinishDateTime']);
        $task->setName($data['Name']);
        $task->setStartDateTime($data['StartDateTime']);

        $curators = explode(",", $data['curators']);
        foreach ($curators as $curatorId) {
            $curator = $this->entityManager->getRepository('Entity\User')->findOneById($id);
            $Task->getCurators()->add($curator);
            $curator->getCurateTasks()->add($Task);
            $this->entityManager->persist($curator);
        }

        $executors = explode(",", $data['Executors']);
        foreach ($executors as $executorId) {
            $executor = $this->entityManager->getRepository('Entity\User')->findOneById($id);
            $Task->getExecutors()->add($executor);
            $executor->getExecuteTasks()->add($Task);
            $this->entityManager->persist($executor);
        }

        $necessarys = explode(",", $data['Necessary']);
        foreach ($necessarys as $necessaryId) {
            $necessary = $this->entityManager->getRepository('Entity\Task')->findOneById($id);
            $Task->getNecessary()->add($necessary);
            $necessary->getInvNecessary()->add($Task);
            $this->entityManager->persist($necessary);
        }

        $sufficientlys = explode(",", $data['Sufficiently']);
        foreach ($sufficientlys as $sufficientlyId) {
            $sufficiently = $this->entityManager->getRepository('Entity\Task')->findOneById($id);
            $Task->getSufficiently()->add($sufficiently);
            $sufficiently->getInvSufficiently()->add($Task);
            $this->entityManager->persist($sufficiently);
        }
        
        $this->entityManager->flush();
        return new JsonModel($data);
    }

}
