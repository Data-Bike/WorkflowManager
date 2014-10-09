<?php

namespace wm\Controller;

use Application\Controller\JsonRESTEntityUsingController,
    Zend\View\Model\JsonModel;

class TaskController extends JsonRESTEntityUsingController {

    public function create($data) {

        $Task = new \wm\Entity\Task();
        $Task->setAbout($data['about']);
        $Task->setFinishDateTime(new \DateTime($data['finishDateTime']));
        $Task->setName($data['name']);
        $Task->setStartDateTime(new \DateTime($data['startDateTime']));

        $curators = explode(",", $data['curators']);
        foreach ($curators as $curatorId) if($curatorId){
            $curator = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($curatorId);
            $Task->getCurators()->add($curator);
            $curator->getCurateTasks()->add($Task);
            $this->getEntityManager()->persist($curator);
        }

        $executors = explode(",", $data['Executors']);
        foreach ($executors as $executorId) if($executorId){
            $executor = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($executorId);
            $Task->getExecutors()->add($executor);
            $executor->getExecuteTasks()->add($Task);
            $this->getEntityManager()->persist($executor);
        }

        $necessarys = explode(",", $data['Necessary']);
        foreach ($necessarys as $necessaryId) if($necessaryId){
            $necessary = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($necessaryId);
            $Task->getNecessary()->add($necessary);
            $necessary->getInvNecessary()->add($Task);
            $this->getEntityManager()->persist($necessary);
        }

        $sufficientlys = explode(",", $data['Sufficiently']);
        foreach ($sufficientlys as $sufficientlyId) if($sufficientlyId){
            $sufficiently = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($sufficientlyId);
            $Task->getSufficiently()->add($sufficiently);
            $sufficiently->getInvSufficiently()->add($Task);
            $this->getEntityManager()->persist($sufficiently);
        }

        $this->getEntityManager()->persist($Task);
        $this->getEntityManager()->flush();

        $id = $Task->getId();
        $array = array('id' => $id);
        return new JsonModel($array);
    }

    public function delete($id) {
        $taskToDelete = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($id);
        $this->getEntityManager()->remove($taskToDelete);
        $this->getEntityManager()->flush();
        return;
    }

    public function deleteList() {
        return $this->methodNotAllowed();
    }

    public function get($id) {
        $task = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($id);
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
        $params = $this->params()->fromQuery();
        $tasks = $this->getEntityManager()->getRepository('wm\Entity\Task')->findBy($params);
        foreach ($tasks as $task) {
            $array[] = array('about' => $task->getAbout(),
                'curators' => $task->getCurators(),
                'Executors' => $task->getExecutors(),
                'FinishDateTime' => $task->getFinishDateTime(),
                'Name' => $task->getName(),
                'Necessary' => $task->getNecessary(),
                'StartDateTime' => $task->getStartDateTime(),
                'Sufficiently' => $task->getSufficiently()
            );
        }
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
        $task = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($id);
        $task->setAbout($data['about']);
        $task->setFinishDateTime($data['FinishDateTime']);
        $task->setName($data['Name']);
        $task->setStartDateTime($data['StartDateTime']);

        $curators = explode(",", $data['curators']);
        foreach ($curators as $curatorId) {
            $curator = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($curatorId);
            $task->getCurators()->add($curator);
            $curator->getCurateTasks()->add($task);
            $this->getEntityManager()->persist($curator);
        }

        $executors = explode(",", $data['Executors']);
        foreach ($executors as $executorId) {
            $executor = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($executorId);
            $task->getExecutors()->add($executor);
            $executor->getExecuteTasks()->add($task);
            $this->getEntityManager()->persist($executor);
        }

        $necessarys = explode(",", $data['Necessary']);
        foreach ($necessarys as $necessaryId) {
            $necessary = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($necessaryId);
            $task->getNecessary()->add($necessary);
            $necessary->getInvNecessary()->add($task);
            $this->getEntityManager()->persist($necessary);
        }

        $sufficientlys = explode(",", $data['Sufficiently']);
        foreach ($sufficientlys as $sufficientlyId) {
            $sufficiently = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($sufficientlyId);
            $task->getSufficiently()->add($sufficiently);
            $sufficiently->getInvSufficiently()->add($task);
            $this->getEntityManager()->persist($sufficiently);
        }

        $this->getEntityManager()->flush();
        return new JsonModel($data);
    }

}
