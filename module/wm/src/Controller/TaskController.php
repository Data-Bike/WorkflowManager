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

        $curators = explode(",", $data['curatorsList']);
        foreach ($curators as $curatorId) {
            if ($curatorId) {
                $curator = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($curatorId);
                $Task->getCurators()->add($curator);
                $curator->getCurateTasks()->add($Task);
                $this->getEntityManager()->persist($curator);
            }
        }
        $executors = explode(",", $data['executorsList']);
        foreach ($executors as $executorId) {
            if ($executorId) {
                $executor = $this->getEntityManager()->getRepository('wm\Entity\User')->findOneById($executorId);
                $Task->getExecutors()->add($executor);
                $executor->getExecuteTasks()->add($Task);
                $this->getEntityManager()->persist($executor);
            }
        }
        $necessarys = explode(",", $data['necessaryList']);
        foreach ($necessarys as $necessaryId) {
            if ($necessaryId) {
                $necessary = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($necessaryId);
                $Task->getNecessary()->add($necessary);
                $necessary->getInvNecessary()->add($Task);
                $this->getEntityManager()->persist($necessary);
            }
        }
        $sufficientlys = explode(",", $data['sufficientlyList']);
        foreach ($sufficientlys as $sufficientlyId) {
            if ($sufficientlyId) {
                $sufficiently = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($sufficientlyId);
                $Task->getSufficiently()->add($sufficiently);
                $sufficiently->getInvSufficiently()->add($Task);
                $this->getEntityManager()->persist($sufficiently);
            }
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
        if ($task) {
            $array = array('about' => $task->getAbout(),
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

    public function getList() {
        $params = $this->params()->fromQuery();
        if ($params['startDateTime']) {
            $params['startDateTime'] = new \DateTime($params['startDateTime']);
        }
        if ($params['finishDateTime']) {
            $params['finishDateTime'] = new \DateTime($params['finishDateTime']);
        }
//        if (strlen($params['curatorsList'])>0) {
//            $params['curators'] = explode(',', $params['curatorsList']);
//        }
//        if (strlen($params['executorsList'])>0) {
//            $params['executors'] = explode(',', $params['executorsList']);
//        }
//        if (strlen($params['sufficientlyList'])>0) {
//            $params['sufficiently'] = explode(',', $params['sufficientlyList']);
//        }
//        if (strlen($params['necessaryList'])>0) {
//            $params['necessary'] = explode(',', $params['necessaryList']);
//        }
        if (!$params['necessaryList']) {
            unset($params['necessaryList']);
        }
        if (!$params['sufficientlyList']) {
            unset($params['sufficientlyList']);
        }
        if (!$params['consequenceList']) {
            unset($params['consequenceList']);
        }
        if (!$params['executorsList']) {
            unset($params['executorsList']);
        }
        if (!$params['curatorsList']) {
            unset($params['curatorsList']);
        }
//        return new JsonModel($params);
        $tasks = $this->getEntityManager()->getRepository('wm\Entity\Task')->getTasksByParams($params);
//        return new JsonModel($tasks);
        foreach ($tasks as $task) {
            $row_grid = array('_about' => $task->getAbout(),
                '_FinishDateTime' => $task->getFinishDateTime() ? $task->getFinishDateTime()->format(\DateTime::W3C) : "нет",
                '_Name' => $task->getName(),
                '_StartDateTime' => $task->getStartDateTime() ? $task->getStartDateTime()->format(\DateTime::W3C) : "нет",
                '_id' => $task->getId(),
                'params' => array('data' => array('about' => $task->getAbout(),
                        'curators' => $this->entitysToArray($task->getCurators()),
                        'Executors' => $this->entitysToArray($task->getExecutors()),
                        'FinishDateTime' => $task->getFinishDateTime()->format(\DateTime::W3C),
                        'Name' => $task->getName(),
                        'Necessary' => $this->entitysToArray($task->getNecessary()),
                        'StartDateTime' => $task->getStartDateTime()->format(\DateTime::W3C),
                        'Sufficiently' => $this->entitysToArray($task->getSufficiently()),
                        'id' => $task->getId()
                    )
                )
            );

            if (count($task->getCurators()) > 0) {
                foreach ($task->getCurators() as $curator) {
                    $row_grid['_curators'].=$curator->getName() . "\r\n";
                }
            } else {
                $row_grid['_curators'] = 'нет';
            }

            if (count($task->getExecutors()) > 0) {
                foreach ($task->getExecutors() as $executor) {
                    $row_grid['_Executors'].=$executor->getName() . "\r\n";
                }
            } else {
                $row_grid['_Executors'] = 'нет';
            }

            if (count($task->getNecessary()) > 0) {
                foreach ($task->getNecessary() as $necessary) {
                    $row_grid['_Necessary'].=$necessary->getName() . "\r\n";
                }
            } else {
                $row_grid['_Necessary'] = 'нет';
            }

            if (count($task->getSufficiently()) > 0) {
                foreach ($task->getSufficiently() as $sufficiently) {
                    $row_grid['_Sufficiently'].=$sufficiently->getName() . "\r\n";
                }
            } else {
                $row_grid['_Sufficiently'] = 'нет';
            }

            $row_array[] = $row_grid;
        }
        return new JsonModel($row_array);
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

        $necessarys = explode(",", $data['necessaryList']);
        foreach ($necessarys as $necessaryId) {
            $necessary = $this->getEntityManager()->getRepository('wm\Entity\Task')->findOneById($necessaryId);
            $task->getNecessary()->add($necessary);
            $necessary->getInvNecessary()->add($task);
            $this->getEntityManager()->persist($necessary);
        }

        $sufficientlys = explode(",", $data['sufficientlyList']);
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
