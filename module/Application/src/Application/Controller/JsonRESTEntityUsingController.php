<?php

namespace Application\Controller;

use wm\Controller\AbstractRestfulJsonController;

class JsonRESTEntityUsingController extends AbstractRestfulJsonController {

    /**
     * @var EntityManager
     */
    protected $entityManager = null;

    /**
     * @param EntityManager $em
     * @access protected
     * @return PostController
     */
    protected function setEntityManager(\Doctrine\ORM\EntityManager $em) {
        $this->entityManager = $em;
        return $this;
    }

    /**
     * @access protected
     * @return EntityManager
     */
    protected function getEntityManager() {
            $this->setEntityManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
        return $this->entityManager;
    }

}
