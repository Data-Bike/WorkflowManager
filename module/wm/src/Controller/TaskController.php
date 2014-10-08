<?php

namespace wm\Controller;

use Application\Controller\JsonRESTEntityUsingController,
    Zend\View\Model\JsonModel;

class TaskController extends JsonRESTEntityUsingController {

    public function create($data) {
        $Task = new \wm\Entity\Task();
        $Task->setAbout($data['about']);
        $Task->setCurators($data['curators']);
        $Task->setExecutors($data['Executors']);
        $Task->setFinishDateTime($data['FinishDateTime']);
        $Task->setName($data['Name']);
        $Task->setNecessary($data['Necessary']);
        $Task->setStartDateTime($data['StartDateTime']);
        $Task->setufficiently($data['Sufficiently']);
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
        $task->setCurators($data['curators']);
        $task->setExecutors($data['Executors']);
        $task->setFinishDateTime($data['FinishDateTime']);
        $task->setName($data['Name']);
        $task->setNecessary($data['Necessary']);
        $task->setStartDateTime($data['StartDateTime']);
        $task->setufficiently($data['Sufficiently']);
        $this->entityManager->flush();
        return new JsonModel($data);
    }

}
